	
	<?php $this->load->view('users/sidebar'); ?>
	
	<div id="content">
	
		<div class="toolbar">
			<h3 class="header">Users</h3>
			<table class="grid-header">
				<thead>
					<tr>
						<th>Username</th>
						<th style="width: 200px">Name</th>
						<th style="width: 125px">Action</th>
						<th style="width: 3px"></th>
					</tr>
				</thead>
			</table>	
		</div>
		
		<div class="content-scroll">
		
			<table class="grid">
				<tbody>
					<?php foreach ($users as $user): ?>
					<tr>
						<td><?php echo $user->username; ?></td>
						<td style="width: 200px"><?php echo $user->firstname; ?> <?php echo $user->lastname; ?></td>
						<td style="width: 120px">
							<?php if ($this->user->data('type') == 'Superadmin'): ?>
							<a href="users/edit/<?php echo $user->id; ?>">Update</a> |
							<a href="users/delete/<?php echo $user->id; ?>">Delete</a>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			
			</table>
		
		</div>
	
	
	</div>