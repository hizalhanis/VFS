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
		</div>
		<p>&copy; Copyright LiveCMS 2010. All rights reserved.</p>
	</body>
</html>
