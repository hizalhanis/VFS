
	<?php $this->load->view('cases/sidebar'); ?>
	
	<div id="content">
		<div class="toolbar">
			<h3 class="header">Survey ID &raquo; <?php echo $case->ReportNumber; ?></h3>
			
			<div class="tab-container">
				<a class="tab tab-current" rel="main-tab">Survey Details</a>

			</div>
		</div>

		<div class="content-scroll">

				
				<div class="main-tab tab">

					<table class="grid">
						<thead>
							<tr>
								<th>Survey</th>
								<th>View</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									General Information
								</td>
								<td style="width: 100px; text-align: center;">
									<a class="btn" href="<?php echo base_url(); ?>cases/gi_entry/<?php echo $case->id; ?>/1">Fill</a>
								</td>								
							</tr>

						</tbody>
					</table>
					

					

				</div>
				
				
				
				<div class="map-tab tab" style="display:none">
					
					<div style="height: 500px; width: 100%; margin-top: 10px; margin-bottom: 10px;" id="map_canvas">
			
					</div>
				</div>
				
			</div>
		</div>
	</div>