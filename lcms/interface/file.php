<script language="javascript" type="text/javascript">
	
	function startUpload(){
		document.getElementById('lcms-file').style.display = 'none';
		document.getElementById('lcms-upload-process').style.display = '';
		return true;
	}
	
	function stopUpload(success){
		var result = '';
		var filename = $('span.lcms-selected-filename').text();
		
		if (success == 1){
			result = '<p class="lcms-file-msg">The file <strong>'+filename+'</strong> was uploaded successfully!</p>';
		} else {
			result = '<p class="lcms-file-emsg">There was an error uploading <strong>'+filename+'</strong>!</p>';
		}
		$('div.lcms-upload-result').html(result);
		
		$('#lcms-upload-process').hide();
		$('#lcms-file').hide();
		$('input.lcms-file-input').val('');
		
		$('span.lcms-selected-filename').text('').hide();
		$('a.lcms-submit').hide();
		
		updateCategoryList();
		
		return true;   
	}
	
	function updateCategoryList(){
		var current = $('ul.lcms-file-category-list li.current').text();
		
		$.ajax({
			url: base_url + 'file/categories',
			success: function(html){
				$('ul.lcms-file-category-list').replaceWith(html);
				$('ul.lcms-file-category-list li').each(function(){
					var text = $(this).text();
					if (text == current){
						$(this).click();
					}
				});
			}
		})
		
	}
	
	$(document).ready(function(){
		$('a.lcms-submit').click(function(event){
			event.preventDefault();
			$(this).parents('form').submit();
		});
		
		if (!lcmsFileCatList){
			lcmsFileCatList = true;
				
		   $('ul.lcms-file-category-list li').live('click',function(){
		   
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
		   				
		   				var addedFolders = [];
		   				$('ul.lcms-folder-list').html('');
		   				$('ul.lcms-folder-list').append('<li class="lcms-current-folder" rel="."><i>All</i></li>');
		   				$('ul.lcms-file-list li').each(function(){
		   					var folder = $(this).attr('folder');
		   					if (!addedFolders[folder] && folder != ''){
		   						$('ul.lcms-folder-list').append('<li rel="'+folder+'">'+folder+'</li>');
		   						addedFolders[folder] = true;
		   					}
		
		   				});
		   				
		   				$('ul.lcms-folder-list li').click(function(){
		   					var folder = $(this).attr('rel');
		   					
		   					$('ul.lcms-folder-list li').removeClass('lcms-current-folder');
		   					$(this).addClass('lcms-current-folder');
		   					
		   					$('input.lcms-file-group').val(folder == '.' ? '' : folder);
		   					
		   					if (folder == '.'){
		   						$('ul.lcms-file-list li').show();
		   					} else {
		   						$('ul.lcms-file-list li').hide();
		   						$('ul.lcms-file-list li[folder='+folder+']').show();
		   					}
		   				});
		   			}
		   	});
		   });
		
		}
		
		$('button.lcms-btn-done').click(function(event){
			event.preventDefault();
			$('#lcms-fileman-container').fadeOut();
		});
		
		$('a.lcms-select-file').click(function(event){
			event.preventDefault();
			$('input.lcms-file-input').click();
			
		});
		
		$('input.lcms-file-input').change(function(e){
			$('a.lcms-submit').show();
			var paths = ($(this).val()).split('\\');
			var filename = paths[paths.length-1];
			$('span.lcms-selected-filename').text(filename).show();
			$('div.lcms-upload-result').html('');
		});
	
	});

</script>   
<div id="lcms-file-manager-container">
	<div id="lcms-file-sidebar">
		<form action="<?=base_url()?>file/upload" method="post" enctype="multipart/form-data" target="lcms_upload_target" onsubmit="startUpload()">
			<img id="lcms-upload-process" src="<?=base_url()?>css/images/loader.gif" style="display:none" />
			<p id="lcms-file" style="margin: 0; padding: 5px 0">
    			<input style="display:none" type="file" name="file_upload[]" multiple="multiple" class="lcms-file-input" />
    			<div class="lcms-upload-result"></div>	
    			<span style="padding: 2px 5px; border-radius: 5px; font-size: 8pt; display: block; width: 176px; margin-bottom: 5px; height: 12px; background: #EEE;" class="lcms-selected-filename"><i>No file selected</i></span>
    			<a style="width: 97px !important; display: block !important; font-size: 14px !important; font-weight: bold !important; text-align: center; padding: 3px 5px !important; margin-bottom: 5px !important;  margin-right: 5px !important; float: left;" href="#selectfiles" class="lcms-dbtn lcms-select-file">Select Files</a>
    			<a style="width: 60px !important; display: block !important; font-size: 14px !important; font-weight: bold !important; text-align: center; padding: 3px 5px !important; margin-bottom: 5px !important; float: left;" href="#save" class="lcms-dbtn lcms-submit">Upload</a>
    			<table style="font-size: 9pt;">
    				<tr>
    					<td>Group</td>
    					<td><input style="width: 100%;" name="group" type="text" class="lcms-txt lcms-file-group" style="width: 100%;" /></td>
    				</tr>
    			</table>
    		</p>
				
			<iframe id="lcms_upload_target" name="lcms_upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
		</form>
		<?=$category_list?> 
	</div>
	<div id="lcms-folder-list">
		<ul class="lcms-folder-list">
		
		</ul>	
	</div>
	<div id="lcms-file-list">
		<ul class="lcms-file-list">
		
		</ul>	
	</div>
	<div id="lcms-file-preview" style="">
		<div class="lcms-file-preview-container">
		
		</div>
	</div>
	<br class="lcms-clearboth" />
	<p><button class="lcms-btn lcms-btn-done">Close</button></p>
</div>