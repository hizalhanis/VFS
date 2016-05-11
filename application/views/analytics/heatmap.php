<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=visualization"></script>
<script>

	$(document).ready(function(){
	
		$('.btn-process').click(function(){
			$('span.loading').html('&nbsp; Loading...');
		})
		
		$('select.filter-select').change(function(){
			var id = $(this).val();
			$('div.control-filter div.control').hide();
			$('div.control[rel='+id+']').show();
		});
		
		$('button.filter-add').click(function(){
			var id = $('select.filter-select').val();
			var control = $('div.control[rel='+id+']').first().clone();
			
			$(control).prepend('<button style="float: right" class="filter-remove">&times;</button>');
		
		
			$('div.actual-filter i').remove();
			$('div.actual-filter').append(control);
			
			$('select.filter-select').val(0).change();
		});
		
		$(document).on('click','button.filter-remove',function(){
			$(this).parents('div.control').remove();
			
			var i = 0;
			$('div.actual-filter div.control').each(function(){
				i++;
			})
			
			if (i == 0){
				$('div.actual-filter').html('');
			}
		})
		
		$('input.marker-toggle').click(function(){
			if ($(this).hasClass('on')){
				$(this).removeClass('on');
				hideMarker();
			} else {
				$(this).addClass('on');
				showMarker();
			}
		})
		
		$('select.type_filter').change(function(){
			
			var logic = $(this).val();			

			$('.filter-logic').html('');
			$('.filter-logic').append($('select.'+logic).first().clone().addClass('new-filter'));
			
			$('select.new-filter').change(function(){
				var id = $(this).val();
				$('div.control-filter div.control').hide();
				$('div.control[rel='+id+']').show();
			});
			
			
			
		})
	});

	var map, pointarray, heatmap;
	var markers = [];
	
	var caseData = [];
		
		
	function readyMap(data){
		
		clearMarker();
		
		console.log(data.length);
		
		var newCaseData = []
		for (var i = 0; i < data.length; i++){
			var coord = data[i];
			newCaseData[i] = new google.maps.LatLng(coord.lat, coord.lng);	
		}
		
		var pointArray = new google.maps.MVCArray(newCaseData);
	
		heatmap.setData(pointArray);
		
		if ($('input.marker-toggle').hasClass('on')){
			var targetMap = map;
		} else {
			var targetMap = null;
		}

		for (var i = 0; i < newCaseData.length; i++){
					
			var marker = new google.maps.Marker({
				position: newCaseData[i],
				map: targetMap,
				title: data[i].ReportNumber
			});
			
			google.maps.event.addListener(marker, 'click', function(marker){
				showDialog('Maklumat Blackspot','Loading...')
				var rn = this.title;
				$.ajax({
					url: 'analytics/poi_info/'+rn,
					success: function (res){
						showDialog('Maklumat Blackspot', res);
					}
					
				})
			});
			
						
			markers.push(marker);
		
		}
		$('span.loading').html('');
		
	}
	
	function clearMarker(){
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(null);
		}
		markers = [];
	}
	
	function initialize() {
		var mapOptions = {
			zoom: 12,
			center: new google.maps.LatLng(3.1621662927672305, 101.71204558849335),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
	
		map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	
		var pointArray = new google.maps.MVCArray(caseData);
	
		heatmap = new google.maps.visualization.HeatmapLayer({
			data: pointArray
		});

		heatmap.setOptions({radius: 20});
			
		heatmap.setMap(map);
		
		for (var i = 0; i < caseData.length; i++){
		
			var marker = new google.maps.Marker({
				position: caseData[i],
				map: null
			});
			
			markers.push(marker);
		
		}
	}
	
	function showMarker(){
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(map);
		}
	}
	
	function hideMarker(){
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap(null);
		}
	}
	
	function toggleHeatmap() {
		heatmap.setMap(heatmap.getMap() ? null : map);
	}
	
	function changeGradient() {
		var gradient = [
			'rgba(0, 255, 255, 0)',
			'rgba(0, 255, 255, 1)',
			'rgba(0, 191, 255, 1)',
			'rgba(0, 127, 255, 1)',
			'rgba(0, 63, 255, 1)',
			'rgba(0, 0, 255, 1)',
			'rgba(0, 0, 223, 1)',
			'rgba(0, 0, 191, 1)',
			'rgba(0, 0, 159, 1)',
			'rgba(0, 0, 127, 1)',
			'rgba(63, 0, 91, 1)',
			'rgba(127, 0, 63, 1)',
			'rgba(191, 0, 31, 1)',
			'rgba(255, 0, 0, 1)'
			]
		heatmap.setOptions({
			gradient: heatmap.get('gradient') ? null : gradient
		});
	}
	
	function changeRadius() {
		heatmap.setOptions({radius: heatmap.get('radius') ? null : 20});
	}
	
	function changeOpacity() {
		heatmap.setOptions({opacity: heatmap.get('opacity') ? null : 0.2});
	}
	
	google.maps.event.addDomListener(window, 'load', initialize);
	

