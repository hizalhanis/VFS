var api_url 		= 'csmcr';
var records 		= {};
var currentRecord 	= null;
var currentRecordID = null;
var currentSubID 	= null;
var sketchData 		= null;
var listener 		= function(event){ event.preventDefault(); };
var currentUser 	= window.localStorage.getItem('current_user') ? JSON.parse(window.localStorage.getItem('current_user')) : null;
var currentBranch	= window.localStorage.getItem('current_branch');
var loggedIn		= window.localStorage.getItem('logged_in');

try {
	var usersJSON = window.localStorage.getItem('users');
	var users = usersJSON ? JSON.parse(usersJSON) : {};
} catch (e){
	var users = {};
}


function goTo(id){
	$('div.screen').hide().removeClass('current_screen');
	$('#'+id).show().addClass('current_screen');
}


$(document).ready(function(){

	var records_json = window.localStorage.getItem('records');
	if (records_json){
		
		records = JSON.parse(records_json);
		if (Object.prototype.toString.call(records) === '[object Array]'){
			records = {};
		} else {
			// do nothing
		}
	} else {
		records = {};
	}
	

	if (loggedIn){
		$('.logged-in').show();
		$('.unlogged-in').hide();
	} else {
		$('.logged-in').hide();
		$('.unlogged-in').show();
		
	}

	loadForms();
	
	$('div.image-preview').click(function(){
		$(this).hide();
	})
	
	$('button.refresh-case-list').click(function(){
		caseList();
	})
	
	$('button.upload-to-server').click(function(){
		submitCase(currentRecordID);
	})
	
	$('button.exit').click(function(){
		navigator.app.exitApp();
	})
	
	$('button.login-page').click(function(){
		$('input.user-username').val('');
		$('input.user-password').val('');
	})
	
	$('a.tab').click(function(){
		var rel = $(this).attr('rel');
		$('div.tab').hide();
		$('a.current-tab').removeClass('current-tab');
		$(this).addClass('current-tab');
		$('div.'+rel).show();
	});
	
	$('button.geo_refresh').click(function(e){
		e.preventDefault();
		updateLocation();
	})
	
	$('button.take-photo').click(function(){
		navigator.camera.getPicture(function(imageURI){
		    savePhoto(currentRecordID,imageURI)	;
		}, function(){
			alert('Failed because: ' + message);
		}, { quality: 50, 
			destinationType: Camera.DestinationType.FILE_URI }); 
		
	})
	
	$('button.logout').click(function(){
		window.localStorage.setItem('logged_in','');
		loggedIn = 0;
		$('.logged-in').hide();
		$('.unlogged-in').show();
	})
	
	$('button.login').click(function(){
		var username = $('input.user-username').val();
		var password = hex_md5($('input.user-password').val());
		var ok = false;
		for (var i in users){
			var user = users[i];
			if (user.username == username && user.password == password){
				goTo('home');
				window.localStorage.setItem('logged_in',1);
				window.localStorage.setItem('current_user',JSON.stringify(user));
				loggedIn = 1;
				$('.logged-in').show();
				$('.unlogged-in').hide();
				ok = true;
				
				currentUser = user;
				
			} else {

			}
		}
		
		if (ok){
			
		} else {
			alert('Invalid username/password')
		}
		
	})
	
	$('a.tab-case').click(function(){
		document.body.removeEventListener('touchmove',listener,false);	
		
	});
	
	$('a.tab-sketch').click(function(){
		document.body.addEventListener('touchmove',listener,false);	
	});
	
	api_url = window.localStorage.getItem('apiurl');
	$('input.api-url').val(window.localStorage.getItem('apiurl'));
	$('input.api-url').change(function(){
		window.localStorage.setItem('apiurl', $(this).val());
		api_url = $(this).val();
	})
	
	$('button.refresh-sketch').click(function(){	    
		if (sketchData){
		    var imageObj = new Image();
		    var context = canvas.getContext('2d');
		    imageObj.src = sketchData;
		    imageObj.onload = function() {
		    	context.drawImage(this, 0, 0);
		    };
	    }
	})
	
	$('button.new-record').click(function(){

			
		try {
			initialize();
			$('div.map-placeholder').show();
		} catch (e){
			// do nothing
		}
		
		updateLocation();
		
		$('#rekod input').val('');
		
		$('div.map-placeholder').append($('div.map-container'));
	})
	
	$('button.sketch').click(function(){
		
	    $(this).toggleClass('hold');
	    
	    if ($(this).hasClass('hold')){
	    	allow_draw = true;
	    } else {
	    	allow_draw = false;
	    }
	});
	
	$('button.erase-sketch').click(function(){
	    $(this).toggleClass('hold');
	    if ($(this).hasClass('hold')){
	    	/* Drawing on Paint App */
	    	tmp_ctx.lineWidth = 30;
	    	tmp_ctx.lineJoin = 'round';
	    	tmp_ctx.lineCap = 'round';
	    	tmp_ctx.strokeStyle = 'white';
	    	tmp_ctx.fillStyle = 'white';

	    } else {
	    	/* Drawing on Paint App */
	    	tmp_ctx.lineWidth = 5;
	    	tmp_ctx.lineJoin = 'round';
	    	tmp_ctx.lineCap = 'round';
	    	tmp_ctx.strokeStyle = 'black';
	    	tmp_ctx.fillStyle = 'black';

	    }
	});
	
	$('button.save-sketch').click(function(){
	    
	    sketchData = canvas.toDataURL();
	    saveSketch();
	    alert('Lakaran telah disimpan');
	    
	})
	
	
	$('button').click(function(){
		var goto = $(this).attr('goto');
		if (goto) goTo(goto);
	})
	
	$('button.records').click(function(){
		caseList();
	})
	
	$('button.update-case-form').click(function(){
		downloadCaseForm();
	});
	
	$('button.update-vehicle-form').click(function(){
		downloadVehicleForm();
	})
	
	$('button.update-branch').click(function(){
		$('button.select-branch').hide();
		$('div.branch-container').html('Loading...');
		api('get_branch_list',null, function(res){
			if (res.status == 'ok'){
				$('div.branch-container').html(res.html);				
				$('button.select-branch').show();
			}
		})
	})
	
	$('button.select-branch').click(function(){
		var id = $('select.select-branch').val();
		$('div.branch-container').html('Memuat-turun Maklumat Pengguna...');
		$('button.select-branch').hide();
		api('get_users','branch_id='+id,function(res){
			users = res.data;
			window.localStorage.setItem('users',JSON.stringify(users));
			window.localStorage.setItem('current_branch', id);
			
			goTo('tetapan');
		});
	});
	
	$('button.update-passenger-form').click(function(){
		downloadPassengerForm();
	})
	
	$('button.update-pedestrian-form').click(function(){
		downloadPedestrianForm();
	})
	
	$('button.form-save').click(function(e){
		
		e.preventDefault();
		
		var serialized = $(this).parents('div.screen').find('form').serializeObject();
		
		var case_id = newCase(serialized);
		
		caseDetails(case_id);
		
	});
	
	$('button.form-update').click(function(e){
		e.preventDefault();
		
		var serialized = $(this).parents('div.screen').find('form').serializeObject();
		
		updateCase(currentRecordID, serialized);
		
		caseDetails(currentRecordID);		
		
	});
	
	$('button.case-details').on('click',function(){
		goTo('borang-kes');
		caseForm(currentRecordID);
	});
	
	
	$(document).on('click','button.mi-details', function(){
		var id = $(this).attr('id');
		miForm(currentRecordID,id);
	});
	
	$(document).on('click','button.pi-details', function(){
		var id = $(this).attr('id');
		piForm(currentRecordID,id);
	});
	
	$(document).on('click','button.rs-details', function(){
		var id = $(this).attr('id');
		rsForm(currentRecordID,id);
	});
	
	$(document).on('click','button.pt-details', function(){
		var id = $(this).attr('id');
		ptForm(currentRecordID,id);
	});
	
	$(document).on('click','button.rss-details', function(){
		var id = $(this).attr('id');
		rssForm(currentRecordID,id);
	});
	
	$('tbody.rec-images').on('click','button.rec-image-view', function(){
		var href = $(this).parents('tr').find('td.href').text();
		previewImage(href);
	});
	
	$('tbody.rec-images').on('click','button.rec-image-delete', function(){
		var id = $(this).attr('id');
		deletePhoto(currentRecordID, id);
	})
	
	$('tbody.case-list').on('click','button.delete-record',function(){
		var id = $(this).attr('id');
		if (confirm('Padam rekod?')){
			deleteRecord(id);
			$(this).parents('tr').remove();
		}
		
	});
	
	$('tbody.case-list').on('click','button.edit-record',function(){
		var case_id = $(this).attr('id');
		caseDetails(case_id);
		$('div.map-placeholder-edit').append($('div.map-container'));
		
		goTo('kemaskini-rekod');
		
		try {
		
			initialize();
		
			var latLng = new google.maps.LatLng(currentRecord.data.geo_lat, currentRecord.data.geo_lng);
			map.setCenter(latLng);
			map.setZoom(16);
			
		    for (var i = 0, marker; marker = markers[i]; i++) {
			    marker.setMap(null);
			}	    
		    
		    var marker = new google.maps.Marker({
			    map: map,
			    position: latLng
			});
		
			markers.push(marker);
			$('div.map-placeholder-edit').show();
		
		} catch (e){

		}
		
		

	});
	
	$('tbody.case-list').on('click','button.view-record',function(){
		var id = $(this).attr('id');
		caseDetails(id);
	});
})



