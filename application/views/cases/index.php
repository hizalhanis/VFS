	
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
			<h3 class="header">Home</h3>
		</div>
<div style="text-align: center; padding: 5px;"> Welcome to CSMCR Survey System </div>
		
<div style="text-align:center; padding: 2px 1px 5px 1px;">
<img src="images/CSMCR_bg.png" style="width: 30%;" />
</div>

	</div>




