			<h1>New Theme</h1>
			<form method="post" action="<?=base_url()?>/admin/save_theme">
				<?php if ($error): ?>
				<p style="border: 1px solid red; padding: 5px; font-size: 10pt; font-family: Arial, sans-serif; color: red"><?=$error?></p>
				<?php endif; ?>
				<table>
					<tr>
						<td style="width: 30%">Name</td>
						<td><input type="text" name="name" value=""/></td>
					</tr>
					<tr>
						<td>Directory Name</td>
						<td><input type="text" name="directory" /></td>
					</tr>
					<tr>
						<td>Author</td>
						<td><input type="text" name="author" value=""/></td>
					</tr>
					<tr>
						<td>Description</td>
						<td><input type="text" name="description"/></td>
					</tr>
 				</table>
				<input type="submit" name="submit" value="Create Theme" />
			</form>