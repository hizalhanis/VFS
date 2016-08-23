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
                  
                  $('select.s-x-axis').change(function(){
                                              
                                              var logic = $(this).val();			
                                              
                                              $('td.s-x-axis').html('');
                                              $('td.s-x-axis').append($('select.'+logic).first().clone().attr('name','x'));
                                              
                                              })
                  
                  $('select.s-y-axis').change(function(){
                                              var logic = $(this).val();			
                                              
                                              $('td.s-y-axis').html('');
                                              $('td.s-y-axis').append($('select.'+logic).first().clone().attr('name','y'));
                                              
                                              
                                              
                                              })
                  });


</script>




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
	
	<div id="content">
		<div class="toolbar">
			<h3 class="header">Frequency Table</h3>
		</div>
        <form method="post" action="analytics/Frequency_process">
		<div class="content-scroll">
			<div style="display:none">
				<?php echo form_dropdown('a',$dropdown_general,'','class="general"'); ?>
			</div>
			<div class="padded">
			

					<table>
                        <tr>
                            <td>Select date of Survey: </td>
                            <td><?php echo form_dropdown('date-selected',$year_list,$date_picked,'class="axis-select"'); ?></td>
						<tr>
                            <td>Frequency of response for question: </td>
							<td class="s-y-axis"><?php echo form_dropdown('y',$dropdown_axis,'','class="axis-select"'); ?></td>
                        </tr>
					</table>
					<input class="btn" type="submit" value="Process" />

					<hr />
				</form>




            <!--add guidelines to check the answer options-->
            <div class="control-filter" style="border: 1px solid #ddd; background: #eee; padding: 10px; border-radius: 5px; margin-bottom: 15px;">


                <div class="filter">
                    Check the answer options for: <?php echo form_dropdown('type',$dropdown,'','class="filter-select"'); ?> <hr />
                </div>
                <?php foreach ($questions as $q): ?>

                <div class="control" rel="<?php echo $q->map_to; ?>" style="display:none; border: 1px solid #ddd; padding: 5px 10px; border-radius: 5px; margin: 5px 0;">
                <?php if ($q->type == 'matrix-answer'): $obj = json_decode($q->question); ?>

                <?php foreach ($obj->questions as $question): ?>
                    <table class="control-box">
                        <td class="label">This is an open-ended question. Answers may varied.</td>
                    </table>

                <?php endforeach; ?>

                <?php else: $qn = json_decode($q->answers);  ?>

                    <p>Possible answers:</p>
                        <?php foreach ($qn as $answer): ?>
                            <?php echo $answer->value; ?><br />
                        <?php endforeach; ?>


                    <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>