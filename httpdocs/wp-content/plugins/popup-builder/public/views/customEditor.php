<?php
$popupId = @$_GET['post'];
$editorModeJs = htmlentities('text/javascript');
$editorModeCss = htmlentities('text/css');

$defaultData = ConfigDataHelper::defaultData();
$jsDefaultData = $defaultData['customEditorContent']['js'];
$cssDefaultData = $defaultData['customEditorContent']['css'];

$savedData = get_post_meta($popupId , 'sg_popup_scripts', true);
?>

<p><?php _e('This section is for adding custom codes (CSS or JS) for the popup, it requires some coding knowledge', SG_POPUP_TEXT_DOMAIN);?>.</p>
<p><?php _e('You may use your custom codes for extra actions connected to the popup opening (before, after, etc.) in the fields below', SG_POPUP_TEXT_DOMAIN);?>.</p>

<div class="sgpb-wrapper">
	<!-- editor buttons -->
	<div class="sgpb-editor-options-tabs-wrapper">
		<input class="btn sgpb-editor-tab-links sgpb-editor-tab-1 col-md-3 sgpb-active-tab" data-attr-custom-tab="js" name="sgpb-js" value="JS" readonly onclick="SGPBBackend.prototype.changeTab(1)">
		<input class="btn sgpb-editor-tab-links sgpb-editor-tab-2 col-md-3" data-attr-custom-tab="js" name="sgpb-css" value="CSS" readonly onclick="SGPBBackend.prototype.changeTab(2)">
	</div>

	<div class="sgpb-editor">
		<!-- JS editor content -->
		<div id="sgpb-editor-options-tab-content-wrapper-1" class="sgpb-editor-options-tab-content-wrapper" style="display: block;">
			<div class="editor-static-text">
			<?php
				foreach ($jsDefaultData['description'] as $text) { ?>
					<div><?php echo $text; ?></div>
				<?php }
			?></div>
			<br>

			<?php foreach ($jsDefaultData['helperText'] as $key => $value) {?>
					<div class="editor-static-text"><div><?php echo $value; ?></div></div>
					<div class="editor-static-text"><div>
						<textarea   class="wp-editor-area editor-content"
									data-attr-event="<?php echo $key; ?>"
									placeholder=" #... type your code"
									mode="<?php echo $editorModeJs; ?>"
									name="sgpb-<?php echo $key; ?>"><?php
									if (!empty($savedData['js']['sgpb-'.$key])) {
											echo $savedData['js']['sgpb-'.$key];
										}
									?></textarea>
					</div></div>
			<?php } ?>
		</div>

		<!-- CSS editor content -->
		<div id="sgpb-editor-options-tab-content-wrapper-2" class="sgpb-editor-options-tab-content-wrapper" style="display: none;">
			<div class="editor-static-text">
			<?php
				foreach ($cssDefaultData['description'] as $text) { ?>
					<div><?php echo $text; ?></div>
				<?php }
			?></div>
			<br>

			<input class="btn reset-button col-md-1" data-attr-custom-tab="reset" value="Clear" readonly onclick="SGPBBackend.prototype.resetCssEditorContent()">
			<div class="editor-static-text"><div></div></div>

			<div class="editor-static-text"><div>
				<textarea class="wp-editor-area editor-content editor-content-css"
							placeholder=" #... type your code"
							mode="<?php echo $editorModeCss; ?>"
							name="sgpb-css-editor"><?php 
							if (isset($savedData['css'])) {
								echo $savedData['css'];
							}?></textarea>
				</div></div>
		</div>
	</div>
</div>
