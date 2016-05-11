			<h1>Add User</h1>
			<form method="post" action="<?=base_url()?>/admin/create_user">
				<?php if ($error): ?>
				<p style="border: 1px solid red; padding: 5px; font-size: 10pt; font-family: Arial, sans-serif; color: red"><?=$error?></p>
				<?php endif; ?>
				<table>
					<tr>
						<td style="width: 30%">Username</td>
						<td><input type="text" name="username" value="<?=$form->username?>"/></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type="password" name="password" /></td>
					</tr>
					<tr>
						<td>Repeat Password</td>
						<td><input type="password" name="repeat_password" /></td>
					</tr>
					<tr>
						<td>First Name</td>
						<td><input type="text" name="firstname" value="<?=$form->firstname?>"/></td>
					</tr>
					<tr>
						<td>Last Name</td>
						<td><input type="text" name="lastname" value="<?=$form->lastname?>"/></td>
					</tr>
					<tr>
						<td>Email</td>
						<td><input type="text" name="email" value="<?=$form->email?>"/></td>
					</tr>
					<tr>
						<td>Type</td>
						<td><?=form_dropdown('type',array('Admin'=>'Admin'))?></td>
					</tr>
				</table>
				<input type="submit" name="submit" value="Create User" />
			</form>
		
