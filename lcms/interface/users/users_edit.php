			<h1>Edit User</h1>
			<form method="post" action="<?=base_url()?>/admin/update_user/<?=$user->id?>">
				<?php if ($error): ?>
				<p style="border: 1px solid red; padding: 5px; font-size: 10pt; font-family: Arial, sans-serif; color: red"><?=$error?></p>
				<?php endif; ?>
				<table>
					<tr>
						<td style="width: 30%">Username</td>
						<td><?=$user->username?></td>
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
						<td><input type="text" name="firstname" value="<?=$user->firstname?>"/></td>
					</tr>
					<tr>
						<td>Last Name</td>
						<td><input type="text" name="lastname" value="<?=$user->lastname?>"/></td>
					</tr>
					<tr>
						<td>Email</td>
						<td><input type="text" name="email" value="<?=$user->email?>"/></td>
					</tr>
				</table>
				<input type="submit" name="submit" value="Update User" />
			</form>
