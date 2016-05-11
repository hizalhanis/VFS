	
	<?php $this->load->view('users/sidebar'); ?>
	
	<div id="content">
	
		<div class="toolbar">
			<h3 class="header">
				<a href="users/add_team" class="btn" style="float:right; padding: 0px 5px">Tambah</a>
				Kumpulan
			</h3>
			<table class="grid-header">
				<thead>
					<tr>
						<th>Nama Kumpulan</th>
						<th style="width: 60px"></th>
						<th style="width: 3px"></th>
					</tr>
				</thead>
			</table>	
		</div>
		
		<div class="content-scroll">
		
			<table class="grid">
				<tbody>
					<?php foreach ($teams as $team): ?>
					<tr>
						<td><?php echo $team->name; ?></td>
						<td style="width: 60px">
							<?php if ($this->user->data('type') == 'Superadmin'): ?>
							<a class="btn delete-btn" href="users/delete_team/<?php echo $team->id; ?>/do">Padam</a>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			
			</table>
		
		</div>
	
	
	</div>