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
		
		$(control).prepend('<button style="float: right" class="filter-remove">&times;</button>');
	
		$('div.actual-filter i').remove();
		$('div.actual-filter').append(control);
		
		$('select.filter-select').val(0).change();
	});
	
	$(document).on('click','button.filter-remove',function(e){
		e.preventDefault();
		$(this).parents('div.control').remove();
		
		var i = 0;
		$('div.actual-filter div.control').each(function(){
			i++;
		})
		
		if (i == 0){
			$('div.actual-filter').html('');
		}
		


	})
	
		
		
		$('select.s-y-axis').change(function(){
			var logic = $(this).val();			

			$('td.s-y-axis').html('');
			$('td.s-y-axis').append($('select.'+logic).first().clone().attr('name','y'));

			
			
		})
});


</script>	
	<?php $this->load->view('analytics/sidebar'); ?>
	
	<div id="content">
		<div class="toolbar">
			<h3 class="header">Survey Analysis Dashboard</h3>
		</div>
	
		<div class="content-scroll">

			<div class="padded">
			
			<form method="post" action="analytics/process">
					<table>
						<tr>
                            <td>Please select the date of survey: </td>
							<td class="s-y-axis"><?php echo form_dropdown('y',$dropdown_axis,'','class="axis-select"'); ?></td>
						</tr>
					</table>
				

					<input class="btn" type="submit" value="Submit" />
					
					<hr />
				</form>

                <div class="control-filter" style="border: 1px solid #ddd; background: #eee; padding: 10px; border-radius: 5px; margin-bottom: 15px;">


                    <div class="filter">
                        Need to filter the dataset? <?php echo form_dropdown('type',$dropdown,'','class="filter-select"'); ?> <button class="filter-add lcms-btn">Add filter</button>
                        <hr />
                    </div>
                    <?php foreach ($questions as $q): ?>

                    <div class="control" rel="<?php echo $q->map_to; ?>" style="display:none; border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px; margin: 5px 0;">
                        <?php if ($q->type == 'matrix-answer'): $obj = json_decode($q->question); ?>

                        <p><?php echo $obj->description; ?></p>
                            <?php foreach ($obj->questions as $question): ?>
                            <table class="control-box">
                                <td class="label"><?php echo $question->question; ?></td>
                                <td class="value"><input type="text" name="q[<?php echo $q->map_to; ?>][<?php echo $question->no; ?>]" /></td>
                            </table>

                            <?php endforeach; ?>

                    <?php else: $qn = json_decode($q->answers);  ?>

                        <p><?php echo $q->question; ?></p>
                        <?php foreach ($qn as $answer): ?>
                            <input type="checkbox" name="q[<?php echo $q->map_to; ?>][]" value="<?php echo $answer->value; ?>|<?php echo $answer->no; ?>|<?php echo $q->question; ?>|<?php echo $q->type; ?>" /> <?php echo $answer->value; ?><br />
                        <?php endforeach; ?>


                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>







			</div>
		</div>
	</div>