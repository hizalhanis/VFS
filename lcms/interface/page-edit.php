<script type="text/javascript">

$(document).ready(function(){
	
	$('button.lcms-edit-page').click(function(event){
		var title 	= $('form.lcms-edit-page-form input[name=title]').val();
	    var name 	= $('form.lcms-edit-page-form input[name=name]').val();
	    var layout	= $('form.lcms-edit-page-form input[name=layout]:checked').val() ? $('form.lcms-edit-page-form input[name=layout]:checked').val() : '';
	    var parent 	= $('form.lcms-edit-page-form select[name=parent]').val() ? $('form.lcms-edit-page-form select[name=parent]').val() : '';
	    var in_nav	= $('form.lcms-edit-page-form input[name=in_nav]').attr('checked') ? 1 : 0;
	    var srchbl	= $('form.lcms-edit-page-form input[name=searchable]').attr('checked') ? 1 : 0;
	    var redirect= $('form.lcms-edit-page-form input[name=redirect_url]').val() ? $('form.lcms-edit-page-form input[name=redirect_url]').val() : '';
	    
	    if (!title) $('form.lcms-edit-page-form input[name=title]').css('background','#f9fec1');
	    if (!name) $('form.lcms-edit-page-form input[name=name]').css('background','#f9fec1');
	    if (!layout) $('#lcms-edit-layouts').css('background', '#f9fec1');
	    
	    if (title && name && layout){
	    	$('div.lcms-status').html('Updating Page');
	    	$('div.lcms-status').show();
	    	$.ajax({
	    		type: "POST",
	    		data: ajaxData({
		    			'title'		: title,
		    			'name'		: encodeURIComponent(name),
		    			'layout'	: layout,
		    			'parent'	: parent,
		    			'redirect'	: redirect,
		    			'in_nav'	: in_nav,
		    			'srchbl'	: srchbl
		    		}),
	    		url: base_url+"page/ajax/edit_page",
	    		success: function(html){
	    			if (html == 'success'){
	    				$('div.lcms-status').html('Page '+ title +' has been updated');

	    				setTimeout(function() { 
	    					$('div.lcms-status').fadeOut('slow', function(){
	    						$(this).css('display','none');
	    					}); 
	    				}, 1000);
	    				
	    				populatePages();

	    				if (in_nav){
	    					if (!$('ul.lcms-navigation li.current').html()){
	    						var name_uri = name.replace(/[^A-Za-z0-9]+/,'_');
	    						$('ul.lcms-navigation').append('<li class="lcms-new"><a href="'+base_url+'p/'+name_uri+'">'+title+'</a></li>');
	    						$('ul.lcms-navigation li.lcms-new').show('slow');
	    					} else {
	    						var name_uri = name.replace(/[^A-Za-z0-9]+/,'_');
	    						$('ul.lcms-navigation li.current a').attr('href',base_url+'p/'+name_uri);
	    						$('ul.lcms-navigation li.current a').html(title);
	    					}
	    				} else {
	    					$('ul.lcms-navigation li.current').hide();
	    				}
	    				
	    				overlayHide();
	    				$('form.lcms-edit-page-form').hide();
	    			} else {
	    				alert('Page name is already used. Try other names.');
	    			}
	    		}
	    	});
	    }
	    event.preventDefault();
	});	
	
	
});


</script>

	<form class="lcms-form lcms-horizontal lcms-edit-page-form">
	    <h3>Edit New Page</h3>
	    <fieldset>
	    	<label for="title"><span class="red">*</span> Page Title</label>
	    	<input type="text" class="lcms-txt" name="title" id="title" value="<?php echo $page->title; ?>" /><br />		
	    	
	    	<label for="name"><span class="red">*</span> Page Name</label>
	    	<input type="text" class="lcms-txt" name="name" id="name" value="<?php echo $page->name; ?>" /><br />
	    	<p class="lcms-hint">Page name will be used to create the URL of the page. It should be alpha in lowercase.</p>
	    	
	    	<?php if ($page->main == 0): ?>
	    	<label for="redirect">Redirect URL</label>
	    	<input type="text" class="lcms-txt" name="redirect_url" id="redirect_url" value="<?php echo $page->redirect_url; ?>" /><br />
	    	<p class="lcms-hint">If set page link will be redirect to the specified URL.</p>
	    	<label for="parent">Parent Page</label>
	    	<select id="lcms-edit-parent" name="parent"><?php echo $parents; ?></select><br />
	    	<?php endif; ?>
	    	


	    	<label for="in-nav">Appear in Navigation</label>
	    	<input type="checkbox" name="in_nav" value="1" <?php echo $page->in_nav == 1 ? 'checked="checked"' : ''; ?> /> Yes<hr />
	    	<label for="in-nav">Searchable</label>
	    	<input type="checkbox" name="searchable" value="1" <?php echo $page->searchable == 1 ? 'checked="checked"' : ''; ?> /> Yes<hr />
	    	<label><span class="red">*</span> Layout</label>
	    	<div id="lcms-edit-layouts"><?php echo $layouts; ?></div>
	    	
	    	<button class="lcms-btn lcms-close-btn">Cancel</button> <button class="lcms-btn lcms-edit-page">Update Page</button>
	    </fieldset>
	</form>