	<div class="lcms-plexcart-breadcrumb">
		<h3><a href="<?php echo $current_page; ?>history">My Orders</a> &raquo; Order #<?php echo $order->id; ?></h3>
	</div>
	<form>
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
	    	<?php 
	    		$delivery = json_decode($order->delivery_address);
	    	?>
	    	<?php echo $delivery->name; ?><br />
	    	Mobile: <?php echo $delivery->mobile; ?><br />
	    	<?php echo $delivery->address1; ?>
	    	<?php echo $delivery->address2; ?><br />
	    	<?php echo $delivery->zipcode; ?> <?php echo $delivery->city; ?><br />
	    	<?php echo $delivery->country; ?>
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
	    	<?php foreach ($order->items as $item): ?>
	    	<?php
	    	    	$total += $item->price;
	    	    	$x++;
	    	?>
	    	    	<tr>
	    	    		<td class="no"><?php echo $x; ?></td>
	    	    		<td class="name"><?php echo $item->item_name; ?></td>
	    	    		<td class="price"><?php echo number_format($item->price_per_unit,2); ?></td>
	    	    		<td class="qty"><?php echo $item->qty; ?></td>
	    	    		<td class="subtotal"><?php echo number_format($item->price,2); ?></td>
	    	    	</tr>
	    	<?php endforeach; ?>
	    	    <tr>
	    	    	<td></td>
	    	    	<td colspan="3">Delivery Charges</td>
	    	    	<td class="delivery"><?php echo number_format($order->sh_charges,2); ?></td>
	    	    </tr>
	    	    <tr>
	    	    	<td></td>
	    	    	<td colspan="3"><strong>GRAND TOTAL</strong>
	    	    	<td class="grand_total"><?php echo number_format($order->total,2); ?></td>
	    	    </tr>
	    	</tbody>
	    </table>
	    
	    <p><strong>Payment Method: </strong><?php echo $order->payment_method; ?></p>
	    <p><strong>Shipping &amp; Delivery: </strong><?php echo $order->shipping; ?></p>
	    
	</form>
