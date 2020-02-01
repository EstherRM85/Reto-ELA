<div class="sg-wp-editor-container">
	<h1 class="sgpb-image-popup-headline"><?php _e('Please choose your picture');?></h1>
	<div class="sgpb-image-uploader-wrapper">
		<input class="input-width-static" id="js-upload-image" type="text" size="36" name="sgpb-image-url" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-image-url')); ?>" required>
		<input id="js-upload-image-button" class="button" type="button" value="Select image">
	</div>
	<div class="sgpb-show-image-container">
		<span class="sgpb-no-image">(<?php _e('No image selected', SG_POPUP_TEXT_DOMAIN);?>)</span>
	</div>
</div>
