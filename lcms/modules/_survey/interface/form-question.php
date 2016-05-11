<script type="text/javascript">

function formSubmitted(id, no){
	no = 0;
	$.ajax({
		url: 'page/ajax/control/survey/get_question_form',
		type: 'post',
		data: 'id='+id+'&no='+no,
		success: function (page){
			// $('div.lcms-survey-edit-box').html(page);
		}
	});
	
	reloadSummaryList();
}

$(document).ready(function(){

	// 
	// Single Answer Events
	//
	var sa = $('div.single-answer').first().clone();
	$(sa).append('<button class="remove-btn single-answer-remove">x</button>').find('input').val('');
		
	$('button.single-answer-add').click(function(){
		$('div.single-answer-question div.answers').append($(sa).clone());
	});
	
	$('button.single-answer-add-custom').click(function(){
		var saa = $(sa).clone();
		$(saa).find('input').val('Other').addClass('other');
		$(saa).append('<br />Please state <input class="dummy-text" type="text" />');
		$('div.single-answer-question div.answers').append(saa);
	});
	
	$('button.single-answer-add-comment').click(function(){
		if ($(this).hasClass('on')){
			$('div.single-answer-question div.comments').hide().find('input').val('');
			$(this).text('Add Comments Field').removeClass('on');
		} else {
			$('div.single-answer-question div.comments').show();
			$(this).text('Remove Comments Field').addClass('on');
		}
	});

	
	//
	// Multiple Answer Events
	//
		
	var ma = $('div.multiple-answer').first().clone();
	$(ma).append('<button class="remove-btn multiple-answer-remove">x</button>').find('input').val('');
	
	$('button.multiple-answer-add').click(function(){
		$('div.multiple-answer-question div.answers').append($(ma).clone());
	});
	
	$('button.multiple-answer-add-custom').click(function(){
		var maa = $(ma).clone();
		$(maa).find('input').val('Other').addClass('other');
		$(maa).append('<br />Please state <input class="dummy-text" type="text" />');
		$('div.multiple-answer-question div.answers').append(maa);
	});
	
	$('button.multiple-answer-add-comment').click(function(){
		if ($(this).hasClass('on')){
			$('div.multiple-answer-question div.comments').hide().find('input').val('');
			$(this).text('Add Comments Field').removeClass('on');
		} else {
			$('div.multiple-answer-question div.comments').show();
			$(this).text('Remove Comments Field').addClass('on');
		}
	});
	
	
	
	//
	// Matrix of Choices
	// 
	
	var col = 3;
	var i = 0;
	var mocsa_head_col = $('<th class="matrix-col"><button class="remove-btn matrix-sa-remove-col">x</button><input name="label[]" type="text" /></th>');
	var mocsa_body_col = $('<td class="matrix-col" style="text-align: center"><input type="radio" name="dummy-matrix" /></td>');

	$('button.matrix-sa-add-row').click(function(){
		var row = $('table.matrix-sa tbody tr').first().clone();
		$(row).find('td').first().append('<button class="remove-btn matrix-sa-remove-row">x</button>');
		$(row).find('input').val('');
		
		$('table.matrix-sa tbody').append(row);
	});
	
	$('button.matrix-sa-add-col').click(function(){
		col++;
		i++;
		
		var head_col = $(mocsa_head_col).clone();
		$(head_col).find('button').attr('i',i);
		$('table.matrix-sa thead tr').append(head_col);
		$('table.matrix-sa tbody tr').append($(mocsa_body_col).clone());

	
		
	});
	
	
	
	$('button.matrix-sa-add-comment').click(function(){
		if ($(this).hasClass('on')){
			$('div.matrix-choice-question div.comments').hide().find('input').val('');
			$(this).text('Add Comments Field').removeClass('on');
		} else {
			$('div.matrix-choice-question div.comments').show();
			$(this).text('Remove Comments Field').addClass('on');
		}

	});
	
	
	
	//
	// Matrix of Choices, Multiple Answer
	// 
	
	var col_ma = 3;
	var i_ma = 0;
	var mocma_head_col = $('<th class="matrix-col"><button class="remove-btn matrix-ma-remove-col">x</button><input name="label[]" type="text" /></th>');
	var mocma_body_col = $('<td class="matrix-col" style="text-align: center"><input type="checkbox" name="dummy-matrix" /></td>');

	$('button.matrix-ma-add-row').click(function(){
		var row = $('table.matrix-ma tbody tr').first().clone();
		$(row).find('td').first().append('<button class="remove-btn matrix-ma-remove-row">x</button>');
		$(row).find('input').val('');
		
		$('table.matrix-ma tbody').append(row);
	});
	
	$('button.matrix-ma-add-col').click(function(){
		col_ma++;
		i_ma++;
		
		var head_col = $(mocma_head_col).clone();
		$(head_col).find('button').attr('i',i_ma);
		$('table.matrix-ma thead tr').append(head_col);
		$('table.matrix-ma tbody tr').append($(mocma_body_col).clone());

	
		
	});
	
	
	
	$('button.matrix-ma-add-comment').click(function(){
		if ($(this).hasClass('on')){
			$('div.matrix-choice-ma-question div.comments').hide().find('input').val('');;
			$(this).text('Add Comments Field').removeClass('on');
		} else {
			$('div.matrix-choice-ma-question div.comments').show();
			$(this).text('Remove Comments Field').addClass('on');
		}

	});
	
	
	//
	// Matrix of Input Answers
	// 
	
	var col = 3;
	var i = 0;
	var moa_body_col = $('<tr><td><button class="remove-btn matrix-answer-remove">x</button></td><td><input class="input-full" name="qn[]" type="text" /></td><td><input class="input-full dummy-text" type="text" name="dummy-matrix" /></td></tr>');

	$('button.matrix-answer-add-row').click(function(){
		var row = $(moa_body_col).clone();
		
		$('table.matrix-answer tbody').append(row);
	});
	
	
	
	
	$('button.matrix-answer-add-comment').click(function(){
		if ($(this).hasClass('on')){
			$('div.matrix-answer-question div.comments').hide().find('input').val('');
			$(this).text('Add Comments Field').removeClass('on');
		} else {
			$('div.matrix-answer-question div.comments').show();
			$(this).text('Remove Comments Field').addClass('on');
		}

	});

	//
	// Common Events	
	//
	
	
	
	$('select.type').change(function(){
		var type = $(this).val();
		
		$('div.question-type').hide();
		$('div.'+type+'-question').show();
	});
	
	$('select.type').change();
	
	$('form button').click(function(e){
		e.preventDefault();
	});
	
	$('button.lcms-submit-btn').click(function(e){
		e.preventDefault();
		$(this).parents('form').submit();
	});
	
	$('form.question').submit(function(){
		var type = $('select.type').val();
		$('div.question-type :input').attr('disabled','disabled');
		$('div.'+type+'-question :input').removeAttr('disabled');
		
		$('input.sid').removeAttr('disabled');
		$('select.sid').removeAttr('disabled');
		
		$('input.other').val('*'+$('input.other').val());
		
		$('form').html('<p align="center"><img style="margin-top: 100px" src="images/loader.gif" /></p>');
		
		var data 	= $(this).serialize();
		var url 	= $(this).attr('action');
		
		$.ajax({
			url: url,
			type: 'post',
			data: data,
			success: function (){
				formSubmitted(id, 0);
			}
		})

		alert(data);
		
		return false;
		

		
	});
});

