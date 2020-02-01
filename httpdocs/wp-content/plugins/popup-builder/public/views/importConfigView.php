<?php
use sgpb\AdminHelper;
use sgpb\SubscriptionPopup;

$fileContent = AdminHelper::getFileFromURL($fileURL);
$csvFileArray = array_map('str_getcsv', file($fileURL));
$ourFieldsArgs = array(
    'class' => 'js-sg-select2 sgpb-our-fields-keys'
);

$formData =  array('' => 'Select Field') + AdminHelper::getSubscriptionColumnsById($formId);
?>
<div class="sgpb-wrapper">
    <div class="sgpb-import-settings-wrapper">
        <div class="row form-group">
            <div class="col-md-12 sgpb-file-settings-label">
                <?php _e('Match Your Fields', SG_POPUP_TEXT_DOMAIN); ?>
            </div>
        </div>
        <hr>
        <div class="row form-group">
            <div class="col-md-6 sgpb-file-settings-label sgpb-file-settings-available-wrapper">
                <?php _e('Available fields', SG_POPUP_TEXT_DOMAIN); ?>
            </div>
            <div class="col-md-6 sgpb-file-settings-label">
                <?php _e('Our list fields', SG_POPUP_TEXT_DOMAIN); ?>
            </div>
        </div>
        <hr>
        <?php foreach($csvFileArray[0] as $index => $current): ?>
            <?php if (empty($current) || $current == 'popup'): ?>
                <?php continue; ?>
            <?php endif; ?>
            <div class="row form-group">
                <div class="col-md-6 sgpb-label-config-left"><?php echo $current; ?></div>
                <div class="col-md-6">
                    <?php
                    $ourFieldsArgs['data-index'] = $index;
                    echo AdminHelper::createSelectBox($formData, '', $ourFieldsArgs);
                    ?>
                </div>
            </div>
        <?php endforeach;?>
        <div class="row">
            <div class="col-md-6">
                <input type="button" value="<?php _e('Save', SG_POPUP_TEXT_DOMAIN); ?>" class="btn btn-sm btn-success sgpb-subscriber-popup-btns sgpb-save-subscriber" data-ajaxnonce="popupBuilderAjaxNonce">
            </div>
            <div class="col-md-6">
                <input type="button" value="<?php _e('Cancel', SG_POPUP_TEXT_DOMAIN); ?>" class="btn btn-sm btn-default sgpb-add-subscriber-popup-close-btn-js sgpb-subscriber-popup-btns sgpb-subscriber-popup-close-btn-js" data-ajaxnonce="popupBuilderAjaxNonce">
            </div>
        </div>
        <input type="hidden" class="sgpb-to-import-popup-id" value="<?php echo esc_attr($formId)?>">
        <input type="hidden" class="sgpb-imported-file-url" value="<?php echo esc_attr($fileURL)?>">
    </div>
</div>