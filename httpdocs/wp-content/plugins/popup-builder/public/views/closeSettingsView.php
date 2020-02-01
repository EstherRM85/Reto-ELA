<?php
	use sgpb\AdminHelper;
	use sgpb\PopupBuilderActivePackage;
	$defaultData = ConfigDataHelper::defaultData();
	$removedOptions = $popupTypeObj->getRemoveOptions();
	$closeButtonPosition = AdminHelper::themeRelatedSettings(
		$popupTypeObj->getOptionValue('sgpb-post-id'),
		$popupTypeObj->getOptionValue('sgpb-close-button-position'),
		$popupTypeObj->getOptionValue('sgpb-popup-themes')
	);

	$hideTopPosition = '';
	if ($closeButtonPosition == 'bottomRight' || $closeButtonPosition == 'bottomLeft') {
		$hideTopPosition = ' style="display:none;"';
	}
	$hideBottomPosition = '';
	if ($closeButtonPosition == 'topRight' || $closeButtonPosition == 'topLeft') {
		$hideBottomPosition = ' style="display:none;"';
	}
	$hideRightPosition = '';
	if ($closeButtonPosition == 'topLeft' || $closeButtonPosition == 'bottomLeft') {
		$hideRightPosition = ' style="display:none;"';
	}
	$hideLeftPosition = '';
	if ($closeButtonPosition == 'topRight' || $closeButtonPosition == 'bottomRight') {
		$hideLeftPosition = ' style="display:none;"';
	}

	$defaultCloseButtonPositions = $defaultData['closeButtonPositions'];
	if ($popupTypeObj->getOptionValue('sgpb-popup-themes') == 'sgpb-theme-1' ||
		$popupTypeObj->getOptionValue('sgpb-popup-themes') == 'sgpb-theme-4' ||
		$popupTypeObj->getOptionValue('sgpb-popup-themes') == 'sgpb-theme-5') {
		$defaultCloseButtonPositions = $defaultData['closeButtonPositionsFirstTheme'];
	}

	$borderRadiusType = $popupTypeObj->getOptionValue('sgpb-border-radius-type');
	if (!$popupTypeObj->getOptionValue('sgpb-border-radius-type')) {
		$borderRadiusType = '%';
	}
	$buttonImage = AdminHelper::defaultButtonImage(
		$popupTypeObj->getOptionValue('sgpb-popup-themes'),
		$popupTypeObj->getOptionValue('sgpb-button-image')
	);
	if (strpos($buttonImage, 'http') === false) {
		$buttonImage = 'data:image/png;base64,'.$buttonImage;
	}
	$disablePopupClosing = PopupBuilderActivePackage::canUseOption('sgpb-disable-popup-closing');
