<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="stylesheet" href="css/style.css" />
		<title>LCMS - Users</title>
		<style>
			h1 {
				font-size: 15pt;
				color: #333;
				font-weight: bold;
			}
			div.header {
				background: url(<?=base_url()?>css/images/lcms-controller-bg.png) repeat-x;
				padding: 7px 5px;
				color: #FFF;
				font-size: 10pt;
			}
			div.header a {
				color: #FFF;
				font-family: "Lucida Sans", "Lucida Grande", tahoma,sans-serif;
				font-size: 10pt;
				font-weight: bold;
				text-decoration: none;
			}
			table {
				width: 100%;
				border-collapse: collapse;
				font-size: 10pt;
				font-family: Arial, Helvetica, tahoma, sans-serif;
				border: 1px solid #DDD;
				margin: 10px 0;
			}
			
			thead {
				background: #eee;
			}
			
			table td, table th {
				padding: 3px;
			}
		</style>
	</head>
	<body style="text-align: center; background: #eee;">
		<div id="lcms-container" style="margin: 0 auto; width: 800px; text-align: left; background: #fff; padding: 15px">
			<h1>LiveCMS</h1>
			<div class="header">
				<a href="<?=base_url()?>">Home</a> | 
				<a href="<?=base_url()?>admin/users">Users</a> | 
				<a href="<?=base_url()?>admin/add_user">Add New User</a> | 
				<a href="<?=base_url()?>admin/themes">Themes</a> | 
				<a href="<?=base_url()?>admin/logout">Log Out</a>
			</div>
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
		</div>
		<p>&copy; Copyright LiveCMS 2010. All rights reserved.</p>
	</body>
</html>
