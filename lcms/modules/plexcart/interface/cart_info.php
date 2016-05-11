
<script type="text/javascript">

var lcms_plexcart_ucc = '<?php echo $options->ucc; ?>';
var lcms_plexcart_ccc = '<?php echo $options->ccc; ?>';

$(document).ready(function(){

	$('div.item-categories').html($('div.lcms-plexcart-categories').html());
	$('div.item-search').html($('div.lcms-plexcart-searchbox-control').html());

	if (typeof lcms_plexcart_ucc == 'undefined' || typeof lcms_plexcart_ccc == 'undefined'){

	} else {

	    if (lcms_plexcart_ucc){

	    	if (lcms_plexcart_ccc){
	    		$(lcms_plexcart_ccc).html($('div.lcms-plexcart-cart-info').html());
	    		$(lcms_plexcart_ucc).html($('div.lcms-plexcart-user-info').html());
	    	} else {
	    		$(lcms_plexcart_ucc).html($('div.lcms-plexcart-cart-info').html() + $('div.lcms-plexcart-user-info').html());		    	
	    	}
	    } else {

	    	if (lcms_plexcart_ccc){
	    		$(lcms_plexcart_ccc).html($('div.lcms-plexcart-cart-info').html() + $('div.lcms-plexcart-user-info').html());
	    	} else {
	    		$('div.lcms-plexcart-data').html($('div.lcms-plexcart-cart-info').html() + $('div.lcms-plexcart-user-info').html());							
	    	}
	
	    }
	}
})


</script>

<div class="lcms-plexcart-info">
	<div class="lcms-plexcart-user-info">
	
		<?php if ($_SESSION['session']): ?>
			<ul>
				<li><a href="<?php echo $current_page; ?>history">My Orders</a></li>
				<li><a href="<?php echo $current_page; ?>account">Account</a></li>
				<li><a href="<?php echo $current_page; ?>logout">Logout</a></li>				
			</ul>

			<p class="lcms-plexcart-user">Logged in as <?php echo $user->contact_person; ?>. Member points <strong><?php echo number_format($user->loyalty_points); ?></strong>.</p>
			<?php if ($warehouse_enabled): ?>
				<?php if ($warehouse->name): ?>
				<p class="lcms-plexcart-store">Current Store <strong><?php echo $warehouse->name; ?></strong>.  <a href="<?php echo $current_page; ?>change_store">Change Store</a></p>
				<?php else: ?>
				<p class="lcms-plexcart-store"><a href="<?php echo $current_page; ?>change_store">Select Online Store</a></p>				
				<?php endif; ?>
			<?php endif; ?>	
		<?php else: ?>
			<ul>
				<li><a href="<?php echo $current_page; ?>register">Register</a></li>
			</ul>
			
			<form method="post" action="<?php echo $current_page; ?>login_do">
				<label for="username"><span>Username</span><input type="text" class="username" name="username" id="username" /></label>
				<label for="password"><span>Password</span><input type="password" class="password" name="password" id="password" /></label>
				<input type="submit" class="lcms-plexcart-login-btn" value="Log in" />
				<small><a href="<?php echo $current_page; ?>forgot_password">Forgot Password?</a></small>
			</form>

			
			<?php if ($fb_enabled): ?>
			| Login with Facebook <span class="fb-login-btn"><fb:login-button onlogin="loginCallback()" perms="email,user_about_me">Log in</fb:login-button></span>
			<?php endif; ?>			
			
			<?php if ($warehouse_enabled): ?>
				<?php if (trim($warehouse->name)): ?>
				<p class="lcms-plexcart-store">Current Store <strong><?php echo $warehouse->name; ?></strong>.  <a href="<?php echo $current_page; ?>change_store">Change Store</a></p>
				<?php else: ?>
				<p class="lcms-plexcart-store"><a href="<?php echo $current_page; ?>change_store">Select Online Store</a></p>				
				<?php endif; ?>
			<?php endif; ?>	
		<?php endif; ?>
	</div>
	<div class="lcms-plexcart-cart-info">
		<a class="lcms-plexcart-my-cart" href="<?php echo $current_page; ?>cart">My Cart</a>
		<span class="lcms-plexcart-cart-items"><?php echo $qty ? $qty : 0; ?> items in cart</span>
		<span class="lcms-plexcart-cart-total"><strong><?php echo $currency; ?><?php echo $total; ?></strong></span>
	</div>
	
	<?php if ($fb_enabled): ?>	
		<div id="fb-root"></div>
		<script>
			
		    FB.init({ 
		    	appId:'<?php echo $fb_app_id; ?>', cookie:true, 
		    	status:true, xfbml:true 
		    });
		</script>
	
		<script>
	
		    function loginCallback(){
		    	
		    	$('span.fb-login-btn').html('<strong>Logging in...</strong>');
				$.ajax({
					url: 'page/ajax/control/plexcart/fb_login',
					type: 'post',
	    			success: function (res){
	    				console.log(res);
	    				window.location.href = window.location.href;
	    			}
	    		});
	     	}
	
		    	    
		</script>		
	<?php endif; ?>

</div>
<div class="lcms-plexcart-categories" style="display:none">
	<ul>
		<?php foreach ($categories as $category): ?>
		<li><a class="cat-<?php echo clean_url($category); ?>" href="<?php echo $current_page;?>category/<?php echo clean_url($category); ?>"><?php echo $category; ?></a></li>
		<?php endforeach; ?>
	</ul>

</div>
<div class="lcms-plexcart-searchbox-control" style="display:none">
	<form method="post" action="<?php echo $current_page; ?>search">
		<input type="text" class="lcms-plexcart-search" name="q" value="<?php echo $search_term; ?>" /> 
		<input type="submit" class="lcms-plexcart-search-btn" value="Search" />
	</form>
</div>
