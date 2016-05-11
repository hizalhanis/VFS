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
				<p style="background: red; padding: 10px; color: white">The information you supplied is not in our record. Please try again.</p>
					<label>Username</label>
					<input type="text" name="username" class="lcms-txt lcms-block" />
					<label>Email</label>
					<input type="email" name="email" class="lcms-txt lcms-block" />
					<hr />
					
					<button class="lcms-btn">Reset</button>
				</fieldset>
			</form>
		</div>
	</body>
</html>
