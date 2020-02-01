<?php
namespace sgpb;
use sgpb\PopupBuilderActivePackage;

$defaultData = \ConfigDataHelper::defaultData();
$required = '';
if ($popupTypeObj->getOptionValue('sgpb-schedule-status')) {
	$required = 'required';
}
$conditionsCanBeUsed = PopupBuilderActivePackage::canUseSection('popupOtherConditionsSection');

?>
<div class="sgpb-wrapper">
	<div class="row">
		<div class="col-md-8">
			<div class="row form-group">
				<label class="col-md-5" for="schedule-status">
					<?php _e('Schedule', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<input type="checkbox" id="schedule-status" class="js-checkbox-accordion" name="sgpb-schedule-status"  <?php echo $popupTypeObj->getOptionValue('sgpb-schedule-status'); ?>>
					<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
					<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
						<?php _e('Select the day(s) of the week and estimate specific time during which the popup will regularly be shown.', SG_POPUP_TEXT_DOMAIN)  ?>
					</span>
				</div>
			</div>
			<div class="sg-full-width">
			<div class="row form-group">
				<label class="col-md-5 sgpb-sub-option">
					<?php _e('Every', SG_POPUP_TEXT_DOMAIN); ?>:
				</label>
				<div class="col-md-6">
					<?php echo AdminHelper::createSelectBox($defaultData['weekDaysArray'], $popupTypeObj->getOptionValue('sgpb-schedule-weeks'), array('name'=>'sgpb-schedule-weeks[]', 'class' => 'schedule-start-selectbox sg-margin0 js-select-basic js-sg-select2', 'multiple'=> 'multiple', 'size'=>7, $required => $required));?>
				</div>
			</div>

			<div class="row form-group">
				<div class="col-md-5"></div>
				<div class="col-md-6">
					<div class="row form-group">
						<div class="col-md-12">
							<div class="row form-group">
								<div class="col-md-2 sgpb-schedule-from-start">
									<span class="sgpb-restriction-unit"><?php _e('From', SG_POPUP_TEXT_DOMAIN); ?>:</span>
								</div>
								<div class="col-md-5 sgpb-schedule-from-start">
									<input id="sgpb-schedule-start-time" type="text" class="sg-time-picker sg-time-picker-style form-control sgpb-full-width-events" name="sgpb-schedule-start-time" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-schedule-start-time'));?>">
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 sgpb-schedule-from-start">
									<span class="sgpb-restriction-unit"><?php _e('To', SG_POPUP_TEXT_DOMAIN); ?>:</span>
								</div>
								<div class="col-md-5 sgpb-schedule-from-start">
									<input id="sgpb-schedule-end-time" type="text" class="sg-time-picker sg-time-picker-style form-control sgpb-full-width-events" name="sgpb-schedule-end-time" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-schedule-end-time'));?>">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			</div>
			<div class="row form-group">
				<label class="col-md-5" for="sgpb-popup-timer-status">
					<?php _e('Show popup in date range', SG_POPUP_TEXT_DOMAIN) ?>:
				</label>
				<div class="col-md-7">
					<input type="checkbox" name="sgpb-popup-timer-status" id="sgpb-popup-timer-status" class="js-checkbox-accordion" <?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-popup-timer-status'));?>>
					<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
					<span class="infoSelectRepeat samefontStyle sgpb-info-text" style="display: none;">
						<?php _e('Select a time period during which the popup will appear on your site. Specify the date and the hours for the start and end of popup showing.', SG_POPUP_TEXT_DOMAIN)  ?>
					</span>
				</div>
			</div>
			<div class="sg-full-width">
				<div class="row form-group">
					<label class="col-md-5 sgpb-sub-option" for="sgpb-popup-start-timer">
						<?php _e('Start date', SG_POPUP_TEXT_DOMAIN) ?>:
					</label>
					<div class="col-md-7">
						<input type="text" class="popup-start-timer form-control sgpb-full-width-events" id="sgpb-popup-start-timer" name="sgpb-popup-start-timer" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-popup-start-timer'));?>">
					</div>
				</div>
				<div class="row form-group">
					<label class="col-md-5 sgpb-sub-option" for="sgpb-popup-end-timer">
						<?php _e('End date', SG_POPUP_TEXT_DOMAIN) ?>:
					</label>
					<div class="col-md-7">
						<input type="text" class="popup-start-timer form-control sgpb-full-width-events" id="sgpb-popup-end-timer" name="sgpb-popup-end-timer" value="<?php echo esc_attr($popupTypeObj->getOptionValue('sgpb-popup-end-timer'));?>">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	#ui-datepicker-div {
		z-index: 9999 !important;
	}
</style>

<?php if (!$conditionsCanBeUsed): ?>
	<div class="sgpb-other-pro-options">
		<div class="sgpb-wrapper">
			<div class="row">
				<div class="col-md-12">

				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
