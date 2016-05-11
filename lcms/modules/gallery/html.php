<script>


$(document).ready(function() {
	$(".lcms-gallery-contents-<?php echo $content->id; ?>").fancybox({
		openEffect	: '<?php echo $options->effect; ?>',
		closeEffect	: '<?php echo $options->effect; ?>'
	});
});	


	

</script>

<div class="lcms-gallery-contents <?php echo $content->class; ?>">
	<div id="gallery<?php echo $content->id; ?>">
		<ul>
			<?php foreach ($images as $image): ?>
				<li>
					<div class="lcms-gallery-photo-item">
						<a href="<?php echo $image->src; ?>" class="lcms-gallery-contents-<?php echo $content->id; ?>" rel="gallery<?php echo $content->id; ?>" title="<?php echo $image->title; ?>"><img src="<?php echo $image->src; ?>" /></a>
					</div>
				</li>
			<?php endforeach;?>
			<br style="clear:left" />
		</ul>
	</div>
</div>