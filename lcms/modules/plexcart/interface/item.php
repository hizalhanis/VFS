<script type="text/javascript">
	function fade(obj) {
        $(obj).hide().delay(500).fadeIn();
        
        if ($(obj).hasClass('primary')){
	        var width = $(obj).width();
	        var height = $(obj).height();
	        
	        if (width > height){
		        $(obj).css('width','100%');
		        $(obj).css('height','auto');
		        
		        var cwidth = $(obj).parents('div.lcms-plexcart-image-container').width();
		        var cheight = (height / width) * cwidth;
		        var whitespace = $(obj).parents('div.lcms-plexcart-image-container').height() - cheight;
		        
		        $(obj).css('padding-top', whitespace / 2);
		        
	
	        } else {
		        $(obj).css('height','100%');
		        $(obj).css('width','auto');
		        
		        
	        }
	    }
	    
	    if ($(obj).hasClass('thumb')){
	        var width = $(obj).width();
	        var height = $(obj).height();
	        
	        if (width > height){
		        $(obj).css('width','100%');
		        $(obj).css('height','auto');
		        
		        var cwidth = $(obj).parents('div.lcms-plexcart-thumb').width();
		        var cheight = (height / width) * cwidth;
		        var whitespace = $(obj).parents('div.lcms-plexcart-thumb').height() - cheight;
		        
		        $(obj).css('padding-top', whitespace / 2);
		        
	
	        } else {
		        $(obj).css('height','100%');
		        $(obj).css('width','auto');
		        
		        
	        }
	    }
    }
</script>
	<div class="lcms-plexcart-breadcrumb">
		<h3><a href="<?php echo $current_page; ?>">Products</a> &raquo; <a href="<?php echo $category_page; ?>"><?php echo $cat; ?></a> &raquo; Item Information</h3>
	</div>
	<div class="lcms-plexcart-single-item">

		<div class="lcms-plexcart-img-container">
			<?php if ($item->primary_image): ?>
			<img onload="fade(this)" src="<?php echo $assets_url; ?><?php echo imgsrc($item->primary_image,'standard'); ?>" />
			<?php endif; ?>
			<?php
				if ($item->image){
					if (strpos($item->image,',')){
						$item_images = explode(',', $item->image);		
						
					} else {
						$item_images[] = $item->image;
						
					}
				}
			?>
			<?php foreach ($item_images as $thumb): ?>
			<div class="lcms-plexcart-image-thumbs">
				<div class="lcms-plexcart-thumb">
					<img onload="fade(this)" class="thumb" src="<?php echo $assets_url; ?><?php echo imgsrc($thumb,'thumbnail'); ?>" />
				</div>
			</div>
			<?php endforeach; ?>
			<br class="clear:left" />
		</div>
		
			
		<div class="lcms-plexcart-item-details">
    		<h3><?php echo $item->name;?></h3>
    		
    		<!--
			<?php
				if (is_object($user) && $user->rank){
					$item->price = $item->{'price'.$item->rules->{str_replace(' ','_',$user->rank)}};
				}
			?>
			-->
			
			<div class="lcms-plexcart-price-holder">
			<?php if ($item->discount_price != 0 && ($item->discount_applies_to == $user->rank || $item->discount_applies_to == 'All')&& ($item->discount_price < $item->price)): ?>
				<?php if ($item->discount_performance_based): ?>
					<?php if ($user->performance[$item->discount_performance_duration] >= $item->discount_performance_limit): ?>
						<span class="discount"><?php echo $item->currency; ?><?php echo $item->price; ?></span>
						<span class="price"><?php echo $item->currency; ?><?php echo $item->discount_price; ?></span>					
					<?php else: ?>
						<span class="price"><?php echo $item->currency; ?><?php echo $item->price; ?></span>					
					<?php endif; ?>
				<?php else: ?>
				<span class="discount"><?php echo $item->currency; ?><?php echo $item->price; ?></span>
				<span class="price"><?php echo $item->currency; ?><?php echo $item->discount_price; ?></span>					
				<?php endif; ?>
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

