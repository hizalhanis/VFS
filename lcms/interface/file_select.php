<script language="javascript" type="text/javascript">


$(document).ready(function(){
	$('a.lcms-submit').click(function(event){
		event.preventDefault();
		$(this).parents('form').submit();
	});	
	
	$('ul.lcms-file-list li').click(function(){
		$('ul.lcms-file-list li').removeClass('lcms-current-file');
		$(this).addClass('lcms-current-file');
	});
	
	$('button.lcms-btn-cancel').click(function(event){
		event.preventDefault();
		hideDialog();
	});

	$('button.lcms-btn-select').click(function(event){
		filename = $('ul.lcms-file-list li.lcms-current-file').html();
		$(lcmsFileObject).val(base_url+'media/'+filename);
		$(lcmsFocusAfterFile).focus();
		$(lcmsFocusAfterFile).contents().focus();

		event.preventDefault();
		hideDialog();
	});
	
	$('ul.lcms-file-list li').click(function(){
		var rel = $(this).attr('rel');
		$.ajax({
			type: 'POST',
			url: base_url + 'file/info/' + rel,
			success: function(html){
				$('div.lcms-file-preview-container').html(html);
					
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
					if (imageWidth > 400){
						$('div.lcms-file-preview-container img').width(200);
						$('div.lcms-file-preview-container img').css('height','auto');
						targetTop = 100 - ($('div.v-file-preview-container img').height() / 2)
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

});

</script>   
<div id="lcms-file-manager-container" style="width: 760px">
	<div id="lcms-folder-list">
		<ul class="lcms-folder-list">
		
		</ul>	
	</div>
	<div id="lcms-file-list">
		<ul class="lcms-file-list">
			<?=$file_list?>	
		</ul>	
	</div>
	<div id="lcms-file-preview" style="padding-left: 515px !important">
		<h4>Info</h4>
		<div class="lcms-file-preview-container">
	
		</div>
	</div>
	<br class="lcms-clearboth" />
	<p><button class="lcms-btn lcms-btn-cancel">Cancel</button> <button class="lcms-btn lcms-btn-select">Select</button></p>
</div>