<?php


$style = 'background: none !important; border-radius: 0; border-bottom: 1px solid #eee; padding: 0px 10px 10px 10px'


?>
<script type="text/javascript">
	
$(document).ready(function(){
                  
    $('a.delete').click(function(e){
        e.preventDefault();
        var hurl = $(this).attr('href');
        if (confirm('Exit survey mode?')) location.href = hurl;
    });

	$('input.min').bind('click',function(){
		var tr = $(this).parents('tr');
		if ($(this).attr('checked')){
			$(tr).next().show();
		} else {
			$(tr).next().hide();
		}
	});
	
	$('input.max').bind('click',function(){
		var tr = $(this).parents('tr');
		if ($(this).attr('checked')){
			$(tr).next().show();
		} else {
			$(tr).next().hide();
		}
	});
	
	$('input.reg').bind('click',function(){
		var tr = $(this).parents('tr');
		
		if ($(this).hasClass('min')) return;
		if ($(this).hasClass('max')) return;
		
		if ($(this).attr('checked')){
			$(tr).next().hide();
		} else {
			$(tr).next().show();
		}
	});
	
	$('input.reason-on-checked').bind('click', function(){
		var div = $(this).parent('div');
		if ($(this).attr('checked')){
			$(div).find('div.reason-on-checked').show();
		} else {
			$(div).find('div.reason-on-checked').hide();			
		}
	})
	
	$('a.jump-nav').click(function(e){
		e.preventDefault();
		
		$('div.lcms-survey-page').removeClass('current-q');
		$('div.lcms-survey-question-slide').removeClass('current-q');
		
		$('div.current-q').find('form').submit();

		var no = $(this).attr('rel');
		var div = $('div[no='+no+']').addClass('current-q');
		
		var cs = $('div.content-scroll').scrollTop();
		var t = $(div).offset();
		console.log(t.top);
		console.log(cs);
		

		var target = cs + t.top - 110;
	
		
		$('div.content-scroll').scrollTo(target);
		
	})
	
	$('input[type=radio],input[type=checkbox]').click(function(){
		var that = this;
		setTimeout(function(){
			$(that).parents('form').submit();
		}, 200);
	});
	
	$('input[type=text]').blur(function(){
		
		$(this).parents('form').submit();
	});
	
	$('div.lcms-survey-question-slide').show();
	
	
});


function ansSubmitted(no){
	$('a.jump-nav[rel='+no+']').addClass('answer-filled');
}



var engine_url = 'cases/ajax/';
var engine_user = '<?php echo $user; ?>';

function send()
{
    alert("Your response has been submitted! Thank you for your feedback");
}

</script>


		<div class="toolbar">
            <a class="btn-right btn" style="padding: 0 15px;" href="cases/New_case" onclick="send()" value="Confirmation">Save/Submit</a>
            <a class="btn-right btn delete" style="padding: 0 30px;" href="<?php echo base_url(); ?>cases/delete/<?php echo $case->id; ?>" value="Cancel">Exit</a>
