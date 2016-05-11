	<div class="lcms-survey-edit lcms-control-form">
		<table class="lcms-control-form">	
			<tr>	
				<td class="label">Survey Name</td>	
				<td class="field"><input class="lcms-txt lcms-survey-name" type="text" /></td>	
			</tr>
			<tr>	
				<td class="label">Password Protected</td>	
				<td class="field"><input class="lcms-survey-password-protected" type="checkbox" value="1" /></td>	
			</tr>	
			<tr class="password-protected" style="display:none">	
				<td class="label">Logins</td>	
				<td class="field">
					<textarea class="lcms-textarea lcms-survey-logins" style="width: 400px; height: 80px"></textarea><br />
					<small>Logins are separated by lines. Username and password separated by hiphens ':'</small><br />
					<small style="color: #999"><i>e.g. username:password</i></small>
				</td>	
			</tr>	
		</table>	
		<button class="lcms-dbtn lcms-survey-update">Update</button>
		<button class="lcms-dbtn lcms-survey-update-publish">Update &amp; Publish</button>
		<button class="lcms-dbtn lcms-survey-discard-update">Discard</button>
	</div>