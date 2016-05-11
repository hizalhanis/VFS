

//
// Vars
//

var max = 0; 
var lcmsCurrentLocation;
var lcmsCurrentType;
var lcmsCurrentAdded;
var lcmsCurrentEdit;
var lcmsCurrentEditId;
var lcmsCurrentRevert;
var lcmsCurrentItem;
var lcmsActiveLocation;
var lcmsFileObject;
var lcmsFocusAfterFile;
var lcmsAddedItems = [];
var lcmsSpotlight = false;
var lcmsToolbarCount = 0;
var lcmsAuthorMode = true;
var lcmsFileCatList = false;
//
// HTML Forms defaults
//

var mouseY;
var mouseX;

function urlencode (str) {
	str = escape(str);
	return str.replace(/[*+\/@]|%20/g,
		function (s) {
			switch (s) {
				case "*": s = "%2A"; break;
				case "+": s = "%2B"; break;
				case "/": s = "%2F"; break;
				case "@": s = "%40"; break;
				case "%20": s = "+"; break;
			}
			return s;
		});
}

$(document).ready(function (){
	$(document).mousemove(function(e){				    	
		mouseY = e.pageY;
		mouseX = e.pageX;
	});
	
	$('input.time').timeEntry({spinnerImage: base_url+'images/spinnerDefault.png'});
	$('input.date').dateEntry({dateFormat: 'dmy/',spinnerImage: base_url+'images/spinnerDefault.png'});

	
	$('input.lcms-author-mode').live('click',function(){

		if ($(this).attr('checked')){
			lcmsAuthorMode = true;
			$('ul.lcms-content li.lcms-empty-author').show().css('display','block !important');
			$('div.lcms-content-toolbar').show();
			$.cookie("lcmsAuthorMode", 1);
		} else {
			lcmsAuthorMode = false;
			$('ul.lcms-content li.lcms-empty-author').hide().css('display','none !important');
			$('div.lcms-content-toolbar').hide();
			$.cookie("lcmsAuthorMode", 0);
		}
	})
	
	$('a.lcms-author-mode').live('click',function(e){
	
	});
	


	//
	// Prepare LCMS AJAX controls
	// 
	$('div.lcms-control-form').live('mouseenter',function(){
		if (!$(this).hasClass('ui-draggable')){
	    	$(this).draggable();
	    	$(this).disableSelection();
	    }
	});
	
	$('*').live('change paste keydown click',function(){
		lcmsContentSpotlight();
	});
	
	$('body').append('<div class="lcms-status">Loading Controllers</div>');
	$('body').append('<div class="lcms-overlay ui-widget-overlay"></div>');
	$('body').append('<div class="lcms-dialog"><h3></h3><div class="lcms-dialog-body"></div></div>');
	$('body').append('<div class="lcms-window"><h3></h3><div class="lcms-window-body"></div></div>');	
	
	
		$(this).parents('div.lcms-content-wrapper').hover(function(e){
			var mouseX = e.pageX; 
			var mouseY = e.pageY;
			$(this).find('div.lcms-content-toolbar').show().css({
				'position': 'absolute',
				'top': mouseY,
				'left': mouseX
			});
		}, function(e){
			$(this).find('div.lcms-content-toolbar').hide();		
		});

	
	//
	// Load controller bar
	//
	
	$.ajax({
		type: "POST",
		url: base_url+"page/ajax/controller_bar/",
		data: 'current_page='+lcmsCurrentPage,
		success: function(html){
			$('body').append(html);
			$('#lcms-controller').animate({"top": "0px"}, "slow", function(){ $('.ui-widget-overlay').hide() });
			populateForm();
			
			if ($.cookie("lcmsAuthorMode") == 0){
			    $('ul.lcms-content li.lcms-empty-author').hide().css('display','none !important');
			    $('div.lcms-content-toolbar').hide();
			    lcmsAuthorMode = false;
			    $('input.lcms-author-mode').removeAttr('checked');
			}
	
			$('select.lcms-jump-nav').change(function (){
				location.href = base_url + 'p/' + $(this).val();
				
			});
		}
	});
	
	//
	// Apply sortable towards navigation
	//
	
	$('ul.lcms-navigation').sortable({axis:'x',scroll:true, scrollSensitivity: 5, tolerance: 'intersect', helper: 'clone'});
	$('ul.lcms-navigation').disableSelection();
	$('ul.lcms-navigation a').disableSelection();
	$('ul.lcms-navigation span').disableSelection();
	$('ul.lcms-navigation li').disableSelection();
	
	populateControls();
});

