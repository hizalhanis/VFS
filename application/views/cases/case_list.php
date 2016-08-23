
	<?php $this->load->view('cases/sidebar');
        
        $noq 			= $this->db->query("SELECT COUNT(DISTINCT Date) AS `no_row` FROM `survey_gen` WHERE Date is not null");
        $q_norow        = $noq->row();
        $aaa            = $q_norow->no_row;
        
        $query          = $this->db->query("SELECT DISTINCT Date AS `date` FROM `survey_gen` WHERE Date is not null  ORDER BY Date DESC");
        for ($m = 0; $m < $aaa; $m++){
            $row = $query->row($m);
            $year_list[] = $row->date;
        }
        
        $year_list[$aaa] = 'All dates';
        if (empty ($date)) {
            $date = $aaa;
        }
        
        ?>
	<script type="text/javascript">
	
		$(document).ready(function(){
			$('a.delete').click(function(e){
			e.preventDefault();
			var hurl = $(this).attr('href');
			if (confirm('Delete this record?')) location.href = hurl;
		})

	})
	</script>
	<div id="content">
	
		<div class="toolbar">
			<h3 class="header">Survey list</h3>
			<div style="padding: 5px;">
				<form method="post" action="cases/filter">
					&nbsp; Survey ID <input value="<?php echo $this->input->post('report_number'); ?>" type="text" class="text" name="report_number" style="width: 100px" />
					&nbsp; Select date of Survey: <?php echo form_dropdown('date-selected',$year_list,$date,'class="axis-select"'); ?>

					<button>Find</button>
				</form>
			</div>
			<table class="grid-header">
				<thead>
					<tr>
						<th style="width: 130px">Survey ID</th>
						<th style="width: 130px">Date</th>
						<th style="width: 130px">Action</th>
					</tr>
				</thead>
			</table>
		</div>
		<div class="content-scroll">
			<table class="grid">
				<tbody>
					<?php foreach ($cases as $case): ?>
					<tr>
						<td style="width: 130px; text-align: center"">
							<?php echo $case->ReportNumber; ?>
						</td>
						<td style="width: 130px; text-align: center"><?php echo $case->Date; ?></td>
						<td style="width: 130px">
							<a class="btn" href="<?php echo base_url(); ?>cases/gi_entry/<?php echo $case->id; ?>">View/Update</a>
                            <?php if ($this->user->data('type') == 'Superadmin'): ?>
                            <a class="btn delete" href="<?php echo base_url(); ?>cases/delete/<?php echo $case->id; ?>">Delete</a>
                            <?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		
		</div>
	
	</div>