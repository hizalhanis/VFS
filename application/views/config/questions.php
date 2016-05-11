<script type="text/javascript">
var logic = '<?php echo $logic; ?>'; 

$(document).ready(function(){
	
	
	$('button.lcms-survey-add-question').click(function(){
		var no = 0;
		
		$.ajax({
			url: 'config/ajax/get_question_form',
			type: 'post',
			data: 'logic='+logic+'&no='+no,
			success: function (page){
				$('div.lcms-survey-edit-box').html(page);
			}
		})
	});
	
	$('a.first-page').click();
	
	$(window).resize(function(){

		$('#lcms-survey-page-content').css('height', '100%');
		$('div.lcms-survey-edit-box').css('height',(contentHeight - 1) + 'px');
	});
	
	$(window).resize();
	
})

function reloadSummaryList(){
	$('div.questions-container').html('<p align="center"><img style="margin-top: 100px" src="images/loader.gif" /></p>');
	$.ajax({
		url: 'config/ajax/summary_list',
		type: 'post',
		data: 'logic='+logic,
		success: function (page){
			$('div.questions-container').html(page);
		}
	})
}

</script>
<script type="text/javascript">


$(document).ready(function(){


	$(document).on('button.single-answer-remove','click',function(){
		$(this).parents('div.single-answer').remove();
	});

	
	$(document).on('button.multiple-answer-remove','click',function(){
		$(this).parents('div.multiple-answer').remove();
	});
	
	
	$(document).on('button.matrix-sa-remove-row','click', function(){
		$(this).parents('tr').remove();
	})
	
	$(document).on('button.matrix-sa-remove-col','click',function(){
		var cur = 0;
		var curCol = 0;
		var curI = $(this).attr('i');
		$(this).parents('tr').find('th').each(function(){
			if ($(this).find('button').attr('i') == curI) curCol = cur;
			cur++;
		});
		

		$('table.matrix-sa thead th').eq(curCol).remove();
		$('table.matrix-sa tbody tr').each(function(){
			$(this).find('td').eq(curCol).remove();
		});
		
		col--;
	});
	
	$(document).on('button.matrix-ma-remove-row','click', function(){
		$(this).parents('tr').remove();
	})
	
	$(document).on('button.matrix-ma-remove-col','click',function(){
		var cur = 0;
		var curCol;
		var curI = $(this).attr('i');
		$(this).parents('tr').find('th').each(function(){
			if ($(this).find('button').attr('i') == curI) curCol = cur;
			cur++;
		});

		$('table.matrix-ma thead th').eq(curCol).remove();
		$('table.matrix-ma tbody tr').each(function(){
			$(this).find('td').eq(curCol).remove();
		});
		
		col_ma--;
	});
	
	
	$(document).on('button.matrix-answer-remove','click', function(){
		$(this).parents('tr').remove();
	});
	
	
	$('ul.lcms-survey-questions').on('li','click', function(){
		$('ul.lcms-survey-questions li').removeClass('current');
		$(this).addClass('current');
	});
	
	reloadSummaryList();

});

</script>


	<?php $this->load->view('config/sidebar'); ?>

	<div id="content">

		<div class="lcms-survey-summary" style="float: left; width: 200px">

			<div style="height: 32px; padding-top: 5px; padding-left: 10px; background: #eee">
				<button class="lcms-btn lcms-survey-add-question">Add Question</button>
			</div>
			<div class="questions-container">
				
			</div>
			
		</div>
		<div class="lcms-survey-edit-box" style="overflow-y: scroll; margin-left: 210px; padding: 0 10px">
			
		</div>

		<div style="clear:both"></div>
	</div>
	
	
	
	