function populateControls(){

	//
	// Populate add content toolbar event
	//
	
	$('a.lcms-add-content').click(function (event){
		showAddToolbar();
		event.preventDefault();

		lcmsCurrentLocation = $(this).attr('rel');
		lcmsActiveLocation = $('ul[rel='+lcmsCurrentLocation+']');
		
	});
	
	//
	// Populate content controls
	//
	
	
	
	$('ul.lcms-content > li').live({
		mouseenter: function(e){
				    	
				    	if ($(this).attr('rel') == lcmsCurrentEditId) return;				    	
				    	
				    	if (!lcmsAuthorMode) return;

				    	var that = this;
				    	
				    	lcmsCurrentItem = $(this);
				    	
				    	lcmsToolbarCount = setTimeout(function(e){

					    	$(that).children('div.lcms-content-controls').show().css({
    			    			'position': 'absolute',
    			    			'top': mouseY,
    			    			'left': mouseX
    			    		}); 
    			    		
    			    		if (lcmsCurrentEdit == null && lcmsCurrentAdded == null){
	    			    		lcmsOnSpotlight();
	    			    		lcmsContentSpotlight();
    			    		}

				    	}, 300);

				    }, 
		mouseleave: function(){ 
						clearTimeout(lcmsToolbarCount);
   			    		if (lcmsCurrentEdit == null && lcmsCurrentAdded == null){
							lcmsOffSpotlight();
						}
	    				$(this).children('div.lcms-content-controls').delay(1000).hide(); 
	    			}
	});
    
	$('ul.lcms-content').sortable({ tolerance: 'pointer', helper: 'clone', handle: 'a.lcms-drag-handle', stop: function(){
			var order = '', x = 0;
			var location = $(this).attr('rel');
			$(this).find("li").each(function(i){ 
				if (x != 0) order += ',' + $(this).attr('rel');
				else order += $(this).attr('rel');
				x++;
			});
			$('div.lcms-status').html('Saving content order');
			$('div.lcms-status').show();
			$.ajax({
				type: "POST",
				url: base_url+"page/ajax/save_content_order",
				data: 'order=' + order + '&location=' + location,
				success: function(html){
					if (html){
					$('div.lcms-status').html('Content order has been saved');
					setTimeout(function() { 
						$('div.lcms-status').fadeOut('slow', function(){
							$(this).css('display','none');
						}); 
					}, 1000);
					} else alert('Error saving content order');
				}
			});		
			event.preventDefault();
		}
	});
		
	$('div.lcms-content-controls a.lcms-content-delete').live('click',function(){
		var id = $(this).parents('li').attr('rel');
		
		createDialog('confirm', 'Do you confirm to delete the content item?', function(){
			$('div.lcms-status').html('Deleting content item');
			$('div.lcms-status').show();
			$.ajax({
				type: "POST",
				url: base_url+"page/ajax/delete_content",
				data: 'id=' + id,
				success: function(html){
					if (html){
						$('div.lcms-status').html('Content has been deleted');
						setTimeout(function() { 
							$('div.lcms-status').fadeOut('slow', function(){
								$(this).css('display','none');
							}); 
						}, 1000);
						$('li[rel='+id+']').fadeOut('slow', function(){
							$('li[rel='+id+']').remove();
						});
						
						lcmsDiscard();
						
					} else alert('Error deleting content');
				}
			});
		});
	});
	
	$('div.lcms-content-controls a.lcms-content-edit').live('click',function(){
		var id = $(this).parents('li').addClass('lcms-current-edit-item').attr('rel');

		lcmsCurrentEditId	= id;
		lcmsCurrentEdit		= $('li[rel='+id+']');
		lcmsCurrentRevert	= $('li[rel='+id+']').html();
		lcmsCurrentType		= $('li[rel='+id+']').attr('type');
		lcmsCurrentItem		= lcmsCurrentEdit;
		
		lcmsOnSpotlight();
		lcmsContentSpotlight();
		
		$('div.lcms-status').html('Loading contents');
		$('div.lcms-status').show();
		
		$.ajax({
	    	type: "POST",
	    	url: base_url+"page/ajax/get_content_data",
	    	data: 'id=' + id,
	    	dataType: 'json',
	    	success: function(data){
	    	
	    		eval(data.type+'.el = lcmsCurrentEdit;');
	    	
		    	if (!lcmsAddedItems[data.type]){

		    	    eval(data.type + '.bind()');
		    	    $('*').live('change paste keydown click',function(){
		    	    	eval(data.type + '.inputs()');
		    	    });

		    	    lcmsAddedItems[data.type] = true;
		    	}
		    	
		    	eval(data.type+'.edit(lcmsCurrentEdit, data)');
	    	    eval(data.type + '.inputs()');		    	
		    	
		    	$('div.lcms-content-controls').hide();
	    		
		    	$('div.lcms-status').html('Content ready to be edited');
		    	setTimeout(function() { 
		    	    $('div.lcms-status').fadeOut('slow', function(){
		    	    	$(this).css('display','none');
		    	    }); 
		    	}, 1000);
	    			
	    	}
	    });

	});
	
	$('a.lcms-versions-handle').live('click',function(){
		var id = $(this).parents('li').attr('rel');	
		lcmsCurrentEditId = id;
		lcmsCurrentItem = $(this).parents('li');
		
		$('div.lcms-content-controls').hide();
		$('div.lcms-status').html('Loading content revisions').show();
		
		$.ajax({
        	type: "POST",
        	url: base_url+"page/ajax/content_revisions",
        	data: 'id=' + id,
        	success: function(html){
        	

	   	    	$('div.lcms-status').html('Content revisions loaded');
	   	    	
	   	    	$('#lcms-content-versions div.lcms-versions-placeholder').html(html);
        		showVersionsToolbar();

    			setTimeout(function() { 
    				$('div.lcms-status').fadeOut('slow', function(){
    					$(this).css('display','none');
    				}); 
    			}, 1000);
        	}
        });
	});
	
	$('a.lcms-published-handle').live('click',function(){
		var id = $(this).parents('li').attr('rel');
		$('div.lcms-status').html('Updating content publish status').show();
		
		if ($(this).hasClass('lcms-published-handle-on')){
			$(this).removeClass('lcms-published-handle-on');
			var published = 0;
		} else {
			$(this).addClass('lcms-published-handle-on');
			var published = 1;
		}

		$.ajax({
        	type: "POST",
        	url: base_url+"page/ajax/published",
        	data: 'id=' + id + '&published=' + published,
        	success: function(html){
	   	    	$('li[rel='+id+']').attr('published',published);
        		if (published == 1){
	    	    	$('div.lcms-status').html('Content has been set to published');
	    	    } else {
	    	    	$('div.lcms-status').html('Content has been set to unpublished');		    	    
	    	    }
    			setTimeout(function() { 
    				$('div.lcms-status').fadeOut('slow', function(){
    					$(this).css('display','none');
    				}); 
    			}, 1000);
        	}
        });
	
	});
	
	$('a.lcms-common-handle').live('click',function(){
		var id = $(this).parents('li').attr('rel');
		$('div.lcms-status').html('Updating content common status').show();
		
		if ($(this).hasClass('lcms-uncommon-handle')){
			$(this).removeClass('lcms-uncommon-handle');
			var common = 1;
		} else {
			$(this).addClass('lcms-uncommon-handle');
			var common = 0;
		}


		$.ajax({
        	type: "POST",
        	url: base_url+"page/ajax/common",
        	data: 'id=' + id + '&common=' + common,
        	success: function(html){

    	    	$('li[rel='+id+']').attr('common',common);
    	    	if (common == 1){
	    			$('div.lcms-status').html('Content is now common');
    			} else {
	    			$('div.lcms-status').html('Content is now unique');
    			}
    			setTimeout(function() { 
    				$('div.lcms-status').fadeOut('slow', function(){
    					$(this).css('display','none');
    				}); 
    			}, 1000);
        	}
        });
	
	});
}

