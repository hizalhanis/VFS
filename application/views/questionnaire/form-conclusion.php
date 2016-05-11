<script type="text/javascript">

function formSubmitted(){
	
}

$(document).ready(function(){
	$(window).resize();
});

</script>


	<form method="post" action="config/ajax/update_page" target="update-frame">
		<input type="hidden" name="type" value="conclusion" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<p>Conclusion Message</p>
		<textarea name="content" id="lcms-survey-page-content" style="width: 100%; "><?php echo $survey->conclusion; ?></textarea>
		<button class="lcms-btn">Save Content</button>
	</form>
	
	<iframe name="update-frame" id="update-frame" style="height: 1px; visibility: hidden"></iframe>