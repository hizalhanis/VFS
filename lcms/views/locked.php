<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html>
	<head>
		<title>LiveCMS Administration</title>
		<link rel="stylesheet" href="<?=base_url()?>css/controller.css" />
		<link rel="stylesheet" href="<?=base_url()?>css/login.css" />
	</head>
	<body>
		<div id="lcms-container">
			<form class="lcms-form" method="post" action="<?=base_url()?>admin/auth">
				<h3>LiveCMS Administration - Locked</h3>
				<fieldset>
					<p>You have attempted to log in 3 times. Your session has been locked temporarily.</p>
				</fieldset>
			</form>
		</div>
	</body>
</html>
