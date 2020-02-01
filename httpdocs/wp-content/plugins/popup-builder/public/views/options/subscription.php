<?php
	use sgpb\AdminHelper;
	use sgpb\MultipleChoiceButton;
	use sgpb\Functions;
	$defaultData = ConfigDataHelper::defaultData();
	$placeholderColor = $popupTypeObj->getOptionValue('sgpb-subs-text-placeholder-color');
	$popupId = 0;

	if (!empty($_GET['post'])) {
		$popupId = (int)$_GET['post'];
	}
	$subscriptionSubPopups = $popupTypeObj->getPopupsIdAndTitle();
	$successPopup = $popupTypeObj->getOptionValue('sgpb-subs-success-popup');

	// for old popups
	if (function_exists('sgpb\sgpGetCorrectPopupId')) {
		$successPopup = sgpb\sgpGetCorrectPopupId($successPopup);
	}
	$forceRtlClass = '';
	$forceRtl = $popupTypeObj->getOptionValue('sgpb-force-rtl');
	if ($forceRtl) {
		$forceRtlClass = ' sgpb-forms-preview-direction';
	}
?>

<div class="sgpb-wrapper">
	<div class="row">
		<div class="col-md-7">
			<!-- form background options start -->
			<div class="row form-group">
				<label class="col-md-12 control-label">
					<?php _e('Form background options', SG_POPUP_TEXT_DOMAIN); ?>
				</label>
			</div>
			<div class="row form-group">
				<label class="col-md-6 control-label sgpb-sub-option">
					<?php _e('Form background color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<div class="sgpb-color-picker-wrapper">
						<input class="sgpb-color-picker js-subs-color-picker" data-subs-rel="sgpb-subscription-admin-wrapper" data-style-type="background-color" type="text" name="sgpb-subs-form-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-form-bg-color')); ?>" autocomplete="off">
					</div>
				</div>
			</div>
			<div class="row form-group">
				<label for="content-padding" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option">
					<?php _e('Form background opacity', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-5 sgpb-slider-wrapper">
					<div class="slider-wrapper">
						<input type="text" name="sgpb-subs-form-bg-opacity" class="js-subs-bg-opacity" value="<?php echo $popupTypeObj->getOptionValue('sgpb-subs-form-bg-opacity'); ?>" rel="<?php echo $popupTypeObj->getOptionValue('sgpb-subs-form-bg-opacity'); ?>">
						<div id="js-subs-bg-opacity" data-init="false" class="display-box"></div>
					</div>
				</div>
			</div>
			<div class="row form-group">
				<label for="sgpb-subs-form-padding" class="col-md-6 control-label sgpb-sub-option">
					<?php _e('Form padding', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-5">
					<div class="sgpb-color-picker-wrapper">
						<input type="number" min="0" data-default="<?php echo esc_attr($popupTypeObj->getOptionDefaultValue('sgpb-subs-form-padding'))?>" class="form-control js-sgpb-form-padding sgpb-full-width-events" id="sgpb-subs-form-padding" name="sgpb-subs-form-padding" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-form-padding'))?>" autocomplete="off">
					</div>
				</div>
				<div class="col-md-1">
					<span class="sgpb-restriction-unit">px</span>
				</div>
			</div>
			<!-- form background options end -->
			<div class="row form-group">
				<label for="subs-email-placeholder" class="col-md-6 control-label sgpb-static-padding-top">
					<?php _e('Email placeholder', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="text" name="sgpb-subs-email-placeholder" id="subs-email-placeholder" class="form-control js-subs-field-placeholder sgpb-full-width-events" data-subs-rel="js-subs-email-input" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-email-placeholder')); ?>">
				</div>
			</div>

			<!-- GDPR checkbox start -->
			<div class="row form-group">
				<label for="subs-gdpr-status" class="col-md-6 control-label sgpb-static-padding-top">
					<?php _e('Enable GDPR', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="checkbox" class="js-checkbox-accordion js-checkbox-field-status" id="subs-gdpr-status" data-subs-field-wrapper="js-gdpr-wrapper" name="sgpb-subs-gdpr-status" <?php echo $popupTypeObj->getOptionValue('sgpb-subs-gdpr-status'); ?>>
				</div>
			</div>
			<div class="sg-full-width">
				<div class="row form-group">
					<label for="sgpb-subs-gdpr-label" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option sgpb-gdpr-label">
						<?php _e('Label', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-6">
						<input type="text" name="sgpb-subs-gdpr-label" id="sgpb-subs-gdpr-label" class="form-control js-subs-field-placeholder sgpb-full-width-events" data-subs-rel="js-subs-gdpr-label" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-gdpr-label')); ?>">
					</div>
				</div>
				<div class="row form-group">
					<label for="sgpb-subs-gdpr-text" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option sgpb-gdpr-label">
						<?php _e('Confirmation text', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-6">
						<textarea name="sgpb-subs-gdpr-text" id="sgpb-subs-gdpr-text" class="form-control"><?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-gdpr-text')); ?></textarea>
					</div>
				</div>
			</div>
			<!-- GDPR checkbox end -->

			<!-- First name start -->
			<div class="row form-group">
				<label for="subs-first-name-status" class="col-md-6 control-label">
					<?php _e('First name', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="checkbox" class="js-checkbox-accordion js-checkbox-field-status" id="subs-first-name-status" data-subs-field-wrapper="js-first-name-wrapper" name="sgpb-subs-first-name-status" <?php echo $popupTypeObj->getOptionValue('sgpb-subs-first-name-status'); ?>>
				</div>
			</div>
			<div class="sg-full-width">
				<div class="row form-group">
					<label for="subs-first-placeholder" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option">
						<?php _e('Placeholder', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-6">
						<input type="text" name="sgpb-subs-first-placeholder" id="subs-first-placeholder" class="form-control js-subs-field-placeholder sgpb-full-width-events" data-subs-rel="js-subs-first-name-input" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-first-placeholder')); ?>">
					</div>
				</div>
				<div class="row">
					<label for="subs-first-name-required" class="col-md-6 control-label sgpb-sub-option">
						<?php _e('Required field', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-6">
						<input type="checkbox" name="sgpb-subs-first-name-required" id="subs-first-name-required" <?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-first-name-required')); ?>>
					</div>
				</div>
			</div>

			<!-- First name end -->

			<!-- Last name start -->

			<div class="row form-group">
				<label for="subs-last-name-status" class="col-md-6 control-label">
					<?php _e('Last name', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="checkbox" class="js-checkbox-accordion js-checkbox-field-status" id="subs-last-name-status" data-subs-field-wrapper="js-last-name-wrapper" name="sgpb-subs-last-name-status" <?php echo $popupTypeObj->getOptionValue('sgpb-subs-last-name-status'); ?>>
				</div>
			</div>
			<div class="sg-full-width">
				<div class="row form-group">
					<label for="subs-last-placeholder" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option">
						<?php _e('Placeholder', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-6">
						<input type="text" name="sgpb-subs-last-placeholder" id="subs-last-placeholder" class="form-control js-subs-field-placeholder sgpb-full-width-events" data-subs-rel="js-subs-last-name-input" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-last-placeholder')); ?>">
					</div>
				</div>
				<div class="row">
					<label for="subs-last-name-required" class="col-md-6 control-label sgpb-sub-option form-group">
						<?php _e('Required field', SG_POPUP_TEXT_DOMAIN); ?>:
					</label>
					<div class="col-md-6">
						<input type="checkbox" name="sgpb-subs-last-name-required" id="subs-last-name-required" <?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-last-name-required')); ?>>
					</div>
				</div>
			</div>

			<!-- Last name end -->

			<div class="row form-group">
				<label for="subs-validation-message" class="col-md-6 control-label sgpb-static-padding-top">
					<?php _e('Required field message', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="text" name="sgpb-subs-validation-message" id="subs-validation-message" class="form-control sgpb-full-width-events" maxlength="90" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-validation-message')); ?>">
				</div>
			</div>

			<!-- Input styles start -->

			<div class="row form-group">
				<label class="col-md-12 control-label sgpb-static-padding-top">
					<?php _e('Inputs\' style', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
			</div>
			<div class="row form-group">
				<label for="subs-text-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option">
					<?php _e('Width', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="text" class="form-control js-subs-dimension sgpb-full-width-events" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="width" name="sgpb-subs-text-width" id="subs-text-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-width')); ?>">
				</div>
			</div>
			<div class="row form-group">
				<label for="subs-text-height" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option">
					<?php _e('Height', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input class="form-control js-subs-dimension sgpb-full-width-events" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="height" type="text" name="sgpb-subs-text-height" id="subs-text-height" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-height')); ?>">
				</div>
			</div>
			<div class="row form-group">
				<label for="subs-text-border-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option">
					<?php _e('Border width', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input class="form-control js-subs-dimension sgpb-full-width-events" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="border-width" type="text" name="sgpb-subs-text-border-width" id="subs-text-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-border-width')); ?>">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-6 control-label sgpb-sub-option">
					<?php _e('Background color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<div class="sgpb-color-picker-wrapper">
						<input class="sgpb-color-picker js-subs-color-picker" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="background-color" type="text" name="sgpb-subs-text-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-bg-color')); ?>" >
					</div>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-6 control-label sgpb-sub-option">
					<?php _e('Border color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<div class="sgpb-color-picker-wrapper">
						<input class="sgpb-color-picker js-subs-color-picker" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="border-color" type="text" name="sgpb-subs-text-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-border-color')); ?>" >
					</div>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-6 control-label sgpb-sub-option">
					<?php _e('Text color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<div class="sgpb-color-picker-wrapper">
						<input class="sgpb-color-picker js-subs-color-picker" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="color" type="text" name="sgpb-subs-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-text-color')); ?>" >
					</div>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-6 control-label sgpb-sub-option">
					<?php _e('Placeholder color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<div class="sgpb-color-picker-wrapper">
						<input class="sgpb-color-picker js-subs-color-picker sgpb-full-width-events" data-field-type="input" data-subs-rel="js-subs-text-inputs" data-style-type="placeholder" type="text" name="sgpb-subs-text-placeholder-color" value="<?php echo esc_html($placeholderColor); ?>" >
					</div>
				</div>
			</div>

			<!-- Input styles end -->

			<!-- Submit styles start -->

			<div class="row form-group">
				<label class="col-md-12 control-label">
					<?php _e('Submit button styles', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
			</div>
			<div class="row form-group">
				<label for="subs-btn-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option">
					<?php _e('Width', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input class="form-control js-subs-dimension sgpb-full-width-events" data-subs-rel="js-subs-submit-btn" data-field-type="submit" data-style-type="width" type="text" name="sgpb-subs-btn-width" id="subs-btn-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-width')); ?>">
				</div>
			</div>
			<div class="row form-group">
				<label for="subs-btn-height" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option">
					<?php _e('Height', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input class="form-control js-subs-dimension sgpb-full-width-events" data-subs-rel="js-subs-submit-btn" data-field-type="submit" data-style-type="height" type="text" name="sgpb-subs-btn-height" id="subs-btn-height" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-height')); ?>">
				</div>
			</div>
			<div class="row form-group">
				<label for="sgpb-subs-btn-border-width" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option">
					<?php _e('Border width', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input class="form-control js-subs-dimension sgpb-full-width-events" data-field-type="submit" data-subs-rel="js-subs-submit-btn" data-style-type="border-width" type="text" name="sgpb-subs-btn-border-width" id="sgpb-subs-btn-border-width" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-border-width')); ?>">
				</div>
			</div>
			<div class="row form-group">
				<label for="sgpb-subs-btn-border-radius" class="col-md-6 control-label sgpb-sub-option">
					<?php _e('Border radius', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input class="form-control js-subs-dimension sgpb-full-width-events" data-subs-rel="js-subs-submit-btn" data-field-type="submit" data-style-type="border-radius" type="text" name="sgpb-subs-btn-border-radius" id="sgpb-subs-btn-border-radius" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-border-radius')); ?>">
				</div>
			</div>
			<div class="row form-group">
				<label for="sgpb-subs-btn-border-color" class="col-md-6 control-label sgpb-sub-option">
					<?php _e('Border color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<div class="sgpb-color-picker-wrapper">
						<input id="sgpb-subs-btn-border-color" class="sgpb-color-picker js-subs-color-picker" data-field-type="submit" data-subs-rel="js-subs-submit-btn" data-style-type="border-color" type="text" name="sgpb-subs-btn-border-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-border-color')); ?>" >
					</div>
				</div>
			</div>
			<div class="row form-group">
				<label for="subs-btn-title" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option">
					<?php _e('Title', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="text" name="sgpb-subs-btn-title" id="subs-btn-title" class="form-control js-subs-btn-title sgpb-full-width-events" data-field-type="submit" data-subs-rel="js-subs-submit-btn" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-title')); ?>">
				</div>
			</div>
			<div class="row form-group">
				<label for="btn-progress-title" class="col-md-6 control-label sgpb-static-padding-top sgpb-sub-option">
					<?php _e('Title (in progress)', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="text" name="sgpb-subs-btn-progress-title" id="btn-progress-title" class="form-control sgpb-full-width-events" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-progress-title')); ?>">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-6 control-label sgpb-sub-option">
					<?php _e('Background color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<div class="sgpb-color-picker-wrapper">
						<input class="sgpb-color-picker js-subs-color-picker" data-field-type="submit" data-subs-rel="js-subs-submit-btn" data-style-type="background-color" type="text" name="sgpb-subs-btn-bg-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-bg-color')); ?>" >
					</div>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-6 control-label sgpb-sub-option">
					<?php _e('Text color', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<div class="sgpb-color-picker-wrapper">
						<input class="sgpb-color-picker js-subs-color-picker" data-field-type="submit" data-subs-rel="js-subs-submit-btn" data-style-type="color" type="text" name="sgpb-subs-btn-text-color" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-subs-btn-text-color')); ?>" >
					</div>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-6 control-label sgpb-static-padding-top">
					<?php _e('Error message', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="text" class="form-control sgpb-full-width-events" name="sgpb-subs-error-message" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-error-message')); ?>">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-6 control-label sgpb-static-padding-top">
					<?php _e('Invalid email message', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="text" class="form-control sgpb-full-width-events" name="sgpb-subs-invalid-message" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-invalid-message')); ?>">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-6 control-label sgpb-static-padding-top" for="sgpb-subs-show-form-to-top">
					<?php _e('Show form on the Top', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="checkbox" id="sgpb-subs-show-form-to-top" name="sgpb-subs-show-form-to-top" <?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-show-form-to-top')); ?>>
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-6 control-label sgpb-static-padding-top" for="sgpb-subs-hide-subs-users">
					<?php _e('Hide for already subscribed users', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="checkbox" id="sgpb-subs-hide-subs-users" name="sgpb-subs-hide-subs-users" <?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-hide-subs-users')); ?>>
				</div>
			</div>

			<!-- submit styles end -->

			<div class="row form-group">
				<label class="col-md-12 control-label sgpb-static-padding-top">
					<?php _e('After successful subscription', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
			</div>
			<?php
			$multipleChoiceButton = new MultipleChoiceButton($defaultData['subscriptionSuccessBehavior'], $popupTypeObj->getOptionValue('sgpb-subs-success-behavior'));
			echo $multipleChoiceButton;
			?>
			<div class="sg-hide sg-full-width" id="subs-show-success-message">
				<div class="row form-group">
					<label for="sgpb-subs-success-message" class="col-md-6 control-label sgpb-double-sub-option">
						<?php _e('Success message', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6"><input name="sgpb-subs-success-message" id="sgpb-subs-success-message" class="form-control sgpb-full-width-events" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-subs-success-message')); ?>"></div>
				</div>
			</div>
			<div class="sg-hide sg-full-width" id="subs-redirect-to-URL">
				<div class="row form-group">
					<label for="sgpb-subs-success-redirect-URL" class="col-md-6 control-label sgpb-double-sub-option">
						<?php _e('Redirect URL', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6"><input type="url" name="sgpb-subs-success-redirect-URL" id="sgpb-subs-success-redirect-URL" placeholder="https://www.example.com" class="form-control sgpb-full-width-events" value="<?php echo $popupTypeObj->getOptionValue('sgpb-subs-success-redirect-URL'); ?>"></div>
				</div>
				<div class="row form-group">
					<label for="subs-success-redirect-new-tab" class="col-md-6 control-label sgpb-double-sub-option">
						<?php _e('Redirect to new tab', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6"><input type="checkbox" name="sgpb-subs-success-redirect-new-tab" id="subs-success-redirect-new-tab" placeholder="https://www.example.com" <?php echo $popupTypeObj->getOptionValue('sgpb-subs-success-redirect-new-tab'); ?>></div>
				</div>
			</div>
			<div class="sg-hide sg-full-width" id="subs-open-popup">
				<div class="row form-group">
					<label for="sgpb-subs-success-redirect-URL" class="col-md-6 control-label sgpb-double-sub-option">
						<?php _e('Select popup', SG_POPUP_TEXT_DOMAIN)?>:
					</label>
					<div class="col-md-6">
						<?php echo AdminHelper::createSelectBox($subscriptionSubPopups, $successPopup, array('name' => 'sgpb-subs-success-popup', 'class'=>'js-sg-select2 sgpb-full-width-events')); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-5">
			<div>
				<h1 class="sgpb-align-center"><?php _e('Live preview', SG_POPUP_TEXT_DOMAIN);?></h1>
				<?php
				$popupTypeObj->setSubsFormData(@$_GET['post']);
				$formData = $popupTypeObj->createFormFieldsData();
				?>
				<div class="sgpb-subs-form-<?php echo $popupId; ?> sgpb-subscription-admin-wrapper<?php echo $forceRtlClass; ?>">
					<?php echo Functions::renderForm($formData); ?>
				</div>
				<?php
				$styleData = array(
					'placeholderColor' => $placeholderColor
				);
				echo $popupTypeObj->getFormCustomStyles($styleData)
				?>
				<div style="max-width: 300px;margin: 0 auto;">
					<span class="sgpb-align-center"><?php _e('Get the <a href="'.SG_POPUP_SUBSCRIPTION_PLUS_URL.'">Subscription Plus</a> extension to add or customize the form fields.', SG_POPUP_TEXT_DOMAIN);?></span>
				</div>
			</div>
		</div>
	</div>
</div>