function api(request, data, success){
	$.ajax({
		url: api_url+request,
		type: 'post',
		data: data,
		dataType: 'json',
		success: function (res){
			success(res);
		}
	});
}

function caseForm(case_id){
	loadForms();
	caseRecord(case_id);
	goTo('borang-kes');
}

function vehicleForm(case_id, sub_id){
	loadForms();
	vehicleRecord(case_id, sub_id);
	goTo('borang-kenderaan');
}

function passengerForm(case_id, sub_id){
	loadForms();
	passengerRecord(case_id, sub_id);
	goTo('borang-penumpang');
	
}

function pedestrianForm(case_id, sub_id){
	loadForms();
	pedestrianRecord(case_id, sub_id);
	goTo('borang-pejalan-kaki');
}

function caseList(){
	$('tbody.case-list').html('');
	

	for (var id in records){
		var record = records[id];
		var row = '<tr>';
		row += '<td>'+record.data.from_distance+'KM DARI '+record.data.from_location+' MENGHALA KE '+record.data.to_location+' SEJAUH '+record.data.to_distance+'KM</td>';
		row += '<td style="text-align: center">'+record.data.vehicle_count+'</td>';
		row += '<td style="text-align: center">'+record.data.time+' '+record.data.date+'</td>';
		row += '<td>BELUM LENGKAP</td>';
		row += '<td><button id="'+id+'" class="delete-record">Padam</button> <button id="'+id+'" class="edit-record">Kemaskini</button> <button id="'+id+'" class="view-record">Butiran</button></td>';
		row += '</tr>';
		
		$('tbody.case-list').append(row);
	}
}

