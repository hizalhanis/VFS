<script type="text/javascript">

$(document).ready(function(){
	
	
})

</script>
	<div class="lcms-plexcart-breadcrumb">
		<h3><a href="<?php echo $current_page; ?>">Store</a> &raquo; Password Recovery</h3>
	</div>
	<div class="lcms-plexcart-register">
	    	
	    	<?php if ($result == 'ok'): ?>
		    	<h3>Password Reset Successful</h3>
		    	<p class="notice">Your password has been reset. Your new password has been to your email.</p>    	
	    	<?php else: ?>
	    		<form class="lcms-plexcart" method="post" action="<?php echo $current_page; ?>password_check_do">
	    		    <h3>Customer Information</h3>
	    		    
	    		    <?php if ($result == 'mismatch'): ?>
	    		    <p class="error">Your Account is not found. Please ensure your email and mobile number is correct.</p>
	    		    <?php endif; ?>
	    		
	    		    <p>Enter your registered email address and Mobile Number to proceed.</p>
	    		    <table class="form-grid lcms-plexcart-ci">
	    		    	<tr>
	    		    		<td class="label">Email <span class="red">*</span></td>
	    		    		<td class="input"><input class="text email mandatory" type="text" name="email" /></td>
	    		    		<td class="label">Mobile Number <span class="red">*</span></td>
	    		    		<td class="input"><input class="text mobile mandatory" type="text mandatory" name="mobile" /></td>
	    		    	</tr>
	    		    	
	    		    </table>
	    		    
	    		   	<p class="lcms-plexcart-register-submit"><input type="submit" class="lcms-btn submit" value="Recover Password" /></p>
	    		    	    	
	    		</form>
	    	<?php endif; ?>
	    
	</div>