function populateForm(){
	$.ajax({
		type: "POST",
		url: base_url+"page/ajax/forms/",
		data: 'current_page='+lcmsCurrentPage,
		success: function(html){
			$('body').append(html);
			populateLayouts();
			populatePages();
			$('div.lcms-status').html('Loading Forms');
		}
	});

}

function populateEvents(){

	//
	// Delete current viewing page
	//
	
	$('li.lcms-delete-page > a').click(function(event){
		createDialog('confirm', 'Do you confirm to delete this page?', function(){
			$('div.lcms-status').html('Deleting current page');
			$('div.lcms-status').show();
			$.ajax({
				type: "POST",
				url: base_url+"page/ajax/delete_page",
				data: 'page=' + lcmsCurrentPage,
				success: function(html){
					if (html){
						$('div.lcms-status').html('Page has been deleted');
						setTimeout(function() { 
							$('div.lcms-status').fadeOut('slow', function(){
								$(this).css('display','none');
							}); 
							location.href = base_url;
						}, 1000);						
					} else alert('Error deleting page');
				}
			});
		});

	});
	
	//
	// Loads file manager and show on the screen
	//
	
	$('a.lcms-file-manager').click(function(e){
		e.preventDefault();
		
		$('div.lcms-status').html('Loading file manager');
		$('div.lcms-status').show();
		
		$.ajax({
			type: "POST",
			url: base_url+"file",
			success: function(html){
				if (html){
				$('div.lcms-status').html('File manager loaded');

				$('#lcms-fileman-container').html(html).fadeIn();
				setTimeout(function() { 
					$('div.lcms-status').fadeOut('slow', function(){
						$(this).css('display','none');
					}); 
				}, 1000);
				} else alert('Error loading file manager');
			}
		});		

	});
	

	//
	// Save navigation page order
	//

	$('li.lcms-save-order > a').click(function(event){
		var order = '', x = 0;
		$("ul.lcms-navigation").find("li").each(function(i){ 
			if (x != 0) order += ',' + $(this).children('a').attr('name');
			else order += $(this).children('a').attr('name');
			x++;
		});
		$('div.lcms-status').html('Saving page order');
		$('div.lcms-status').show();
		
		$.ajax({
			type: "POST",
			url: base_url+"page/ajax/save_order",
			data: 'order=' + order,
			success: function(html){
				if (html){
				$('div.lcms-status').html('Page order has been saved');
				setTimeout(function() { 
					$('div.lcms-status').fadeOut('slow', function(){
						$(this).css('display','none');
					}); 
				}, 1000);
				} else alert('Error saving page order');
			}
		});		
		event.preventDefault();
	});
	
	//
	// Show form to create new page
	//

	$('li.lcms-new-page > a').click(function(event){
		
		overlayShow();

		var windowWidth = $(window).width();
		var windowHeight = $(window).height();

		var formWidth = $('form.lcms-new-page-form').width();
		var formHeight = $('form.lcms-new-page-form').height();
		
		var positionTop = (windowHeight / 2) - (formHeight / 2);
		var positionLeft = (windowWidth / 2) - (formWidth / 2);
		
		$('form.lcms-new-page-form').css('top',positionTop);
		$('form.lcms-new-page-form').css('left',positionLeft);
		$('form.lcms-new-page-form').show();
		
		//
		// Create new page action
		// initiated by button.lcms-create-page
		//
		
		
		
		$('form.lcms-new-page-form input[type=text]').keypress(function(){
			$(this).css('background','#FFFFFF');
		});
		$('form.lcms-new-page-form input[name=layout]').click(function(){
			$('#lcms-layouts').css('background','none');
		});
		populateVUI();
	});
	
	
	
	$('li.lcms-edit-page > a').click(function(event){
		
		overlayShow();

		var windowWidth = $(window).width();
		var windowHeight = $(window).height();

		var formWidth = $('form.lcms-edit-page-form').width();
		var formHeight = $('form.lcms-edit-page-form').height();
		
		var positionTop = (windowHeight / 2) - (formHeight / 2);
		var positionLeft = (windowWidth / 2) - (formWidth / 2);
		
		$('form.lcms-edit-page-form').css('top',positionTop);
		$('form.lcms-edit-page-form').css('left',positionLeft);
		$('form.lcms-edit-page-form').show();
		//
		// Edit page action
		// initiated by button.lcms-edit-page
		//
		
		$('form.lcms-edit-page-form input[type=text]').keypress(function(){
			$(this).css('background','#FFFFFF');
		});
		$('form.lcms-edit-page-form input[name=layout]').click(function(){
			$('#lcms-edit-layouts').css('background','none');
		});
		
		populateVUI();
	});
	


}