function caseDetails(case_id){
	var record = records[case_id];
	currentRecord = record;
	currentRecordID = case_id;
	
	sketchData = currentRecord.sketchData;
	$('button.refresh-sketch').click();
	
	$('.rec-location-from').text(record.data.from_location);
	$('.rec-location-to').text(record.data.to_location);
	$('.rec-location-from-distance').text(record.data.from_distance + 'KM');
	$('.rec-location-to-distance').text(record.data.to_distance + 'KM');
	$('.rec-report-number').text(record.data.report_number);
	
	$('#kemaskini-rekod input[name=report_number]').val(record.data.report_number);
	$('#kemaskini-rekod input[name=from_location]').val(record.data.from_location);
	$('#kemaskini-rekod input[name=from_distance]').val(record.data.from_distance);
	$('#kemaskini-rekod input[name=to_location]').val(record.data.to_location);
	$('#kemaskini-rekod input[name=to_distance]').val(record.data.to_distance);

	$('#kemaskini-rekod input[name=date]').val(record.data.date);
	$('#kemaskini-rekod input[name=time]').val(record.data.time);
	
	$('#kemaskini-rekod input[name=section_no]').val(record.data.section_no);
	$('#kemaskini-rekod input[name=vehicle_count]').val(record.data.vehicle_count);
	$('#kemaskini-rekod input[name=vehicle_damaged]').val(record.data.vehicle_damaged);
	$('#kemaskini-rekod input[name=driver_death_count]').val(record.data.driver_death_count);
	$('#kemaskini-rekod input[name=driver_injured_count]').val(record.data.driver_injured_count);
	$('#kemaskini-rekod input[name=passenger_death_count]').val(record.data.passenger_death_count);
	$('#kemaskini-rekod input[name=passenger_injured_count]').val(record.data.passenger_injured_count);
	$('#kemaskini-rekod input[name=pedestrian_death_count]').val(record.data.pedestrian_death_count);
	$('#kemaskini-rekod input[name=pedestrian_injured_count]').val(record.data.pedestrian_injured_count);
	$('#kemaskini-rekod input[name=geo_lat]').val(record.data.geo_lat);
	$('#kemaskini-rekod input[name=geo_lng]').val(record.data.geo_lng);
	
	
	
	$('#kemaskini-rekod inpit[name=time]').val(record.data.time);
	$('#kemaskini-rekod inpit[name=date]').val(record.data.date);
	
	$('tbody.rec-vehicle-list').html('');
	for (var i = 1; i <= parseInt(record.data.vehicle_count); i++){
		var row = '<tr>';
		row += '<td>Kenderaan #'+i+'</td>';
		row += '<td style="text-align: center">Belum Lengkap</td>';
		row += '<td style="text-align: center"><button id="'+i+'" class="vehicle-details">Butiran</button></td>';
		row += '</tr>';
		
		$('tbody.rec-vehicle-list').append(row);
	}
	
	$('tbody.rec-passenger-list').html('');
	for (var i = 1; i <= parseInt(record.data.passenger_death_count) + parseInt(record.data.passenger_injured_count); i++){
		var row = '<tr>';
		row += '<td>Penumpang Cedera/Mati #'+i+'</td>';
		row += '<td style="text-align: center">Belum Lengkap</td>';
		row += '<td style="text-align: center"><button id="PD'+i+'" class="passenger-details">Butiran</button></td>';
		row += '</tr>';
		
		$('tbody.rec-passenger-list').append(row);
	}
	/*
	for (var i = 1; i <= parseInt(record.data.passenger_injured_count); i++){
		var row = '<tr>';
		row += '<td>Penumpang Cedera #'+i+'</td>';
		row += '<td style="text-align: center">Belum Lengkap</td>';
		row += '<td style="text-align: center"><button id="PI'+i+'" class="passenger-details">Butiran</button></td>';
		row += '</tr>';
		
		$('tbody.rec-passenger-list').append(row);
	}
	*/
	
	$('tbody.rec-pedestrian-list').html('');
	for (var i = 1; i <= parseInt(record.data.pedestrian_death_count) + parseInt(record.data.pedestrian_injured_count); i++){
		var row = '<tr>';
		row += '<td>Pejalan Kaki Cedera/Mati #'+i+'</td>';
		row += '<td style="text-align: center">Belum Lengkap</td>';
		row += '<td style="text-align: center"><button id="PED'+i+'" class="pedestrian-details">Butiran</button></td>';
		row += '</tr>';
		
		$('tbody.rec-pedestrian-list').append(row);
	}
	
	/*
	for (var i = 1; i <= parseInt(); i++){
		var row = '<tr>';
		row += '<td>Pejalan Kaki Cedera #'+i+'</td>';
		row += '<td style="text-align: center">Belum Lengkap</td>';
		row += '<td style="text-align: center"><button id="PEI'+i+'" class="pedestrian-details">Butiran</button></td>';
		row += '</tr>';
		
		$('tbody.rec-pedestrian-list').append(row);
	}
	*/
	
	$('tbody.rec-images').html('');
	for (var sub_id in record.images){
		image = record.images[sub_id];
		var row = '<tr>';
		row += '<td class="href">'+image+'</td>';
		row += '<td><button id="'+sub_id+'" class="rec-image-view">View</button> <button id="'+sub_id+'" class="rec-image-delete">Delete</button></td>';
		row += '</tr>';
		
		$('tbody.rec-images').append(row);
	}

	goTo('butiran');	
}

