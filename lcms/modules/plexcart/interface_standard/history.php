	<div class="lcms-plexcart-breadcrumb">
		<h3>My Orders</h3>
	</div>
	<table class="grid">
	    <thead>
	    	<tr>
	    		<th>Date</th>
	    		<th>Order No</th>
	    		<th>Payment Method</th>
	    		<th>Amount</th>
	    		<th>Payment Status</th>
	    		<th>Order Status</th>
	    		<th>Remarks</th>
	    	</tr>
	    </thead>
	    
	    <tbody>
	    	<?php if (!count($orders)): ?>
	    	<tr>
	    		<td colspan="5"><i>You have no orders in your history.</i></td>
	    	</tr>
	    	<?php endif; ?>
	    	<?php foreach($orders as $order): $x++; ?>
	    	<tr>
	    		<td class="align-center"><?php echo date('d/m/Y h:i:s',strtotime($order->date)); ?></td>
	    		<td><a href="<?php echo $current_page; ?>order/<?php echo $order->id; ?>">ORD-<?php echo $order->id; ?></a></td>
	    		<td><?php echo $order->payment_method; ?></td>
	    		<td class="align-right"><?php echo number_format($order->total,2); ?></td>
	    		<td><?php echo $order->payment_status; ?></td>
	    		<td><?php echo $order->status; ?></td>
	    		<td><?php echo $order->remarks; ?></td>
	    	</tr>
	    	<?php endforeach; ?>
	    </tbody>
	</table>