<script type="text/javascript">

$(document).ready(function(){
	$('table.lcms-revisions-table tbody tr').click(function(){
		$('table.lcms-revisions-table tbody tr').removeClass('current');
		$(this).addClass('current');
		
		var id = $(this).attr('rel');
		
		$(lcmsCurrentItem).html('<div style="text-align: center;"><img src="images/loader.gif" /></div>');
		lcmsContentSpotlight();
		
		lcmsPreviewRevision(id);
		
		
	});
	
	$('a.lcms-revisions-commit').click(function(){
		var current, published;
		$('input.lcms-revision-current').each(function(){
			if ($(this).attr('checked')) current = $(this).val();
		});

		$('input.lcms-revision-published').each(function(){
			if ($(this).attr('checked')) published = $(this).val();
		});
			
		$(lcmsCurrentItem).html('<div style="text-align: center;"><img src="images/loader.gif" /></div>');
		
		$.ajax({
			url: base_url + 'page/ajax/commit_revision',
			type: 'post',
			data: 'current='+current+'&published='+published,
			success: function (res){

				lcmsPreviewRevision(current, true);
				
			}
		})
	});
	
	
});

function lcmsPreviewRevision(id, commit){
	$.ajax({
		url: base_url + 'page/ajax/get_content_by_id',
		type: 'post',
		data: 'id=' + id,
		success: function(html){
			if (commit){
				var newItem = $(html);
				$(lcmsCurrentItem).replaceWith(newItem);
				lcmsCurrentItem = newItem;

			} else {
				$(lcmsCurrentItem).html($(html).html());
			}
			lcmsContentSpotlight();
		}
			
	});
}


</script>

	<table class="lcms-revisions-table">
		<thead>
			<tr>
				<th>Revision Date/Time</th>
				<th style="width: 80px">Current</th>
				<th style="width: 80px">Published</th>
				<th style="width: 8px; padding: 0"></th>
			</tr>
		</thead>
	</table>
	<form>
	<div style="height: 200px; padding-bottom: 5px; overflow-y: scroll; background: #FFF;">
		<table class="lcms-revisions-table">
			<tbody>
				<?php foreach ($revisions as $revision): ?>
				<tr rel="<?php echo $revision->id; ?>">
					<td><?php echo date('n:iA j F, Y', strtotime($revision->datetime)); ?></td>
					<td style="width: 80px; text-align: center"><input type="radio" value="<?php echo $revision->id; ?>" class="lcms-revision-current" name="current" <?php if ($revision->current) echo 'checked="checked"'; ?> /></td>
					<td style="width: 80px; text-align: center"><input type="radio" value="<?php echo $revision->id; ?>" class="lcms-revision-published" name="published" <?php if ($revision->published) echo 'checked="checked"'; ?> /></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>


	</div>
	<br />
	<a class="lcms-dbtn lcms-revisions-commit">Commit Changes</a>
	</form>