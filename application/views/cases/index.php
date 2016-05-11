	
	<?php $this->load->view('cases/sidebar'); ?>
	<script type="text/javascript">
	
		$(document).ready(function(){
			$('a.delete').click(function(e){
			e.preventDefault();
			var hurl = $(this).attr('href');
			if (confirm('Padam rekod kemalangan?')) location.href = hurl;
		})

	})
	</script>
	<div id="content">
	
		<div class="toolbar">
			<h3 class="header">Summary</h3>
		</div>
		

	
	</div>