?>
<div class="sgpb-wrapper form-horizontal">
	<div class="row">
		<div class="col-md-8">
			<?php if (empty($removedOptions['sgpb-esc-key'])) :?>
			<div class="row form-group">
				<label for="esc-key" class="col-md-5 control-label sgpb-static-padding-top">
					<?php _e('Dismiss on "esc" key', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<div class="col-md-5">
					<input type="checkbox" id="esc-key" name="sgpb-esc-key" <?php echo $popupTypeObj->getOptionValue('sgpb-esc-key'); ?>>
					<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
					<span class="infoSelectRepeat samefontStyle sgpb-info-text">
						<?php _e('The popup will close if the "Esc" key of your keyboard is clicked.', SG_POPUP_TEXT_DOMAIN)?>.
					</span>
				</div>
			</div>
			<?php endif;?>
			<?php if (empty($removedOptions['sgpb-enable-close-button'])) :?>
			<div class="row form-group">
				<label for="close-button" class="col-md-5 control-label sgpb-static-padding-top">
					<?php _e('Show "close" button', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<div class="col-md-5">
					<input class="js-checkbox-accordion" type="checkbox" id="close-button" name="sgpb-enable-close-button" <?php echo $popupTypeObj->getOptionValue('sgpb-enable-close-button'); ?>>
					<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
					<span class="infoSelectRepeat samefontStyle sgpb-info-text">
						<?php _e('Uncheck this option if you don\'t want to show a "close" button on your popup.', SG_POPUP_TEXT_DOMAIN)?>.
					</span>
				</div>
			</div>
			<div class="sg-full-width">
				<?php if (empty($removedOptions['sgpb-close-button-delay'])) :?>
					<div class="row form-group">
						<label for="sgpb-close-button-delay" class="col-md-5 control-label sgpb-sub-option">
							<?php _e('Button delay', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-6">
							<input type="number" min="0" id="sgpb-close-button-delay" class="sgpb-full-width-events form-control" name="sgpb-close-button-delay" value="<?php echo $popupTypeObj->getOptionValue('sgpb-close-button-delay'); ?>" placeholder="e.g.: 1">
						</div>
						<div class="col-md-1 sgpb-info-wrapper">
							<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
							<span class="infoSelectRepeat samefontStyle sgpb-info-text">
								<?php _e('Specify the time (in seconds) after which the close button will appear. The close button will be shown by default without any delay if no time is specified', SG_POPUP_TEXT_DOMAIN)?>.
							</span>
						</div>
					</div>
				<?php endif; ?>
				<?php if (empty($removedOptions['sgpb-close-button-position'])) :?>
					<div class="row form-group">
						<label for="redirect-to-url" class="col-md-5 control-label sgpb-sub-option">
							<?php _e('Button position', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-6"><?php echo AdminHelper::createSelectBox($defaultCloseButtonPositions, $closeButtonPosition, array('name' => 'sgpb-close-button-position', 'class'=>'js-sg-select2 sgpb-close-button-position')); ?></div>
					</div>
					<div class="row form-group sgpb-button-position-top-js"<?php echo $hideTopPosition ;?>>
						<label for="sgpb-button-position-top" class="col-md-5 control-label sgpb-double-sub-option">
							<?php _e('top', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-6">
							<input id="sgpb-button-position-top" class="sgpb-full-width form-control sgpb-full-width-events" step="0.5" type="number" name="sgpb-button-position-top" value="<?php echo $popupTypeObj->getOptionValue('sgpb-button-position-top'); ?>">
						</div>
						<div class="col-md-1">
							<span class="sgpb-restriction-unit">px</span>
						</div>
					</div>
					<div class="row form-group sgpb-button-position-right-js"<?php echo $hideRightPosition ;?>>
						<label for="sgpb-button-position-right" class="col-md-5 control-label sgpb-double-sub-option">
							<?php _e('right', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-6">
							<input id="sgpb-button-position-right" class="sgpb-full-width form-control sgpb-full-width-events" step="0.5" type="number" name="sgpb-button-position-right" value="<?php echo $popupTypeObj->getOptionValue('sgpb-button-position-right'); ?>">
						</div>
						<div class="col-md-1">
							<span class="sgpb-restriction-unit">px</span>
						</div>
					</div>
					<div class="row form-group sgpb-button-position-bottom-js"<?php echo $hideBottomPosition ;?>>
						<label for="sgpb-button-position-bottom" class="col-md-5 control-label sgpb-double-sub-option">
							<?php _e('bottom', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-6">
							<input id="sgpb-button-position-bottom" class="sgpb-full-width form-control sgpb-full-width-events" step="0.5" type="number" name="sgpb-button-position-bottom" value="<?php echo $popupTypeObj->getOptionValue('sgpb-button-position-bottom'); ?>">
						</div>
						<div class="col-md-1">
							<span class="sgpb-restriction-unit">px</span>
						</div>
					</div>
					<div class="row form-group sgpb-button-position-left-js"<?php echo $hideLeftPosition ;?>>
						<label for="sgpb-button-position-left" class="col-md-5 control-label sgpb-double-sub-option">
							<?php _e('left', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-6">
							<input id="sgpb-button-position-left" class="sgpb-full-width form-control sgpb-full-width-events" step="0.5" type="number" name="sgpb-button-position-left" value="<?php echo $popupTypeObj->getOptionValue('sgpb-button-position-left'); ?>">
						</div>
						<div class="col-md-1">
							<span class="sgpb-restriction-unit">px</span>
						</div>
					</div>
				<?php endif; ?>
				<div class="<?php echo ($popupTypeObj->getOptionValue('sgpb-popup-themes') == 'sgpb-theme-4') ? 'sg-hide ' : '' ;?>sgpb-close-button-image-option-wrapper">
					<div class="row form-group">
						<label for="redirect-to-url" class="col-md-5 control-label sgpb-static-padding-top sgpb-sub-option">
							<?php _e('Button image', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div>
							<div class="sgpb-button-image-uploader-wrapper">
								<input class="sg-hide" id="js-button-upload-image" type="text" size="36" name="sgpb-button-image" value="<?php echo (esc_attr($popupTypeObj->getOptionValue('sgpb-button-image'))) ? esc_attr($popupTypeObj->getOptionValue('sgpb-button-image')) : '' ; ?>">
							</div>
						</div>
						<div class="col-md-7">
							<div class="row">
								<div class="col-md-3 sgpb-close-btn-image-wrapper">
									<div class="sgpb-show-button-image-container" style="background-image: url(<?php echo $buttonImage;?>);">
										<span class="sgpb-no-image"></span>
									</div>
								</div>
								<div class="col-md-6 sgpb-close-btn-change-image-wrapper">
									<input id="js-button-upload-image-button" class="btn btn-sm btn-default" type="button" value="<?php _e('Change image', SG_POPUP_TEXT_DOMAIN);?>">
								</div>
								<div class="col-md-3 js-sgpb-remove-close-button-image<?php echo (!$popupTypeObj->getOptionValue('sgpb-button-image')) ? ' sg-hide' : '';?>">
									<input id="js-button-upload-image-remove-button" class="btn btn-sm btn-danger" type="button" value="<?php _e('Remove', SG_POPUP_TEXT_DOMAIN);?>">
								</div>
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label for="redirect-to-url" class="col-md-5 control-label sgpb-static-padding-top sgpb-double-sub-option">
							<?php _e('Width', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-6">
							<input class="sgpb-full-width form-control sgpb-full-width-events" type="number" min="0" name="sgpb-button-image-width" value="<?php echo $popupTypeObj->getOptionValue('sgpb-button-image-width'); ?>" required>
						</div>
						<div class="col-md-1">
							<span class="sgpb-restriction-unit">px</span>
						</div>
					</div>
					<div class="row form-group">
						<label for="redirect-to-url" class="col-md-5 control-label sgpb-static-padding-top sgpb-double-sub-option">
							<?php _e('Height', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-6">
							<input class="sgpb-full-width form-control sgpb-full-width-events" type="number" min="0" name="sgpb-button-image-height" value="<?php echo $popupTypeObj->getOptionValue('sgpb-button-image-height'); ?>" required>
						</div>
						<div class="col-md-1">
							<span class="sgpb-restriction-unit">px</span>
						</div>
					</div>
				</div>
				<div class="<?php echo ($popupTypeObj->getOptionValue('sgpb-popup-themes') != 'sgpb-theme-3') ? 'sg-hide ' : '' ;?>sgpb-close-button-border-options">
					<div class="row form-group">
						<label class="col-md-5 control-label sgpb-static-padding-top">
							<?php _e('Popup border color', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-7">
							<div class="sgpb-color-picker-wrapper sgpb-border-color">
								<input class="sgpb-color-picker sgpb-border-color" type="text" name="sgpb-border-color" value="<?php echo (esc_attr($popupTypeObj->getOptionValue('sgpb-border-color'))) ? esc_attr($popupTypeObj->getOptionValue('sgpb-border-color')) : '#000000' ; ?>">
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class="col-md-5 control-label sgpb-static-padding-top">
							<?php _e('Popup border radius', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-6">
							<input class="sgpb-full-width form-control sgpb-full-width-events" type="number" min="0" name="sgpb-border-radius" value="<?php echo (esc_attr($popupTypeObj->getOptionValue('sgpb-border-radius'))) ? esc_attr($popupTypeObj->getOptionValue('sgpb-border-radius')) : '0' ; ?>">
						</div>
						<div class="col-md-1">
							<?php echo AdminHelper::createSelectBox($defaultData['pxPercent'], $borderRadiusType, array('name' => 'sgpb-border-radius-type', 'class'=>'sgpb-border-radius-type js-sg-select2')); ?>
						</div>
					</div>
				</div>
				<div class="<?php echo ($popupTypeObj->getOptionValue('sgpb-popup-themes') != 'sgpb-theme-4') ? 'sg-hide ' : '' ;?>sgpb-close-button-text-option-wrapper">
					<div class="row form-group">
						<label class="col-md-5 control-label sgpb-static-padding-top">
							<?php _e('Button text', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-button-text">
								<input class="form-control sgpb-full-width-events" type="text" name="sgpb-button-text" value="<?php echo (esc_attr($popupTypeObj->getOptionValue('sgpb-button-text'))) ? esc_attr($popupTypeObj->getOptionValue('sgpb-button-text')) : __('Close', SG_POPUP_TEXT_DOMAIN) ; ?>" autocomplete="off">
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif;?>
			<?php if (empty($removedOptions['sgpb-disable-page-scrolling'])): ?>
				<div class="row form-group">
					<label for="overlay-click" class="col-md-5 control-label sgpb-static-padding-top">
						<?php _e('Dismiss on overlay click', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-7">
						<input type="checkbox" id="overlay-click" name="sgpb-overlay-click" <?php echo $popupTypeObj->getOptionValue('sgpb-overlay-click'); ?>>
						<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
						<span class="infoSelectRepeat samefontStyle sgpb-info-text">
							<?php _e('The popup will close when clicked on the overlay of the popup', SG_POPUP_TEXT_DOMAIN)?>.
						</span>
					</div>
				</div>
			<?php endif; ?>
			<div class="row form-group">
				<label for="popup-closing" class="col-md-5 control-label sgpb-static-padding-top">
					<?php _e('Disable popup closing', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<div class="col-md-2<?php echo (!$disablePopupClosing) ? ' sgpb-pro-options-row' : '' ;?>">
					<?php if ($disablePopupClosing): ?>
						<input type="checkbox" id="popup-closing" name="sgpb-disable-popup-closing" <?php echo $popupTypeObj->getOptionValue('sgpb-disable-popup-closing'); ?>>
					<?php else: ?>
						<input type="checkbox" id="popup-closing" name="sgpb-disable-popup-closing" disabled>
					<?php endif; ?>
					<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
					<span class="infoSelectRepeat samefontStyle sgpb-info-text">
						<?php _e('The users will not be able to close the popup, if this option is checked', SG_POPUP_TEXT_DOMAIN)?>.
					</span>
				</div>
				<?php if (!$disablePopupClosing): ?>
					<div class="col-md-2 sgpb-pro-options-label-wrapper">
						<a href="<?php echo SG_POPUP_ADVANCED_CLOSING_URL;?>" target="_blank" class="btn btn-warning btn-xs sgpb-pro-label-sm sgpb-advanced-closing-pro-label"><?php _e('UNLOCK OPTION', SG_POPUP_TEXT_DOMAIN) ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
