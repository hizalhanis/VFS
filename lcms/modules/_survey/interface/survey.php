<script type="text/javascript">

$(document).ready(function(){
	$('a.lcms-survey-settings').live('click',function(e){
		e.preventDefault();
		$(this).parents('li.lcms-editable-object').find('a.lcms-edit-handle').click();
	});
});

</script>

	<?php if ($author_mode): ?>
	<div class="lcms-survey-control">
		<a class="lcms-btn" href="<?php echo $current_page; ?>edit">Edit This Survey</a>
		<a class="lcms-btn" href="<?php echo $current_page; ?>preview/<?php echo $survey->id; ?>" target="_blank">Preview Printable Survey</a>
		<a class="lcms-btn lcms-survey-settings" href="#">Survey Settings</a>
	</div>
	<?php endif; ?>

	<div>
		<div no="0" class="lcms-survey-page lcms-survey-introduction">
			<?php echo nl2br($survey->introduction); ?>
		</div>
		<?php $x = 1; foreach ($questions as $question):  $answers = json_decode($question->answers); ?>

				<?php if ($question->type == 'single-answer'): ?>
					<div no="<?php echo $x; ?>" class="lcms-survey-question-slide">
					<form method="post" action="<?php echo $current_url; ?>submit_answer" target="submit_answer">
						<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
						<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
						<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
						<div class="survey-question">
							<div>
								<h3><span><?php echo $question->no; ?>.</span> <?php echo $question->question; ?></h3>
							</div>
							
						</div>
						<div class="single-answer">
							<?php foreach ($answers as $answer): ?>
								<?php if ($answer->type == 'other'): ?>
									<div><input class="survey-radio-btn" type="radio" name="ans" value="<?php echo $answer->no; ?>" /> <?php echo $answer->value; ?></div>
									<div><input class="survey-answer-other" type="text" name="other" /></div>
								<?php else: ?>
									<div><input type="radio" name="ans" value="<?php echo $answer->no; ?>" /> <?php echo $answer->value; ?></div>
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
				
				<?php if ($question->type == 'multiple-answer'): ?>
					<div no="<?php echo $x; ?>" class="lcms-survey-question-slide">
					<form method="post" action="<?php echo $current_url; ?>submit_answer" target="submit_answer">
						<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
						<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
						<input type="hidden" name="type" value="<?php echo $question->type; ?>" />

						<div class="multiple-answer">
							<div>
								<h3><span><?php echo $question->no; ?>.</span> <?php echo $question->question; ?></h3>
							</div>
	
							<?php foreach ($answers as $answer):?>
								<?php if ($answer->type == 'other'): ?>
									<div><input class="survey-checkbox-btn" type="radio" name="ans[]" value="<?php echo $answer->no; ?>" /> <?php echo $answer->value; ?></div>
									<div><input class="survey-answer-other" type="text" name="other[<?php echo $answer->no; ?>]" /></div>
								<?php else: ?>
									<div><input class="survey-checkbox-btn" type="radio" name="ans[]" value="<?php echo $answer->no; ?>" /> <?php echo $answer->value; ?></div>
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
				
				
				<?php if ($question->type == 'matrix-choice'): $q = json_decode($question->question);?>
					<div no="<?php echo $x; ?>" class="lcms-survey-question-slide">
					<form method="post" action="<?php echo $current_url; ?>submit_answer" target="submit_answer">
						<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
						<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
						<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
	
						<div class="matrix-choice">
							<div>
								<h3><span><?php echo $question->no; ?>.</span> <?php echo $q->description; ?></h3>
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
									<tr <?php if ($j%2) echo 'class="odd"'; ?>>
										<td><?php echo $qn->question; ?></td>
										<?php foreach ($q->labels as $label): ?>
											<td style="width: 100px; text-align: center;"><input type="radio" name="ans[<?php echo $qn->no; ?>]" value="<?php echo $label->value; ?>" /></td>
										<?php endforeach; ?>
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
				
				
				<?php if ($question->type == 'matrix-choice-ma'): $q = json_decode($question->question);?>
					<div no="<?php echo $x; ?>" class="lcms-survey-question-slide">
					<form method="post" action="<?php echo $current_url; ?>submit_answer" target="submit_answer">
						<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
						<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
						<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
	
						<div class="matrix-choice">
							<div>
								<h3><span><?php echo $question->no; ?>.</span> <?php echo $q->description; ?></h3>
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
									<tr <?php if ($j%2) echo 'class="odd"'; ?>>
										<td><?php echo $qn->question; ?></td>
										<?php foreach ($q->labels as $label): ?>
											<td style="width: 100px; text-align: center;"><input type="checkbox" name="ans[<?php echo $qn->no; ?>][]" value="<?php echo $label->value; ?>" /></td>
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
					<div no="<?php echo $x; ?>" class="lcms-survey-question-slide">
					<form method="post" action="<?php echo $current_url; ?>submit_answer" target="submit_answer">
						<input type="hidden" name="id" value="<?php echo $survey->id; ?>" />
						<input type="hidden" name="no" value="<?php echo $question->no; ?>" />
						<input type="hidden" name="type" value="<?php echo $question->type; ?>" />
	
						<div class="matrix-choice">
							<div>
								<h3><span><?php echo $question->no; ?>.</span> <?php echo $q->description; ?></h3>
							</div>
							<table style="width: 100%">
	
								<tbody>
									<?php $j = 0; foreach ($q->questions as $qn): ?>
									<tr <?php if ($j%2) echo 'class="odd"'; ?>>
										<td><?php echo $qn->question; ?></td>
										<td style="text-align: right; width: 40%"><input class="survey-answer" style="width: 93%" type="text" name="ans[<?php echo $qn->no; ?>]" value="" /></td>
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
		
		
		<div class="lcms-survey-question-control">
			<a class="lcms-survey-next">Next</a>
			<a class="lcms-survey-prev">Previous</a>
		</div>

	</div>