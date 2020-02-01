<?php
use sgpb\AdminHelper;
use sgpb\MultipleChoiceButton;
use sgpb\PopupBuilderActivePackage;
$removedOptions = $popupTypeObj->getRemoveOptions();
$defaultData = ConfigDataHelper::defaultData();
$defaultAnimation = esc_attr($popupTypeObj->getOptionValue('sgpb-open-animation-effect'));
if (!empty($_GET['sgpb_type'])) {
	if (defined('SGPB_POPUP_TYPE_RECENT_SALES')) {
		if ($_GET['sgpb_type'] == defined('SGPB_POPUP_TYPE_RECENT_SALES') && !$popupTypeObj->getOptionValue('sgpb-open-animation-effect')) {
			$defaultAnimation = 'sgpb-fadeIn';
		}
	}
}
$autoClose = PopupBuilderActivePackage::canUseOption('sgpb-auto-close');
$closeAfterPageScroll = PopupBuilderActivePackage::canUseOption('sgpb-close-after-page-scroll');
$afterXpagesUseOption = PopupBuilderActivePackage::canUseOption('sgpb-show-popup-after-x-pages');
if (!empty($removedOptions['content-copy-to-clipboard'])) {
	if (isset($defaultData['contentClickOptions']['fields'])) {
		// where 2 is copy to clipboard index
		unset($defaultData['contentClickOptions']['fields'][2]);
	}
}
?>
<div class="sgpb-wrapper">
	<div class="row">
		<div class="col-md-8">
			<?php if(empty($removedOptions['sgpb-content-click'])): ?>
				<div class="row form-group">
					<label for="content-click" class="col-md-5 control-label sgpb-static-padding-top">
						<?php _e('Action on popup content click', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6"><input type="checkbox" id="content-click" name="sgpb-content-click" class="js-checkbox-accordion" <?php echo $popupTypeObj->getOptionValue('sgpb-content-click'); ?>></div>
				</div>

				<div class="sg-full-width">
					<?php
					$multipleChoiceButton = new MultipleChoiceButton($defaultData['contentClickOptions'], $popupTypeObj->getOptionValue('sgpb-content-click-behavior'));
					echo $multipleChoiceButton;
					?>
					<div class="sg-hide sg-full-width" id="content-click-redirect">
						<div class="row form-group">
							<label for="redirect-to-url" class="col-md-5 control-label sgpb-static-padding-top sgpb-double-sub-option">
								<?php _e('URL', SG_POPUP_TEXT_DOMAIN)?>:
							</label>
							<div class="col-md-6"><input name="sgpb-click-redirect-to-url" id="redirect-to-url" class="form-control sgpb-full-width-events" placeholder="http://" value="<?php echo $popupTypeObj->getOptionValue('sgpb-click-redirect-to-url'); ?>"></div>
						</div>
						<div class="row form-group">
							<label for="redirect-to-url" class="col-md-5 control-label sgpb-static-padding-top sgpb-double-sub-option">
								<?php _e('Redirect to new tab', SG_POPUP_TEXT_DOMAIN)?>:
							</label>
							<div class="col-md-6"><input type="checkbox" name="sgpb-redirect-to-new-tab" <?php echo $popupTypeObj->getOptionValue('sgpb-redirect-to-new-tab');?>></div>
						</div>
					</div>
					<div class="sg-hide sg-full-width" id="content-copy-to-clipboard">
						<div class="row form-group">
							<label for="sgpb-copy-to-clipboard-text" class="col-md-5 control-label sgpb-static-padding-top sgpb-double-sub-option">
								<?php _e('Text', SG_POPUP_TEXT_DOMAIN)?>:
							</label>
							<div class="col-md-6"><input name="sgpb-copy-to-clipboard-text" id="sgpb-copy-to-clipboard-text" class="form-control sgpb-full-width-events" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-copy-to-clipboard-text')); ?>"></div>
						</div>
						<div class="row form-group">
							<label for="sgpb-copy-to-clipboard-close-popup" class="col-md-5 control-label sgpb-static-padding-top sgpb-double-sub-option">
								<?php _e('Close popup', SG_POPUP_TEXT_DOMAIN)?>:
							</label>
							<div class="col-md-6"><input type="checkbox" name="sgpb-copy-to-clipboard-close-popup" id="sgpb-copy-to-clipboard-close-popup" <?php echo $popupTypeObj->getOptionValue('sgpb-copy-to-clipboard-close-popup'); ?>></div>
						</div>
						<div class="row form-group">
							<label for="sgpb-copy-to-clipboard-alert" class="col-md-5 control-label sgpb-static-padding-top sgpb-double-sub-option">
								<?php _e('Show alert', SG_POPUP_TEXT_DOMAIN)?>:
							</label>
							<div class="col-md-6">
								<input type="checkbox" id="sgpb-copy-to-clipboard-alert" class="js-checkbox-accordion" name="sgpb-copy-to-clipboard-alert" <?php echo $popupTypeObj->getOptionValue('sgpb-copy-to-clipboard-alert'); ?>>
							</div>
						</div>
						<div class="sg-full-width form-group">
							<div class="row">
								<label for="col-md-5 sgpb-copy-to-clipboard-message" class="col-md-5 control-label sgpb-static-padding-top sgpb-double-sub-option">
									<?php _e('Message', SG_POPUP_TEXT_DOMAIN)?>:
								</label>
								<div class="col-md-6">
									<input type="text" id="sgpb-copy-to-clipboard-message" class="form-control sgpb-full-width-events " name="sgpb-copy-to-clipboard-message" value="<?php echo $popupTypeObj->getOptionValue('sgpb-copy-to-clipboard-message'); ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<!-- this often start -->
			<?php if (empty($removedOptions['sgpb-show-popup-same-user'])): ?>
			<div class="row form-group">
				<label for="sgpb-show-popup-same-user" class="col-md-5 control-label sgpb-static-padding-top">
					<?php _e('Popup showing limitation', SG_POPUP_TEXT_DOMAIN)?>:
				</label>
				<div class="col-md-6">
					<input type="checkbox" id="sgpb-show-popup-same-user" name="sgpb-show-popup-same-user" class="js-checkbox-accordion" <?php echo $popupTypeObj->getOptionValue('sgpb-show-popup-same-user'); ?>>
					<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
					<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
						<?php _e('Estimate the popup showing frequency to the same user.', SG_POPUP_TEXT_DOMAIN);?>
					</span>
				</div>
			</div>
			<div class="sg-full-width">
				<div class="row form-group">
					<label for="sgpb-show-popup-same-user-count" class="col-md-5 control-label sgpb-static-padding-top sgpb-sub-option">
						<?php _e('Popup showing count', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6">
						<input type="number" min="1" disabled required id="sgpb-show-popup-same-user-count" class="sgpb-full-width-events form-control" name="sgpb-show-popup-same-user-count" value="<?php echo $popupTypeObj->getOptionValue('sgpb-show-popup-same-user-count'); ?>" placeholder="e.g.: 1">
					</div>
					<div class="col-md-1 sgpb-info-wrapper">
						<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
						<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
							<?php _e('Select how many times the popup will be shown for the same user.', SG_POPUP_TEXT_DOMAIN);?>
						</span>
					</div>
				</div>
				<div class="row form-group">
					<label for="sgpb-show-popup-same-user-expiry" class="col-md-5 control-label sgpb-static-padding-top sgpb-sub-option">
						<?php _e('Popup showing expiry', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6">
						<input type="number" min="0" disabled required id="sgpb-show-popup-same-user-expiry" class="sgpb-full-width-events form-control" name="sgpb-show-popup-same-user-expiry" value="<?php echo $popupTypeObj->getOptionValue('sgpb-show-popup-same-user-expiry'); ?>" placeholder="e.g.: 1">
					</div>
					<div class="col-md-1 sgpb-info-wrapper">
						<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
						<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
							<?php _e('Select the count of the days after which the popup will be shown to the same user, or set the value "0" if you want to save cookies by session.', SG_POPUP_TEXT_DOMAIN);?>
						</span>
					</div>
				</div>
				<div class="row form-group">
					<label for="sgpb-show-popup-same-user-page-level" class="col-md-5 control-label sgpb-static-padding-top sgpb-sub-option">
						<?php _e('Apply option on each page', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6">
						<input type="checkbox" disabled id="sgpb-show-popup-same-user-page-level" name="sgpb-show-popup-same-user-page-level" <?php echo $popupTypeObj->getOptionValue('sgpb-show-popup-same-user-page-level'); ?>>
						<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
						<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
							<?php _e('If this option is checked the popup showing limitation will be saved for the current page. Otherwise, the limitation will refer site wide, and the popup will be shown for specific times on each page selected.The previously specified count of days will be reset every time you check/uncheck this option.', SG_POPUP_TEXT_DOMAIN);?>
						</span>
					</div>
				</div>
			</div>
			<?php endif; ?>
			<!-- this often end -->
			<div class="row form-group">
				<label class="col-md-5" for="open-sound">
					<?php _e('Popup opening sound', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="checkbox" id="open-sound" class="js-checkbox-accordion" name="sgpb-open-sound" <?php echo $popupTypeObj->getOptionValue('sgpb-open-sound'); ?>>
					<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
					<span class="infoSelectRepeat samefontStyle sgpb-info-text">
						<?php _e('If this option is enabled the popup will appear with a sound. The sound option is not available on mobile devices, as there are restrictions on sound auto-play options for mobile devices', SG_POPUP_TEXT_DOMAIN)?>.
					</span>
				</div>
			</div>
			<div class="sg-full-width" style="display: inline-block;">
				<div class="row form-group">
					<div class="col-md-5"></div>
					<div class="col-md-6">
						<input type="text" id="js-sound-open-url" readonly class="form-control input-sm sgpb-full-width-events" name="sgpb-sound-url" value="<?php echo $popupTypeObj->getOptionValue('sgpb-sound-url'); ?>">
					</div>
					<div class="col-md-1">
						<span class="dashicons dashicons-controls-volumeon js-preview-sound"></span>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-5"></div>
					<div class="col-md-4">
						<input id="js-upload-open-sound-button" class="btn btn-sm btn-default sgpb-change-sound-btn" type="button" value="<?php _e('Change sound', SG_POPUP_TEXT_DOMAIN)?>">
					</div>
					<div class="col-md-2 align-right">
						<input type="button" data-default-song="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-sound-url')); ?>" id="js-reset-to-default-song" class="btn btn-sm btn-danger sgpb-reset-sound-btn" value="<?php _e('Reset', SG_POPUP_TEXT_DOMAIN);?>">
					</div>
				</div>
			</div>
			<!-- opening animation -->
			<div class="row form-group">
				<label class="col-md-5" for="open-animation">
					<?php _e('Popup opening animation', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="checkbox" id="open-animation" class="js-checkbox-accordion" name="sgpb-open-animation" <?php echo $popupTypeObj->getOptionValue('sgpb-open-animation'); ?>>
				</div>
			</div>
			<div class="sg-full-width form-group">
				<div class="row">
					<label class="col-md-5 sgpb-align-with-select2 sgpb-sub-option">
						<?php _e('Type', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-6 sgpb-select2-input-styles">
						<?php echo AdminHelper::createSelectBox($defaultData['openAnimationEfects'], $defaultAnimation, array('name' => 'sgpb-open-animation-effect', 'class'=>'js-sg-select2 sgpb-open-animation-effects')); ?>
					</div>
					<div class="col-md-1">
						<span class="sgpb-preview-animation"></span>
					</div>
				</div>
				<div class="row">
					<label class="col-md-5 sgpb-static-padding-top sgpb-sub-option" for="sgpb-open-animation-speed">
						<?php _e('Speed', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-6">
						<input type="number" min="0" step="0.1" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-open-animation-speed'))?>" class="js-sgpb-reset-default-value sgpb-full-width-events form-control" id="sgpb-open-animation-speed" name="sgpb-open-animation-speed" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-open-animation-speed'))?>">
					</div>
					<div class="col-md-1 sgpb-relative-position">
						<span class="sgpb-restriction-unit">
							<?php _e('Second(s)', SG_POPUP_TEXT_DOMAIN)?>
						</span>
						<div id="js-open-animation-effect"></div>
					</div>
				</div>
			</div>
			<!-- opening animation end -->
			<!-- closing animation -->
			<div class="row form-group">
				<label class="col-md-5" for="open-animation">
					<?php _e('Popup closing animation', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="checkbox" id="close-animation" class="js-checkbox-accordion" name="sgpb-close-animation" <?php echo $popupTypeObj->getOptionValue('sgpb-close-animation'); ?>>
				</div>
			</div>
			<div class="sg-full-width form-group">
				<div class="row">
					<label class="col-md-5 sgpb-align-with-select2 sgpb-sub-option">
						<?php _e('Type', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-6 sgpb-select2-input-styles">
						<?php echo AdminHelper::createSelectBox($defaultData['closeAnimationEfects'], esc_attr($popupTypeObj->getOptionValue('sgpb-close-animation-effect')), array('name' => 'sgpb-close-animation-effect', 'class'=>'js-sg-select2 sgpb-close-animation-effects')); ?>
					</div>
					<div class="col-md-1">
						<span class="sgpb-preview-close-animation"></span>
					</div>
				</div>
				<div class="row">
					<label class="col-md-5 sgpb-static-padding-top sgpb-sub-option" for="sgpb-close-animation-speed">
						<?php _e('Speed', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-6">
						<input type="number" min="0" step="0.1" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-close-animation-speed'))?>" class="js-sgpb-reset-default-value sgpb-full-width-events form-control" id="sgpb-close-animation-speed" name="sgpb-close-animation-speed" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-close-animation-speed'))?>">
					</div>
					<div class="col-md-1 sgpb-relative-position">
						<span class="sgpb-restriction-unit">
							<?php _e('Second(s)', SG_POPUP_TEXT_DOMAIN)?>
						</span>
						<div id="js-close-animation-effect"></div>
					</div>
				</div>
			</div>
			<!-- closing animation end -->
			<div class="row form-group">
				<label class="col-md-5 sgpb-static-padding-top" for="popup-fixed">
					<?php _e('Popup location', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="checkbox" id="popup-fixed" class="js-checkbox-accordion" name="sgpb-popup-fixed" <?php echo $popupTypeObj->getOptionValue('sgpb-popup-fixed'); ?>>
				</div>
			</div>
			<div class="row sg-full-width form-group">
				<div class="col-md-5"></div>
				<div class="col-md-6">
					<div class="fixed-wrapper">
						<div class="js-fixed-position-style" id="fixed-position1" data-sgvalue="1"></div>
						<div class="js-fixed-position-style" id="fixed-position2" data-sgvalue="2"></div>
						<div class="js-fixed-position-style" id="fixed-position3" data-sgvalue="3"></div>
						<div class="js-fixed-position-style" id="fixed-position4" data-sgvalue="4"></div>
						<div class="js-fixed-position-style" id="fixed-position5" data-sgvalue="5"></div>
						<div class="js-fixed-position-style" id="fixed-position6" data-sgvalue="6"></div>
						<div class="js-fixed-position-style" id="fixed-position7" data-sgvalue="7"></div>
						<div class="js-fixed-position-style" id="fixed-position8" data-sgvalue="8"></div>
						<div class="js-fixed-position-style" id="fixed-position9" data-sgvalue="9"></div>
					</div>
					<input type="hidden" name="sgpb-popup-fixed-position" class="js-fixed-position" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-popup-fixed-position'));?>">
				</div>
			</div>
			<?php if (empty($removedOptions['sgpb-disable-page-scrolling'])): ?>
				<div class="row form-group">
					<label class="col-md-5 sgpb-static-padding-top" for="disable-page-scrolling">
					<?php _e('Disable page scrolling', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-6">
						<input type="checkbox" id="disable-page-scrolling" name="sgpb-disable-page-scrolling" <?php echo $popupTypeObj->getOptionValue('sgpb-disable-page-scrolling'); ?>>
						<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
						<span class="infoSelectRepeat samefontStyle sgpb-info-text">
							<?php _e('If this option is checked, the page won\'t scroll until the popup is open', SG_POPUP_TEXT_DOMAIN)?>.
						</span>
					</div>
				</div>
			<?php endif; ?>
			<?php if (empty($removedOptions['sgpb-enable-content-scrolling'])): ?>
				<div class="row form-group">
					<label for="content-scrolling" class="col-md-5 control-label sgpb-static-padding-top">
						<?php _e('Enable content scrolling', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6">
						<input type="checkbox" id="content-scrolling" name="sgpb-enable-content-scrolling" <?php echo $popupTypeObj->getOptionValue('sgpb-enable-content-scrolling'); ?>>
						<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
						<span class="infoSelectRepeat samefontStyle sgpb-info-text">
							<?php _e('If the content is larger than the specified dimensions, then the content will be scrollable', SG_POPUP_TEXT_DOMAIN)?>.
						</span>
					</div>
				</div>
			<?php endif; ?>
			<?php if (empty($removedOptions['sgpb-auto-close'])): ?>
				<div class="row form-group">
					<label class="col-md-5 sgpb-static-padding-top" for="auto-close">
						<?php _e('Auto close popup', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-2<?php echo (!$autoClose) ? ' sgpb-pro-options-row' : '' ;?>">
						<?php if ($autoClose): ?>
							<input type="checkbox" id="auto-close" class="js-checkbox-accordion" name="sgpb-auto-close" <?php echo $popupTypeObj->getOptionValue('sgpb-auto-close'); ?>>
						<?php else: ?>
							<input type="checkbox" id="auto-close" name="sgpb-auto-close" disabled>
						<?php endif; ?>
					</div>
					<?php if (!$autoClose): ?>
					<div class="col-md-2 sgpb-pro-options-label-wrapper">
						<a href="<?php echo SG_POPUP_ADVANCED_CLOSING_URL;?>" target="_blank" class="btn btn-warning btn-xs sgpb-pro-label-sm sgpb-advanced-closing-pro-label"><?php _e('UNLOCK OPTION', SG_POPUP_TEXT_DOMAIN) ?></a>
					</div>
				<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ($autoClose && empty($removedOptions['sgpb-auto-close-time'])): ?>
				<div class="sg-full-width">
					<div class="row form-group">
						<label class="col-md-5 sgpb-static-padding-top sgpb-sub-option">
							<?php _e('Auto close after', SG_POPUP_TEXT_DOMAIN); ?>:
						</label>
						<div class="col-md-6">
							<input type="number" min="0" class="form-control sgpb-full-width-events" name="sgpb-auto-close-time" value="<?php echo $popupTypeObj->getOptionValue('sgpb-auto-close-time'); ?>">
						</div>
						<div class="col-md-1">
							<span class="sgpb-restriction-unit">
								<?php _e('Second(s)', SG_POPUP_TEXT_DOMAIN) ?>
							</span>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<?php if (empty($removedOptions['sgpb-close-after-page-scroll'])): ?>
				<div class="row form-group">
					<label class="col-md-5 sgpb-static-padding-top" for="sgpb-close-after-page-scroll">
						<?php _e('Close popup after the page scroll', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-2<?php echo (!$closeAfterPageScroll) ? ' sgpb-pro-options-row' : '' ;?>">
						<?php if ($closeAfterPageScroll): ?>
							<input type="checkbox" id="sgpb-close-after-page-scroll" class="" name="sgpb-close-after-page-scroll" <?php echo $popupTypeObj->getOptionValue('sgpb-close-after-page-scroll'); ?>>
						<?php else: ?>
							<input type="checkbox" id="sgpb-close-after-page-scroll" name="sgpb-close-after-page-scroll" disabled>
						<?php endif; ?>
					</div>
					<?php if (!$closeAfterPageScroll): ?>
						<div class="col-md-2 sgpb-pro-options-label-wrapper">
							<a href="<?php echo SG_POPUP_ADVANCED_CLOSING_URL;?>" target="_blank" class="btn btn-warning btn-xs sgpb-pro-label-sm sgpb-advanced-closing-pro-label"><?php _e('UNLOCK OPTION', SG_POPUP_TEXT_DOMAIN) ?></a>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if (empty($removedOptions['sgpb-reopen-after-form-submission'])): ?>
				<div class="row form-group">
					<label class="col-md-5" for="reopen-after-form-submission">
						<?php _e('Reopen after form submission', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-6">
						<input type="checkbox" id="reopen-after-form-submission"  name="sgpb-reopen-after-form-submission" <?php echo $popupTypeObj->getOptionValue('sgpb-reopen-after-form-submission'); ?>>
						<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
						<span class="infoSelectRepeat samefontStyle sgpb-info-text">
							<?php _e('If this option is enabled, the popup will reopen after the form submission', SG_POPUP_TEXT_DOMAIN)?>.
						</span>
					</div>
				</div>
			<?php endif; ?>

			<?php if (empty($removedOptions['sgpb-popup-order'])): ?>
			<div class="row form-group">
				<label class="col-md-5 sgpb-static-padding-top" for="sgpb-popup-order">
					<?php _e('Popup order', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="number" min="0" name="sgpb-popup-order" id="sgpb-popup-order" class="form-control sgpb-full-width-events" value="<?php echo (int)$popupTypeObj->getOptionValue('sgpb-popup-order'); ?>">
				</div>
				<div class="col-md-1 sgpb-info-wrapper">
					<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
					<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
						<?php _e('Select the priority number for your popup to appear on the page, along with other popups. The popup with a lower order number will be behind the others with a higher order number.', SG_POPUP_TEXT_DOMAIN)  ?>
					</span>
				</div>
			</div>
			<?php endif; ?>

			<?php if (empty($removedOptions['sgpb-popup-delay'])): ?>
				<div class="row form-group">
					<label class="col-md-5 sgpb-static-padding-top" for="sgpb-popup-delay">
						<?php _e('Custom event delay', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-6">
						<input type="number" min="0" name="sgpb-popup-delay" id="sgpb-popup-delay" class="form-control sgpb-full-width-events" value="<?php echo (int)$popupTypeObj->getOptionValue('sgpb-popup-delay'); ?>">
					</div>
					<div class="col-md-1 sgpb-info-wrapper">
						<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
						<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
							<?php _e('You can add an opening delay for the popup, in seconds. This will refer to custom events, like:
										Shortcodes, custom CSS classes, HTML attributes, or JS called custom events', SG_POPUP_TEXT_DOMAIN)?>.
						</span>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
