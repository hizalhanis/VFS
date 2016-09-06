<!DOCTYPE html>
<html>
<head>
	<base href="<?php echo base_url(); ?>" />
	<title>CSMCR Survey - School of CS, UoM</title>
	<script type="text/javascript">
		var base_url = "<?php echo base_url(); ?>";
	</script>

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>
	<script type="text/javascript" src="js/plugins.js"></script>
	<script type="text/javascript" src="js/window.js"></script>	
	<script type="text/javascript" src="js/survey.js"></script>
	<script type="text/javascript" src="js/jquery.dateentry.min.js"></script>
	<script type="text/javascript" src="js/jquery.timeentry.min.js"></script>
	<script type="text/javascript" src="js/jquery.highchart.js"></script>
	<script type="text/javascript" src="js/highchart.js"></script>
	<script type="text/javascript" src="js/TextboxList.js"></script>
	<script type="text/javascript" src="js/TextboxList.Autocomplete.js"></script>
	<script type="text/javascript" src="js/TextboxList.Autocomplete.Binary.js"></script>
	<script type="text/javascript" src="js/GrowingInput.js"></script>

	<link rel="stylesheet" href="css/survey.css" />
	<link rel="stylesheet" href="css/lcms-survey.css" />
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet" href="css/jqueryui.css" />
	<link rel="stylesheet" href="css/textboxlist.css" />
	<link rel="stylesheet" href="css/TextboxList.Autocomplete.css" />
	
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="viewport" content="width=device-width; initial-scale = 1, user-scalable=no" />
	
	<script type="text/javascript">
	
	$(document).ready(function(){
		$('select.data-select').change(function(){
			var val = $(this).val();
			location.href = 'cases/select_year/'+val;
		})
	});
	
	</script>
</head>
<body>

		<div id="header">
                <h1>CSMCR Survey</h1>

</div>

		
		