function caseRecord(case_id){

	var c = records[case_id].caseData;
	
	for (var i in c){
		var v = c[i];
		try {
			if (v.type == 'matrix-answer'){
				$('form[no='+v.no+']').find('input.survey-answer').val(v.ans);
			} else if (v.type == 'single-answer'){
				$('form[no='+v.no+']').find('input.survey-radio-btn').each(function(){
					if ($(this).val() == v.ans){
						$(this).attr('checked',true);
					}
				})			
			} else if (v.type == 'multiple-answer'){
				$('form[no='+v.no+']').find('input.survey-checkbox-btn').removeAttr('checked');
				for (var j = 0; j < v.ans.length; j++){
					var subans = v.ans[j];
					$('form[no='+v.no+']').find('input.survey-checkbox-btn').each(function(){
						if ($(this).val() == subans){

							$(this).attr('checked',true);
						}
					})			
	
				}
			}
		} catch (e){
			// do nothing
		}

	}


}

function saveSketch(){
	case_id = currentRecordID;

	var record = records[case_id];
	record.sketchData = sketchData;
	records[case_id] = record;
	
	saveData();
}

function savePhoto(case_id, data){
	var id = 'photo' + (new Date()).valueOf();
	var images = currentRecord.images;
	
	images[id] = data;		
	
	currentRecord.images = images;
	
	records[case_id] = currentRecord;
	
	saveData();
	
	caseDetails(case_id);
}