function populateLayouts(){
	$.ajax({
		type: "POST",
		url: base_url+"page/ajax/layouts",
		data: 'current_page='+lcmsCurrentPage,
		success: function(html){
			$('#lcms-layouts').html(html);
			populateEvents();
		}
	});
}

function populatePages(){

	$.ajax({
		type: "POST",
		url: base_url+"page/ajax/page_list/",
		data: 'current_page='+lcmsCurrentPage,
		dataType: 'json',
		success: function(data){
		

			
			$('#lcms-parent').html(data.new_page_list);
			$('#lcms-edit-parent').html(data.edit_page_list);
			$('select.lcms-jump-nav').html(data.jump_nav_list);
			$('div.lcms-status').html('Controller started');

			setTimeout(function() { 
				$('div.lcms-status').fadeOut('slow', function(){
					$(this).css('display','none');
				}); 
			}, 1000);			
		}
	});
	
		
}

function populateVUI(){

	// Begin VUI
	
	$('a.lcms-toggle').click(function(){
		if ($(this).hasClass('v-toggle-active')){
			$(this).removeClass('v-toggle-active');
		} else {

			$(this).addClass('v-toggle-active');
		}
	});
	
	$('span.lcms-btn-group a.lcms-toggle').click(function(){
		
		$(this).parents('span').children('a.lcms-toggle').removeClass('v-toggle-active');
		$(this).addClass('v-toggle-active');
	});
	
	$('span.lcms-btn-group-vertical a.lcms-toggle').click(function(){
		$(this).parents('span').children('a.lcms-toggle').removeClass('v-toggle-active');
		$(this).addClass('v-toggle-active');
	});
	
	$('input.lcms-number').keypress(function(event){
		var key = event.which;
		var keychar = String.fromCharCode(key);
		if ((key==null) || (key==0) || (key==8) ||  (key==9) || (key==13) || (key==27) ) return true;
		else if ((("0123456789").indexOf(keychar) > -1)) return true;
		else event.preventDefault();
	});
	
	$('input.lcms-number-decimal').keypress(function(event){
		var key = event.which;
		var keychar = String.fromCharCode(key);
		if ((key==null) || (key==0) || (key==8) ||  (key==9) || (key==13) || (key==27) ) return true;
		else if ((("0123456789").indexOf(keychar) > -1)) return true;
		else if (keychar == "."){
			var value = $(this).val();
			if (value.indexOf('.') > -1){
				event.preventDefault()
			}
			return true;
		}
		else event.preventDefault();
	});
	

    $("form.lcms-horizontal label").each(function(){
		if ($(this).width() > max) max = $(this).width();    
    });
    $("label").width(max);
    
    $("form.lcms-form p.lcms-hint").css('padding-left',max+ 25);
    
    $("form.lcms-form .lcms-close-btn").click(function(event){
    	$(this).parents('form').hide();
    	overlayHide();	    	
  		event.preventDefault();  		
    });
    
    // End VUI
}

