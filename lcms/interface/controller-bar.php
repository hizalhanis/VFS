
	<div id="lcms-controller">
		<select class="lcms-jump-nav">
			<?php echo $jump_nav; ?>
		</select>
		<ul>
	    	<li class="lcms-logo">PlexCMS</li>
	    	<li class="lcms-author-mode"><a class="lcms-author-mode" href="javascript:;"><input checked="checked" type="checkbox" class="lcms-author-mode" />Author Mode</a></li>
	    	<li class="lcms-settings"><a href="<?php echo base_url(); ?>admin/users">Admin</a></li>
	    	<li class="lcms-file-manager"><a href="#" class="lcms-file-manager">File Manager</a></li>
	    	<li class="lcms-save-order"><a href="javascript:;">Save Page Order</a></li>
	    	<li class="lcms-new-page"><a href="javascript:;">New Page</a></li>
	    	<li class="lcms-edit-page"><a href="javascript:;">Edit This Page</a></li>
	    	<?php if ($main_page != $current_page): ?>
	    		<li class="lcms-delete-page"><a href="javascript:;">Delete This Page</a></li>
	    	<?php endif; ?>
	    	<li class="lcms-logout"><a href="<?php echo base_url(); ?>admin/logout">Logout</a></li>
	    	
		</ul>
	</div>
	<div id="lcms-editor-pane">
	
	</div>
	<div id="lcms-fileman-container">
	
	</div>
	
	<div id="lcms-content-versions" class="lcms-control-form" style="display:none; width: 400px">
		<a class="lcms-close-btn"><span>Close</span></a>
		<h3 class="lcms-toolbar">Content Revisions</h3>

		<div class="lcms-versions-placeholder">
		
		</div>

	</div>
	
	<div class="lcms-spotlight lcms-spotlight-left"></div>
	<div class="lcms-spotlight lcms-spotlight-top"></div>
	<div class="lcms-spotlight lcms-spotlight-bottom"></div>
	<div class="lcms-spotlight lcms-spotlight-right"></div>