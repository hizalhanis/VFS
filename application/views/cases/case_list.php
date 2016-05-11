	
	<?php $this->load->view('cases/sidebar'); ?>
	<script type="text/javascript">
	
		$(document).ready(function(){
			$('a.delete').click(function(e){
			e.preventDefault();
			var hurl = $(this).attr('href');
			if (confirm('Padam rekod kemalangan?')) location.href = hurl;
		})

	})
	</script>
	<div id="content">
	
		<div class="toolbar">
			<h3 class="header">Survey list</h3>
			<div style="padding: 5px;">
				<form method="post" action="cases/filter">
					&nbsp; Status <?php echo form_dropdown('status', array('Please choose','Tidak Lengkap'=>'Incomplete','Lengkap'=>'Completed'), $this->input->post('status')); ?>
					&nbsp; Survey ID <input value="<?php echo $this->input->post('report_number'); ?>" type="text" class="text" name="report_number" style="width: 100px" />
					&nbsp; Date from <input type="text" class="text date" name="df" value="<?php echo $this->input->post('df') ? $this->input->post('df') : ($this->session->userdata('case_df') ? $this->session->userdata('case_df') : date('1/m/Y')); ?>" style="width:80px" />
					&nbsp; Date untill <input type="text" class="text date" name="dt" value="<?php echo $this->input->post('dt') ? $this->input->post('dt') : ($this->session->userdata('case_dt') ? $this->session->userdata('case_dt') : date('d/m/Y')); ?>" style="width:80px" />

					<button>Find</button>
				</form>
			</div>
			<table class="grid-header">
				<thead>
					<tr>
						<th style="width: 130px">Survey ID</th>
						<th>Location</th>
						<th style="width: 130px">Date</th>
						<th style="width: 130px">Status</th>
						<th style="width: 180px"></th>
						<th style="width: 3px"></th>	
					</tr>
				</thead>
			</table>
		</div>
		<div class="content-scroll">
			<table class="grid">
				<tbody>
					<?php foreach ($cases as $case): ?>
					<tr>
						<td style="width: 130px">
							<?php echo $case->ReportNumber; ?>
						</td>
						<td>
							<?php echo $case->nama_jalan; ?>
						</td>
						<td>
							<?php echo $case->nama_tempat; ?>
						</td>
	
						<td style="width: 130px; text-align: center"><?php echo date('d/m/Y',strtotime($case->month)); ?></td>
						<td style="width: 130px; text-align: center;"><?php echo $case->status ? $case->status : 'Tidak Lengkap'; ?></td>
						<td style="width: 130px; text-align: center;"><?php echo $case->verified ? $case->verified : 'Belum Disahkan'; ?></td>
						<td style="width: 180px">
							<?php if ($this->user->data('type') == 'Superadmin'): ?>
							<a class="btn delete" href="<?php echo base_url(); ?>cases/delete/<?php echo $case->id; ?>">Delete</a>
							<?php endif; ?>
							<?php if ($this->user->has_case_access($case)): ?>
							<a class="btn" href="<?php echo base_url(); ?>cases/edit/<?php echo $case->id; ?>">Update</a>
							<?php endif; ?>
							<a class="btn" href="<?php echo base_url(); ?>cases/view/<?php echo $case->id; ?>">View details</a>


						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		
		</div>
	
	</div>