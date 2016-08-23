<script type="text/javascript">

$(document).ready(function(){
	
	$('td.cell').click(function(){
		var sql = $(this).find('span.sql').text();
		alert(sql);
		
	})
	
	
});


</script>

	<?php $this->load->view('analytics/sidebar'); ?>
	<div id="content">
		<div class="toolbar">
			<h3 class="header">Survey Analysis</h3>
		</div>
	
		<div class="content-scroll">
			<div class="padded">
				<form method="post" action="analytics/excel">
					<input type="hidden" name="data" value="<?php echo $base64; ?>" />
					<button style="float:right">Download as Excel</button>
				</form>
				<br style="clear:right" />
				<h3 style="text-align: center"> Frequency Table for Survey Date <?php echo $date_print; ?>
					<br /><br />
					<?php echo $y_axis; ?>
				</h3> 

                <!--filtering capabilities-->
                <div>
					<?php foreach ($filter_desc as $qnc => $filter): ?>
						<p style="text-align: center"><strong><?php echo $qnc; ?></strong>: 
							<?php $x = 0; foreach ($filter as $ans): ?>
								<?php if ($x == 0) echo $ans; else echo ', ' . $ans; ?>
							<?php $x++; endforeach; ?>
						</p>
					<?php endforeach; ?>
				</div>

				<table class="chart" data-graph-container=".. .. .highchart-container" data-graph-type="column">
					<thead>
						<tr>
							<th>Answer</th>

							<th>No of Response</th>


						</tr>
					</thead>
					<tbody>
						<?php $y = 0; foreach ($y_field as $ans): $total = 0; ?>
						<tr>
							<td><?php echo $ans; ?></td>
							<?php for ($x = 0; $x < 1; $x++): ?>
							<td class="cell" style="text-align: center">
								<?php echo $chartdata[$x][$y]; ?>
								<!--
								<span class="sql" style="display:none">
									<?php echo $queries[$x][$y]; ?>
								</span>
								-->
							</td>
							<?php $total += $chartdata[$x][$y]; $ftotal += $chartdata[$x][$y]; $totalx[$x] += $chartdata[$x][$y]; endfor; ?>

						</tr>
						<?php $y++; endforeach; ?>
					
					</tbody>

                    <!--bottom total-->
					<tfoot>
						<tr>
							<th><strong>TOTAL</strong></th>
							<?php for ($x = 0; $x < 1; $x++): ?>
								<th style="text-align: center"><?php echo $totalx[$x]; ?></th>
							<?php endfor; ?>

						</tr>
					</tfoot>
				
				
				
				</table>
				
				<div class="highchart-container" style="margin-top: 50px; margin-bottom: 50px; margin-right: 50px; margin-left: 30px;">
				
				
				</div>
				
				<script type="text/javascript">
				    $(document).ready(function(){
					    $('table.chart').highchartTable();
				    });
				</script>
			</div>
		</div>
	</div>