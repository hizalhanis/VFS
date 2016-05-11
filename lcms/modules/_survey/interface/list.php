				<ul class="lcms-survey-questions">
					<li><a class="page" href="introduction">Introduction</a></li>
					<li><a class="page" href="conclusion">Conclusion/Result Page</a></li>
					<li><a class="page" href="thank_you">Thank You Message</a></li>

				<?php foreach ($questions as $question): ?>
					<?php
						if ($question->type == 'matrix-choice' || $question->type == 'matrix-choice-ma' || $question->type == 'matrix-answer'){
							$qn = json_decode($question->question);
							$qna = truncate($qn->description, 100);
						} else {
							$qna = truncate($question->question, 100);
						}
					?>
					<li><a href="<?php echo $question->no; ?>"><?php echo $question->no; ?>. <?php echo $qna; ?></a></li>
				<?php endforeach; ?>
				</ul>