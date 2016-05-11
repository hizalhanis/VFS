			
			<div class="lcms-plexcart-register">
				<form class="lcms-plexcart" method="post" action="<?php echo $current_page; ?>register_do">
					<h1>Register as Member</h1>
					
					<?php if ($IS_REGISTER_ERROR): ?>
					<p class="error">Email is already registered. Forgot your password?</p>
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
							<td class="label">Name <span class="red">*</span></td>
							<td class="input"><input class="text name mandatory" type="text" value="" name="name" /></td>
							<td class="label">Mobile Number <span class="red">*</span></td>
							<td class="input"><input class="text mobile mandatory" type="text mandatory" name="mobile" value=""/></td>
						</tr>

						<tr>
							<td class="label">Street Address 1 <span class="red">*</span></td>
							<td class="input"><input class="text address1 mandatory" type="text" value=""name="address1" /></td>
							<td class="label">Street Address 2</td>
							<td class="input"><input class="text address2 mandatory" type="text" value=""name="address2" /></td>
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
							<td class="label">Country <span class="red">*</span></td>
							<td class="input"><select name="country" class="mandatory"><option value="Malaysia">Malaysia</option></select></td>
						</tr>

					</table>
					<p class="lcms-plexcart-register-submit"><input type="submit" class="btn-large submit" value="Sign Up" /></p>
				</form>
			
			</div>


