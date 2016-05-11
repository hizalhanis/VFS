	<?php $this->load->view('database/sidebar'); ?>
	
	<div id="content">
		<div class="toolbar">
			<h3 class="header">Database</h3>
			
		</div>

		<div style="padding: 10px">
			<div style="float: left; width: 50%">
				<div style="margin: 10px; padding: 0 15px; background: #fafafa; border: 1px solid #ddd; border-radius: 5px; height: 230px">
					<h3>Download CSV</h3>
					<a href="database/download_csv" class="btn">Download Data (CSV)</a>
				</div>
			</div>
			
			<div style="float: left; width: 50%">
				<div style="margin: 10px; padding: 0 15px; background: #fafafa; border: 1px solid #ddd; border-radius: 5px; height: 230px">
					<h3>Upload CSV</h3>
					<?php if ($upload_error): ?>
					<p class="notice">Invalid file</p>
					<?php endif; ?>
					<?php if ($upload_ok): ?>
					<p class="notice">
						<?php echo number_format($total_updates); ?> Record Updated. <?php echo number_format($total_new); ?> New Record.
					</p>
					<?php endif; ?>
					<form method="post" enctype="multipart/form-data" action="database/upload_csv">
						CSV File <input type="file" name="file" /><br />
						Archieve name <input type="text" class="text" name="archive_name" /><br /><br />
						<input type="submit" class="btn" value="Upload data (CSV)" />
					</form>
				</div>
			</div>
		</div>
	
	</div>