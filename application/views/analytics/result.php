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
			<h3 class="header">Analisis Rekod Kemalangan</h3>
		</div>
	
		<div class="content-scroll">
			<div class="padded">
				<form method="post" action="analytics/excel">
					<input type="hidden" name="data" value="<?php echo $base64; ?>" />
					<button style="float:right">Muat Turun Sebagai Excel</button>
				</form>
				<br style="clear:right" />
				<h3 style="text-align: center">
					<?php if ($answer_type == 'cases'): ?>
					BILANGAN KEMALANGAN
					<?php else: ?>
					BILANGAN KENDERAAN
					<?php endif; ?>
					<br /><br />
					<?php echo $x_axis; ?> VS <?php echo $y_axis; ?>
				</h3> 
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
							<th></th>
							<?php foreach ($x_field as $ans): ?>
							<th><?php echo $ans; ?></th>
							<?php endforeach; ?>
							<th><strong>Total</strong></th>
						</tr>
					</thead>
					<tbody>
						<?php $y = 0; foreach ($y_field as $ans): $total = 0; ?>
						<tr>
							<td><?php echo $ans; ?></td>
							<?php for ($x = 0; $x < count($x_field); $x++): ?>
							<td class="cell" style="text-align: center">
								<?php echo $chartdata[$x][$y]; ?>
								<!--
								<span class="sql" style="display:none">
									<?php echo $queries[$x][$y]; ?>
								</span>
								-->
							</td>
							<?php $total += $chartdata[$x][$y]; $ftotal += $chartdata[$x][$y]; $totalx[$x] += $chartdata[$x][$y]; endfor; ?>
							<th style="text-align: center"><?php echo $total; ?></th>
						</tr>
						<?php $y++; endforeach; ?>
					
					</tbody>
					<tfoot>
						<tr>
							<th><strong>TOTAL</strong></th>
							<?php for ($x = 0; $x < count($x_field); $x++): ?>
								<th style="text-align: center"><?php echo $totalx[$x]; ?></th>
							<?php endfor; ?>
							<th style="text-align: center"><?php echo $ftotal; ?></th>
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