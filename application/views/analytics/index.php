<?php $this->load->view('analytics/sidebar');
    
    $noq 			= $this->db->query("SELECT COUNT(DISTINCT Date) AS `no_row` FROM `survey_gen` WHERE Date is not null");
    $q_norow        = $noq->row();
    $aaa            = $q_norow->no_row;
    
    $query          = $this->db->query("SELECT DISTINCT Date AS `date` FROM `survey_gen` WHERE Date is not null ORDER BY Date DESC");
        for ($m = 0; $m < $aaa; $m++){
            $row = $query->row($m);
            $year_list[] = $row->date;
        }
    
    $year_list[$aaa] = 'All dates';
    if (empty ($date_picked) AND $date_picked != 0) {
        $date_picked = $m-1;
    }
?>

    <div id="content" class="toolbar">

        <form method="post" action="analytics/process">
            <table>
                <td><h3>Analysis Dashboard for Survey Date of <?php echo $year_list[$date_picked] ?></td>
                <td><?php echo form_dropdown('date-selected',$year_list,$date_picked,'class="axis-select"'); ?></td>
                </h3>
                <td><input class="btn" type="submit" value="Change" /></td>
            </table>
        </form>
    </div>

    <div class="content-scroll">
			<div class="padded">




<?php for ($c = 0; $c < $totalq; $c++) {
    $y = $c; $j = 0;
    
    foreach ($y_field[$y] as $ans) {
        //sum is $totalx[$c]
        $totalx[$c] += $chartdata[$c][$j];
        $j++;
        $y++;
    }
}
?>


<?php for ($c = 0; $c < $totalq; $c++): ?>

				<br style="clear:right" />
                    <h3 style="text-align: center"> Survey Question:
					<br /><br />
					<?php echo $y_axis[$c]; ?>
                    </h3>

            <table class="chart" data-graph-container-before="1" data-graph-type="pie"  data-graph-datalabels-enabled="1" >
					<thead>
						<tr>
							<th>Answer</th>
							<th>Response (%)</th>
						</tr>
					</thead>
					<tbody>
						<?php $y = $c; $j = 0; foreach ($y_field[$y] as $ans):; ?>
						<tr>
							<td><?php echo $ans; ?></td>
                                <td data-graph-name= <?php echo $ans; ?> class="cell" style="text-align: center">
								<?php echo round(($chartdata[$c][$j]/$totalx[$c]*100),2); ?>
							</td>
                            <?php $j++; ?>
						</tr>
						<?php $y++; endforeach; ?>
                    </tbody>
                </table>
    <?php endfor; ?>


                <script type="text/javascript">
				    $(document).ready(function(){
					    $('table.chart').highchartTable();
				    });
				</script>




			</div>
		</div>
	</div>