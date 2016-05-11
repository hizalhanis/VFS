<script type="text/javascript">

	$(document).ready(function(){
		
		var myNicEditor = new nicEditor({fullPanel : true});
		myNicEditor.addInstance('lcms-news-post-textarea');
		
		if ($('#lcms-editor-pane').attr('id') != 'lcms-editor-pane'){
			$('body').append('<div id="lcms-editor-pane"></div>');
		}
		myNicEditor.setPanel('lcms-editor-pane');

		$('a.lcms-news-delete-btn').click(function(e){
			e.preventDefault();
			var href = $(this).attr('href');
			createDialog('confirm', 'Do you confirm to delete the post?', function(){
				window.location.href = href;
			});
			
		});
		
		$('button.lcms-news-image-select').live('click',function(e){ e.preventDefault(); lcmsFileObject = $('input.lcms-news-image-url'); selectFile('images') });
	});

</script>
	<div style="border-left: 1px solid #EEE; padding: 10px; border-right: 1px solid #EEE; border-bottom: 1px solid #EEE; background: #FFF; box-shadow: 0 3px 7px rgba(0,0,0,0.2)">
	<a href="<?php echo page_url(); ?>" style="margin-top: 1px; margin-right: 40px;" class="lcms-dbtn">Main</a> 
	<a href="<?php echo page_url(); ?>article/<?php echo $entry->id; ?>" style="margin-top: 1px; margin-right: 2px;" class="lcms-dbtn">Back</a> 
	

	<h1 class="lcms-news-form-title">
		<a style="padding: 0 5px !important; float: right" class="lcms-btn lcms-news-delete-btn" href="<?php echo page_url(); ?>delete_article/<?php echo $entry->id; ?>">Delete This Article</a>
		Edit Article
	</h1>

	<form method="post" action="<?php echo page_url(); ?>/edit_article_do">
		<input type="hidden" name="name" value="<?php echo $content->content; ?>" />
		<input type="hidden" name="id" value="<?php echo $entry->id; ?>" />
		<table class="lcms-news-form" style="margin-top: 10px; margin-bottom: 10px"> 	
			<tr>	
				<td class="label">News Title</td>	
				<td class="field"><input value="<?php echo $entry->title; ?>" style="width: 100%" name="title" class="lcms-txt lcms-news-post-title" type="text" /></td>	
			</tr>	
			<tr>	
				<td class="label">Category</td>	
				<td class="field"><input value="<?php echo $entry->cat; ?>" style="width: 100%" name="cat" class="lcms-txt lcms-news-post-category" type="text" /></td>	
			</tr>
			<tr>	
				<td class="label">Tags</td>	
				<td class="field"><input value="<?php echo $entry->tags; ?>" style="width: 100%" name="tags" class="lcms-txt lcms-news-post-tags" type="text" /></td>	
			</tr>
			<tr>	
				<td class="label">Excerpt</td>	
				<td class="field"><textarea style="width: 100%; height: 100px;" name="excerpt" class="lcms-txtarea lcms-news-post-except"><?php echo $entry->excerpt; ?></textarea></td>	
			</tr>
			<tr>	
				<td class="label">Image URL</td>	
				<td class="field"><input style="width: 174px" value="<?php echo $entry->image; ?>" name="image" class="lcms-txt lcms-news-image-url" id="lcms-news-image-url" type="text" /> <button class="lcms-btn lcms-news-image-select">Media Gallery</button></td> 	
			</tr>
			<tr>	
				<td class="label">Date/Time</td>	
				<td class="field">
					<input name="date" value="<?php echo date('d/m/Y',strtotime($entry->date)); ?>" class="date lcms-txt lcms-date lcms-news-post-date" type="text" style="width: 70px" />
					<input name="time" value="<?php echo date('h:iA',strtotime($entry->date)); ?>" class="time lcms-txt lcms-time lcms-news-post-time" type="text" style="width: 70px" />
				</td>	
				
			</tr>

		</table>	
		<p><strong>Post Content</strong></p>
		<div style="border: 1px solid #EEE; margin-bottom: 10px;">
		<textarea class="lcms-txt" style="width:100%" id="lcms-news-post-textarea" name="text"><?php echo $entry->content; ?></textarea><br />	
		</div>
		<input type="submit" name="action" class="lcms-btn lcms-news-post-save" value="Save" />
		<input type="submit" name="action" class="lcms-btn lcms-news-post-save" value="Save &amp; Publish" />
		<a class="lcms-btn" href="<?php echo page_url(); ?>">Cancel</a>
	</form>
	</div>