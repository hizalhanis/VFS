<html>
<head>
	<base href="<?php echo base_url(); ?>" />
	<title>Welcome to CSMCR</title>
	<link rel="stylesheet" href="css/style.css" />
</head>
<body>
	<div id="login-container" class="middle" style="border: 1px solid #ccc; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.3);">
	    <form method="post" action="login/auth">
	    	<div style="text-align:center; padding: 2px 1px 5px 1px;">
	    		<img src="images/UoM_logo.png" style="width: 70%;" />
	    	</div>
            <div style="text-align:center; padding: 2px 1px 5px 1px;">
                <img src="images/CS.png" style="width: 70%;" />
            </div>
	    	<div style="text-align: center; padding: 5px;">
	    		<?=$error?>
	    	</div>
	    	<label for="username">Username</label><input type="text" class="text" style="width: 100%" name="username" id="username" class="v-txt" />
	    	<label for="password">Password</label><input type="password" class="text" style="width: 100%" name="password" id="password" class="v-txt " />
	    	<br class="v-clear" />
	    	<p style="padding-top: 6px"><button class="v-btn" style="margin-right: 6px">Log in</button></p>
	    </form>
	</div>
</body>
</html>