function overlayShow(){
	$('.ui-widget-overlay').show();
}

function overlayHide(){
	$('.ui-widget-overlay').hide();
}

function hideAddToolbar(){
	overlayHide();
	$('div.lcms-new-content').hide();
}
function hideVersionsToolbar(){
	lcmsOffSpotlight();
	lcmsCurrentEditId = null;
	$('#lcms-content-versions').hide();
}


function showAddToolbar(){
	var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    var formWidth = $('div.lcms-new-content').width();
    var formHeight = $('div.lcms-new-content').height();
    var positionTop = (windowHeight / 2) - (formHeight / 2);
    var positionLeft = (windowWidth / 2) - (formWidth / 2);
    
    $('div.lcms-new-content').css('top',positionTop-50);
    $('div.lcms-new-content').css('left',positionLeft);
    overlayShow();
    $('div.lcms-new-content').show();

    $('div.lcms-new-content a.lcms-close-btn').click(function(event){
    	hideAddToolbar();
    	event.preventDefault();
    });
}

function showVersionsToolbar(){
	var windowWidth = $(document).width();
    var windowHeight = $(document).height();
    var formWidth = $('#lcms-content-versions').width();
    var formHeight = $('#lcms-content-versions').height();
    var positionTop = (windowHeight / 2) - (formHeight / 2);
    var positionLeft = (windowWidth / 2) - (formWidth / 2);
    
    if (!$('#lcms-content-versions').hasClass('ui-draggable')){
	    $('#lcms-content-versions').draggable();
	    $('#lcms-content-versions').disableSelection();
    }
    

    $('#lcms-content-versions').css('top',positionTop);
    $('#lcms-content-versions').css('left',positionLeft);
    $('#lcms-content-versions').show();
    
    lcmsOnSpotlight();
    lcmsContentSpotlight();

    $('#lcms-content-versions a.lcms-close-btn').click(function(event){
    	hideVersionsToolbar();
    	event.preventDefault();
    });
}


