<script type="text/javascript">

$(document).ready(function(){
	
	$('select.country').change(function(){
		var val = $(this).val();
		$('td.box').hide();
		$('input.ic').removeClass('mandatory');
		$('input.retype-ic').removeClass('mandatory');
		$('td.box'+val).show();
		
	});
	
	$('.lcms-plexcart-register-submit').click(function() {
		if($('#agree').is(':checked')){
		//$(".submit-reg input").submit();
		}
		else{
		alert("You must agree to the Terms and Conditions first");
		return false;
		}
	});
	
	$(function() {
		var moveLeft = 20;
		var moveDown = 10;
		
		$('a#trigger').hover(function(e) {
		$('div#pop-up').show();
		//.css('top', e.pageY + moveDown)
		//.css('left', e.pageX + moveLeft)
		//.appendTo('body');
		}, function() {
		$('div#pop-up').hide();
		});
		
		$('a#trigger').mousemove(function(e) {
		$("div#pop-up").css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
		});
	
	});
	
})

</script>
	
			<div class="lcms-plexcart-register">
				<form class="lcms-plexcart" method="post" action="<?php echo $current_page; ?>register_do">
					<h1>Register as Member</h1>
					
					<?php if ($error == 'email_err'): ?>
					<p class="error">Email is already registered. Forgot your password?</p>
					<?php endif; ?>
					<?php if ($error == 'pass_err'): ?>
					<p class="error">Password entered mismatched! </p>
					<?php endif; ?>
					<?php if ($error == 'ic_err'): ?>
					<p class="error">IC number entered mismatched! </p>
					<?php endif; ?>
					<?php if ($error == 'ic_false'): ?>
					<p class="error">IC number entered already existed in record! </p>
					<?php endif; ?>
					<?php if ($error == 'email_false'): ?>
					<p class="error">Email entered already existed in record! </p>
					<?php endif; ?>				
					<h3>Customer Information</h3>
					<p>Enter a valid email address as your email will be used as your username.</p>
					
					<table class="form-grid lcms-plexcart-ci">
						<tr>
							<td class="label">Email <span class="red">*</span></td>
							<td class="input"><input class="text email mandatory" type="text" name="email" /></td>
							<td class="label">Retype Email <span class="red">*</span></td>
							<td class="input"><input class="text retype-email mandatory" type="text" name="retype_email" /></td>
						</tr>
						<tr>
							<td class="label">Password <span class="red">*</span><br />&nbsp;</td>
							<td class="input"><input class="text password mandatory" type="password" name="password" /><br /><small>Must be at least 6 characters long.</small></td>
							<td class="label">Retype Password <span class="red">*</span><br />&nbsp;</td>
							<td class="input"><input class="text retype-password mandatory" type="password" name="retype_password" /><br /><small>&nbsp;</small></td>
						</tr>

					</table>
					
					
					<h3>Details</h3>
					<p>Enter your details here.</p>
					
					<table class="form-grid lcms-plexcart-ci">
						<tr>
							<td class="label">Country <span class="red">*</span></td>
							<td class="input">
							<?php echo form_dropdown('country',$countries, 'Malaysia', 'class="mandatory country"'); ?>
							</td>
						</tr>					
						<tr>
							<td class="label">Name <span class="red">*</span></td>
							<td class="input"><input class="text name mandatory" type="text" value="" name="name" /></td>
							<td class="label">Mobile Number <span class="red">*</span></td>
							<td class="input"><input class="text mobile mandatory" type="text mandatory" name="mobile" value=""/></td>
						</tr>
						<tr>
							<td class="label box boxMalaysia">IC Number <span class="red">*</span></td>
							<td class="input box boxMalaysia ic"><input class="text ic mandatory" type="text" value="" name="ic" /></td>
							<td class="label box boxMalaysia">Retype IC Number <span class="red">*</span></td>
							<td class="input box boxMalaysia retype-ic"><input class="text retype-ic mandatory" type="text mandatory" name="retype_ic" value=""/></td>
						</tr>


						<tr>
							<td class="label">Street Address 1 <span class="red">*</span></td>
							<td class="input"><input class="text address1 mandatory" type="text" value=""name="address1" /></td>
							<td class="label">Street Address 2</td>
							<td class="input"><input class="text address2" type="text" value=""name="address2" /></td>
						</tr>
						<tr>
							<td class="label">City <span class="red">*</span></td>
							<td class="input"><input class="text city mandatory" type="text" name="city" value=""/></td>
							<td class="label">State <span class="red">*</span></td>
							<td class="input"><input class="text city mandatory" type="text" name="state" value=""/></td>
						</tr>
						<tr>	
							<td class="label">Postal Code <span class="red">*</span></td>
							<td class="input"><input class="text zipcode mandatory" type="text" name="zipcode" value=""/></td>
							<td class="label">Introducer's IC</td>
							<td class="input"><input class="text introducer_ic" type="text" name="introducer_ic" value=""/></td>
						</tr>	
					</table>
					<div style="padding: 10px 0">
						<h3>Terms and Conditions</h3>
						<textarea style="width: 100%; height: 100px"><?php echo $tnc; ?></textarea>
					</div>
					<div style="padding: 10px 0">
						<input type="checkbox" id="agree"/>
						<label for="agree">I agree with the Terms and Conditions.</label>
					</div>

					<p class="lcms-plexcart-register-submit"><input type="submit" class="lcms-btn submit" value="Sign Up" /></p>
				</form>
				
			</div>


