	<div class="lcms-plexcart-breadcrumb">
		<h3><a href="<?php echo $current_page; ?>">Store</a> &raquo; Choose Our Store</h3>
	</div>

	<ul class="lcms-plexcart-stores">
	<?php foreach ($warehouses as $warehouse): ?>
	
		<li>
			<a href="<?php echo $current_page; ?>warehouse/<?php echo $warehouse->id; ?>"><?php echo $warehouse->name; ?></a>
			<p><?php echo $warehouse->address1; ?><br /><?php echo $warehouse->address2; ?><br /><?php echo $warehouse->zipcode; ?> <?php echo $warehouse->city; ?><br /><?php echo $warehouse->state; ?><br />
		</li>
	
	<?php endforeach; ?>
	</ul>
	
	<br style="clear:both" />
	