function deletePhoto(case_id, sub_id){
	var images = currentRecord.images;
	var newRecords = {};
	for (var cur_id in images){
		var image = images[cur_id];
		
		if (sub_id == cur_id){
			// do nothing
		} else {
			newRecords[cur_id] = record;
		}
	}
	
	currentRecord.images = newRecords;
	
	records[case_id] = currentRecord;
	
	saveData();
	
	caseDetails();
}

function saveCaseRecord(case_id, no, data){
	var record = records[case_id];
	record.caseData[no] = data;
	records[case_id] = record;
	saveData();
}

function vehicleRecord(case_id, vehicle_id){
	currentSubID = vehicle_id;
	var vehicle = records[case_id].vehicleData[vehicle_id];
	
	for (var i in vehicle){
		var v = vehicle[i];
		try {
			if (v.type == 'matrix-answer'){
				$('form[no='+v.no+']').find('input.survey-answer').val(v.ans);
			} else if (v.type == 'single-answer'){
				$('form[no='+v.no+']').find('input.survey-radio-btn').each(function(){
					if ($(this).val() == v.ans){
						$(this).attr('checked',true);
					}
				})			
			} else if (v.type == 'multiple-answer'){
				$('form[no='+v.no+']').find('input.survey-checkbox-btn').removeAttr('checked');
				for (var j = 0; j < v.ans.length; j++){
					var subans = v.ans[j];
					$('form[no='+v.no+']').find('input.survey-checkbox-btn').each(function(){
						if ($(this).val() == subans){
							$(this).attr('checked',true);
						}
					})			
	
				}
			}
		} catch (e){
			// do nothing
		}

	}
	
}

