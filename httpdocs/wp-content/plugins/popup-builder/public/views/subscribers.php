<?php

require_once(SG_POPUP_CLASSES_PATH.'/dataTable/Subscribers.php');
require_once(SG_POPUP_CLASSES_POPUPS_PATH.'SubscriptionPopup.php');
require_once(SG_POPUP_HELPERS_PATH.'AdminHelper.php');
use sgpb\AdminHelper;
use sgpb\SubscriptionPopup;

$subscribers = SubscriptionPopup::getSubscribersCount();
$allData = SubscriptionPopup::getAllSubscriptionForms();

$fistElement = array_values($allData);
if (isset($fistElement[0])) {
	$fistElement = $fistElement[0];
}

$subscribersSelectbox = AdminHelper::createSelectBox(
	$allData,
	$fistElement,
	array(
		'name' => 'sgpb-add-subscriber-input',
		'class' => 'js-sg-select2 js-sg-newsletter-forms sgpb-add-subscriber-input js-select-basic',
		'multiple' => 'multiple',
		'autocomplete' => 'off'
	)
);

$importSubscribersSelectbox = AdminHelper::createSelectBox(
	$allData,
	$fistElement,
	array(
		'name' => 'sgpb-import-subscriber-input',
		'class' => 'js-sg-select2 js-sg-import-list sgpb-add-subscriber-input js-select-basic',
		'autocomplete' => 'off'
	)
);
$isEmpty = empty($allData);
$disable = '';
if ($isEmpty) {
	$disable = 'disabled';
}
?>
<div class="wrap subscribers-wrapper">
	<div class="headers-wrapper">
		<h1 class="wp-heading-inline"><?php _e('Subscribers', SG_POPUP_TEXT_DOMAIN)?></h1>
		<a href="javascript:void(0)" class="page-title-action sgpb-import-subscriber"><?php _e('Import', SG_POPUP_TEXT_DOMAIN)?></a>
		<a href="javascript:void(0)" class="page-title-action sgpb-export-subscriber"><?php _e('Export', SG_POPUP_TEXT_DOMAIN)?></a>
		<a href="javascript:void(0)" class="page-title-action sgpb-add-subscriber"><?php _e('Add new', SG_POPUP_TEXT_DOMAIN)?></a>
	</div>
	<div class="sgpb-subs-delete-button-wrapper">
		<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" alt="gif" class="sgpb-subscribers-remove-spinner js-sg-spinner sg-hide-element js-sg-import-gif" width="20px">
		<input type="button" value="<?php _e('Delete subscriber(s)', SG_POPUP_TEXT_DOMAIN)?>" class="sg-subs-delete-button button-primary" data-ajaxNonce="<?php echo SG_AJAX_NONCE;?>">
	</div>
	<?php
		$table = new Subscribers();
		echo $table;
	?>
</div>

<!-- -->
<div id="sgpb-import-subscribers" class="sgpb-subscribers-popup">
	<div class="sgpb-add-new-subscribers-wrapper sgpb-import-settings-wrapper">
		<div class="sgpb-wrapper">
			<div class="sgpb-imges-wrapper">
				<div class="col-sm-1 sgpb-add-subscriber-header-spinner-column">
					<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" alt="gif" class="sgpb-subscribers-add-spinner js-sg-spinner js-sgpb-add-spinner sg-hide-element js-sg-import-gif" width="20px">
				</div>
				<img src="<?php echo SG_POPUP_IMG_URL.'subscribers_close.png'; ?>" alt="gif" class="sgpb-add-subscriber-popup-close-btn sgpb-import-subscribers-close sgpb-subscriber-popup-close-btn-js" width="20px">
			</div>
			<div class="sgpb-add-subscriber-content-wrapper">
			<div class="row">
				<div class="col-sm-7 sgpb-add-subscriber-header-column sgpb-clear">
					<h4>
						<?php _e('Select subscription(s):', SG_POPUP_TEXT_DOMAIN); ?>
					</h4>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-12">
					<?php echo $importSubscribersSelectbox; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-7">
					<h4 for="js-import-subscriber-file-url">
						<?php _e('Import Subscribers from file:', SG_POPUP_TEXT_DOMAIN); ?>
					</h4>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sm-9">
					<input class="form-control input-sm" id="js-import-subscriber-file-url" type="text" size="36" name="js-import-subscriber-file-url" value="" required="">
				</div>
				<div class="col-sm-3">
					<input id="js-import-subscriber-button" placeholder="<?php _e('No file selected', SG_POPUP_TEXT_DOMAIN); ?>" class="btn btn-primary btn-sm sgpb-subscriber-popup-btns" type="button" value="<?php _e('Select file', SG_POPUP_TEXT_DOMAIN); ?>">
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<input type="button" value="<?php _e('Import', SG_POPUP_TEXT_DOMAIN); ?>" class="btn btn-sm btn-success sgpb-subscriber-popup-btns sgpb-import-subscriber-to-list" data-ajaxnonce="popupBuilderAjaxNonce" <?php echo $disable; ?>>
				</div>
				<div class="col-md-6">
					<input type="button" value="<?php _e('Cancel', SG_POPUP_TEXT_DOMAIN); ?>" class="btn btn-sm btn-default sgpb-add-subscriber-popup-close-btn-js sgpb-subscriber-popup-btns sgpb-subscriber-popup-close-btn-js" data-ajaxnonce="popupBuilderAjaxNonce">
				</div>
			</div>
			</div>
		</div>
	</div>
