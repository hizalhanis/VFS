	<div class="lcms-plexcart-breadcrumb">
		<h3><a href="<?php echo $current_page; ?>">Products</a> &raquo; My Cart</h3>
	</div>

	
	<form class="lcms-plexcart" method="post" action="<?php echo $current_page; ?>update_cart">
		<table class="lcms-plexcart-checkout">
			<thead>
				<tr>
					<th></th>
					<th>No</th>
					<th>Item</th>
					<th>Unit Price</th>
					<th>Qty</th>
					<th>Subtotal</th>
					</tr>
				</thead>
			<tbody>
			<?php if (!$cart_items || !count($cart_items)): ?>
				<tr>
					<td colspan="6"><i>There are currently no items in the cart</i></td>
				</tr>
			<?php endif; ?>
			<?php foreach ($cart_items as $id => $item): ?>
			<?php
					$subtotal = $item['qty'] * $item['price'];
					$total_qty += $item['qty'];
					$total += $subtotal;
					
					$subtotal_number = number_format($subtotal,2);
					$x++;
			?>
					<tr>
						<td class="action"><button rel="<?php echo $item['id']; ?>" class="lcms-plexcart-removeitem">Remove</td>
						<td class="no"><?php echo $x; ?></td>
						<td class="name"><?php echo $item['name']; ?></td>
						<td class="price"><?php echo $item['price']; ?></td>
						<td class="qty">
							<?php echo form_dropdown('item['.$id.']',number_range($item['min'] ? $item['min'] : 1, $item['max'] ? $item['max'] : 3),$item['qty'],'class="lcms-plexcart-item-qty"'); ?>
						</td>
						<td class="subtotal"><?php echo $subtotal_number; ?></td>
					</tr>
			<?php endforeach; ?>
				<tr>
					<td colspan="5">ITEMS TOTAL</td>
					<td class="grand-total"><?php echo number_format($total,2); ?></td>
				</tr>
				
			</tbody>
		</table>
		<br />
		<?php if ($cart_items && count($cart_items)): ?>
		<input type="submit" class="lcms-btn" value="Update Cart" />
		<?php endif; ?>
		
	</form>
	
		<?php if ($cart_items && count($cart_items)): ?>
			<h1 class="checkout">Checkout</h1>
			<?php if (!$_SESSION['session']): ?>
			<table class="form-grid" style="width: 100%">
				<tr>
					<td style="width: 50%; border-right: 1px solid #DDD; padding: 0 20px 0 0; vertical-align: top">
						<h3>Login</h3>
						<form method="post" action="<?php echo $current_page ?>login_do/cart">
							<table class="form-grid">
								<tr>
									<td class="label">Email</td>
									<td><input class="text mandatory" type="text" value="" name="username" /></td>
								</tr>
								<tr>
									<td class="label">Password</td>
									<td><input class="text mandatory" type="password" value="" name="password" /></td>						
								</tr>
							</table>
						
							<p class="align-right"><input type="submit" class="lcms-btn submit" value="Login" /></p>
						</form>
					</td>
					<td style="width: 50%; padding: 0 0 0 20px; vertical-align: top">
						<h3>Register</h3>
						<p>Don&#39;t have an account yet? <a href="<?php echo $current_page; ?>register">Sign up</a> now for free.</p>
						
					</td>
				</tr>
			</table>
			<?php else: ?>
		
		<form method="post" action="<?php echo $current_page; ?>checkout">
		
		<div class="lcms-plexcart-delivery-details">
			<h3>Delivery Details</h3>
			<p>Enter your delivery details here.</p>
			
			<table class="form-grid lcms-plexcart-ci">
			    <tr>
			    	<td class="label">Name <span class="red">*</span></td>
			    	<td class="input"><input value="<?php echo $_SESSION['delivery']['name'] ? $_SESSION['delivery']['name'] : ($user->contact_person ? $user->contact_person : ''); ?>" class="text name mandatory" type="text" value="" name="delivery[name]" /></td>
			    	<td class="label">Mobile Number <span class="red">*</span></td>
			    	<td class="input"><input value="<?php echo $_SESSION['delivery']['mobile'] ? $_SESSION['delivery']['mobile'] : ($user->contact_number ? $user->contact_number : ''); ?>" class="text mobile mandatory" type="text mandatory" name="delivery[mobile]" value=""/></td>
			    </tr>
	
			    <tr>
			    	<td class="label">Street Address 1 <span class="red">*</span></td>
			    	<td class="input"><input value="<?php echo $_SESSION['delivery']['address1'] ? $_SESSION['delivery']['address1'] : ($user->address1 ? $user->address1 : ''); ?>" class="text address1 mandatory" type="text mandatory" value=""name="delivery[address1]" /></td>
			    	<td class="label">Street Address 2</td>
			    	<td class="input"><input value="<?php echo $_SESSION['delivery']['address2'] ? $_SESSION['delivery']['address2'] : ($user->address2 ? $user->address2 : ''); ?>" class="text address2" type="text" value=""name="delivery[address2]" /></td>
			    </tr>
			    <tr>
			    	<td class="label">City <span class="red">*</span></td>
			    	<td class="input"><input value="<?php echo $_SESSION['delivery']['city'] ? $_SESSION['delivery']['city'] : ($user->city ? $user->city : ''); ?>" class="text city mandatory" type="text mandatory" name="delivery[city]" value=""/></td>
			    	<td class="label">State <span class="red">*</span></td>
			    	<td class="input"><input value="<?php echo $_SESSION['delivery']['state'] ? $_SESSION['delivery']['state'] : ($user->state ? $user->state : ''); ?>" class="text city mandatory" type="text mandatory" name="delivery[state]" value=""/></td>
	
			    </tr>
			    <tr>
			    	<td class="label">Postal Code <span class="red">*</span></td>
			    	<td class="input"><input value="<?php echo $_SESSION['delivery']['zipcode'] ? $_SESSION['delivery']['zipcode'] : ($user->zipcode ? $user->zipcode : ''); ?>" class="text postcode mandatory" type="text" name="delivery[zipcode]" value=""/></td>
			    	<td class="label">Country <span class="red">*</span></td>
			    	<td class="input"><select value="<?php echo $_SESSION['delivery']['country']; ?>" name="delivery[country]" class="mandatory"><option value="Malaysia">Malaysia</option></select></td>
			    </tr>
	
			</table>
		</div>
		
		<div class="lcms-plexcart-payment-method">
			<h3>Payment Method</h3>
			<p>Choose your payment method.</p>
			<table class="form-grid lcms-plexcart-select">
				<?php foreach ($pgs as $pg): ?>
				<tr>
				    <td class="input"><input type="radio" checked="checked" name="gateway" value="<?php echo $pg->id; ?>" /> </td>
				    <td class="label"><strong><?php echo $pg->name; ?></strong><br /><?php echo $pg->description; ?></td> 
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
		
		<div class="lcms-plexcart-shipping">
			<h3>Shipping &amp; Delivery</h3>
			<p>Choose your delivery service.</p>
			<table class="form-grid lcms-plexcart-select">
				<?php foreach ($shippings as $shipping): ?>
				<tr>
				    <td class="input"><input type="radio" checked="checked" name="shipping" value="<?php echo $shipping->id; ?>" /> </td>
				    <td class="label"><strong><?php echo $shipping->name; ?></strong><br /><?php echo $shipping->description; ?></td> 
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
		<?php if (($total+$delivery->total_rate) >= $online_rule->min_purchase):?>
		<input type="submit" class="lcms-plexcart-checkout-btn" value="Checkout" />
	    <?php else: ?>
	    <p class="warning">Minimum Grand Total for Online Purchase is: <?php echo $online_rule->currency; echo $online_rule->min_purchase; ?>, please add more items to Cart before you can proceed to Checkout!</p>
	    <?php endif;?>
		
		
		<?php endif; ?>

		</form>
		<?php endif; ?>