function saveVehicleRecord(case_id, vehicle_id, no, data){
	var record = records[case_id];
	if (record.vehicleData[vehicle_id]){
		var vehicle = record.vehicleData[vehicle_id];
	} else {
		var vehicle = [];
	}

	vehicle[no] = data;
	record.vehicleData[vehicle_id] = vehicle;
	
	records[case_id] = record;
	
	saveData();
}

function passengerRecord(case_id, passenger_id){
	currentSubID = passenger_id;
	var passenger = records[case_id].passengerData[passenger_id];
	
	for (var i in passenger){
		var v = passenger[i];
		try {
			if (v.type == 'matrix-answer'){
				$('form[no='+v.no+']').find('input.survey-answer').val(v.ans);
			} else if (v.type == 'single-answer'){
				$('form[no='+v.no+']').find('input.survey-radio-btn').each(function(){
					if ($(this).val() == v.ans){
						$(this).attr('checked',true);
					}
				})			
			} else if (v.type == 'multiple-answer'){
				$('form[no='+v.no+']').find('input.survey-checkbox-btn').removeAttr('checked');
				for (var j = 0; j < v.ans.length; j++){
					var subans = v.ans[j];
					$('form[no='+v.no+']').find('input.survey-checkbox-btn').each(function(){
						if ($(this).val() == subans){
							$(this).attr('checked',true);
						}
					})			
	
				}
			}
		} catch (e){
			// do nothing
		}
	}
}

function savePassengerRecord(case_id, passenger_id, no, data){
	var record = records[case_id];
	if (record.passengerData[passenger_id]){
		var passenger = record.passengerData[passenger_id];	
	} else {
		var passenger = [];
	}
	
	passenger[no] = data;
	record.passengerData[passenger_id] = passenger;
	
	records[case_id] = record;
	
	saveData();
}

function pedestrianRecord(case_id, pedestrian_id){
	currentSubID = pedestrian_id;
	var pedestrian = records[case_id].pedestrianData[pedestrian_id];
	
	for (var i in pedestrian){
		var v = pedestrian[i];
		try {
			if (v.type == 'matrix-answer'){
				$('form[no='+v.no+']').find('input.survey-answer').val(v.ans);
			} else if (v.type == 'single-answer'){
				$('form[no='+v.no+']').find('input.survey-radio-btn').each(function(){
					if ($(this).val() == v.ans){
						$(this).attr('checked',true);
					}
				})			
			} else if (v.type == 'multiple-answer'){
				$('form[no='+v.no+']').find('input.survey-checkbox-btn').removeAttr('checked');
				for (var j = 0; j < v.ans.length; j++){
					var subans = v.ans[j];
					$('form[no='+v.no+']').find('input.survey-checkbox-btn').each(function(){
						if ($(this).val() == subans){
							$(this).attr('checked',true);
						}
					})			
	
				}
			}
		} catch (e){
			// do nothing
		}

	}

}

function savePedestrianRecord(case_id, pedestrian_id, no, data){
	var record = records[case_id];
	if (record.pedestrianData[pedestrian_id]){
		var pedestrian = record.pedestrianData[pedestrian_id];	
	} else {
		var pedestrian = [];
	}
	pedestrian[no] = data;
	record.pedestrianData[pedestrian_id] = pedestrian;
	
	records[case_id] = record;
	
	saveData();
}

function updateCase(case_id, data){
	var record = records[case_id];
	record.data = data;
	records[case_id] = record;
	
	saveData();
}

function newCase(data){
	
	var id = 'rec' + (new Date()).valueOf();
	var record = {
		id: id,
		data: data,
		caseData: {},	
		vehicleData: {},
		passengerData: {},
		pedestrianData: {},
		images: {}
	};
	
	
	if (!records){
		records = {};
		records[id] = record;
	} else {
		records[id] = record;
	}
	
	saveData();
	
	return id;
}

