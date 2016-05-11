<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html>
	<head>
		<title>PlexCMS Administration</title>
		<link rel="stylesheet" href="<?=base_url()?>css/controller.css" />
		<link rel="stylesheet" href="<?=base_url()?>css/login.css" />
	</head>
	<body>
		<div id="lcms-container">
			<p align="center"><img src="<?=base_url()?>images/plexcms.png" alt="plexcms" width="100" height="85" /></p>
			<form class="lcms-form" method="post" action="<?=base_url()?>admin/auth">
				<h3 style="text-align: center">PlexCMS Administration</h3>
				<fieldset>
					<?php if ($err): ?>
					<p style="background: red; padding: 10px; color: white">Wrong username or password. Please make sure you have entered the right username and password.</p>
					<?php endif; ?>
					<label>Username</label>
					<input type="text" name="username" class="lcms-txt lcms-block" />
					<label>Password</label>
					<input type="password" name="password" class="lcms-txt lcms-block" />
					<p><a href="<?=base_url()?>admin/forgot">Forgot password</a></p>
					<hr />
					
					<button class="lcms-btn">Log In</button>
				</fieldset>
			</form>
		</div>
	</body>
</html>
