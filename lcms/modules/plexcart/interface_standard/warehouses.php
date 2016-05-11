	<h1>Choose Our Store</h1>

	<ul>
	<?php foreach ($warehouses as $warehouse): ?>
	
		<li>
			<a href="<?php echo $current_page; ?>warehouse/<?php echo $warehouse->id; ?>"><?php echo $warehouse->name; ?></a><br />
			<p><?php echo $warehouse->address1; ?><br /><?php echo $warehouse->address2; ?><br /><?php echo $warehouse->zipcode; ?> <?php echo $warehouse->city; ?><br /><?php echo $warehouse->state; ?><br />
		</li>
	
	<?php endforeach; ?>
	</ul>
	
