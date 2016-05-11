<?php $user = time(); ?>
<script type="text/javascript">

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
		$('div.lcms-survey-page').hide();
		$('div.lcms-survey-question-slide').hide();

		var no = $(this).attr('rel');
		$('div[no='+no+']').show();
		
	})
	
});

var engine_url = '<?php echo $current_page; ?>';
var engine_user = '<?php echo $user; ?>';

</script>


	<?php $this->load->view('cases/sidebar'); ?>
	
	<div id="content">
		<div class="toolbar">
			<a class="btn-right" href="cases/view/<?php echo $id; ?>">Kembali</a>
			<h3 class="header">Rekod Kes Kemalangan</h3>
		</div>

		<div class="content-scroll">
			<div class="side-nav" style="width: 250px; float: left; position: fixed; height: inherit; background: #555; color: #FFF; overflow-y: auto">
				<?php $y = 1; foreach ($questions as $question): ?>
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
						
					?>
					<a href="#" class="jump-nav" style="color: #FFF; padding: 10px; border-bottom: 1px solid #888; display: block;" rel="<?php echo $y; ?>"><?php echo $the_question; ?></a>
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
						<a class="lcms-survey-next">Next</a>
						<a class="lcms-survey-prev">Previous</a>
					</div>
			
					<div no="0" class="lcms-survey-page lcms-survey-introduction">
						<?php echo nl2br($survey->introduction); ?>
					</div>
					<?php $x = 1; foreach ($questions as $question):  $answers = json_decode($question->answers); ?>
					
							<?php if ($question->type == 'section'): ?>
							<div id="sq<?php echo $x; ?>" no="<?php echo $x; ?>" class="lcms-survey-question-slide survey-section">
								<h1><?php echo $question->question; ?></h1>
								<?php echo nl2br($question->comments_description); ?>
								<?php if ($question->question) $section_title = $question->question; ?>
							</div>
							
							<?php endif; ?>
			
			
							<?php if ($question->type == 'single-answer'): $other = json_decode($question->other); ?>
								<div id="sq<?php echo $x; ?>" no="<?php echo $x; ?>" class="lcms-survey-question-slide">
								<?php if ($section_title): ?><div class="section-title"><?php echo $section_title; ?></div><?php endif; ?>
								<form method="post" action="<?php echo $current_page; ?>submit_answer" target="submit_answer">
									<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
									<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
									<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
									<input type="hidden" name="user" value="<?php echo $user; ?>" />
									<div class="survey-question">
										<div>
											<h3><?php echo $question->question; ?></h3>
										</div>
										
									</div>
									<div class="single-answer">
										<?php if ($question->modifier): ?>
											<?php
											$output = str_replace('<answer','<input class="survey-radio-btn" type="radio" name="ans"', $question->modifier);
											$output = str_replace('></answer>', ' />', $output);
											echo $output;
										?>
													
										<?php else: ?>
											<?php foreach ($answers as $answer): ?>
												<?php if ($answer->type == 'other'): ?>
													<div class="s-a-answer"><input class="survey-radio-btn" type="radio" name="ans" value="<?php echo $answer->no; ?>" /> <?php echo $answer->value; ?></div>
													<div style="padding-left: 20px">Please state: <input class="survey-answer-other" type="text" name="other" style="width: 250px"  /></div>
												<?php else: ?>
													<div class="s-a-answer-other">
														<input class="survey-radio-btn <?php if ($other->state_reason_if_checked) echo 'reason-on-checked'; ?>" type="radio" name="ans" value="<?php echo $answer->no; ?>" /> <?php echo $answer->value; ?>
														<div class="reason-on-checked" style="display:none; padding-left: 20px">Please state: <input class="survey-answer-other" style="width: 250px" type="text" name="other" /></div>
													</div>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</div>
									<?php if ($question->comments): ?>
									<div style="margin-top: 10px"><?php echo $question->comments_description; ?></div>
									<textarea class="survey-comments" name="comments"></textarea>
									<?php endif; ?>
									
								</form>
								</div>
							<?php endif; ?>
							
							
							<?php if ($question->type == 'multiple-answer'): $other = json_decode($question->other); ?>
								<div id="sq<?php echo $x; ?>" no="<?php echo $x; ?>" class="lcms-survey-question-slide">
								<?php if ($section_title): ?><div class="section-title"><?php echo $section_title; ?></div><?php endif; ?>
								<form method="post" action="<?php echo $current_page; ?>submit_answer" target="submit_answer">
									<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
									<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
									<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
									<input type="hidden" name="user" value="<?php echo $user; ?>" />
									<div class="multiple-answer">
										<div>
											<h3><?php echo $question->question; ?></h3>
										</div>
				
										<?php foreach ($answers as $answer):?>
											<?php if ($answer->type == 'other'): ?>
												<div><input class="survey-checkbox-btn" type="checkbox" name="ans[]" value="<?php echo $answer->no; ?>" /> <?php echo $answer->value; ?></div>
												<div style="padding-left: 20px">Please state: <input class="survey-answer-other" type="text" name="other[<?php echo $answer->no; ?>]" style="width: 250px"  /></div>
											<?php else: ?>
												<div>
													<input class="survey-checkbox-btn <?php if ($other->state_reason_if_checked) echo 'reason-on-checked'; ?>" type="checkbox" name="ans[]" value="<?php echo $answer->no; ?>" /> <?php echo $answer->value; ?>
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
								<div id="sq<?php echo $x; ?>" no="<?php echo $x; ?>" class="lcms-survey-question-slide"> 
								<?php if ($section_title): ?><div class="section-title"><?php echo $section_title; ?></div><?php endif; ?>
								<form method="post" action="<?php echo $current_page; ?>submit_answer" target="submit_answer">
									<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
									<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
									<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
									<input type="hidden" name="user" value="<?php echo $user; ?>" />
									<div class="matrix-choice">
										<div>
											<h3><?php echo $q->description; ?></h3>
										</div>
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
								<div id="sq<?php echo $x; ?>" no="<?php echo $x; ?>" class="lcms-survey-question-slide">
								<?php if ($section_title): ?><div class="section-title"><?php echo $section_title; ?></div><?php endif; ?>
								<form method="post" action="<?php echo $current_page; ?>submit_answer" target="submit_answer">
									<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
									<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
									<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
									<input type="hidden" name="user" value="<?php echo $user; ?>" />
									
									<div class="matrix-choice">
										<div>
											<h3><?php echo $q->description; ?></h3>
										</div>
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
								<div id="sq<?php echo $x; ?>" no="<?php echo $x; ?>" class="lcms-survey-question-slide">
								<?php if ($section_title): ?><div class="section-title"><?php echo $section_title; ?></div><?php endif; ?>
								<form method="post" action="<?php echo $current_page; ?>submit_answer" target="submit_answer">
									<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
									<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
									<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
									<input type="hidden" name="user" value="<?php echo $user; ?>" />
									
									<div class="matrix-choice">
										<div>
											<h3><?php echo $q->description; ?></h3>
										</div>
										<table style="width: 100%">
				
											<tbody>
												<?php $j = 0; foreach ($q->questions as $qn): ?>
												<tr class="ans <?php if ($j%2) echo 'odd'; ?>">
													<td><?php echo $qn->question; ?></td>
													<td style="text-align: right; width: 40%"><input class="survey-answer" style="width: 93%" type="text" name="ans[a<?php echo $qn->no; ?>]" value="" /></td>
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
							
							

					<?php $x++; endforeach; ?>
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
							

												
