	
	<?php $this->load->view('cases/sidebar'); ?>
	<script type="text/javascript">
	
		$(document).ready(function(){
			$('a.delete').click(function(e){
			e.preventDefault();
			var hurl = $(this).attr('href');
			if (confirm('Delete survey record?')) location.href = hurl;
		})

	})
	</script>
	<div id="content">

<div style="text-align: center; padding: 30px;"> <img src="images/surveyhome2.png" style="width: 20%;" /></div>
		
<div style="text-align:center; padding: 2px 1px 5px 1px;">
<img src="images/CSMCR_bg.png" style="width: 20%;" />
<img src="images/surveyhome.jpg" style="width: 30%;" />


</div>

	</div>




