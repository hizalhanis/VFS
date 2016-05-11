<script type="text/javascript">

$(document).ready(function(){
	
	$('select.filter-select').change(function(){
		var id = $(this).val();
		$('div.control-filter div.control').hide();
		$('div.control[rel='+id+']').show();
	});
	
	$('button.filter-add').click(function(){
		var id = $('select.filter-select').val();
		var control = $('div.control[rel='+id+']').first().clone();
		
		$(control).prepend('<button style="float: right" class="filter-remove">Remove</button>');
	
		$('div.actual-filter i').remove();
		$('div.actual-filter').append(control);
	});
	
	$('button.filter-remove').live('click',function(){
		$(this).parents('div.control').remove();
		
		var i = 0;
		$('div.actual-filter div.control').each(function(){
			i++;
		})
		
		if (i == 0){
			$('div.actual-filter').html('<i>There are no filters applied. Select a filter and click \'Add\'</i>');
		}
	})
});


</script>

	<div class="main-survey" style="position: fixed; top: 35px; left: 0; right: 0; bottom: 0; background: #FFF; padding: 15px; overflow-y: auto">
	
		<?php if ($author_mode): ?>
		<div class="lcms-survey-control">
			<a class="lcms-btn" href="<?php echo $current_page; ?>">View This Survey</a>
			<a class="lcms-btn" href="<?php echo $current_page; ?>edit">Edit This Survey</a>
			<a class="lcms-btn" href="<?php echo $current_page; ?>preview/<?php echo $survey->id; ?>" target="_blank">Preview Printable Survey</a>
			<a class="lcms-btn" href="<?php echo $current_page; ?>analysis/<?php echo $survey->id; ?>">Analysis</a>
			<a class="lcms-btn lcms-survey-settings" href="#">Survey Settings</a>
		</div>
		
		
		<div class="analysis" style="padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
		
			<div class="filter">
				Field <?php echo form_dropdown('type',$dropdown,'','class="filter-select"'); ?> <button class="filter-add lcms-btn">Add</button>
				<hr />
			</div>
		
		
			<div class="control-filter" style="border: 1px solid #ddd; background: #eee; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
				<strong>Filter Preview</strong><br />	
				<?php foreach ($questions as $q): ?>
				
				<div class="control" rel="<?php echo $q->id; ?>" style="display:none; border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px; margin: 5px 0;">
					<?php if ($q->type == 'matrix-answer'): $obj = json_decode($q->question); ?>
					
					<p><?php echo $obj->description; ?></p>
						<?php foreach ($obj->questions as $question): ?>
						<table class="control-box">
							<td class="label"><?php echo $question->question; ?></td>
							<td class="value"><input type="text" name="q[<?php echo $q->id; ?>][<?php echo $question->no; ?>]" /></td>
						</table>
						
						<?php endforeach; ?>
		
					<?php else: $qn = json_decode($q->answers);  ?>
		
						<p><?php echo $q->question; ?></p>
						<?php foreach ($qn as $answer): ?>
							<input type="checkbox" name="q[<?php echo $q->id; ?>][]" value="<?php echo $answer->value; ?>" /> <?php echo $answer->value; ?><br />
						<?php endforeach; ?>
					
					
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>
		

		
			<form method="post" action="<?php echo $current_page; ?>analysis_process/<?php echo $survey->id; ?>">
				<h3>Applied Filters</h3>
				<div class="actual-filter">
					<i>There are no filters applied. Select a filter and click 'Add'</i>
					
				</div>
			
				<hr />
				<input class="lcms-btn" type="submit" value="Process Filter" />
			</form>
		
			
			<?php endif; ?>
		
		</div>
			
	</div>
	
