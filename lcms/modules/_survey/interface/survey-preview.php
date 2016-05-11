<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
	<title><?php echo $survey->name; ?></title>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			var titleDiv = $('div.title').clone();
			$(titleDiv).css('page-break-before', 'always');
			var cumulativeHeight = 0;
			$('div.lcms-survey-preview-slide').each(function(){
				cumulativeHeight += ($(this).height() + 20);
				if (cumulativeHeight > 850){

					$(this).before($(titleDiv).clone());
					// $(this).before('<hr />'+(cumulativeHeight - ($(this).height() + 20))+' - '+($(this).height()+20)+'<hr />');
					cumulativeHeight = $(this).height();
				}
			})
			
			$('input[type=radio]').replaceWith('<div class="dummy-radio"></div>');
			$('input[type=checkbox]').replaceWith('<div class="dummy-checkbox"></div>');
		});
	</script>
	<style>
	
	body {
		margin: 0;
		padding: 0;
		font-family: Arial;
		font-size: 10pt;
		line-height: 150%;
	}
	
	div.dummy-radio, div.dummy-checkbox {
		border: 2px solid #000;
		border-radius: 10px;
		width: 10px;
		height: 10px;
		margin: 0 auto;
	}
	
	div.lcms-survey-preview-slide {
		margin: 10px 0;
		page-break-before: auto;
		padding: 0 5px;
	}
	
	div.single-answer > div{
		padding-left: 25px;
	}
	
	div.single-answer div.dummy-radio, div.single-answer div.dummy-checkbox {
		float: left;
		margin-top: 2px;
		margin-left: -25px;
	}
	
	div.lcms-survey-preview-slide * {
		page-break-after: auto;
	}
	
	table {
		font-size: 10pt;
		border-collapse: collapse;
	}
	
	textarea {
		width: 100%;
		resize: none;
		border: 1px solid #000;
		height: 50px;
		margin-top: 10px;
	}
	
	input[type=text] {
		width: 100%;
		resize: none;
		border: 1px solid #000;		
		padding: 5px;
	}
	
	tr.odd {
		background: #EEE;
	}
	
	table td {
		padding: 5px;
	}
	
	div.title {
		background: #000;
		color: #FFF;
		font-size: 16pt;
		padding: 10px;
	}

	
	</style>

</head>
<body>
	<div class="lcms-survey-page">
		<div class="title"><?php echo $survey->name; ?></div>
		<div no="0" class="lcms-survey-preview-slide">
			<?php echo nl2br($survey->introduction); ?>
		</div>
		<?php $x = 1; foreach ($questions as $question):  $answers = json_decode($question->answers); ?>

				<?php if ($question->type == 'single-answer'): ?>
					<div no="<?php echo $x; ?>" class="lcms-survey-preview-slide">
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
					<div no="<?php echo $x; ?>" class="lcms-survey-preview-slide">
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
					<div no="<?php echo $x; ?>" class="lcms-survey-preview-slide">
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
					<div no="<?php echo $x; ?>" class="lcms-survey-preview-slide">
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
					<div no="<?php echo $x; ?>" class="lcms-survey-preview-slide">
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
		<div no="<?php echo $x; ?>" class="lcms-survey-preview-slide">
			<?php echo nl2br($survey->conclusion); ?>
		</div>
		<div no="<?php echo $x+1; ?>" class="lcms-survey-preview-slide">
			<?php echo nl2br($survey->thank_you); ?>
		</div>
		
		

	</div>
</body>
</html>