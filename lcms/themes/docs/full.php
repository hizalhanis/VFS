<!DOCTYPE html>
<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<head>
		<title><?=$title?></title>
		<?=$headers?>
	</head>
	<body>
	<div id="container">
		<div id="user-info">
			
		</div>
		<div id="header">
			<div id="cart-info">
			
			</div>
			<div class="placeholder">
				<?php content($content, 'header')?>
			</div>
			<div id="nav">
				<?=$navigation?>
			</div>
		</div>

		<div id="page">
			<div class="full">
				<div class="placeholder">
				<?php content($content, 'acc_main')?>
				</div>
			</div>		
			<br class="clearboth">
		</div>
		<div id="footer">
			<?php content($content, 'acc_footer')?>
		</div>
	</div>
	
	</body>
</html>