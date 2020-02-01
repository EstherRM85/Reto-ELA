<?php
	use sgpb\AdminHelper;
	use sgpb\MultipleChoiceButton;
	$defaultData = ConfigDataHelper::defaultData();
	$removedOptions = $popupTypeObj->getRemoveOptions();
	$multipleChoiceButton = new MultipleChoiceButton($defaultData['popupDimensions'], $popupTypeObj->getOptionValue('sgpb-popup-dimension-mode'));
	$subOptionClass = ' sgpb-sub-option';
	if (!empty($removedOptions['sgpb-popup-dimension-mode'])) {
		$subOptionClass = '';
	}
?>
<div class="sgpb-wrapper">
	<div class="row">
		<div class="col-md-8">
			<div class="sgpb-wrapper">
				<?php echo (!empty($removedOptions['sgpb-popup-dimension-mode'])) ? '' : $multipleChoiceButton; ?>
			</div>
			<div class="<?php echo (!empty($removedOptions['sgpb-popup-dimension-mode'])) ? '' : 'sg-hide '; ?>sg-full-width" id="custom-dimension-wrapper">
				<div class="row form-group">
					<label for="width" class="col-md-5 control-label sgpb-static-padding-top<?php echo $subOptionClass; ?>"><?php _e('Width', SG_POPUP_TEXT_DOMAIN)  ?>:</label>
					<div class="col-md-6"><input type="text" id="width" class="form-control sgpb-full-width-events" name="sgpb-width" placeholder="<?php _e('Ex: 100, 100px or 100%', SG_POPUP_TEXT_DOMAIN)?>" pattern = "\d+(([px]+|%)|)" title="<?php _e('It must be number  + px or %', SG_POPUP_TEXT_DOMAIN)  ?>" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-width')) ?>"></div>
				</div>
				<div class="row form-group">
					<label for="height" class="col-md-5 control-label sgpb-static-padding-top<?php echo $subOptionClass; ?>">
						<?php _e('Height', SG_POPUP_TEXT_DOMAIN)  ?>:
					</label>
					<div class="col-md-6"><input type="text" id="height" class="form-control sgpb-full-width-events" name="sgpb-height" placeholder="<?php _e('Ex: 100, 100px or 100%', SG_POPUP_TEXT_DOMAIN)?>" pattern = "\d+(([px]+|%)|)" title="<?php _e('It must be number  + px or %', SG_POPUP_TEXT_DOMAIN)  ?>" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-height')) ?>"></div>
				</div>
			</div>
			<div class="sg-hide sg-full-width" id="responsive-dimension-wrapper">
				<div class="row form-group">
					<label class="col-md-5<?php echo $subOptionClass; ?>" for="max-height"><?php _e('Size', SG_POPUP_TEXT_DOMAIN)  ?>:</label>
					<div class="col-md-6"><?php echo AdminHelper::createSelectBox($defaultData['responsiveDimensions'], esc_html($popupTypeObj->getOptionValue('sgpb-responsive-dimension-measure')), array('name' => 'sgpb-responsive-dimension-measure', 'class'=>'js-sg-select2 sgpb-responsive-mode-change-js')); ?></div>
				</div>
			</div>
			<div class="row form-group">
				<label for="max-width" class="col-md-5 control-label sgpb-static-padding-top"><?php _e('Max width', SG_POPUP_TEXT_DOMAIN)  ?>:</label>
				<div class="col-md-6"><input type="text" id="max-width" class="form-control sgpb-full-width-events" name="sgpb-max-width" placeholder="<?php _e('Ex: 100, 100px or 100%', SG_POPUP_TEXT_DOMAIN)?>" pattern = "\d+(([px]+|%)|)" title="<?php _e('It must be number  + px or %', SG_POPUP_TEXT_DOMAIN)  ?>" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-max-width')) ?>"></div>
			</div>
			<div class="row form-group">
				<label class="col-md-5 sgpb-static-padding-top" for="max-height"><?php _e('Max height', SG_POPUP_TEXT_DOMAIN)  ?>:</label>
				<div class="col-md-6"><input type="text" id="max-height" class="form-control sgpb-full-width-events" name="sgpb-max-height" placeholder="<?php _e('Ex: 100, 100px or 100%', SG_POPUP_TEXT_DOMAIN)?>" pattern = "\d+(([px]+|%)|)" title="<?php _e('It must be number  + px or %', SG_POPUP_TEXT_DOMAIN)  ?>" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-max-height')) ?>"></div>
			</div>
			<div class="row form-group">
				<label class="col-md-5 sgpb-static-padding-top" for="min-width"><?php _e('Min width', SG_POPUP_TEXT_DOMAIN)  ?>:</label>
				<div class="col-md-6"><input type="text" id="min-width" class="form-control sgpb-full-width-events" name="sgpb-min-width" placeholder="<?php _e('Ex: 100, 100px or 100%', SG_POPUP_TEXT_DOMAIN)?>" pattern = "\d+(([px]+|%)|)" title="<?php _e('It must be number  + px or %', SG_POPUP_TEXT_DOMAIN)  ?>" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-min-width')) ?>"></div>
			</div>
			<div class="row form-group">
				<label class="col-md-5 sgpb-static-padding-top" for="min-height"><?php _e('Min height', SG_POPUP_TEXT_DOMAIN)  ?>:</label>
				<div class="col-md-6"><input type="text" id="min-height" class="form-control sgpb-full-width-events" name="sgpb-min-height" placeholder="<?php _e('Ex: 100, 100px or 100%', SG_POPUP_TEXT_DOMAIN)?>" pattern = "\d+(([px]+|%)|)" title="<?php _e('It must be number  + px or %', SG_POPUP_TEXT_DOMAIN)  ?>" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-min-height')) ?>"></div>
			</div>
		</div>
	</div>
</div>