function deleteRecord(id){
	var newRecords = {};
	
	for (var cur_id in records){
		var record = records[cur_id];
		
		if (id == cur_id){
			// do nothing
		} else {
			newRecords[cur_id] = record;
		}
	}
	
	records = newRecords;
	

	saveData();
	
}

function loadForms(){
	var cf = window.localStorage.getItem('caseForm');
	var vf = window.localStorage.getItem('vehicleForm');
	var pf = window.localStorage.getItem('passengerForm');
	var pdf = window.localStorage.getItem('pedestrianForm');
	
	if (cf){
		$('span.case-form-status').html(' &nbsp; &#x2713;');
	}
	
	if (vf){
		$('span.vehicle-form-status').html(' &nbsp; &#x2713;');
	}
	
	if (pf){
		$('span.passenger-form-status').html(' &nbsp; &#x2713;');
	}
	
	if (pdf){
		$('span.pedestrian-form-status').html(' &nbsp; &#x2713;');
	}
	
	$('#borang-kes div.content').html(cf);
	$('#borang-kenderaan div.content').html(vf);
	$('#borang-penumpang div.content').html(pf);
	$('#borang-pejalan-kaki div.content').html(pdf);
	
	$('form.question').submit(function(e){
		e.preventDefault();
		
		var no = $(this).attr('no');
		var logic = $(this).attr('logic');
		var data = $(this).serializeObject();
		
		switch (logic){
			case 'case':
				saveCaseRecord(currentRecordID, no, data);
				break;
			case 'vehicle':
				saveVehicleRecord(currentRecordID, currentSubID, no, data);
				break;
			case 'passenger':
				savePassengerRecord(currentRecordID, currentSubID, no, data);		
				break;
			case 'pedestrian':
				savePedestrianRecord(currentRecordID, currentSubID, no, data);			
				break;
		}
	});
}

function downloadCaseForm(){
	api('get_case_form',null,function(res){
		if (res.status == 'ok'){
			window.localStorage.setItem('caseForm',res.html);
			alert('Borang telah dimuatturun dan dikemaskini.');
		} else { 
			// do nothing for now
		}
	});
	
	loadForms();
}

function downloadVehicleForm(){
	api('get_vehicle_form',null,function(res){
		if (res.status == 'ok'){
			window.localStorage.setItem('vehicleForm',res.html);
			alert('Borang telah dimuatturun dan dikemaskini.');
		} else { 
			// do nothing for now
		}
	});	
	
	loadForms();
}

function downloadPassengerForm(){
	api('get_passenger_form',null,function(res){
		if (res.status == 'ok'){
			window.localStorage.setItem('passengerForm',res.html);
			alert('Borang telah dimuatturun dan dikemaskini.');
		} else { 
			// do nothing for now
		}
	});	
	
	loadForms();
}

function downloadPedestrianForm(){
	api('get_pedestrian_form',null,function(res){
		if (res.status == 'ok'){
			window.localStorage.setItem('pedestrianForm',res.html);
			alert('Borang telah dimuatturun dan dikemaskini.');
		} else { 
			// do nothing for now
		}
	});

	loadForms();	
}

function submitCase(id){
	var case_record = records[id];
	var case_data = JSON.stringify(case_record);
	
	api('submit_case', 'data='+encodeURIComponent(case_data)+'&user_id='+currentUser.id, function(res){
	
		if (res.status == 'OK'){
			deleteRecord(id);
			alert('Data telah dimuatnaik.');
			caseList();
			goTo('senarai');			
		} else {
			alert('No laporan telah wujud.');
		}
		
		
	})
}


function saveData(){
	var data = JSON.stringify(records);
	window.localStorage.setItem('records',data);
}

function loadData(){
	var data = window.localStorage.getItem('records');
	if (!data) records = [];
	records = JSON.parse(data);
}

function previewImage(href){
	$('div.image-preview').show();
	$('div.image-preview').html('<img src="'+href+'" style="width: 70%; margin-top: 50px;" /><br /><p style="color: #fff">Tekan pada skrin untuk kembali.</p>');
	
}

$.fn.serializeObject = function(){
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
