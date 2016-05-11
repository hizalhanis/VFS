var no = 0;
var total = 0;

$(document).ready(function(){	
	$('a.lcms-survey-next').click(function(){
		next();
	});
	
	$('a.lcms-survey-prev').click(function(){
		prev();
	});

	
	$('div.lcms-survey-question-slide').each(function(){total++;});
	total += 2;
});

function next(){

	if (no == total - 1){
		$.ajax({
			url: engine_url + 'complete/' + engine_user,
			success: function(){
				console.log('survey complete');
			}
		})		
	}

	if (no != total){
		
		/*	
		if (!validateAnswer(no)){
			alert('Please complete the question before proceeding to the next.');
			return;
		}
		*/

		$('div.lcms-survey-page').hide();
		$('div.lcms-survey-question-slide').hide();
		
		
		var form = $('div[no='+no+']').find('form')
		
		ansSubmitted();
		/*
		if (!form.length){
			ansSubmitted();
		} else {
			form.submit();
		}
		*/
		
		
	}
	
}

function ansSubmitted(){
	no++;
	$('div[no='+no+']').show();
		
	var progress = no / total * 100;
	$('div.progress-bar-highlight').css('width',progress);
	
}

function prev(){
	if (no != 0){
		$('div.lcms-survey-page').hide();
		$('div.lcms-survey-question-slide').hide();

		no--;
		$('div[no='+no+']').show();
		
		var progress = no / total * 100;
		$('div.progress-bar-highlight').css('width',progress);
	}
}

function validateAnswer(no){
	var ans 	= $('div[no='+no+']');
	var type 	= ans.find('input[name=type]').val();
	var ok;
	
	ok = true;
	
	switch (type){
	
		case "single-answer":
			ok = false;
			ans.find('input.survey-radio-btn').each(function(){
				if ($(this).attr('checked')) ok = true;
			});
			
			ans.find('input.ignore').each(function(){
				ok = true;	
			})
		
			break;
			
		case "multiple-answer":
			ok = false;
			ans.find('input.survey-checkbox-btn').each(function(){
				if ($(this).attr('checked')) ok = true;
			});
			
			ans.find('input.ignore').each(function(){
				ok = true;	
			})

			
			break;
			
		case "matrix-choice":
			ok = true;
			ans.find('tr.ans').each(function(){
				if ($(this).find('input:checked').length == 0){
					ok = false;
				}
				if ($(this).find('input').hasClass('ignore')) ok = true;
			});
			break;
		case "matrix-choice-ma":
			ok = true;
			ans.find('tr.ans').each(function(){
				if ($(this).find('input:checked').length == 0){
					ok = false;
				}
				if ($(this).hasClass('ignore')) ok = true;
			});
			break;
		case "matrix-answer":
			ok = true;
			ans.find('tr.ans').each(function(){
				if (!$(this).find('input').val()){
					ok = false;
				}
				if ($(this).find('input').hasClass('ignore')) ok = true;
			});
			break;
			
	}
	
	return ok;
}