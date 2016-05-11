	<div class="lcms-forms-hidden" style="display:none">
	<?php foreach($controls as $control){ echo $this->{$control}->prehtml(); } ?>
	</div>

	<div class="lcms-new-content">
		<a class="lcms-close-btn"><span>Close</span></a>
		<h3 class="lcms-toolbar">Choose Content Type</h3>
		<?php foreach($controls as $control): ?>
			<a onclick="lcmsAddItem('<?php echo $this->{$control}->namespace; ?>')" class="lcms-content-type lcms-tool-<?php echo $this->{$control}->namespace; ?>"><span><?php echo $this->{$control}->name; ?></span></a>
		<?php endforeach; ?>
	</div>