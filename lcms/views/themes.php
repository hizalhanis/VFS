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
			<br />
			<a href="<?=base_url()?>admin/new_theme/">Create New Theme</a>
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Author</th>
						<th>Directory</th>
						<th>Added On</th>
						<th>Active</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($themes as $theme):?>
					<tr>
						<td><?=$theme->id?></td>
						<td><?=$theme->name?></td>
						<td><?=$theme->auther?></td>
						<td><?=$theme->directory?></td>
						<td><?=$theme->added_on?></td>
						<td><?=$theme->active ? 'Yes' : 'No'?></td>
						<td>
							<a href="<?=base_url()?>/admin/set_active_theme/<?=$theme->id?>">Set Active</a>
							<?php if ($theme->type != 'installed'): ?>

							<a href="<?=base_url()?>/admin/delete_theme/<?=$theme->id?>">Delete</a>
							<a href="<?=base_url()?>/admin/view_theme/<?=$theme->id?>">View</a>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<p>&copy; Copyright LiveCMS 2010. All rights reserved.</p>
	</body>
</html>
