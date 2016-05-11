	<div class="lcms-plexcart-breadcrumb">
		<h3><a href="<?php echo $current_page; ?>">Products</a> &raquo; <a href="<?php echo $category_page; ?>"><?php echo $cat; ?></a> &raquo; <a href="<?php echo $subcategory_page; ?>"><?php echo $subcat; ?></a></h3>
	</div>
	<div class="lcms-plexcart-single-item">

		<div class="lcms-plexcart-img-container">
			<?php if ($item->primary_image): ?>
			<img src="<?php echo $assets_url; ?><?php echo $item->primary_image?>" />
			<?php endif; ?>
			<?php
				if ($item->image){
					if (strpos($item->image,',')){
						$images = explode(',', $item->image);		
						
					} else {
						$images[] = $item->image;
						
					}
				}
			?>
			<?php foreach ($images as $thumb): ?>
			<div class="lcms-plexcart-image-thumbs">
				<div class="lcms-plexcart-thumb">
					<img src="<?php echo $assets_url; ?><?php echo $thumb; ?>" />
				</div>
			</div>
			<?php endforeach; ?>
			<br class="clear:left" />
		</div>
		
			
		<div class="lcms-plexcart-item-details">
    		<h3><?php echo $item->name; ?></h3>
    		<div class="lcms-plexcart-price-holder">
    			<?php if ($item->discount_price): ?>
    				<span class="discount"><?php echo $item->currency; ?><?php echo $item->price; ?></span>
    				<span class="price"><?php echo $item->currency; ?><?php echo $item->discount_price; ?></span>					
    			<?php else: ?>
    				<span class="price"><?php echo $item->currency; ?><?php echo $item->price; ?></span>
    			<?php endif; ?>
    		</div>
    		
    		<div class="lcms-plexcart-addtocart-holder">
				<?php if ($item->qty - $item->alert_qty >= ($item->max_cart_qty ? $item->max_cart_qty : 3)): ?>
					<?php if (is_array($cart_items[$item->id])): ?>
	    				<span rel="<?php echo $item->id; ?>" class="lcms-plexcart-item-added">Item has been added.</span>
	    			<?php else: ?>
						<?php echo form_dropdown('lcms-plexcart-item-qty',number_range($item->min_cart_qty ? $item->min_cart_qty : 1, $item->max_cart_qty ? $item->max_cart_qty : 3),'','class="lcms-plexcart-item-qty"'); ?>
						<button rel="<?php echo $item->id; ?>" class="lcms-plexcart-addtocart">Add to Cart</button>
					<?php endif; ?>
				<?php else: ?>
    				<span class="lcms-plexcart-out-of-stock">Out of stock</span>
				<?php endif; ?>
    		</div>
    		
    		<h4>Description</h4>
    		<hr />
    		<p><?php echo nl2br($item->description); ?></p>

    	</div>
			

		<br style="clear:left" />
	</div>

