<?php


$style = 'background: none !important; border-radius: 0; border-bottom: 1px solid #eee; padding: 0px 10px 10px 10px'


?>
<script type="text/javascript">

	var aArrayCount = 0;
	var aArray = new Array();
	var bArrayCount = 0;
	var bArray = new Array();
$(document).ready(function(){

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
	
	$('input[type=text]').change(function(){
		doOHVC();
		$(this).parents('form').submit();
	});
	
	
	$('div.lcms-survey-question-slide').show();
	
	doOHVC();
});


function ansSubmitted(no){
	$('a.jump-nav[rel='+no+']').addClass('answer-filled');
}

function doOHVC(){
	var a_car1 = parseInt($('.a_car1').val(),10) || 0;
	var a_car2 = parseInt($('.a_car2').val(),10) || 0;
	var a_car3 = parseInt($('.a_car3').val(),10) || 0;
	var a_car4 = parseInt($('.a_car4').val(),10) || 0;
	
	var a_motorcycle1 = parseInt($('.a_motorcycle1').val(),10) || 0;
	var a_motorcycle2 = parseInt($('.a_motorcycle2').val(),10) || 0;
	var a_motorcycle3 = parseInt($('.a_motorcycle3').val(),10) || 0;
	var a_motorcycle4 = parseInt($('.a_motorcycle4').val(),10) || 0;
	
	var a_heavy1 = parseInt($('.a_heavy1').val(),10) || 0;
	var a_heavy2 = parseInt($('.a_heavy2').val(),10) || 0;
	var a_heavy3 = parseInt($('.a_heavy3').val(),10) || 0;
	var a_heavy4 = parseInt($('.a_heavy4').val(),10) || 0;
	
	var a_bus1 = parseInt($('.a_bus1').val(),10) || 0;
	var a_bus2 = parseInt($('.a_bus2').val(),10) || 0;
	var a_bus3 = parseInt($('.a_bus3').val(),10) || 0;
	var a_bus4 = parseInt($('.a_bus4').val(),10) || 0;
	
	var a_bicycle1 = parseInt($('.a_bicycle1').val(),10) || 0;
	var a_bicycle2 = parseInt($('.a_bicycle2').val(),10) || 0;
	var a_bicycle3 = parseInt($('.a_bicycle3').val(),10) || 0;
	var a_bicycle4 = parseInt($('.a_bicycle4').val(),10) || 0;
	
	var a_pedestrian1 = parseInt($('.a_pedestrian1').val(),10) || 0;
	var a_pedestrian2 = parseInt($('.a_pedestrian2').val(),10) || 0;
	var a_pedestrian3 = parseInt($('.a_pedestrian3').val(),10) || 0;
	var a_pedestrian4 = parseInt($('.a_pedestrian4').val(),10) || 0;
	
	$('.a_total_car').val(a_car1 + a_car2 + a_car3 + a_car4);
	$('.a_total_motorcycle').val(a_motorcycle1 + a_motorcycle2 + a_motorcycle3 + a_motorcycle4)
	$('.a_total_heavy').val(a_heavy1 + a_heavy2 + a_heavy3 + a_heavy4)
	$('.a_total_bus').val(a_bus1 + a_bus2 + a_bus3 + a_bus4)
	$('.a_total_bicycle').val(a_bicycle1 + a_bicycle2 + a_bicycle3 + a_bicycle4)
	$('.a_total_pedestrian').val(a_pedestrian1 + a_pedestrian2 + a_pedestrian3 + a_pedestrian4)
	
	var a_car_total = parseInt($('.a_total_car').val(),10) || 0;
	var a_motorcycle_total = parseInt($('.a_total_motorcycle').val(),10) || 0;
	var a_heavy_total = parseInt($('.a_total_heavy').val(),10) || 0;
	var a_bus_total = parseInt($('.a_total_bus').val(),10) || 0;
	var a_bicycle_total = parseInt($('.a_total_bicycle').val(),10) || 0;
	var a_pedestrian_total = parseInt($('.a_total_pedestrian').val(),10) || 0;
	
	
	var b_car1 = parseInt($('.b_car1').val(),10) || 0;
	var b_car2 = parseInt($('.b_car2').val(),10) || 0;
	var b_car3 = parseInt($('.b_car3').val(),10) || 0;
	var b_car4 = parseInt($('.b_car4').val(),10) || 0;
	
	var b_motorcycle1 = parseInt($('.b_motorcycle1').val(),10) || 0;
	var b_motorcycle2 = parseInt($('.b_motorcycle2').val(),10) || 0;
	var b_motorcycle3 = parseInt($('.b_motorcycle3').val(),10) || 0;
	var b_motorcycle4 = parseInt($('.b_motorcycle4').val(),10) || 0;
	
	var b_heavy1 = parseInt($('.b_heavy1').val(),10) || 0;
	var b_heavy2 = parseInt($('.b_heavy2').val(),10) || 0;
	var b_heavy3 = parseInt($('.b_heavy3').val(),10) || 0;
	var b_heavy4 = parseInt($('.b_heavy4').val(),10) || 0;
	
	var b_bus1 = parseInt($('.b_bus1').val(),10) || 0;
	var b_bus2 = parseInt($('.b_bus2').val(),10) || 0;
	var b_bus3 = parseInt($('.b_bus3').val(),10) || 0;
	var b_bus4 = parseInt($('.b_bus4').val(),10) || 0;
	
	var b_bicycle1 = parseInt($('.b_bicycle1').val(),10) || 0;
	var b_bicycle2 = parseInt($('.b_bicycle2').val(),10) || 0;
	var b_bicycle3 = parseInt($('.b_bicycle3').val(),10) || 0;
	var b_bicycle4 = parseInt($('.b_bicycle4').val(),10) || 0;
	
	var b_pedestrian1 = parseInt($('.b_pedestrian1').val(),10) || 0;
	var b_pedestrian2 = parseInt($('.b_pedestrian2').val(),10) || 0;
	var b_pedestrian3 = parseInt($('.b_pedestrian3').val(),10) || 0;
	var b_pedestrian4 = parseInt($('.b_pedestrian4').val(),10) || 0;
	
	$('.b_total_car').val(b_car1 + b_car2 + b_car3 + b_car4)
	$('.b_total_motorcycle').val(b_motorcycle1 + b_motorcycle2 + b_motorcycle3 + b_motorcycle4)
	$('.b_total_heavy').val(b_heavy1 + b_heavy2 + b_heavy3 + b_heavy4)
	$('.b_total_bus').val(b_bus1 + b_bus2 + b_bus3 + b_bus4)
	$('.b_total_bicycle').val(b_bicycle1 + b_bicycle2 + b_bicycle3 + b_bicycle4)
	$('.b_total_pedestrian').val(b_pedestrian1 + b_pedestrian2 + b_pedestrian3 + b_pedestrian4)
	
	var b_car_total = parseInt($('.b_total_car').val(),10) || 0;
	var b_motorcycle_total = parseInt($('.b_total_motorcycle').val(),10) || 0;
	var b_heavy_total = parseInt($('.b_total_heavy').val(),10) || 0;
	var b_bus_total = parseInt($('.b_total_bus').val(),10) || 0;
	var b_bicycle_total = parseInt($('.b_total_bicycle').val(),10) || 0;
	var b_pedestrian_total = parseInt($('.b_total_pedestrian').val(),10) || 0;
	
	$('.a_grandtotal').val(a_car_total + a_motorcycle_total + a_heavy_total + a_bus_total + a_bicycle_total + a_pedestrian_total);
	$('.b_grandtotal').val(b_car_total + b_motorcycle_total + b_heavy_total + b_bus_total + b_bicycle_total + b_pedestrian_total);
}

