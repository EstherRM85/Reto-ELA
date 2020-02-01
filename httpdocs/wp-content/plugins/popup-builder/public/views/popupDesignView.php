<?php
use sgpb\AdminHelper;
use sgpb\PopupBuilderActivePackage;
$defaultData = ConfigDataHelper::defaultData();
$enablePopupOverlay = PopupBuilderActivePackage::canUseOption('sgpb-enable-popup-overlay');
$removedOptions = $popupTypeObj->getRemoveOptions();
$popupTheme = $popupTypeObj->getOptionValue('sgpb-popup-themes');
$hidePopupBorderOption = ' sg-hide';
if ($popupTheme == 'sgpb-theme-2' || $popupTheme == 'sgpb-theme-3') {
	$hidePopupBorderOption = '';
}

?>
<div class="sgpb-wrapper">
	<div class="row">
		<div class="col-md-8">
			<?php if (empty($removedOptions['sgpb-force-rtl'])) :?>
				<div class="row form-group">
					<label for="sgpb-force-rtl" class="col-md-5 control-label sgpb-static-padding-top">
						<?php _e('Force RTL', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6">
						<input type="checkbox" id="sgpb-force-rtl" name="sgpb-force-rtl" <?php echo $popupTypeObj->getOptionValue('sgpb-force-rtl'); ?>>
					</div>
				</div>
			<?php endif; ?>
			<?php if (empty($removedOptions['sgpb-content-padding'])) :?>
				<div class="row form-group">
					<label for="content-padding" class="col-md-5 control-label sgpb-static-padding-top">
						<?php _e('Padding', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6"><input type="number" min="0" class="form-control sgpb-full-width-events" id="content-padding" name="sgpb-content-padding" value="<?php echo esc_html((int)$popupTypeObj->getOptionValue('sgpb-content-padding')); ?>"></div>
					<div class="col-md-1 sgpb-info-wrapper">
						<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
						<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
							<?php _e('Add some space, in pixels, around your popup content.', SG_POPUP_TEXT_DOMAIN);?>
						</span>
	 				</div>
				</div>
			<?php endif; ?>

			<?php if (empty($removedOptions['sgpb-popup-z-index'])) :?>
			<div class="row form-group">
				<label class="col-md-5 sgpb-static-padding-top" for="sgpb-popup-z-index">
					<?php _e('Popup z-index', SG_POPUP_TEXT_DOMAIN)  ?>:
				</label>
				<div class="col-md-6">
					<input type="number" min="1" name="sgpb-popup-z-index" id="sgpb-popup-z-index" class="form-control sgpb-full-width-events" value="<?php echo $popupTypeObj->getOptionValue('sgpb-popup-z-index'); ?>">
				</div>
				<div class="col-md-1 sgpb-info-wrapper">
					<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
					<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
						<?php _e('Increase or dicrease the value to set the priority of displaying the popup content in comparison of other elements on the page. The highest value of z-index is 2147483647.', SG_POPUP_TEXT_DOMAIN);?>
					</span>
				</div>
			</div>
			<?php endif; ?>

			<div class="row form-group">
				<label for="sgpb-popup-themes" class="col-md-5 control-label sgpb-static-padding-top">
					<?php _e('Theme', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<div class="col-md-7"><?php AdminHelper::createRadioButtons($defaultData['theme'], "sgpb-popup-themes", esc_html($popupTheme), true); ?></div>
			</div>
			<div class="row">
				<div class="col-md-10">
					<div class="themes-preview theme-preview-1" style="display: none;"></div>
					<div class="themes-preview theme-preview-2" style="display: none;"></div>
					<div class="themes-preview theme-preview-3" style="display: none;"></div>
					<div class="themes-preview theme-preview-4" style="display: none;"></div>
					<div class="themes-preview theme-preview-5" style="display: none;"></div>
					<div class="themes-preview theme-preview-6" style="display: none;"></div>
				</div>
			</div>
			<div class="row form-group sgpb-disable-border-wrapper<?php echo $hidePopupBorderOption ;?>">
				<label for="sgpb-force-rtl" class="col-md-5 control-label sgpb-static-padding-top">
					<?php _e('Disable popup border', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<div class="col-md-6">
					<input type="checkbox" id="sgpb-disable-border" name="sgpb-disable-border" <?php echo $popupTypeObj->getOptionValue('sgpb-disable-border', true); ?>>
				</div>
			</div>
			<!-- popup overlay start -->
			<?php if (empty($removedOptions['sgpb-enable-popup-overlay'])) :?>
				<div class="row form-group">
					<label for="sgpb-enable-popup-overlay" class="col-md-5 control-label sgpb-static-padding-top">
						<?php _e('Enable popup overlay', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-2<?php echo (!$enablePopupOverlay) ? ' sgpb-pro-options-row' : '' ;?>">
						<?php if ($enablePopupOverlay): ?>
							<input type="checkbox" id="sgpb-enable-popup-overlay" name="sgpb-enable-popup-overlay" class="js-checkbox-accordion" <?php echo $popupTypeObj->getOptionValue('sgpb-enable-popup-overlay'); ?> <?php echo (!empty($removedOptions['sgpb-enable-popup-overlay'])) ? ' disabled' : '' ?>>
						<?php else: ?>
							<input type="checkbox" id="sgpb-enable-popup-overlay" name="sgpb-enable-popup-overlay" checked disabled>
						<?php endif; ?>
						<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
						<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
							<?php _e('If this option is checked, the popup will appear with an overlay.', SG_POPUP_TEXT_DOMAIN);?>
						</span>
					</div>
					<?php if (!$enablePopupOverlay): ?>
						<div class="col-md-2 sgpb-pro-options-label-wrapper">
							<a href="<?php echo SG_POPUP_ADVANCED_CLOSING_URL;?>" target="_blank" class="btn btn-warning btn-xs sgpb-pro-label-sm sgpb-advanced-closing-pro-label"><?php _e('UNLOCK OPTION', SG_POPUP_TEXT_DOMAIN) ?></a>
						</div>
					<?php endif; ?>
				</div>
				<div class="sg-full-width">
					<div class="row form-group">
						<label for="sgpb-overlay-custom-class" class="col-md-5 control-label sgpb-static-padding-top sgpb-sub-option">
							<?php _e('Overlay custom css class', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-6">
							<input id="sgpb-overlay-custom-class" class="sgpb-full-width-events form-control" type="text" name="sgpb-overlay-custom-class" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-overlay-custom-class')); ?>" >
						</div>
						<div class="col-md-1 sgpb-info-wrapper">
							<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
							<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
								<?php _e('Add a custom class to the overlay for additional customization.', SG_POPUP_TEXT_DOMAIN);?>
							</span>
						</div>
					</div>
					<div class="row form-group">
						<label for="content-padding" class="col-md-5 control-label sgpb-static-padding-top sgpb-sub-option">
							<?php _e('Change color', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-6">
							<div class="sgpb-color-picker-wrapper sgpb-overlay-color">
								<input class="sgpb-color-picker sgpb-overlay-color" type="text" name="sgpb-overlay-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-overlay-color')); ?>" >
							</div>
						</div>
					</div>
					<div class="row form-group">
						<label class="col-md-5 control-label sgpb-sub-option">
							<?php _e('Opacity', SG_POPUP_TEXT_DOMAIN)?>:
						</label>
						<div class="col-md-6 sgpb-slider-wrapper">
							<div class="slider-wrapper">
								<?php $overlayOpacity = $popupTypeObj->getOptionValue('sgpb-overlay-opacity'); ?>
								<input type="text" name="sgpb-overlay-opacity" class="js-popup-overlay-opacity" value="<?php echo $overlayOpacity?>" rel="<?php echo $overlayOpacity?>">
								<div id="js-popup-overlay-opacity" data-init="false" class="display-box"></div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<!-- popup overlay end -->
			<div class="row form-group">
				<label for="content-custom-class" class="col-md-5 control-label sgpb-static-padding-top">
					<?php _e('Content custom css class', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<div class="col-md-6">
					<input type="text" class="sgpb-full-width-events form-control" id="content-custom-class" name="sgpb-content-custom-class" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-content-custom-class'))?>">
				</div>
				<div class="col-md-1 sgpb-info-wrapper">
					<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
					<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
						<?php _e('Add a custom class to the content for additional customization', SG_POPUP_TEXT_DOMAIN);?>.
					</span>
				</div>
			</div>
			<?php if (empty($removedOptions['sgpb-show-background'])) :?>
			<div class="row form-group">
				<label for="content-padding" class="col-md-10 control-label sgpb-static-padding-top">
					<?php _e('Background options', SG_POPUP_TEXT_DOMAIN)?>
				</label>
			</div>
			<div class="row form-group">
				<label for="sgpb-show-background" class="col-md-5 control-label sgpb-static-padding-top sgpb-sub-option">
					<?php _e('Show background', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<div class="col-md-6">
					<input type="checkbox" class="js-checkbox-accordion" id="sgpb-show-background" name="sgpb-show-background" <?php echo $popupTypeObj->getOptionValue('sgpb-show-background'); ?>>
				</div>
			</div>
			<div class="sg-full-width">
				<div class="row form-group">
					<label for="content-padding" class="col-md-5 control-label sgpb-static-padding-top sgpb-double-sub-option">
						<?php _e('Color', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6">
						<div class="sgpb-color-picker-wrapper">
							<input class="sgpb-color-picker" type="text" name="sgpb-background-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-background-color')); ?>" >
						</div>
					</div>
				</div>
				<div class="row form-group">
					<label for="content-padding" class="col-md-5 control-label sgpb-static-padding-top sgpb-double-sub-option">
						<?php _e('Opacity', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6 sgpb-slider-wrapper">
						<div class="slider-wrapper">
							<?php $contentOpacity = $popupTypeObj->getOptionValue('sgpb-content-opacity'); ?>
							<input type="text" name="sgpb-content-opacity" class="js-popup-content-opacity" value="<?php echo $contentOpacity; ?>" rel="<?php echo $contentOpacity?>">
							<div id="js-popup-content-opacity" data-init="false" class="display-box"></div>
						</div>
					</div>
				</div>
				<?php endif; ?>

				<?php if (empty($removedOptions['sgpb-background-image'])) :?>
				<div class="row">
					<label for="redirect-to-url" class="col-md-5 control-label sgpb-static-padding-top sgpb-double-sub-option">
						<?php _e('Image', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6 form-group">
						<div class="row">
							<div>
								<div class="sgpb-button-image-uploader-wrapper">
									<input class="sg-hide" id="js-background-upload-image" type="text" size="36" name="sgpb-background-image" value="<?php echo (esc_attr($popupTypeObj->getOptionValue('sgpb-background-image'))) ? esc_attr($popupTypeObj->getOptionValue('sgpb-background-image')) : '' ; ?>" autocomplete="off">
								</div>
							</div>

							<div class="col-md-12 form-group">
								<div class="sgpb-show-background-image-container">
									<span class="sgpb-no-image">(<?php _e('No image selected', SG_POPUP_TEXT_DOMAIN);?>)</span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4 sgpb-background-change-image-wrapper">
								<input id="js-background-upload-image-button" class="btn btn-sm btn-default" type="button" value="<?php _e('Change image', SG_POPUP_TEXT_DOMAIN);?>">
							</div>
							<div class="col-md-4 js-sgpb-remove-background-image<?php echo (!$popupTypeObj->getOptionValue('sgpb-background-image')) ? ' sg-hide' : '';?>">
								<input id="js-background-upload-image-remove-button" class="btn btn-sm btn-danger" type="button" value="<?php _e('Remove', SG_POPUP_TEXT_DOMAIN);?>">
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>

				<?php if (empty($removedOptions['sgpb-background-image-mode'])) :?>
				<div class="row form-group">
					<label for="content-padding" class="col-md-5 control-label sgpb-static-padding-top sgpb-double-sub-option">
						<?php _e('Mode', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6 form-group">
						<?php echo AdminHelper::createSelectBox($defaultData['backroundImageModes'], $popupTypeObj->getOptionValue('sgpb-background-image-mode'), array('name' => 'sgpb-background-image-mode', 'class'=>'js-sg-select2')); ?>
					</div>
					<div class="col-md-1 sgpb-info-wrapper">
						<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
						<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
							<?php _e('Choose how the background image will be displayed with your content.', SG_POPUP_TEXT_DOMAIN);?>.
						</span>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
