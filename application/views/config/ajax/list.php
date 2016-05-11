<script type="text/javascript">

$(document).ready(function(){


	$('a.survey-questions').click(function(e){
		e.preventDefault();
		
		$('div.lcms-survey-edit-box').html('<p align="center"><img style="margin-top: 100px" src="images/loader.gif" /></p>');
		
		if ($(this).hasClass('page')){
			
			
		} else {
		
			var no = $(this).attr('href');
			
			$.ajax({
				url: 'config/ajax/get_question_form',
				type: 'post',
				data: 'logic='+logic+'&no='+no,
				success: function (page){
					$('div.lcms-survey-edit-box').html(page);
				}
			});
		
		}
	});

	$('button.delete').click(function(){
		var qid = $(this).parent('li').attr('id');
		
		if (confirm('Are you sure you want to delete this question?')){
			$('div.questions-container').html('<p align="center"><img style="margin-top: 100px" src="images/loader.gif" /></p>');			
			
			$.ajax({
				url: 'config/ajax/delete_question',
				type: 'post',
				data: 'logic='+logic+'&qid='+qid,
				success: function (){
					reloadSummaryList();
				}
				
			})
		}
	
	});
	
	$('ul.lcms-survey-questions li').hover(function(){
		$(this).find('button').show();
	},function(){
		$(this).find('button').hide();
	});
	
	$('ul.lcms-survey-questions').sortable({
		update: function(){
			var order = '';
			var x = 0;
			$('ul.lcms-survey-questions li').each(function(){
				if (x == 0){
					order = $(this).attr('id');
				} else {
					order += ',' + $(this).attr('id');
				}
				x++;
			});
			
			var cwidth = $('div.questions-container').width();
			$('div.questions-container').prepend('<div style="background: rgba(255,255,255,0.5); height: inherit; width: '+cwidth+'px; position: absolute;"></div>');
			
			$.ajax({
				url: 'config/ajax/sort_questions',
				type: 'post',
				data: 'logic='+logic+'&order='+order,
				success: function (res){
					$('div.questions-container div').remove();
					
				}
			})
		}
	});
})

</script>

				<ul class="lcms-survey-questions">
				<?php foreach ($questions as $question): ?>
					<?php
						if ($question->type == 'matrix-choice' || $question->type == 'matrix-choice-ma' || $question->type == 'matrix-answer'){
							$qn = json_decode($question->question);
							$qna = truncate($qn->description, 100);
						} else {
							$qna = truncate($question->question, 100);
						}
					?>
					<?php if ($question->type == 'section'): ?>
					<li id="<?php echo $question->id; ?>" class="section">
						<button style="display: none; float: right; height: 100%; margin-top: -3px; margin-right: -3px" class="remove-btn delete">x</button>
						<a href="<?php echo $question->no; ?>" class="survey-questions"><strong>Section:</strong> <?php echo $qna; ?></a>
					</li>
					
					<?php else: ?>
					<li id="<?php echo $question->id; ?>">
						<button style="display: none; float: right; height: 100%; margin-top: -3px; margin-right: -3px" class="remove-btn delete">x</button>
						<a href="<?php echo $question->no; ?>" class="survey-questions"><strong>Question:</strong> <?php echo $qna; ?></a>
					</li>
					<?php endif; ?>
				<?php endforeach; ?>
				</ul>