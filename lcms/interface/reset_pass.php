<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html>
	<head>
		<title>LiveCMS Administration</title>
		<link rel="stylesheet" href="<?=base_url()?>css/controller.css" />
		<link rel="stylesheet" href="<?=base_url()?>css/login.css" />
	</head>
	<body>
		<div id="lcms-container">
			<form class="lcms-form" method="post" action="<?=base_url()?>admin/reset">
				<h3>LiveCMS Administration - Password Reset</h3>
				<fieldset>
				<p>Your password has been reset successfully. Please check your email for your new password. <a href="<?=base_url()?>admin">Log In</a></p>
				<p style="color: grey"><?=$new_pass?></p>
					

				</fieldset>
			</form>
		</div>
	</body>
</html>
