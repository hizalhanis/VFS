			<h1>Users</h1>
			<?php if ($alert): ?>
			<p style="border: 1px solid #008800; padding: 5px; font-size: 10pt; font-family: Arial, sans-serif; color: #008800"><?=$alert?></p>
			<?php endif; ?>
			<?php if ($error): ?>
			<p style="border: 1px solid red; padding: 5px; font-size: 10pt; font-family: Arial, sans-serif; color: red"><?=$error?></p>
			<?php endif; ?>

			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Username</th>
						<th>First Name</th>
						<th>Last Name</th>
						<th>Email</th>
						<th>Type</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($users as $user):?>
					<tr>
						<td><?=$user->id?></td>
						<td><?=$user->username?></td>
						<td><?=$user->firstname?></td>
						<td><?=$user->lastname?></td>
						<td><?=$user->email?></td>
						<td><?=$user->type?></td>
						<?php if ($me->username == $user->username): ?>
							<td><a href="<?=base_url()?>admin/edit_user/<?=$user->id?>">Edit</a></td>
						<?php elseif ($me->type != 'Super Admin'): ?>
							<td></td>
						<?php elseif ($me->type == 'Super Admin'): ?>
							<td><a href="<?=base_url()?>admin/edit_user/<?=$user->id?>">Edit</a> | <a href="<?=base_url()?>admin/delete_user/<?=$user->id?>">Delete</a></td>
						<?php endif; ?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