function createWindow(body, title){
	$('div.lcms-dialog h3').html(title);
	$('div.lcms-dialog-body').html(body);
	
	var windowWidth = $(window).width();
	var windowHeight = $(window).height();
	
	$('div.lcms-dialog').width('auto');
	$('div.lcms-dialog').height('auto');

	var formWidth = $('div.lcms-dialog').width();
	var formHeight = $('div.lcms-dialog').height();
	var positionTop = (windowHeight / 2) - (formHeight / 2);
	var positionLeft = (windowWidth / 2) - (formWidth / 2);
	
		
	$('div.lcms-dialog').css('top',positionTop);
	$('div.lcms-dialog').css('left',positionLeft);
	overlayShow();
	$('div.lcms-dialog').css('display','block');


}

function createDialog(type, body, fn1, fn2){
	if (type == 'confirm'){
		$('div.lcms-dialog h3').html('Confirm');
		var buttons = '<div class="lcms-dialog-buttons"><a class=\"lcms-btn lcms-btn-no\">No</a> <a class=\"lcms-btn lcms-btn-yes\">Yes</a></div>';
		$('div.lcms-dialog-body').html(body + buttons);
		if (!fn2){
			$('a.lcms-btn-no').click(function(){ hideDialog() });
		} else {
			$('a.lcms-btn-no').click(fn2);
		}
		$('a.lcms-btn-yes').click(fn1);
		$('a.lcms-btn-yes').bind('click', function(){ hideDialog() });
	} else if (type == 'ok'){
		$('div.lcms-dialog h3').html('Alert');
		var buttons = '<div class="lcms-dialog-buttons"><a class=\"lcms-btn lcms-btn-ok\">OK</a></div>';
		$('div.lcms-dialog-body').html(body + buttons);
		if (!fn1){
			$('a.lcms-btn-ok').click(function(){ hideDialog() });
		} else {
			$('a.lcms-btn-ok').click(fn1)
		}
	}
	var windowWidth = $(window).width();
	var windowHeight = $(window).height();
	var formWidth = $('div.lcms-dialog').width();
	var formHeight = $('div.lcms-dialog').height();
	var positionTop = (windowHeight / 2) - (formHeight / 2);
	var positionLeft = (windowWidth / 2) - (formWidth / 2);
	
	$('div.lcms-dialog').css('top',positionTop);
	$('div.lcms-dialog').css('left',positionLeft);
	overlayShow();
	$('div.lcms-dialog').show();
} 

function hideDialog(){
	$('div.lcms-dialog').hide();
	overlayHide(); 
}

function selectFile(type, focusObj){
	$('div.lcms-status').html('Loading file manager');
	$('div.lcms-status').show();
	lcmsFocusAfterFile = $(focusObj);

	$.ajax({
		type: "POST",
		url: base_url+"file/select/"+type,
		success: function(html){
			if (html){
			$('div.lcms-status').html('File manager loaded');
			createWindow(html, 'File Select');
			setTimeout(function() { 
				$('div.lcms-status').fadeOut('slow', function(){
					$(this).css('display','none');
				}); 
			}, 1000);
			} else alert('Error loading file manager');
		}
	});		
}


