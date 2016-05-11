	<div class="lcms-gallery-edit lcms-control-form">
		<div>
			
		</div>
		<table class="lcms-control-form">		
			<tr>
				<td class="label">Class</td>
				<td class="field"><input type="text" name="class" class="lcms-txt lcms-gallery-class" /></td>
			</tr>
			<tr>
				<td class="label">Effect</td>
				<td class="field"><?php echo form_dropdown('effect', array('fade'=>'Fade','elastic'=>'Elastic','none'=>'None'),'','class="lcms-gallery-effect"'); ?></td>
			</tr>
			<tr style="display:none">
				<td class="label">Caption Position</td>
				<td class="field"><?php echo form_dropdown('position', array('outside'=>'Outside','inside'=>'Inside','over'=>'Over'),'','class="lcms-gallery-title-position"'); ?></td>
			</tr>
			<tr>
				<td style="border-top: 1px solid #666;" colspan="2"><strong style="display: block; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px dashed #666;">Add Image</strong></td>
			</tr>
			<tr>
				<td colspan="2">
					<div class="lcms-gallery-image-container" style="background: #FFF;padding: 5px; height: 170px; overflow-y: scroll; box-shadow: 0 0 5px rgba(0,0,0,0.4) inset; width: 360px">
						<ul style="margin: 0; list-style: none; padding: 0;">

						</ul>
					</div>
				</td>
			</tr>
			<tr>	
				<td class="label">Image URL</td>	
				<td class="field"><input type="text" class="lcms-txt lcms-gallery-image-url" /><button class="lcms-dbtn lcms-gallery-select">Media Gallery</button></td> 	
			</tr>
			<tr>	
				<td class="label">Caption</td>	
				<td class="field"><input type="text" class="lcms-txt lcms-gallery-image-title" /></td> 	
			</tr>
			<tr>	
				<td class="label"></td>	
				<td><button class="lcms-dbtn lcms-gallery-add">Add</button><br /><br /></td>	
			</tr>	
		</table>	
		<button class="lcms-dbtn lcms-gallery-update">Save</button>	
		<button class="lcms-dbtn lcms-gallery-update-publish">Save &amp; Publish</button>	
		<button class="lcms-dbtn lcms-gallery-discard-update">Discard</button>	
	</div>