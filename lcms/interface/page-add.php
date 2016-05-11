<script type="text/javascript">

$(document).ready(function(){

	$('button.lcms-create-page').click(function(event){
		var title 	= $('form.lcms-new-page-form input[name=title]').val();
	    var name 	= $('form.lcms-new-page-form input[name=name]').val();
	    var layout	= $('form.lcms-new-page-form input[name=layout]:checked').val() ? $('form.lcms-new-page-form input[name=layout]:checked').val() : '';
	    var parent	= $('form.lcms-new-page-form select[name=parent]').val() ? $('form.lcms-new-page-form select[name=parent]').val() : '';
	    var in_nav	= $('form.lcms-new-page-form input[name=in_nav]').attr('checked') ? 1 : 0;
	    var srchbl	= $('form.lcms-new-page-form input[name=searchable]').attr('checked') ? 1 : 0;
	    var redirect= $('form.lcms-new-page-form input[name=redirect_url]').val() ? $('form.lcms-new-page-form input[name=redirect_url]').val() : '';
	    
	    if (!title) $('form.lcms-new-page-form input[name=title]').css('background','#f9fec1');
	    if (!name) $('form.lcms-new-page-form input[name=name]').css('background','#f9fec1');
	    if (!layout) $('#lcms-layouts').css('background', '#f9fec1');
	    
	    if (title && name && layout){
	    	$('div.lcms-status').html('Creating New Page');
	    	$('div.lcms-status').show();
	    	$.ajax({
	    		type: "POST",
	    		data: ajaxData({
		    			'title'		: title,
		    			'name'		: encodeURIComponent(name),
		    			'layout'	: layout,
		    			'parent'	: parent,
		    			'in_nav'	: in_nav,
		    			'redirect'	: redirect,
		    			'searchable': srchbl
		    		}),
	    		url: base_url+"page/ajax/new_page",
	    		success: function(html){
	    			if (html){
	    				$('div.lcms-status').html('Page '+ title +' has been created');
	    				setTimeout(function() { 
	    					$('div.lcms-status').fadeOut('slow', function(){
	    						$(this).css('display','none');
	    					}); 
	    				}, 1000);
	    				
	    				$('form.lcms-new-page-form input[type=text]').val('');
	    				$('form.lcms-new-page-form input[type=checkbox]').removeAttr('checked');
	    				$('form.lcms-new-page-form input[type=radio]').removeAttr('checked');
	    				$('form.lcms-new-page-form select').val('');;
	    				
	    				if (in_nav){
	    					var name_uri = name.replace(/[^A-Za-z0-9]+/,'_');
	    					$('ul.lcms-navigation').append('<li class="lcms-new"><a href="'+base_url+'p/'+name_uri+'">'+title+'</a></li>');
	    					$('ul.lcms-navigation li.lcms-new').show('slow');
	    				}
	    				
	    				populatePages();
	    				
	    				overlayHide();
	    				$('form.lcms-new-page-form').hide();
	    			} else {
	    				alert('Failed to create new page.');
	    			}
	    		}
	    	});
	    }
	    event.preventDefault();
	});
});

</script>

	<form class="lcms-form lcms-horizontal lcms-new-page-form">
	    <h3>Create New Page</h3>
	    <fieldset>
	    	<label for="title"><span class="red">*</span> Page Title</label>
	    	<input type="text" class="lcms-txt" name="title" id="title" /><br />
	    	
	    	<label for="name"><span class="red">*</span> Page Name</label>
	    	<input type="text" class="lcms-txt" name="name" id="name" /><br />
	    	<p class="lcms-hint">Page name will be used to create the URL of the page. It should be alpha in lowercase.</p>

	    	<label for="redirect">Redirect URL</label>
	    	<input type="text" class="lcms-txt" name="redirect_url" id="redirect_url" value="<?php echo $page->redirect_url; ?>" /><br />
	    	<p class="lcms-hint">If set page link will be redirect to the specified URL.</p>

	    	
	    	<label for="parent">Parent Page</label>
	    	<select id="lcms-parent" name="parent"><?php echo $pages; ?></select><br />

	    	<label for="in-nav">Appear in Navigation</label>
	    	<input type="checkbox" name="in_nav" value="1" /> Yes<hr />
	    	<label for="in-nav">Searchable</label>
	    	<input type="checkbox" name="searchable" value="1" <?php echo $searchable; ?>/> Yes<hr />

	    	<label><span class="red">*</span> Layout</label>
	    	<div id="lcms-layouts">Loading layouts...</div>					
	    	<button class="lcms-btn lcms-close-btn">Cancel</button> <button class="lcms-btn lcms-create-page">Create Page</button>
	    </fieldset>
	</form>
			