var engine_url = 'cases/ajax/';
var engine_user = '<?php echo $user; ?>';

</script>




	<?php $this->load->view('cases/sidebar'); ?>
	
	<div id="content">
		<div class="toolbar">
			<a class="btn-right btn" style="padding: 0 5px;" href="cases/view/<?php echo $id; ?>">Kembali</a>
			<h3 class="header">Rekod Kes Kemalangan</h3>
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
													<td style="text-align: right; width: 40%"><input class="survey-answer date" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>									
										<?php endif; ?>
										<?php if ($question->data_type == 'time'): ?>
													<td style="text-align: right; width: 40%"><input class="survey-answer time" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>									
										<?php endif; ?>
										<?php if ($question->data_type == ''): ?>
											<?php if ($question->map_to == 'ah_ohvc_a_total_car'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_total_car" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>									
											<?php elseif ($question->map_to == 'ah_ohvc_a_total_motorcycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_total_motorcycle" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_total_bus'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_total_bus" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_total_heavy'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_total_heavy" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_total_bicycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_total_bicycle" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_total_pedestrian'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_total_pedestrian" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_total_car'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_total_car" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_total_motorcycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_total_motorcycle" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_total_bus'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_total_bus" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_total_heavy'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_total_heavy" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_total_pedestrian'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_total_pedestrian" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_total_bicycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_total_bicycle" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_st15_car'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_car1" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_nd15_car'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_car2" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_rd15_car'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_car3" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_th15_car'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_car4" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_st15_motorcycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_motorcycle1" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_nd15_motorcycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_motorcycle2" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_rd15_motorcycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_motorcycle3" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_th15_motorcycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_motorcycle4" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_st15_heavy'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_heavy1" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_nd15_heavy'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_heavy2" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_rd15_heavy'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_heavy3" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_th15_heavy'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_heavy4" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_st15_bus'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_bus1" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_nd15_bus'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_bus2" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_rd15_bus'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_bus3" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_th15_bus'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_bus4" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_st15_bicycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_bicycle1" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_nd15_bicycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_bicycle2" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_rd15_bicycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_bicycle3" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_th15_bicycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_bicycle4" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_st15_pedestrian'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_pedestrian1" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_nd15_pedestrian'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_pedestrian2" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_rd15_pedestrian'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_pedestrian3" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_th15_pedestrian'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_pedestrian4" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_st15_car'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_car1" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_nd15_car'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_car2" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_rd15_car'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_car3" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_th15_car'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_car4" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_st15_motorcycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_motorcycle1" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_nd15_motorcycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_motorcycle2" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_rd15_motorcycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_motorcycle3" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_th15_motorcycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_motorcycle4" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_st15_heavy'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_heavy1" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_nd15_heavy'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_heavy2" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_rd15_heavy'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_heavy3" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_th15_heavy'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_heavy4" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_st15_bus'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_bus1" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_nd15_bus'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_bus2" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_rd15_bus'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_bus3" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_th15_bus'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_bus4" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_st15_bicycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_bicycle1" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_nd15_bicycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_bicycle2" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_rd15_bicycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_bicycle3" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_th15_bicycle'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_bicycle4" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_st15_pedestrian'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_pedestrian1" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_nd15_pedestrian'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_pedestrian2" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_rd15_pedestrian'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_pedestrian3" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_th15_pedestrian'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_pedestrian4" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_a_grand_total'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer a_grandtotal" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif ($question->map_to == 'ah_ohvc_b_grand_total'): ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer b_grandtotal" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif (strpos($question->map_to, 'sp_a') !== false): ?>
												<script type="text/javascript">
													$(document).ready(function(){
													aArray[aArrayCount] = <?php echo $info->{$question->map_to}; ?>;
													aArrayCount++;
													aArray.sort(function(a, b){return a-b});
													});
												</script>
													<td style="text-align: right; width: 40%"><input class="survey-answer sp_a" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php elseif (strpos($question->map_to, 'sp_b') !== false): ?>
												<script type="text/javascript">
													$(document).ready(function(){
													bArray[bArrayCount] = <?php echo $info->{$question->map_to}; ?>;
													bArrayCount++;
													aArray.sort(function(a, b){return a-b});
													});
												</script>
													<td style="text-align: right; width: 40%"><input class="survey-answer sp_b" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>	
											<?php else: ?>
														<td style="text-align: right; width: 40%"><input class="survey-answer" style="width: 93%" type="text" name="ans[<?php echo $j; ?>]" value="<?php echo $info->{$question->map_to}; ?>" /></td>									
											<?php endif; ?>
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
	</div>
							

												
