<script type="text/javascript">

$(document).ready(function(){
	$('a.lcms-survey-settings').live('click',function(e){
		e.preventDefault();
		$(this).parents('li.lcms-editable-object').find('a.lcms-edit-handle').click();
	})
});

</script>

	<?php if ($author_mode): ?>
	<div class="lcms-survey-control">
		<a class="lcms-btn" href="<?php echo $current_page; ?>/edit">Edit This Survey</a>
		<a class="lcms-btn lcms-survey-settings" href="#">Survey Settings</a>
	</div>
	<?php endif; ?>


	<div class="lcms-survey-page" style="display: block; min-height: 200px; margin-top: 20px;">
		<h3>Login Required</h3>
		<p>You need to be logged in to participate in this survey</p>
		
		<form method="post" action="<?php echo $current_page; ?>login">
			<input type="hidden" name="id" value="<?php echo $id; ?>" />
			<table>
				<tr>
					<td>Username</td>
					<td><input type="text" name="username" />
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" name="password" />
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Login" /></td>
				</tr>
			</table>
		
		</form>
	
	</div>