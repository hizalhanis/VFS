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
	total += 3;
});

function next(){
	if (no != total-1){
		$('div.lcms-survey-page').hide();
		$('div.lcms-survey-question-slide').hide();

		no++;
		$('div[no='+no+']').show();
	}
}

function prev(){
	if (no != 0){
		$('div.lcms-survey-page').hide();
		$('div.lcms-survey-question-slide').hide();

		no--;
		$('div[no='+no+']').show();
	}
}