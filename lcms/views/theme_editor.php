
<html>
<head>
	<base href="<?=base_url()?>" />
	<title>Theme Editor</title>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jqueryui.js"></script>
	<script type="text/javascript" src="js/theme.js"></script>
	<script type="text/javascript" src="js/colorpicker.js"></script>
    <script type="text/javascript" src="js/eye.js"></script>
    <script type="text/javascript" src="js/utils.js"></script>
    <script type="text/javascript" src="js/layout.js?ver=1.0.2"></script>
	<link rel="stylesheet" href="css/colorpicker.css" type="text/css" />
    <link rel="stylesheet" media="screen" type="text/css" href="css/layout.css" />

	<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.7.2.custom.css"/>
	<link rel="stylesheet" href="css/buttons.css"/>
	<link rel="stylesheet" href="css/theme.css"/>
	<style>
	<?=str_replace('body {',"#editor-canvas {",$css)?>
	</style>

</head>
<body>
<a class="lcms-btn" style="float:right; margin: 5px;" href="<?=base_url()?>/admin/view_theme/<?=$theme_id?>">Back</a>
<h1 style="text-align:left"> Layout Creator</h1>

<div id="editor-canvas" style="margin: 10px;">
	<?=$html?>
</div>



<div id="controls" style="float:right; position:absolute; right: 10px; top: 50px" class="ui-widget-content">
	<h3 class="ui-widget-header">Toolbar</h3>
	<p>
		<button class="lcms-btn new-row">+ Row</button>
		<button class="lcms-btn new-left-column">+ Left Column</button>
		<button class="lcms-btn new-right-column">+ Right Column</button>
	</p>
	<p>
		<form method="post" action="<?=base_url()?>admin/save_layout">
			<textarea name="html" style="padding: 0; margin: 0; height: 1px; width: 1px; visibility:hidden"></textarea>
			<textarea name="css" style="padding: 0; margin: 0; height: 1px; width: 1px; visibility:hidden"></textarea><br />
			<input type="hidden" name="theme_id" value="<?=$theme_id?>" />
			<input type="hidden" name="layout_id" value="<?=$layout_id?>" />
			Layout Name <input style="width: 100px" type="text" name="layout_name" value="<?=$layout_name?>" />
			<button class="lcms-btn export">Save</button>
		</form>
	</p>
	<fieldset>
		<legend>Canvas</legend>
			<p>
				<span style="float:right">
					<select class="canvas-image">
						<option>none</option>
						<?php foreach($images as $image): ?>
						<option value="<?=base_url()?>media/<?=$image->name?>"><?=$image->name?></option>
						<?php endforeach; ?>
					</select>
				</span>
				Image
			<p>
			<p>	
				<span style="float:right">
					<select class="canvas-repeat">
						<option value="no-repeat">No Repeat</option>
						<option value="repeat-x">Repeat X Axis</option>
						<option value="repeat-y">Repeat Y Axis</option>
						<option value="repeat">Repeat Both</option>
					</select>
				</span>
				Repeat
			</p>
			<p>	
				<span style="float:right">
					<select class="canvas-position">
						<option value="top">Top</option>
						<option value="top left">Top Left</option>
						<option value="top right">Top Right</option>
						<option value="bottom">Bottom</option>
						<option value="bottom left">Bottom Left</option>
						<option value="bottom right">Bottom Right</option>
						<option value="center">Center</option>
						<option value="center top">Center Top</option>
						<option value="center bottom">Center Bottom</option>
						<option value="middle">Middle</option>
						<option value="middle left">Middle Left</option>
						<option value="middle right">Middle Right</option>
					</select>
				</span>
				Position
			</p>	
			<p><span style="float:right"><input class="canvas-color px" value="0" />#</span>Color<p>
			<p><span style="float:right"><input class="canvas-height px" value="400" />px</span>Height</p>
			<p><span style="float:right"><input class="canvas-width px" value="800" />px</span>Width<p>
			<p><span style="float:right"><input class="canvas-top-margin px" value="10" />px</span>Top Margin<p>
			<p><span style="float:right"><input class="canvas-bottom-margin px" value="10" />px</span>Bottom Margin<p>
			<p><span style="float:right"><input class="canvas-left-margin px" value="10" />px</span>Left Margin<p>
			<p><span style="float:right"><input class="canvas-right-margin px" value="10" />px</span>Right Margin<p>
	</fieldset>
	<fieldset>
		<legend>Section</legend>
			<p><span style="float:right"><input class="padding-top px" value="0" />px</span>Top Padding</p>
			<p><span style="float:right"><input class="padding-right px" value="0" />px</span>Right Padding<p>
			<p><span style="float:right"><input class="padding-bottom px" value="0" />px</span>Bottom Padding<p>
			<p><span style="float:right"><input class="padding-left px" value="0" />px</span>Left Padding<p>
			<p>
				<span style="float:right">
					<select class="font">
						<option value="Arial">Arial</option>
						<option value="Comic Sans">Comic Sans</option>
						<option value="Courier New">Courier New</option>
						<option value="Georgia">Georgia</option>
						<option value="Helvetica">Helvetica</option>
						<option value="Impact">Impact</option>
						<option value="Times">Times</option>
						<option value="Trebuchet">Trebuchet</option>
						<option value="Verdana">Verdana</option>
					</select>
				</span>
				Font<p>
	</fieldset>
	<fieldset>
		<legend>Background</legend>
		<p>
			<span style="float:right">
				<select class="image">
					<option>none</option>
					<?php foreach($images as $image): ?>
					<option value="<?=base_url()?>media/<?=$image->name?>"><?=$image->name?></option>
					<?php endforeach; ?>
				</select>
			</span>
			Image
		<p>
		<p><span style="float:right"><input class="color px" value="0" />#</span>Color<p>
		<p><span style="float:right"><input class="text-color px" value="0" />#</span>Text Color<p>
		<p>	
			<span style="float:right">
				<select class="repeat">
					<option value="no-repeat">No Repeat</option>
					<option value="repeat-x">Repeat X Axis</option>
					<option value="repeat-y">Repeat Y Axis</option>
					<option value="repeat">Repeat Both</option>
				</select>
			</span>
			Repeat
		</p>
		<p>	
			<span style="float:right">
				<select class="position">
					<option value="top">Top</option>
					<option value="top left">Top Left</option>
					<option value="top right">Top Right</option>
					<option value="bottom">Bottom</option>
					<option value="bottom left">Bottom Left</option>
					<option value="bottom right">Bottom Right</option>
					<option value="center">Center</option>
					<option value="center top">Center Top</option>
					<option value="center bottom">Center Bottom</option>
					<option value="middle">Middle</option>
					<option value="middle left">Middle Left</option>
					<option value="middle right">Middle Right</option>
				</select>
			</span>
			Position
		</p>
		<p><span style="float:right"><input class="x-offset px" value="0" />px</span>X Offset<p>
		<p><span style="float:right"><input class="y-offset px" value="0" />px</span>Y Offset<p>
	</fieldset>
</div>


</body>
</html>