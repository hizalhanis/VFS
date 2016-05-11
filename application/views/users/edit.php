	
	<?php $this->load->view('users/sidebar'); ?>
	
	<div id="content">
		<div class="toolbar">
			<h3 class="header">Update user &raquo; <?php echo $user->username; ?></h3>
			<div class="tool">
				<button class="submit-btn" rel="form">Save</button>
			</div>

		</div>
		<div class="content-scroll">
			<div class="padded">
				<form id="form" method="post" action="users/edit/<?php echo $user->id; ?>/do">
					
					<table class="form">
						<tr>
							<td class="label">Username</td>
							<td class="input">
								<input value="<?php echo $user->username; ?>" type="text" class="text" name="username" />
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
								<input value="<?php echo $user->firstname; ?>" type="text" class="text" name="firstname" />
							</td>
						</tr>
						<tr>
							<td class="label">Department</td>
							<td class="input">
								<?php echo form_dropdown('branch', $this->branch->dropdown_list(), $user->branch); ?>
							</td>
						</tr>
				
						
					</table>
					
					
				</form>
			</div>
		</div>

	
	</div>