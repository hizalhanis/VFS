<script type="text/javascript">

function formSubmitted(){
	
}

$(document).ready(function(){
	$(window).resize();
});

</script>


	<form method="post" action="page/ajax/control/survey/update_page" target="update-frame">
		<input type="hidden" name="type" value="thank_you" />
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<p>Thank You Message</p>
		<textarea name="content" id="lcms-survey-page-content" style="width: 100%;"><?php echo $survey->thank_you; ?></textarea>
		<button class="lcms-btn">Save Content</button>
	</form>
	
	<iframe name="update-frame" id="update-frame" style="height: 1px; visibility: hidden"></iframe>