<!DOCTYPE html>
<html>
<head>
	<base href="<?php echo base_url(); ?>" />
	<title>CSMCR - Main Menu</title>
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
	<div id="dialog-overlay" style="position: fixed; left: 0; right: 0; bottom: 0; top: 0; background: rgba(0,0,0,0.5); z-index: 3000; text-align: center; display: none">
		<div id="dialog" style="width: 500px; background: #eee; padding: 10px; border-radius: 5px; box-shadow: 0 0 30px rgba(0,0,0,1); margin: 0 auto; margin-top: 200px; text-align: left;">
			<h3 class="title" style="margin: 5px 0; padding: 0"></h3>
			<p class="text" style="color: #555"></p>
			<hr style="border-bottom: 1px solid #fff; border-top: 1px solid #aaa;" />
			<div style="text-align: right">
			<button>OK</button>
			</div>
		</div>
	</div>
	<div id="container">
		<div id="header">
			<p style="float:right">
				Log in as
				<strong><?php echo $this->user->data('username'); ?></strong> <button onclick="location.href='logout'">Logout</button>
			</p>
			<h1>CSMCR Open Day Survey Management System</h1>
		</div>
		<div id="nav">
			<ul>
				<li><a <?php if ($this->uri->segment(1) == 'cases') echo 'class="current"'; ?> href="cases">Survey</a></li>
				<li><a <?php if ($this->uri->segment(1) == 'analytics') echo 'class="current"'; ?> >Analysis</a></li>
				<li><a <?php if ($this->uri->segment(1) == 'users') echo 'class="current"'; ?> href="users">Users</a></li>
				<li><a <?php if ($this->uri->segment(1) == 'config') echo 'class="current"'; ?> href="config">Settings</a></li>
				<li><a <?php if ($this->uri->segment(1) == 'database') echo 'class="current"'; ?> href="database">Database Management</a></li>
			</ul>
		</div>
		<div id="main">
		
		
