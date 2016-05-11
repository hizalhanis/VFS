	<div class="lcms-slideshow-edit lcms-control-form">
		<div>
			
		</div>
		<table class="lcms-control-form">		
			<tr>
				<td class="label">Class</td>
				<td class="field"><input type="text" name="class" class="lcms-txt lcms-slideshow-class" /></td>
			</tr>
			<tr>
				<td class="label">Effect</td>
				<td class="field"><?php echo form_dropdown('effect', array('slide'=>'Slide','fade'=>'Fade'),'','class="lcms-slideshow-effect"'); ?></td>
			</tr>
			<tr>
				<td class="label">Show Markers</td>
				<td class="field"><input type="checkbox" class="lcms-slideshow-show-markers" /> Yes</td>
			</tr>
			<tr>
				<td class="label">Show Controls</td>
				<td class="field"><input type="checkbox" class="lcms-slideshow-show-controls" /> Yes</td>
			</tr>
			<tr>
				<td class="label">Center Markers</td>
				<td class="field"><input type="checkbox" class="lcms-slideshow-center-markers" /> Yes</td>
			</tr>

			<tr>
				<td style="border-top: 1px solid #666;" colspan="2"><strong style="display: block; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px dashed #666;">Add Image</strong></td>
			</tr>
			<tr>
				<td colspan="2">
					<div class="lcms-slideshow-image-container" style="background: #FFF;padding: 5px; height: 170px; overflow-y: scroll; box-shadow: 0 0 5px rgba(0,0,0,0.4) inset; width: 360px">
						<ul style="margin: 0; list-style: none; padding: 0;">

						</ul>
					</div>
				</td>
			</tr>
			<tr>	
				<td class="label">Image URL</td>	
				<td class="field"><input type="text" class="lcms-txt lcms-slideshow-image-url" /><button class="lcms-dbtn lcms-slideshow-select">Media Gallery</button></td> 	
			</tr>
			<tr>	
				<td class="label">Link URL</td>	
				<td class="field"><input type="text" class="lcms-txt lcms-slideshow-link-url" /></td> 	
			</tr>
			<tr>	
				<td class="label"></td>	
				<td><button class="lcms-dbtn lcms-slideshow-add">Add</button><br /><br /></td>	
			</tr>	
		</table>	
		<button class="lcms-dbtn lcms-slideshow-update">Save</button>	
		<button class="lcms-dbtn lcms-slideshow-update-publish">Save &amp; Publish</button>	
		<button class="lcms-dbtn lcms-slideshow-discard-update">Discard</button>	
	</div>