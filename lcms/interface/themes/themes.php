			<h1>Themes</h1>
			<?php if ($alert): ?>
			<p style="border: 1px solid #008800; padding: 5px; font-size: 10pt; font-family: Arial, sans-serif; color: #008800"><?=$alert?></p>
			<?php endif; ?>
			<?php if ($error): ?>
			<p style="border: 1px solid red; padding: 5px; font-size: 10pt; font-family: Arial, sans-serif; color: red"><?=$error?></p>
			<?php endif; ?>
			<a href="">Create New Theme<sup>Coming Soon!</sup></a>
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
