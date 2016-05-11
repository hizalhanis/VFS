	<div class="lcms-plexcart-breadcrumb">
		<h3>
			<a href="<?php echo $current_page; ?>">Products</a> 
			&raquo; <a href="<?php echo $current_page; ?>cart">My Cart</a>
			&raquo; Checkout Confirmation
		</h3>
	</div>
	<form method="post" action="<?php echo $current_page; ?>checkout_do">
	    <h3>Your Orders</h3>
	    
	    <?php if ($warehouse): ?>
	    <p class="lcms-plexcart-warehouse-address">
			<strong>Store Address:</strong><br />
			<?php echo $warehouse->name; ?> <br />
			<?php echo $warehouse->address1; ?> <br />
			<?php echo $warehouse->address2; ?> <br />
			<?php echo $warehouse->zipcode; echo " ";echo $warehouse->city; ?> <br />												
			<?php echo $warehouse->state; echo ","; echo $warehouse->country?> <br />						
		</p>
		<?php endif; ?>
	    
	    <p class="lcms-plexcart-bill-to">
	    	<strong>Bill To:</strong><br />
	    	<?php echo $user->contact_person; ?><br />
	    	Mobile: <?php echo $user->contact_number; ?><br />
	    	<?php echo $user->address1; ?>
	    	<?php echo $user->address2; ?><br />
	    	<?php echo $user->zipcode; ?> <?php echo $user->city; ?><br />
	    	<?php echo $user->country; ?>					
	    </p>
	    <p class="lcms-plexcart-deliver-to">
	    	<strong>Deliver To:</strong><br />
	    	<?php echo $_SESSION['delivery']['name']; ?><br />
	    	Mobile: <?php echo $_SESSION['delivery']['mobile']; ?><br />
	    	<?php echo $_SESSION['delivery']['address1']; ?>
	    	<?php echo $_SESSION['delivery']['address2']; ?><br />
	    	<?php echo $_SESSION['delivery']['zipcode']; ?> <?php echo $_SESSION['delivery']['city']; ?><br />
	    	<?php echo $_SESSION['delivery']['country']; ?>
	    </p>
	    
	    <table class="lcms-plexcart-checkout">
	    	<thead>
	    	    <tr>
	    	    	<th>No</th>
	    	    	<th>Item</th>
	    	    	<th>Unit Price</th>
	    	    	<th>Qty</th>
	    	    	<th>Subtotal</th>
	    	    	</tr>
	    	    </thead>
	    	<tbody>
	    	<?php foreach ($items as $id => $item): ?>
	    	<?php
	    	    	$subtotal = $item['qty'] * $item['price'];
	    	    	$total_qty += $item['qty'];
	    	    	$total += $subtotal;
	    	    	
	    	    	$x++;
	    	?>
	    	    	<tr>
	    	    		<td class="no"><?php echo $x; ?></td>
	    	    		<td class="name"><?php echo $item['name']; ?></td>
	    	    		<td class="price"><?php echo $item['price']; ?></td>
	    	    		<td class="qty"><?php echo $item['qty']; ?></td>
	    	    		<td class="subtotal"><?php echo number_format($subtotal,2); ?></td>
	    	    	</tr>
	    	<?php endforeach; ?>
	    	    <tr>
	    	    	<td></td>
	    	    	<td colspan="3">Delivery Charges (<?php echo $delivery->total_weight  . $delivery->weight_unit; ?>)</td>
	    	    	<td class="delivery"><?php echo number_format($delivery->total_rate,2); ?></td>
	    	    </tr>
	    	    <tr>
	    	    	<td></td>
	    	    	<td colspan="3"><strong>GRAND TOTAL</strong>
	    	    	<td class="grand_total"><?php echo number_format($total+$delivery->total_rate,2); ?></td>
	    	    </tr>
	    	</tbody>
	    </table>
	    
	    <p><strong>Payment Method: </strong><?php echo $gateway->name; ?></p>
	    <p><strong>Shipping &amp; Delivery: </strong><?php echo $shipping->name; ?></p>
	    
	    <input type="submit" class="lcms-plexcart-checkout-commit-btn" value="Proceed to Payment" /> <a href="<?php echo $current_page; ?>cart">Back</a>
	</form>
