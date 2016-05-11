<script>

$(function() {

	
	$('#banner<?php echo $content->id; ?> img').hide();
	

});

$(window).load(function(){
	var fimg = $('#banner<?php echo $content->id; ?>').find('img').first();

	var height = $(fimg).height();
	var width = $(fimg).width();
	$('#banner<?php echo $content->id; ?> img').fadeIn();
	$('#banner<?php echo $content->id; ?> ul').addClass('bjqs');
	$('#banner<?php echo $content->id; ?>').bjqs({
		'height'		: height,
		'width'			: width,
		'animation' 	: '<?php echo $options->effect; ?>',
		'showMarkers' 	: <?php echo $options->show_markers; ?>,
		'showControls' 	: <?php echo $options->show_controls; ?>,
		'centerMarkers' : <?php echo $options->center_markers; ?>
	});
		


});

	

</script>

<div class="lcms-slideshow-contents <?php echo $content->class; ?>">
	<div id="banner<?php echo $content->id; ?>">
		<ul style="list-style:none">
			<?php foreach ($images as $image): ?>
				<?php if ($image->href):?>
				<li><a href="<?php echo $image->href; ?>"><img src="<?php echo $image->src; ?>" /></a></li>
				<?php else: ?>
				<li><img src="<?php echo $image->src; ?>" /></li>
				<?php endif; ?>
			<?php endforeach;?>
		</ul>
	</div>
</div>