	
	<?php $this->load->view('users/sidebar'); ?>
	
	<div id="content">
		<div class="toolbar">
			<h3 class="header">New User</h3>
			<div class="tool">
				<button class="submit-btn" rel="form">Save</button>
			</div>

		</div>
		<div class="content-scroll">
			<div class="padded">
				<form id="form" method="post" action="users/add/do">
					
					<table class="form">
						<tr>
							<td class="label">Username</td>
							<td class="input">
								<input type="text" class="text" name="username" />
							</td>
						</tr>
						<tr>
							<td class="label">Password</td>
							<td class="input">
								<input type="password" class="text" name="password" />
							</td>
						</tr>
						<tr>
							<td class="label">Name of user</td>
							<td class="input">
								<input type="text" class="text" name="firstname" />
							</td>
						</tr>
						<tr>
							<td class="label">Department</td>
							<td class="input">
								<?php echo form_dropdown('branch', $this->branch->dropdown_list()); ?>
							</td>
						</tr>
						<tr>
							<td class="label">Authorisation level</td>
							<td class="input">
								<?php echo form_dropdown('type', array('Superadmin'=>'System administrator','Admin'=>'General administrator','User'=>'General user')); ?>
							</td>
						</tr>
						
					</table>
					
					
				</form>
			</div>
		</div>

	
	</div>