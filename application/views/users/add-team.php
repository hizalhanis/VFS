	
	<?php $this->load->view('users/sidebar'); ?>
	
	<div id="content">
		<div class="toolbar">
			<h3 class="header">Kumpulan Baru</h3>
			<div class="tool">
				<button class="submit-btn" rel="form">Simpan</button>
			</div>

		</div>
		<div class="content-scroll">
			<div class="padded">
				<form id="form" method="post" action="users/add_team/do">
					
					<table class="form">
						<tr>
							<td class="label">Nama Kumpulan</td>
							<td class="input">
								<input type="text" class="text" name="name" />
							</td>
						</tr>
						
					</table>
					
					
				</form>
			</div>
		</div>

	
	</div>