</script>
	<?php $this->load->view('analytics/sidebar'); ?>
	
	<div id="content">
		<div class="toolbar">
			<h3 class="header">Heat Map</h3>
		</div>
		<div class="sidebar" style="width: 250px; color: #fff; height: 450px; background: rgba(0,0,0,0.8); border-radius: 5px; position: fixed; z-index: 1000; right: 0px; margin-top: 30px;">
			<div class="padded" style="height: 430px; overflow: auto">
				<p class="notice">Sila pilih filter terlebih dahulu untuk menjana peta</p>
				
				<div style="display:none">
				<?php echo form_dropdown('type',$dropdown_general,'','class="type-filter general"'); ?>
				<?php echo form_dropdown('type',$dropdown_vehicle_car,'','class="type-filter vehicle-car"'); ?>
				<?php echo form_dropdown('type',$dropdown_vehicle_truck,'','class="type-filter vehicle-truck"'); ?>
				<?php echo form_dropdown('type',$dropdown_vehicle_motorcycle,'','class="type-filter vehicle-motorcycle"'); ?>
				<?php echo form_dropdown('type',$dropdown_vehicle_bus,'','class="type-filter vehicle-bus"'); ?>
				</div>
				
				<div class="filter heatmap-filter">
					<span class="filter-logic"><?php echo form_dropdown('type',$dropdown,'','class="filter-select" style="width: 200px"'); ?></span> <button class="filter-add lcms-btn">Add</button>
					<hr />
				</div>
			
			
				<div class="control-filter" style="border-radius: 5px; margin-bottom: 15px;">
					<?php foreach ($questions as $q): if (!$mapped[$q->map_to]): $mapped[$q->map_to] = true; ?>
					
					<div class="control" rel="<?php echo $q->map_to; ?>" style="display:none; border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px; margin: 5px 0;">
						<?php if ($q->type == 'matrix-answer'): $obj = json_decode($q->question); ?>
						
						<p><?php echo $obj->description; ?></p>
							<?php foreach ($obj->questions as $question): ?>
							<table class="control-box">
								<td class="label"><?php echo $question->question; ?></td>
								<td class="value"><input type="text" name="q[<?php echo $q->id; ?>][<?php echo $question->no; ?>]" /></td>
							</table>
							
							<?php endforeach; ?>
			
						<?php else: $qn = json_decode($q->answers);  ?>
			
							<p><?php echo $q->question; ?></p>
							<?php foreach ($qn as $answer): ?>
								<input type="checkbox" name="q[<?php echo $q->map_to; ?>][]" value="<?php echo $answer->value; ?>|<?php echo $answer->no; ?>|<?php echo $q->question; ?>|<?php echo $q->type; ?>" /> <?php echo $answer->value; ?><br />
							<?php endforeach; ?>
						
						
						<?php endif; ?>
					</div>
					<?php endif; endforeach; ?>
				</div>
			
	
				<iframe style="display:none" name="heatmap" id="heatmap"></iframe>
				<form method="post" target="heatmap" action="analytics/heatmap_process">
					<input type="checkbox" name="marker" class="marker-toggle" /> Marker
					<hr />
					<h3>Filter</h3>
					<div class="actual-filter">
						
					</div>
				
					<hr />
					<input class="btn btn-process" type="submit" value="Proses" /> <span class="loading"></span>
				</form>
	
			
			</div>

		</div>

		<div id="map-canvas" class="content-scroll">
		
		</div>
	</div>