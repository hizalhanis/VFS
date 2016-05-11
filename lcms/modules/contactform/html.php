	<div class="lcms-contactform-table">
		<form class="lcms-contactform" method="post" action="<?php echo $url; ?>">
			<?php if ($author_mode): ?>
			<div class="lcms-contactform-control-buttons" style="display:none">
				<button class="lcms-dbtn lcms-contactform-add-input">Add Text Field</button>
				<button class="lcms-dbtn lcms-contactform-add-textarea">Add Multi-line Field</button>
			</div>
			<?php endif; ?>

			<table>
				<?php $fields = json_decode($content->options); ?>
				<?php foreach ($fields as $field): ?>
				
				<?php if ($author_mode): ?>
				<tr class="cf-row">
				<?php else: ?>
				<tr>
				<?php endif; ?>
					<td class="label"<?php if ($author_mode) echo ' style="vertical-align: top"'; ?>>
						<?php if ($author_mode): ?>
						<input style="border:none; background: none; border-bottom: 1px solid #DDD; font-family:inherit; font-size: inherit; display:none" type="text" value="<?php echo $field->label; ?>" class="label" fieldtype="<?php echo $field->type; ?>" />
						<?php endif; ?>
						<span><?php echo $field->label; ?></span>

					</td>
					<td class="field"<?php if ($author_mode) echo ' style="vertical-align: top"'; ?>>
						<?php if ($field->type == 'textarea'): ?>
						<textarea name="field[<?php echo clean_url($field->label); ?>]"></textarea>
						<?php else: ?>
						<input class="lcms-contactform-text" type="text" name="field[<?php echo clean_url($field->label); ?>]" />						
						<?php endif; ?>
					</td>
				</tr>	
				
				<?php endforeach; ?>
				<tr class="submit">
					<td class="label"></td>
					<td class="input"><input class="lcms-contactform-button" type="submit" value="Submit" /></td>
				</tr>
			</table>
		</form>
	</div>