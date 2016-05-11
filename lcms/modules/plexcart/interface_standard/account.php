	<div class="lcms-plexcart-breadcrumb">
	    <h3>My Account</h3>	
	</div>
	<form class="lcms-plexcart" method="post" action="<?php echo $current_page; ?>account_update_do">
		<?php if ($status == 'ok'): ?>
		<p class="notice">Your account informatiom has been updated.</p>
		<?php endif; ?>
	
		<input type="hidden" name="submit" value="1" />
	    <h3>Login Information</h3>
	    <p>To change your password, enter your old password and your new password.</p>
	    
	    <?php 
	    switch ($status){
			case "error1": echo '<p class="error">Password must be at least 6 characters long.</p>'; break;
			case "error2": echo '<p class="error">Password supplied does not match.</p>'; break;
			case "error3": echo '<p class="error">Old password does not match.</p>'; break;
	    }
	    ?>
	    
	    <table class="form-grid lcms-plexcart-ci">
	    	<tr>
	    		<td class="label">Email</td>
	    		<td class="input"><?php echo $user->email; ?></td>
	    		<td class="label">Old Password</td>
	    		<td class="input"><input class="text password mandatory" type="password" name="old_password" /></td>
	    	</tr>
	    	<tr>
	    		<td class="label">Change Password<br />&nbsp;</td>
	    		<td class="input"><input class="text password" type="password" name="password" /><br /><small>Must be at least 6 characters long.</small></td>
	    		<td class="label">Retype Password<br />&nbsp;</td>
	    		<td class="input"><input class="text retype-password" type="password" name="retype_password" /><br /><small>&nbsp;</small></td>
	    	</tr>

	    </table>
	    
	    
	    <h3>Details</h3>
	    <p>Enter your details here.</p>
	    
	    <?php if ($status == 'error'): ?>
	    <p class="error">Please make sure you have filled in the fields correctly.</p>
	    <?php endif; ?>
	    
	    <table class="form-grid lcms-plexcart-ci">
	    	<tr>
	    		<td class="label">Name <span class="red">*</span></td>
	    		<td class="input"><input class="text name" type="text mandatory" value="<?php echo $this->input->post('name') ? $this->input->post('name') : ($user->contact_person ? $user->contact_person : ''); ?>" name="name" /></td>
	    		<td class="label">Mobile Number <span class="red">*</span></td>
	    		<td class="input"><input class="text mobile mandatory" type="text" name="mobile" value="<?php echo $this->input->post('mobile') ? $this->input->post('mobile') : ($user->contact_number ? $user->contact_number : ''); ?>"/></td>
	    	</tr>
	    	<tr>
	    		<td class="label">Street Address 1 <span class="red">*</span></td>
	    		<td class="input"><input class="text address1" type="text mandatory" value="<?php echo $this->input->post('address1') ? $this->input->post('address1') : ($user->address1 ? $user->address1 : ''); ?>"name="address1" /></td>
	    		<td class="label">Street Address 2</td>
	    		<td class="input"><input class="text address2" type="text" value="<?php echo $this->input->post('address2') ? $this->input->post('address2') : ($user->address2 ? $user->address2 : ''); ?>"name="address2" /></td>
	    	</tr>
	    	<tr>
	    		<td class="label">City <span class="red">*</span></td>
	    		<td class="input"><input class="text city mandatory" type="text" name="city" value="<?php echo $this->input->post('city') ? $this->input->post('city') : ($user->city ? $user->city : ''); ?>"/></td>
	    		<td class="label">State <span class="red">*</span></td>
	    		<td class="input"><input class="text state mandatory" type="text" name="state" value="<?php echo $this->input->post('state') ? $this->input->post('state') : ($user->state ? $user->state : ''); ?>"/></td>

	    	</tr>
	    	<tr>
	    		<td class="label">Postal Code <span class="red">*</span></td>
	    		<td class="input"><input class="text zipcode mandatory" type="text" name="zipcode" value="<?php echo $this->input->post('zipcode') ? $this->input->post('zipcode') : ($user->zipcode ? $user->zipcode : ''); ?>"/></td>
	    		<td class="label">Country <span class="red">*</span></td>
	    		<td class="input"><select name="country"><option value="Malaysia">Malaysia</option></select></td>
	    	</tr>
	    </table>
	    <p class="align-right"><input type="submit" class="btn-large submit" value="Update" /></p>
	</form>
	
