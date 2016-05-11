	<style>
		table.chart {
			width: 100%;
			border: 1px solid #ddd;
			border-collapse: collapse;
		}
		table.chart td {
			text-align: center;
			border: 1px solid #ddd;
		}
		
		table.chart th {
			border: 1px solid #ddd;
		}
</style>
	
	<div class="lcms-survey-control">
		<a class="lcms-btn" href="<?php echo $current_page; ?>">View This Survey</a>
		<a class="lcms-btn" href="<?php echo $current_page; ?>edit">Edit This Survey</a>
		<a class="lcms-btn" href="<?php echo $current_page; ?>preview/<?php echo $survey->id; ?>" target="_blank">Preview Printable Survey</a>
		<a class="lcms-btn" href="<?php echo $current_page; ?>analysis/<?php echo $survey->id; ?>">Analysis</a>
		<a class="lcms-btn lcms-survey-settings" href="#">Survey Settings</a>
	</div>
	<br /><br />
	<h3>Result</h3>
	
	<table class="chart">
		<thead>
			<tr>
				<th></th>
				<?php foreach ($x as $ans): ?>
				<th><?php echo $ans; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($y as $ans): ?>
			<tr>
				<th><?php echo $ans; ?></th>
				<?php for ($i = 0; $i < count($x); $i++): ?>
				<td><?php echo mt_rand(0,2); ?></td>
				<?php endfor; ?>
			</tr>
			<?php endforeach; ?>
		
		</tbody>
	
	
	
	</table>