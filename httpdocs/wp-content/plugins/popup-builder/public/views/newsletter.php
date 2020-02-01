<?php
	require_once SG_POPUP_CLASSES_POPUPS_PATH.'SubscriptionPopup.php';
	use sgpb\SubscriptionPopup;
	use sgpb\AdminHelper;
	$adminEmail = get_option('admin_email');
	$subscriptionIdTitle = SubscriptionPopup::getAllSubscriptionForms();

	$subscriptionSelectBox = AdminHelper::createSelectBox(
		$subscriptionIdTitle,
		'',
		array(
			'name' => 'sgpb-subscription-form',
			'class' => 'js-sg-select2 js-sg-newsletter-forms js-sg-select2 js-select-basic',
			'autocomplete' => 'off'
		)
	);

	reset($subscriptionIdTitle);
	$defaultSelectedPopupId = key($subscriptionIdTitle);
	$subscriptionPopupsCustomFields = AdminHelper::getCustomFormFieldsByPopupId($defaultSelectedPopupId);
?>
<div class="sgpb-wrapper sgpb-newsletter">
	<div class="row">
		<div class="col-md-6">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="postbox-container-2" class="postbox-container">
					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<div class="postbox popup-builder-special-postbox">
							<div class="handlediv js-special-title" title="Click to toggle"><br></div>
							<h3 class="hndle ui-sortable-handle js-special-title">
								<span><?php _e('Newsletter Settings', SG_POPUP_TEXT_DOMAIN); ?></span>
							</h3>
							<div class="sgpb-options-content">
								<div class="sgpb-alert sgpb-newsletter-notice sgpb-alert-info fade in sgpb-hide">
									<span><?php _e('You will receive an email notification after all emails are sent', SG_POPUP_TEXT_DOMAIN); ?>.</span>
								</div>
								<div class="row form-group">
									<label class="col-md-6 sgpb-label-align-center-sm">
										<?php _e('Choose the popup', SG_POPUP_TEXT_DOMAIN); ?>
									</label>
									<div class="col-md-6">
										<?php echo $subscriptionSelectBox; ?>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="sg-hide-element sgpb-newsletter-validation sgpb-newsletter-popup-error"><?php _e('Select a popup', SG_POPUP_TEXT_DOMAIN); ?>.</div>
									</div>
								</div>
								<div class="row form-group">
									<label class="col-md-6 sgpb-label-align-center-sm" for="sgpb-emails-in-flow">
										<?php _e('Emails to send in one flow per 1 minute', SG_POPUP_TEXT_DOMAIN); ?>
									</label>
									<div class="col-md-6">
										<input type="number" id="sgpb-emails-in-flow" class="sgpb-emails-in-flow form-control input-sm" value="<?php echo SGPB_FILTER_REPEAT_INTERVAL; ?>">
									</div>
								</div>
								<div class="row form-group">
									<label class="col-md-6 sgpb-label-align-center-sm" for="sgpb-newsletter-from-email">
										<?php _e('From email', SG_POPUP_TEXT_DOMAIN); ?>
									</label>
									<div class="col-md-6">
										<input type="email" id="sgpb-newsletter-from-email" class="sgpb-newsletter-from-email form-control input-sm" value="<?php echo $adminEmail; ?>">
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="sg-hide-element sgpb-newsletter-validation sgpb-newsletter-from-email-error"><?php _e('Please enter a valid email', SG_POPUP_TEXT_DOMAIN); ?>.</div>
									</div>
								</div>
								<div class="row form-group">
									<label class="col-md-6 sgpb-label-align-center-sm" for="sgpb-newsletter-subject">
										<?php _e('Email\'s subject', SG_POPUP_TEXT_DOMAIN); ?>
									</label>
									<div class="col-md-6">
										<input type="email" id="sgpb-newsletter-subject" class="sgpb-newsletter-subject form-control input-sm" value="<?php _e('Your subject here', SG_POPUP_TEXT_DOMAIN); ?>">
									</div>
								</div>
								<div class="row form-group">
									<label class="col-md-12 sgpb-label-align-center-sm">
										<?php _e('Enter newsletter email template below', SG_POPUP_TEXT_DOMAIN); ?>
									</label>
								</div>
								<div class="row form-group">
									<div class="col-md-12">
										<?php
											$editorId = 'sgpb-newsletter-text';
											$content = '<p>Hi [First name] [Last name],</p>
											<p>Super excited to have you on board, we know you’ll just love us.</p>
											<p>Sincerely,</p>
											<p>[Blog name]</p>
											<p>[Unsubscribe title="Unsubscribe"]</p>';
											$settings = array(
												'wpautop' => false,
												'tinymce' => array(
													'width' => '100%'
												),
												'textarea_rows' => '18',
												'media_buttons' => true
											);
											wp_editor($content, $editorId, $settings);
										?>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-12">
										<input type="submit" class="btn btn-primary btn-sm js-send-newsletter" value="<?php _e('Send newsletter', SG_POPUP_TEXT_DOMAIN)?>">
										<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" width="20px" class="sgpb-hide sgpb-js-newsletter-spinner">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="postbox-container-2" class="postbox-container">
					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<div class="postbox popup-builder-special-postbox">
							<div class="handlediv js-special-title" title="Click to toggle"><br></div>
							<h3 class="hndle ui-sortable-handle js-special-title">
								<span><?php _e('Newsletter Shortcodes', SG_POPUP_TEXT_DOMAIN); ?></span>
							</h3>
							<div class="sgpb-options-content">
								<div class="row form-group">
									<div class="col-md-12">
										<label>
											<?php _e('Default shortcodes', SG_POPUP_TEXT_DOMAIN); ?>:
										</label>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-6">
										<code><?php _e('[First name]', SG_POPUP_TEXT_DOMAIN); ?></code>
									</div>
									<div class="col-md-6">
										<?php _e('Subscriber First name', SG_POPUP_TEXT_DOMAIN); ?>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-6">
										<code><?php _e('[Last name]', SG_POPUP_TEXT_DOMAIN); ?></code>
									</div>
									<div class="col-md-6">
										<?php _e('Subscriber Last name', SG_POPUP_TEXT_DOMAIN); ?>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-6">
										<code><?php _e('[Blog name]', SG_POPUP_TEXT_DOMAIN); ?></code>
									</div>
									<div class="col-md-6">
										<?php _e('Your blog name', SG_POPUP_TEXT_DOMAIN); ?>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-6">
										<code><?php _e('[User name]', SG_POPUP_TEXT_DOMAIN); ?></code>
									</div>
									<div class="col-md-6">
										<?php _e('Your user name', SG_POPUP_TEXT_DOMAIN); ?>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-6">
										<code><?php _e('[Unsubscribe title="Unsubscribe"]', SG_POPUP_TEXT_DOMAIN); ?></code>
									</div>
									<div class="col-md-6">
										<?php _e('Unsubscribe', SG_POPUP_TEXT_DOMAIN); ?>
									</div>
								</div>
								<?php if (!empty($subscriptionPopupsCustomFields)) :?>
								<div class="row">
									<div class="col-md-6">
										<label>
											<?php _e('Custom fields\' shortcodes', SG_POPUP_TEXT_DOMAIN); ?>:
										</label>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-5">
									</div>
									<div class="col-md-6">
										<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" width="20px" class="sgpb-hide sgpb-js-newsletter-custom-fields-spinner">
									</div>
								</div>
								<div class="sgpb-newsletter-custom-fields-wrapper">
									<?php
									foreach ($subscriptionPopupsCustomFields as $field) {
										if (empty($field)) {
											continue;
										}
										?>

										<div class="row form-group">
											<div class="col-md-6">
												<code>[<?php echo @$field['fieldName'];?>]</code>
											</div>
											<div class="col-md-6">
												<?php echo @$field['fieldName']; ?>
											</div>
										</div>
										<?php
									}
									?>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
