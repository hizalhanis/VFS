

				<table class="chart">
					<thead>
						<tr>
							<th colspan="<?php echo count($x_field) + 1; ?>">
								<h3 style="text-align: center"><?php echo $x_axis; ?> VS <?php echo $y_axis; ?></h3>
							</th>
						</tr>
						<tr>
							<th colspan="<?php echo count($x_field) + 1; ?>">
							<?php foreach ($filter_desc as $qnc => $filter): ?>
								<p style="text-align: center"><strong><?php echo $qnc; ?></strong>: 
									<?php $x = 0; foreach ($filter as $ans): ?>
										<?php if ($x == 0) echo $ans; else echo ', ' . $ans; ?>
									<?php $x++; endforeach; ?>
								</p>
							<?php endforeach; ?>
							</th>
						</tr>
					</thead>
					<thead>
						<tr>
							<th></th>
							<?php foreach ($x_field as $ans): ?>
							<th><?php echo $ans; ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php $y = 0; foreach ($y_field as $ans): ?>
						<tr>
							<th><?php echo $ans; ?></th>
							<?php for ($x = 0; $x < count($x_field); $x++): ?>
							<td style="text-align: center"><?php echo $chartdata[$x][$y]; ?></td>
							<?php endfor; ?>
						</tr>
						<?php $y++; endforeach; ?>
					
					</tbody>
				
				
				
				</table>