<div style="text-align: center">
			<h3 class="header">Survey Questionnaire Form</h3></div>
		</div>

		<div class="content-scroll">
			<div class="side-nav" style="width: 250px; float: left; position: fixed; height: inherit; background: #555; color: #FFF; overflow-y: auto">
				<?php $y = 1; foreach ($questions as $question):  ?>
				<?php if ($question->type == 'section'): ?>
					<h3 class="section"><?php echo $question->question; ?></h3>
				<?php else: ?>
					<?php 
						if ($question->type == 'matrix-answer'){
							$obj = json_decode($question->question);
							$the_question = ucwords(strtolower($obj->description));
						} else {
							$the_question = ucwords(strtolower($question->question));
						}
						
						if (trim($info->{$question->map_to})){
							$filled = true;
							$filled_class = "answer-filled";
						} else {
							$filled = false;
							$filled_class = "";
						}
					?>
					<?php if ($filled): ?>
						<a href="#" class="jump-nav <?php echo $filled_class; ?>" style="color: #FFF; padding: 10px; border-bottom: 1px solid #888; display: block;" rel="<?php echo $y; ?>"><?php echo $the_question; ?></a>					
					<?php else: ?>
						<a href="#" class="jump-nav <?php echo $filled_class; ?>" style="color: #FFF; padding: 10px; border-bottom: 1px solid #888; display: block;" rel="<?php echo $y; ?>"><?php echo $the_question; ?></a>
					<?php endif; ?>
				<?php endif; ?>
				<?php $y++; endforeach; ?>
			</div>
			
			
			<div class="main-survey" style="background: #FFF; padding: 15px 15px 15px 250px;">
		
				
				<iframe style="display: none" name="submit_answer" id="submit_answer"></iframe>
			
				<div>
					<div class="lcms-survey-question-control" style="display:none">
						<div class="progress">
							<div class="progress-bar">
								<div class="progress-bar-highlight"></div>
							</div>			
							Survey Progress
						</div>
						
					</div>
			
					<div no="0" class="lcms-survey-page lcms-survey-introduction">
						<?php echo nl2br($survey->introduction); ?>
					</div>
					<?php $x = 1; foreach ($questions as $question): if ($question->display): $answers = json_decode($question->answers); ?>
					
							<?php if ($question->type == 'section'): ?>
							<div id="sq<?php echo $x; ?>" no="<?php echo $x; ?>" class="lcms-survey-question-slide survey-section">
								<h1><?php echo $question->question; ?></h1>
								<?php echo nl2br($question->comments_description); ?>
								<?php if ($question->question) $section_title = $question->question; ?>
							</div>
							
							<?php endif; ?>
			
			
							<?php if ($question->type == 'single-answer'): $other = json_decode($question->other); ?>
								<div id="sq<?php echo $x; ?>" no="<?php echo $x; ?>" class="lcms-survey-question-slide" style="<?php echo $style; ?>">
								<form method="post" action="cases/ajax/submit_answer" target="submit_answer">
									<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
									<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
									<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
									<input type="hidden" name="user" value="<?php echo $user; ?>" />
									<input type="hidden" name="map_to" value="<?php echo $question->map_to; ?>" />
									<input type="hidden" name="report_number" value="<?php echo $case->ReportNumber; ?>" />
									<input type="hidden" name="sub_id" value="<?php echo $sub_id; ?>" />
									<input type="hidden" name="logic" value="<?php echo $logic; ?>" />
									
									<div class="survey-question">
										<div>
											<h3><?php echo $question->question; ?></h3>
										</div>
										<?php if ($question->modifier): ?>
											<?php
											$output = str_replace('<answer','<input class="survey-radio-btn" type="radio" name="ans"', $question->modifier);
											$output = str_replace('></answer>', ' />', $output);
											echo $output;
										?>

										
									</div>
									
									<div class="single-answer">
										<?php endif; ?>													

											<?php $a = 0; foreach ($answers as $answer): $a++; ?>
												<?php if ($answer->type == 'other'): ?>
													<div class="s-a-a"><input <?php if ($info->{$question->map_to} == $a) echo 'checked="checked"'; ?> class="survey-radio-btn" type="radio" name="ans" value="<?php echo $a; ?>" /> <?php echo $answer->value; ?></div>
													<div style="padding-left: 20px">Please state: <input class="survey-answer-other" type="text" name="other" style="width: 250px"  /></div>
												<?php else: ?>
													<div class="s-a-a">
														<input <?php if ($info->{$question->map_to} == $a) echo 'checked="checked"'; ?> class="survey-radio-btn <?php if ($other->state_reason_if_checked) echo 'reason-on-checked'; ?>" type="radio" name="ans" value="<?php echo $a; ?>" /> <?php echo $answer->value; ?>
														<div class="reason-on-checked" style="display:none; padding-left: 20px">Please state: <input class="survey-answer-other" style="width: 250px" type="text" name="other" /></div>
													</div>
												<?php endif; ?>
											<?php endforeach; ?>

									</div>
									<?php if ($question->comments): ?>
									<div style="margin-top: 10px"><?php echo $question->comments_description; ?></div>
									<textarea class="survey-comments" name="comments"></textarea>
									<?php endif; ?>
									
								</form>
								</div>
							<?php endif; ?>
							
							
							<?php if ($question->type == 'multiple-answer'): $other = json_decode($question->other); ?>
								<div id="sq<?php echo $x; ?>" no="<?php echo $x; ?>" class="lcms-survey-question-slide"  style="<?php echo $style; ?>">
								<form method="post" action="cases/ajax/submit_answer" target="submit_answer">
									<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
									<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
									<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
									<input type="hidden" name="user" value="<?php echo $user; ?>" />
									<input type="hidden" name="map_to" value="<?php echo $question->map_to; ?>" />
									<input type="hidden" name="report_number" value="<?php echo $case->ReportNumber; ?>" />
									<input type="hidden" name="sub_id" value="<?php echo $sub_id; ?>" />
									<input type="hidden" name="logic" value="<?php echo $logic; ?>" />
									
									<?php $infosel = explode(',', $info->{$question->map_to}); ?>

									<div class="survey-question">
										
										<h3><?php echo $question->question; ?></h3>
										<?php if ($question->modifier): ?>
											<?php
											$output = str_replace('<answer','<input class="survey-radio-btn" type="radio" name="ans"', $question->modifier);
											$output = str_replace('></answer>', ' />', $output);
											echo $output;
											?>
										<?php endif; ?>

									</div>
									<div class="multiple-answer">
										
										<?php $a = 0; foreach ($answers as $answer): $a++; ?>
											<?php if ($answer->type == 'other'): ?>
												<div><input <?php if (in_array($a, $infosel)) echo 'checked="checked"'; ?> class="survey-checkbox-btn" type="checkbox" name="ans[]" value="<?php echo $a; ?>" /> <?php echo $answer->value; ?></div>
												<div style="padding-left: 20px">Please state: <input class="survey-answer-other" type="text" name="other[<?php echo $answer->no; ?>]" style="width: 250px"  /></div>
											<?php else: ?>
												<div>
													<input <?php if (in_array($a, $infosel)) echo 'checked="checked"'; ?> class="survey-checkbox-btn <?php if ($other->state_reason_if_checked) echo 'reason-on-checked'; ?>" type="checkbox" name="ans[]" value="<?php echo $a; ?>" /> <?php echo $answer->value; ?>
													<div class="reason-on-checked" style="display:none; padding-left: 20px">Please state: <input class="survey-answer-other" style="width: 250px" type="text" name="other" /></div>
												</div>
											<?php endif; ?>
										<?php endforeach; ?>
									</div>
									<?php if ($question->comments): ?>
										<div style="margin-top: 10px"><?php echo $question->comments_description; ?></div>
										<textarea class="survey-comments" name="comments"></textarea>
									<?php endif; ?>
			
								</form>
								</div>
							<?php endif; ?>

							
							<?php if ($question->type == 'matrix-choice'): $q = json_decode($question->question); $other = json_decode($question->other); ?>
								<div id="sq<?php echo $x; ?>" no="<?php echo $x; ?>" class="lcms-survey-question-slide" style="<?php echo $style; ?>"> 
								<form method="post" action="cases/ajax/submit_answer" target="submit_answer">
									<input type="hidden" name="id" value="<?php echo $id; ?>" />
									<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
									<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
									<input type="hidden" name="user" value="<?php echo $user; ?>" />
									<input type="hidden" name="map_to" value="<?php echo $question->map_to; ?>" />
									<input type="hidden" name="report_number" value="<?php echo $case->ReportNumber; ?>" />
									<input type="hidden" name="sub_id" value="<?php echo $sub_id; ?>" />
									<input type="hidden" name="logic" value="<?php echo $logic; ?>" />

									<div class="matrix-choice">
										<div>
											<h3><?php echo $q->description; ?></h3>
										</div>
										<?php if ($question->modifier): ?>
											<?php
											$output = str_replace('<answer','<input class="survey-radio-btn" type="radio" name="ans"', $question->modifier);
											$output = str_replace('></answer>', ' />', $output);
											echo $output;
											?>
										<?php endif; ?>
										<table style="width: 100%">
											<thead>
												<tr>
													<th></th>
													<?php foreach ($q->labels as $label): ?>
														<th style="width: 100px; text-align: center"><?php echo $label->value; ?></th>
													<?php endforeach; ?>
												</tr>
											</thead>
											<tbody>
												<?php $j = 0; foreach ($q->questions as $qn): ?>
												<tr class="ans <?php if ($j%2) echo 'odd'; ?>">
												<td><?php echo $qn->question; ?></td>
												<?php $jx = 0; $max = count($q->labels) - 1; foreach ($q->labels as $label): ?>
													<td style="width: 100px; text-align: center;"><input class="<?php if ($q->state_reason_if_min) echo $jx == 0 ? 'min' : ''; ?> reg <?php if ($q->state_reason_if_max) echo $jx == $max ? 'max' : ''; ?> survey-answer" type="radio" name="ans[a<?php echo $qn->no; ?>]" value="<?php echo $label->value; ?>" /></td>
												<?php $jx++; endforeach; ?>
											</tr>
												<?php if ($q->state_reason_if_min || $q->state_reason_if_max): ?>
												<tr class="state-reason  <?php if ($j%2) echo 'odd'; ?>" style="display:none">
													<td colspan="<?php echo $jx + 1; ?>">
														Please state: <input type="text" style="width: 80%" name="reason[a<?php echo $qn->no; ?>]" />
													</td>
												</tr>
												<?php endif; ?>
												<?php $j++; endforeach; ?>
											</tbody>
										</table>
										<?php if ($q->comments): ?>
										<div style="margin-top: 10px"><?php echo $q->comments_description; ?></div>
										<textarea class="survey-comments" name="other"></textarea>
										<?php endif; ?>

									</div>
								</form>
								</div>
							<?php endif; ?>
							

							<?php if ($question->type == 'matrix-choice-ma'): $q = json_decode($question->question);?>
								<div id="sq<?php echo $x; ?>" no="<?php echo $x; ?>" class="lcms-survey-question-slide" style="<?php echo $style; ?>">
								<form method="post" action="cases/ajax/submit_answer" target="submit_answer">
									<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
									<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
									<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
									<input type="hidden" name="user" value="<?php echo $user; ?>" />
									<input type="hidden" name="map_to" value="<?php echo $question->map_to; ?>" />
									<input type="hidden" name="report_number" value="<?php echo $case->ReportNumber; ?>" />
									<input type="hidden" name="sub_id" value="<?php echo $sub_id; ?>" />
									<input type="hidden" name="logic" value="<?php echo $logic; ?>" />

									<div class="matrix-choice">
										<div>
											<h3><?php echo $q->description; ?></h3>
										</div>
										<?php if ($question->modifier): ?>
											<?php
											$output = str_replace('<answer','<input class="survey-radio-btn" type="radio" name="ans"', $question->modifier);
											$output = str_replace('></answer>', ' />', $output);
											echo $output;
											?>
										<?php endif; ?>
										<table style="width: 100%">
											<thead>
												<tr>
													<th></th>
													<?php foreach ($q->labels as $label): ?>
														<th style="width: 100px; text-align: center;"><?php echo $label->value; ?></th>
													<?php endforeach; ?>
												</tr>
											</thead>
											<tbody>
												<?php $j = 0; foreach ($q->questions as $qn): ?>
												<tr class="ans <?php if ($j%2) echo 'odd'; ?>">
													<td><?php echo $qn->question; ?></td>
													<?php foreach ($q->labels as $label): ?>
														<td style="width: 100px; text-align: center;"><input class="survey-answer" type="checkbox" name="ans[a<?php echo $qn->no; ?>][]" value="<?php echo $label->value; ?>" /></td>
													<?php endforeach; ?>
												</tr>
												<?php $j++; endforeach; ?>
											</tbody>
										</table>
										<?php if ($q->comments): ?>
										<div><?php echo $q->comments_description; ?></div>
										<textarea class="survey-comments" name="other"></textarea>
										<?php endif; ?>
								
									</div>
								</form>
								</div>
							<?php endif; ?>
							
							<?php if ($question->type == 'matrix-answer'): $q = json_decode($question->question);?>
								<div id="sq<?php echo $x; ?>" no="<?php echo $x; ?>" class="lcms-survey-question-slide" style="<?php echo $style; ?>">
								<form method="post" action="cases/ajax/submit_answer" target="submit_answer">
									<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
									<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
									<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
									<input type="hidden" name="user" value="<?php echo $user; ?>" />
									<input type="hidden" name="map_to" value="<?php echo $question->map_to; ?>" />
									<input type="hidden" name="report_number" value="<?php echo $case->ReportNumber; ?>" />
									<input type="hidden" name="sub_id" value="<?php echo $sub_id; ?>" />
									<input type="hidden" name="logic" value="<?php echo $logic; ?>" />

									<div class="matrix-choice">
										<div>
											<h3><?php echo $q->description; ?></h3>
										</div>
										<?php if ($question->modifier): ?>
											<?php
											$output = str_replace('<answer','<input class="survey-radio-btn" type="radio" name="ans"', $question->modifier);
											$output = str_replace('></answer>', ' />', $output);
											echo $output;
											?>
										<?php endif; ?>
										<table style="width: 100%">
				
											<tbody>
												<?php $j = 0; $a = 0; foreach ($q->questions as $qn): $a++; ?>
												<tr class="ans <?php if ($j%2) echo 'odd'; ?>">
													<td><?php echo $qn->question; ?></td>
										<?php if ($question->data_type == 'date'): ?>
											<td style="text-align: left; width: 100%"><input class="survey-answer date" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>
										
										<?php elseif ($question->data_type == 'time'): ?>
											<td style="text-align: left; width: 100%"><input class="survey-answer time" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>
										<?php else: ?>

												<td style="text-align: left; width: 100%"><input class="survey-answer <?php echo $question->map_to; ?>" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>

										<?php endif; ?>

										</tr>
												<?php $j++; endforeach; ?>
											</tbody>
										</table>
										<?php if ($q->comments): ?>
										<div style="margin-top: 10px"><?php echo $q->comments_description; ?></div>
										<textarea class="survey-comments" name="other"></textarea>
										<?php endif; ?>
									</div>
								</form>
								</div>
							<?php endif; ?>
							
							

					<?php $x++; endif;  endforeach; ?>
					<div no="<?php echo $x; ?>" class="lcms-survey-page conclusion">
						<?php echo nl2br($survey->conclusion); ?>
					</div>
					<div no="<?php echo $x+1; ?>" class="lcms-survey-page thank_you">
						<?php echo nl2br($survey->thank_you); ?>
					</div>
					
					
			
				</div>
			</div>
												
		</div>
							

												
