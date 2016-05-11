<script language="javascript" type="text/javascript">
<!--
function startUpload(){
	document.getElementById('lcms-file').style.display = 'none';
	document.getElementById('lcms-upload-process').style.display = '';
	return true;
}

function stopUpload(success){
	var result = '';
	if (success == 1){
		result = '<p class="lcms-file-msg">The file was uploaded successfully!</p>';
	} else {
		result = '<p class="lcms-file-emsg">There was an error during file upload!</p>';
	}
	document.getElementById('lcms-upload-process').style.display = 'none';
	document.getElementById('lcms-file').style.display = '';
	document.getElementById('lcms-file').innerHTML = result + '<input type="file" name="file_upload" class="lcms-file-input" /><br /><a href="#save" class="lcms-btn lcms-submit">Upload</a>';
	$('a.lcms-submit').click(function(event){
		event.preventDefault();
		$(this).parents('form').submit();
	});
	return true;   
}

$(document).ready(function(){
	$('a.lcms-submit').click(function(event){
		event.preventDefault();
		$(this).parents('form').submit();
	});
	
	$('ul.lcms-file-category-list li').click(function(){
	
		$('ul.lcms-file-category-list li').removeClass('lcms-current-file');
		$(this).addClass('lcms-current-file');
		
		$('ul.lcms-file-list').html('<li><i>Loading files</i></li>');
		var type = $(this).html();
		$.ajax({
			type: 'POST',
			url: base_url + 'file/files/' + type,
			success: function(html){
					$('ul.lcms-file-list').html(html);
					
					$('ul.lcms-file-list li').click(function(){
						$('ul.lcms-file-list li').removeClass('lcms-current-file');
						$(this).addClass('lcms-current-file');
					
						$('ul.lcms-file-preview-container').html("<img src=\""+base_url+"css/images/loader.gif\"> Loading info..");
						var rel = $(this).attr('rel');
						$.ajax({
							type: 'POST',
							url: base_url + 'file/info/' + rel,
							success: function(html){
									$('div.lcms-file-preview-container').html(html);
									
									$('a.lcms-file-delete').click(function(e){
										e.preventDefault();
										if (window.confirm('Are you sure you want to delete this file?')){
											var rel_file = $(this).attr('rel');
											$.ajax({
												type: 'POST',
												url: base_url + 'file/delete/' + rel_file,
												success: function(html){
													$('ul.lcms-file-list li[rel='+rel+']').remove();
													$('div.lcms-file-preview-container').html('');
												}
											});
										} else {
										
										}
									});
									
									
									
									var imageHeight = $('div.lcms-file-preview-container img').height();
									var imageWidth = $('div.lcms-file-preview-container img').width();
									var targetTop = 90 - (imageHeight / 2);
									
									if (imageHeight > imageWidth){
										if (imageHeight > 200){
											$('div.lcms-file-preview-container img').height(200);
											$('div.lcms-file-preview-container img').css('width','auto');
											targetTop = 0;
										}
									} else if (imageHeight < imageWidth){
										if (imageWidth > 200){
											$('div.lcms-file-preview-container img').width(200);
											$('div.lcms-file-preview-container img').css('height','auto');
											targetTop = 100 - ($('div.lcms-file-preview-container img').height() / 2)
										}
									} else {
										if (imageWidth > 200){
											$('div.lcms-file-preview-container img').height(200);
											$('div.lcms-file-preview-container img').width(200);
											targetTop = 0;
										}
									}
									
									$('div.lcms-file-preview-container img').css('margin-top',targetTop);
								}
						});
					

					});
				}
		});
	});
	
	$('button.lcms-btn-done').click(function(event){
		event.preventDefault();
		hideDialog();
	});

});
//-->
</script>   
<div id="lcms-file-manager-container">
<div id="lcms-file-sidebar">
	<form action="<?=base_url()?>file/upload" method="post" enctype="multipart/form-data" target="lcms_upload_target" onsubmit="startUpload()">
		<img id="lcms-upload-process" src="<?=base_url()?>css/images/loader.gif" style="display:none" />
		<p id="lcms-file"><input type="file" name="file_upload" class="lcms-file-input" /><br /><a href="#save" class="lcms-btn lcms-submit">Upload</a></p>
		<iframe id="lcms_upload_target" name="lcms_upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
	</form>
	<?=$category_list?> 
</div>
<div id="lcms-file-list">

	<ul class="lcms-file-list">
	
	</ul>	
</div>
<div id="lcms-file-preview">
	<h4>Info</h4>
	<div class="lcms-file-preview-container">
	
	</div>
</div>
<br class="lcms-clearboth" />
<p><button class="lcms-btn lcms-btn-done">Close</button></p>
</div>