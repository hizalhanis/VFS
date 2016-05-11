			<h1>Theme Details</h1>
			<?php if ($alert): ?>
			<p style="border: 1px solid #008800; padding: 5px; font-size: 10pt; font-family: Arial, sans-serif; color: #008800"><?=$alert?></p>
			<?php endif; ?>
			<?php if ($error): ?>
			<p style="border: 1px solid red; padding: 5px; font-size: 10pt; font-family: Arial, sans-serif; color: red"><?=$error?></p>
			<?php endif; ?>
			<a href="">Create New Layout<sup>Coming Soon!</sup></a>
			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($layouts as $layout):?>
					<tr>
						<td><?=$layout->id?></td>
						<td><?=$layout->name?></td>
						<td>
							<a href="<?=base_url()?>/admin/delete_layout/<?=$layout->id?>">Delete</a>
							<a href="<?=base_url()?>/admin/edit_layout/<?=$layout->id?>">Edit</a>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
