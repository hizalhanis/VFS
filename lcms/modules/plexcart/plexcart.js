
		$(document).ready(function(){
		
			$('form.lcms-plexcart').submit(function(){
				var ok = true;
				$('input.mandatory').each(function(){
					var val = $(this).val();
					if (!val.trim()) ok = false;
				})
				
				if (!ok){
					alert('Please make sure you have filled in the form correctly.');
					return false;
				}
			});
			
			lcms_plexcart_update_cart_info();
		
			$('button.lcms-plexcart-addtocart').click(function(){
				var id = $(this).attr('rel');
				var qty = $(this).parents('div.lcms-plexcart-addtocart-holder').find('.lcms-plexcart-item-qty').val();
				$(this).attr('disabled',true);
				
				var that = this;
				$.ajax({
					url: 'page/ajax/control/plexcart/add_to_cart',
					type: 'post',
					data: 'id=' + id + '&qty=' + qty,
					success: function(){
						lcms_plexcart_update_cart_info();
						$(that).parents('div.lcms-plexcart-addtocart-holder').html('<span class="lcms-plexcart-item-added">Item has been added.</span>');
					}
				});
				
				
			});
			
			$('button.lcms-plexcart-removeitem').click(function(e){
				e.preventDefault();
				var id = $(this).attr('rel');
				var that = this;
				
				$(this).attr('disabled',true);
				
				var that = this;

				$.ajax({
					url: 'page/ajax/control/plexcart/remove_from_cart',
					type: 'post',
					data: 'id=' + id,
					success: function(){
						lcms_plexcart_update_cart_info();		
						$(that).parents('tr').fadeOut(500, function(){
							$(that).parents('tr').remove();
						})
					}
				});
				
				
			});
		});
		
		function lcms_plexcart_update_cart_info(){
			$.ajax({
				url: 'page/ajax/control/plexcart/cart_data',
				success: function(res){
					$('div.lcms-plexcart-cart-info').remove();
					$('div.lcms-plexcart-user-info').remove();
					
					if (res == 'false') return;
					$('body').append(res);

					$('div.lcms-plexcart-cart-info').hide();
					$('div.lcms-plexcart-user-info').hide();

				}
			})
		}