function ajaxData(p){
	var first = true;
	var string = '';
	
	for (var key in p) {
		if (p.hasOwnProperty(key)) {
			if (first){
				string += key + "=" + encodeURIComponent(p[key]);
				first = false;
			} else {
				string += '&' + key + "=" + encodeURIComponent(p[key]);
			}
		}	
	}
	
	return string;
}

function lcmsReloadContent(id, callback){
	$.ajax({
		url		: 'page/ajax/reload',
		type	: 'post',
		data	: ajaxData({'id': id}),
		success	: function(html){
			$('li[rel='+id+']').html(html);
			try { callback(html); } catch (e){};
		}
	})	
}

function lcmsGetContent(id, callback){
	$.ajax({
		url		: 'page/ajax/reload',
		type	: 'post',
		data	: ajaxData({'id': id}),
		success	: function(html){
			try { callback(html); } catch (e){};
		}
	})	
}

function lcmsClearPane(){
	$('#lcms-editor-pane').html('');
}


var lcmsStatus = {
	set: function (string){
		$('div.lcms-status').html(string);
		return this;
	},
	fade: function (delay){
		if (!delay) delay = 1000;
		setTimeout(function() { 
			$('div.lcms-status').fadeOut('slow', function(){
				$(this).css('display','none');
			}); 
		}, delay);
	}
}


function lcmsAddItem(control){
	hideAddToolbar();
	lcmsCurrentType = control;
	$('<li class="lcms-current-added-item"></li>').appendTo(lcmsActiveLocation).each(function(){
		lcmsCurrentAdded = this;
		lcmsCurrentItem	= this;
		
		lcmsOnSpotlight();
		lcmsContentSpotlight();
		
		if (!lcmsAddedItems[control]){
			eval(control + '.bind()');
			$('*').live('change paste keydown click',function(){
				eval(control + '.inputs()');
			});

			lcmsAddedItems[control] = true;
		}
		eval(control + '.add($(this))');
		eval(control + '.inputs()');
	});

}

function lcmsSave(content, options, css_class, callback){
	$(lcmsCurrentAdded).removeClass('lcms-current-added-item').html('<div style="text-align: center;"><img src="images/loader.gif" /></div>');
	
	$('div.lcms-status').html('Saving content item');
	$('div.lcms-status').show();


	$.ajax({
		url: 'page/ajax/save_content',
		type: 'post',
		dataType: 'json',
		data: ajaxData({
			'location'	: lcmsCurrentLocation,
			'page'		: lcmsCurrentPage,
			'type'		: lcmsCurrentType,
			'content'	: content,
			'options'	: options,
			'class'		: css_class
		}),
		success: function (result){
		
			$('div.lcms-status').html('Content has been saved.');
			setTimeout(function() { 
				$('div.lcms-status').fadeOut('slow', function(){
					$(this).css('display','none');
				}); 
			}, 1000);
			
			lcmsOffSpotlight();
					
			$(lcmsCurrentAdded).remove();
			$(lcmsActiveLocation).append(result.html);
			lcmsCurrentAdded = $(lcmsActiveLocation).find('li:last');
			eval(result.type+'el = lcmsCurrentAdded;');
			try { callback(result.id, result.data); } catch (e){}
			lcmsCurrentAdded = null;
			
			lcmsDiscard();
			
		}
	});
}

function lcmsSavePublish(content, options, css_class, callback){
	$(lcmsCurrentAdded).removeClass('lcms-current-added-item').html('<div style="text-align: center;"><img src="images/loader.gif" /></div>');

	$('div.lcms-status').html('Saving and publishing content item');
	$('div.lcms-status').show();
	
	$.ajax({
		url: 'page/ajax/save_publish_content',
		type: 'post',
		dataType: 'json',
		data: ajaxData({
			'location'	: lcmsCurrentLocation,
			'page'		: lcmsCurrentPage,
			'type'		: lcmsCurrentType,
			'content'	: content,
			'options'	: options,
			'class'		: css_class
		}),
		success: function (result){
			
			$('div.lcms-status').html('Content has been saved and published');
			setTimeout(function() { 
				$('div.lcms-status').fadeOut('slow', function(){
					$(this).css('display','none');
				}); 
			}, 1000);
			
			lcmsOffSpotlight();
					
			$(lcmsCurrentAdded).remove();
			$(lcmsActiveLocation).append(result.html);
			lcmsCurrentAdded = $(lcmsActiveLocation).find('li:last');
			eval(result.type+'el = lcmsCurrentAdded;');
			try { callback(result.id, result.data); } catch (e){}
			lcmsCurrentAdded = null;
			
			lcmsDiscard();
		}
	});
}