</div>

<!-- add subscribers popup start -->
<div id="sgpb-add-new-subscriber">
	<div class="sgpb-add-new-subscribers-wrapper">
		<div class="sgpb-wrapper">
			<div class="row">
				<div class="col-sm-7 sgpb-add-subscriber-header-column">
					<h4>
						<?php _e('Select subscription(s):', SG_POPUP_TEXT_DOMAIN)?>
					</h4>
				</div>
				<div class="col-sm-1 sgpb-add-subscriber-header-spinner-column">
					<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" alt="gif" class="sgpb-subscribers-add-spinner js-sg-spinner js-sgpb-add-spinner sg-hide-element js-sg-import-gif" width="20px">
				</div>
				<img src="<?php echo SG_POPUP_IMG_URL.'subscribers_close.png'; ?>" alt="gif" class="sgpb-add-subscriber-popup-close-btn sgpb-add-subscriber-popup-close-btn-js" width="20px">
			</div>
			<div class="row sgpb-subscriber-adding-error sg-hide-element">
				<div class="col-md-12">
					<div class="alert alert-danger fade in alert-dismissable">
					    <?php _e('Error occurred: could not add subscriber.', SG_POPUP_TEXT_DOMAIN)?>
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-12">
					<?php echo $subscribersSelectbox; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="sg-hide-element sgpb-subscription-error"><?php _e('Subscription is not selected', SG_POPUP_TEXT_DOMAIN)?>.</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-12">
					<input type="email" autocomplete="off" name="subs-email" class="form-control sgpb-add-subscribers-email sgpb-add-subscriber-input input-sm" placeholder="<?php _e('Email', SG_POPUP_TEXT_DOMAIN)?>">
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="sg-hide-element sgpb-email-error"><?php _e('Invalid email address', SG_POPUP_TEXT_DOMAIN)?>.</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-12">
					<input type="text" autocomplete="off" name="subs-firstName" class="form-control sgpb-add-subscribers-first-name sgpb-add-subscriber-input input-sm" placeholder="<?php _e('First name', SG_POPUP_TEXT_DOMAIN)?>">
				</div>
			</div>
			<div class="row form-group">
				<div class="col-md-12">
					<input type="text" autocomplete="off" name="subs-firstName" class="form-control sgpb-add-subscribers-last-name sgpb-add-subscriber-input input-sm" placeholder="<?php _e('Last name', SG_POPUP_TEXT_DOMAIN)?>">
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<input type="button" value="<?php _e('Add to list', SG_POPUP_TEXT_DOMAIN)?>" class="btn btn-sm btn-success sgpb-add-subscriber-popup-btns sgpb-add-to-list-js" data-ajaxNonce="<?php echo SG_AJAX_NONCE;?>">
				</div>
				<div class="col-md-6">
					<input type="button" value="<?php _e('Cancel', SG_POPUP_TEXT_DOMAIN)?>" class="btn btn-sm btn-default sgpb-add-subscriber-popup-close-btn-js sgpb-add-subscriber-popup-btns" data-ajaxNonce="<?php echo SG_AJAX_NONCE;?>">
				</div>
			</div>
		</div>
	</div>
</div>
<!-- add subscribers popup end -->

<div id="sgpb-subscriber-data">
	<div class="sgpb-subscriber-data-wrapper">
		<div class="sgpb-wrapper">
			<div class="row">
				<div class="col-sm-8 sgpb-add-subscriber-header-column">
					<h4>
						<?php _e('Subscriber submitted data', SG_POPUP_TEXT_DOMAIN)?>
					</h4>
				</div>
				<div class="col-sm-1 sgpb-add-subscriber-header-spinner-column">
					<img src="<?php echo SG_POPUP_IMG_URL.'ajaxSpinner.gif'; ?>" alt="gif" class="sgpb-subscribers-add-spinner js-sg-spinner js-sgpb-add-spinner sg-hide-element js-sg-import-gif" width="20px">
				</div>
				<img src="<?php echo SG_POPUP_IMG_URL.'subscribers_close.png'; ?>" alt="gif" class="sgpb-add-subscriber-popup-close-btn sgpb-subscriber-data-popup-close-btn-js" width="20px">
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	.select2-container {
		z-index: 9999;
	}
</style>