</script>


	<form method="post" action="<?php echo base_url(); ?>page/ajax/control/survey/update_question">
		<p>Question Type: <?php echo form_dropdown('type', array('single-answer'=>'Multiple Choice, Single Answer', 'multiple-answer'=>'Multiple Choice, Multiple Answer', 'matrix-choice'=>'Matrix of Choices', 'matrix-choice-ma'=>'Matrix of Choices, Multiple Answer', 'matrix-answer'=> 'Matrix of Custom Answer'),$type,'class="type sid"'); ?></p>
		
		<input type="hidden" class="sid" name="id" value="<?php echo $id; ?>" />
		<input type="hidden" class="sid" name="no" value="<?php echo $no; ?>" />
		
		<div class="question-type single-answer-question">
			<div class="question-control">
				<button class="lcms-btn single-answer-add">Add Answer</button>
				<button class="lcms-btn single-answer-add-custom">Add Custom Answer</button>
				<button class="lcms-btn single-answer-add-comment">Add Comments Field</button>
			</div>
			<strong>Question</strong>
			<div class="question">
				<input class="question" type="text" name="question" value="<?php echo $question; ?>" />
			</div>
			<strong>Answers</strong>
			<div class="answers">
				<?php $x = 0; foreach ($answers as $answer): ?>
				<div class="single-answer">
					<input type="radio" class="dummy-radio" name="dummy-radio" /> <input class="qnx" type="text" name="answers[]" value="<?php echo $answer->value; ?>" />
					<?php if ($x): ?>
					<button class="remove-btn single-answer-remove">x</button>
					<?php endif; ?>
					<?php if ($answer->type == 'other'): ?>
					<br />Please state <input class="dummy-text" type="text" />
					<?php endif; ?>

				</div>
				<?php $x++; endforeach; ?>
				<?php if (!count($answers)): ?>
				<div class="single-answer">
					<input type="radio" class="dummy-radio" name="dummy-radio" /> <input class="qnx" type="text" name="answers[]" value="<?php echo $answer->value; ?>" />
				</div>
				<?php endif; ?>
			</div>
			<div class="comments" <?php if (!$comments): ?>style="display:none"<?php endif; ?>>
				<strong>Comments Field</strong>
				<div>
					<input class="input-full" type="text" name="comments_description" value="<?php echo $comments_description; ?>" /><br />
					<textarea class="dummy-text" name="comments" style="width: 100%;"></textarea>
				</div>
			</div>
			<br />
		</div>
		
		
		
		<div class="question-type multiple-answer-question">
			<div class="question-control">
				<button class="lcms-btn multiple-answer-add">Add Answer</button>
				<button class="lcms-btn multiple-answer-add-custom">Add Custom Answer</button>		
				<button class="lcms-btn multiple-answer-add-comment">Add Comments Field</button>
			</div>
			<strong>Question</strong>
			<div class="question">
				<input class="question" type="text" name="question" value="<?php echo $question; ?>" />
			</div>
			<strong>Answers</strong>
			<div class="answers">

				<?php $x = 0; foreach ($answers as $answer): ?>
				<div class="multiple-answer">
					<input type="checkbox" class="dummy-checkbox" name="dummy-checkbox" /> <input class="qnx" type="text" name="answers[]" value="<?php echo $answer->value; ?>" />
					<?php if ($x): ?>
					<button class="remove-btn multiple-answer-remove">x</button>
					<?php endif; ?>
					<?php if ($answer->type == 'other'): ?>
					<br />Please state <input class="dummy-text" type="text" />
					<?php endif; ?>

				</div>
				<?php $x++; endforeach; ?>				
				<?php if (!count($answers)): ?>
				<div class="multiple-answer">
					<input type="checkbox" class="dummy-checkbox" name="dummy-checkbox" /> <input class="qnx" type="text" name="answers[]" value="<?php echo $answer->value; ?>" />
				</div>
				<?php endif; ?>
			</div>
			<div class="comments" <?php if (!$comments): ?>style="display:none"<?php endif; ?>>
				<strong>Comments Field</strong>
				<div>
					<input class="input-full" type="text" name="comments_description" value="<?php echo $comments_description; ?>" /><br />
					<textarea class="dummy-text" name="comments" style="width: 100%;"></textarea>
				</div>
			</div>
			<br />
		</div>
		
		
		
		
		<div class="question-type matrix-choice-question">
			<div class="question-control">
				<button class="lcms-btn matrix-sa-add-row">Add Row</button>
				<button class="lcms-btn matrix-sa-add-col">Add Column</button>
				<button class="lcms-btn matrix-sa-add-comment">Add Comments Field</button>
			</div>
			<strong>Question</strong>
			<div class="question">
				<input class="question" type="text" name="question" value="<?php echo $question; ?>" />
			</div>
			<strong>Answers</strong>
			<div class="answers">
				<table class="matrix-sa">
					<thead>
						<tr>
							<th></th>
							<th></th>
							<?php $y = 0; foreach ($labels as $label): ?>
							<th class="matrix-col">
								<?php if ($y >= 2 ): ?>
								<button i="<?php echo 'a' . $y; ?>" class="remove-btn matrix-sa-remove-col">x</button>
								<?php endif; ?>
								<input class="input-full" name="label[]" type="text" value="<?php echo $label->value; ?>" />
							</th>
							<?php $y++; endforeach; ?>
							<?php if (!count($labels)): ?>
							<th class="matrix-col">							
								<input class="input-full" name="label[]" type="text" />
							</th>
							<th class="matrix-col">							
								<input class="input-full" name="label[]" type="text" />
							</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php $x = 0; foreach ($answers as $answer): ?>
						<tr>
							<td class="remove-btn">
								<?php if ($x >= 1): ?>
								<button class="remove-btn matrix-sa-remove-row">x</button>
								<?php endif; ?>
							</td>
							<td class="matrix-qn"><input class="input-full" name="qn[]" type="text" value="<?php echo $answer->value; ?>" /></td>
							<?php foreach ($labels as $label): ?>
							<td class="matrix-col" style="text-align: center"><input type="radio" name="dummy-matrix" /></td>
							<?php endforeach; ?>
							<?php if (!count($labels)): ?>
							<td class="matrix-col" style="text-align: center"><input type="radio" name="dummy-matrix" /></td>
							<td class="matrix-col" style="text-align: center"><input type="radio" name="dummy-matrix" /></td>							
							<?php endif; ?>
						</tr>
						<?php $x++; endforeach; ?>
						<?php if (!count($answers)): ?>
						<tr>
							<td></td>
							<td class="matrix-qn"><input class="input-full" name="qn[]" type="text" value="<?php echo $answer->value; ?>" /></td>
							<?php foreach ($labels as $label): ?>
							<td class="matrix-col" style="text-align: center; width: 15%"><input type="checkbox" name="dummy-matrix" /></td>
							<?php endforeach; ?>
							<?php if (!count($labels)): ?>
							<td class="matrix-col" style="text-align: center; width: 15%"><input type="checkbox" name="dummy-matrix" /></td>
							<td class="matrix-col" style="text-align: center; width: 15%"><input type="checkbox" name="dummy-matrix" /></td>
							<?php endif; ?>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div class="comments" <?php if (!$comments): ?>style="display:none"<?php endif; ?>>
				<strong>Comments Field</strong>
				<div>
					<input class="input-full" type="text" name="comments_description" value="<?php echo $comments_description; ?>" /><br />
					<textarea class="dummy-text" name="comments" style="width: 100%;"></textarea>
				</div>
			</div>
			<br />
		</div>
		
		
		
		<div class="question-type matrix-choice-ma-question">
			<div class="question-control">
				<button class="lcms-btn matrix-ma-add-row">Add Row</button>
				<button class="lcms-btn matrix-ma-add-col">Add Column</button>
				<button class="lcms-btn matrix-ma-add-comment">Add Comments Field</button>
			</div>
			<strong>Question</strong>
			<div class="question">
				<input class="question" type="text" name="question" value="<?php echo $question; ?>" />
			</div>
			<strong>Answers</strong>
			<div class="answers">
				<table class="matrix-ma">
					<thead>
						<tr>
							<th></th>
							<th></th>
							<?php $y = 0; foreach ($labels as $label): ?>
							<th class="matrix-col">
								<?php if ($y >= 2 ): ?>
								<button i="<?php echo 'b' . $y; ?>" class="remove-btn matrix-ma-remove-col">x</button>
								<?php endif; ?>
								<input class="input-full" name="label[]" type="text" value="<?php echo $label->value; ?>" style="width: 100%" />
							</th>
							<?php $y++; endforeach; ?>
							<?php if (!count($labels)): ?>
							<th class="matrix-col">							
								<input class="input-full" name="label[]" type="text" />
							</th>
							<th class="matrix-col">
								<input class="input-full" name="label[]" type="text" />
							</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php $x = 0; foreach ($answers as $answer): ?>
						<tr>
							<td class="remove-btn">
								<?php if ($x >= 1): ?>
								<button class="remove-btn matrix-ma-remove-row">x</button>
								<?php endif; ?>
							</td>
							<td class="matrix-qn"><input class="input-full" name="qn[]" type="text" value="<?php echo $answer->value; ?>" /></td>
							<?php foreach ($labels as $label): ?>
							<td class="matrix-col" style="text-align: center; width: 15%"><input type="checkbox" name="dummy-matrix" /></td>
							<?php endforeach; ?>
							<?php if (!count($labels)): ?>
							<td class="matrix-col" style="text-align: center; width: 15%"><input type="checkbox" name="dummy-matrix" /></td>
							<td class="matrix-col" style="text-align: center; width: 15%"><input type="checkbox" name="dummy-matrix" /></td>
							<?php endif; ?>
						</tr>
						<?php $x++; endforeach; ?>
						<?php if (!count($answers)): ?>
						<tr>
							<td class="remove-btn"></td>
							<td class="matrix-qn"><input class="input-full"  name="qn[]" type="text" value="<?php echo $answer->value; ?>" /></td>
							<?php foreach ($labels as $label): ?>
							<td class="matrix-col" style="text-align: center; width: 15%"><input type="checkbox" name="dummy-matrix" /></td>
							<?php endforeach; ?>
							<?php if (!count($labels)): ?>
							<td class="matrix-col" style="text-align: center; width: 15%"><input type="checkbox" name="dummy-matrix" /></td>
							<td class="matrix-col" style="text-align: center; width: 15%"><input type="checkbox" name="dummy-matrix" /></td>
							<?php endif; ?>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div class="comments" <?php if (!$comments): ?>style="display:none"<?php endif; ?>>
				<strong>Comments Field</strong>
				<div>
					<input class="input-full" type="text" name="comments_description" value="<?php echo $comments_description; ?>" /><br />
					<textarea class="dummy-text" name="comments" style="width: 100%;"></textarea>
				</div>
			</div>
			<br />
		</div>
		
		
		
		<div class="question-type matrix-answer-question">
			<div class="question-control">
				<button class="lcms-btn matrix-answer-add-row">Add Row</button>
				<button class="lcms-btn matrix-answer-add-comment">Add Comments Field</button>
			</div>
			<strong>Question</strong>
			<div class="question">
				<input class="question" type="text" name="question" value="<?php echo $question; ?>" />
			</div>
			<strong>Answers</strong>
			<div class="answers">
				<table class="matrix-answer">
					<tbody>
						<?php $x = 0; foreach ($answers as $answer): ?>
						<tr>
							<td class="remove-btn">
								<?php if ($x >= 1): ?>
								<button class="remove-btn matrix-answer-remove">x</button>
								<?php endif; ?>
							</td>
							<td class="matrix-qn"><input class="qn" type="text" name="qn[]" value="<?php echo $answer->value; ?>" /></td>
							<td style="text-align: center; width: 200px;"><input class="dummy-text" type="text" name="dummy-matrix" /></td>
						</tr>
						<?php $x++; endforeach; ?>
						<?php if (!count($answers)): ?>
						<tr>
							<td class="remove-btn"></td>
							<td class="matrix-qn"><input class="qn" type="text" name="qn[]" value="<?php echo $answer->value; ?>" /></td>
							<td style="text-align: center; width: 200px;"><input class="dummy-text" type="text" name="dummy-matrix" /></td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div class="comments" <?php if (!$comments): ?>style="display:none"<?php endif; ?>>
				<strong>Comments Field</strong>
				<div>
					<input class="input-full comments_description" type="text" name="comments_description" value="<?php echo $comments_description; ?>" /><br />
					<textarea class="dummy-text comments" name="comments" style="width: 100%;"></textarea>
				</div>
			</div>
			<br />
		</div>
		
		
		<hr />
		<button class="lcms-btn lcms-submit-btn">Save Question</button>
	</form>
	
	<iframe name="update-frame" id="update-frame" style="height: 1px; visibility: hidden"></iframe>