function lcmsUpdate(id, content, options, css_class, callback){

	$('div.lcms-status').html('Saving content item');
	$('div.lcms-status').show();
	

	$.ajax({
		url: 'page/ajax/update_content',
		type: 'post',
		dataType: 'json',
		data: ajaxData({
			'id'		: id,
			'location'	: lcmsCurrentLocation,
			'page'		: lcmsCurrentPage,
			'type'		: lcmsCurrentType,
			'content'	: content,
			'options'	: options,
			'class'		: css_class
		}),
		success: function (result){

		
			$('div.lcms-status').html('Content has been saved');
			setTimeout(function() { 
				$('div.lcms-status').fadeOut('slow', function(){
					$(this).css('display','none');
				}); 
			}, 1000);
					

			$(lcmsCurrentEdit).replaceWith(result.html);
			lcmsCurrentEditId = null;

			
			lcmsOffSpotlight();

			try { callback(result.id, result.data); } catch (e){}
			lcmsCurrentEdit = null;
			
			lcmsDiscard();	
		}
	});
}

function lcmsUpdatePublish(id, content, options, css_class, callback){

	$('div.lcms-status').html('Saving and publishing content item');
	$('div.lcms-status').show();
	
	
	$.ajax({
		url: 'page/ajax/update_publish_content',
		type: 'post',
		dataType: 'json',
		data: ajaxData({
			'id'		: id,
			'location'	: lcmsCurrentLocation,
			'page'		: lcmsCurrentPage,
			'type'		: lcmsCurrentType,
			'content'	: content,
			'options'	: options,
			'class'		: css_class
		}),
		success: function (result){
		
			$('div.lcms-status').html('Content has been saved and published');
			setTimeout(function() { 
				$('div.lcms-status').fadeOut('slow', function(){
					$(this).css('display','none');
				}); 
			}, 1000);
					
			$(lcmsCurrentEdit).replaceWith(result.html);
			lcmsCurrentEditId = null;
			
			lcmsOffSpotlight();

			try { callback(result.id, result.data); } catch (e){}
			lcmsCurrentEdit = null;
			
			lcmsDiscard();
		}
	});
}

function lcmsDiscard(){
	$(lcmsCurrentEdit).removeClass('lcms-current-edit-item').html(lcmsCurrentRevert);
	$(lcmsCurrentEdit).find('div.lcms-content-controls').hide();
	lcmsCurrentEditId = null;
	lcmsCurrentEdit = null;
	lcmsCurrentAdded = null;
	lcmsCurrentItem = null;
	lcmsOffSpotlight();
}




function validateEmail(email) { 
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function lcmsOnSpotlight(){
	lcmsSpotlight = true;
}


function lcmsContentSpotlight(){
	return;
	if (!lcmsSpotlight) return;
	setTimeout(function(){
		var el = lcmsCurrentItem;
		
		if (!el) return;
		
		var offset = $(el).offset();
		var width = $(el).width();
		var height = $(el).height();
	
		$('div.lcms-spotlight-left').css({
			'height': $(document).height(),
			'top'	: 0,
			'left'	: 0,
			'width'	: offset.left - 5
		});
		
		$('div.lcms-spotlight-top').css({
			'height': offset.top - 5,
			'top'	: 0,
			'left'	: offset.left - 5,
			'width'	: width + 10
		});
		
		$('div.lcms-spotlight-bottom').css({
			'height': $(document).height() - (offset.top + height + 5),
			'top'	: offset.top + height + 5,
			'left'	: offset.left - 5,
			'width'	: width + 10
		});
		
		$('div.lcms-spotlight-right').css({
			'height': $(document).height(),
			'top'	: 0,
			'left'	: offset.left + width + 5,
			'width'	: $(document).width() - (offset.left + width + 5) 
		});
		
		$('div.lcms-spotlight').fadeIn();
	
	}, 10);
	
}

function lcmsOffSpotlight(){
	lcmsSpotlight = false;
	$('div.lcms-spotlight').fadeOut();
}