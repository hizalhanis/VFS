	<div class="lcms-plexcart-breadcrumb">
		<h3><a href="<?php echo $current_page; ?>">Store</a> &raquo; My Orders</h3>
	</div>
	<table class="grid">
	    <thead>
	    	<tr>
	    		<th class="align-left">Date</th>
	    		<th class="align-left">Order No</th>
	    		<th class="align-left">Payment Method</th>
	    		<th class="align-left">Remarks</th>
	    		<th>Payment Status</th>
	    		<th>Delivery Status</th>
	    		<th class="align-right">Amount (<?php echo $currency; ?>)</th>
	    	</tr>
	    </thead>
	    
	    <tbody>
	    	<?php if (!count($orders)): ?>
	    	<tr>
	    		<td colspan="5"><i>You have no orders in your history.</i></td>
	    	</tr>
	    	<?php endif; ?>
	    	<?php foreach($orders as $order): $x++; ?>
	    	<tr <?php if ($x%2 == 0) echo 'class="alt"'; ?>>
	    		<td class="align-left"><?php echo date('d/m/Y',strtotime($order->date)); ?></td>
	    		<td class="align-left"><a href="<?php echo $current_page; ?>order/<?php echo $order->id; ?>">ORD-<?php echo $order->txn_no; ?></a></td>
	    		<td class="align-left"><?php echo $order->payment_method ? $order->payment_method : 'N/A'; ?></td>
	    		<td class="align-left"><?php echo $order->remarks; ?></td>
	    		<td class="align-center"><?php echo $order->invoice->payment_status; ?></td>
	    		<td class="align-center"><?php echo $order->invoice->delivery_status; ?></td>
	    		<td class="align-right"><?php echo number_format($order->total,2); ?></td>
	    	</tr>
	    	<?php endforeach; ?>
	    </tbody>
	</table>