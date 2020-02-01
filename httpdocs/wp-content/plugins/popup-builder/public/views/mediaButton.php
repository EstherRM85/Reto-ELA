<?php
	namespace sgpb;
	$defaultData = \ConfigDataHelper::defaultData();
	$excludePostId = 0;
	if (isset($_GET['post']) && !empty($_GET['post'])) {
		$excludePostId = $_GET['post'];
	}
	$excludedPopups = array($excludePostId);
	$allPopups = AdminHelper::getPopupsIdAndTitle($excludedPopups);
?>

<div class="sgpb-hide" style="display: none">
	<div id="sgpb-hidden-media-popup" class="sgpb-wrapper">
		<div class="row">
			<div class="col-sm-10">
				<label><?php _e('Insert the [shortcode]', SG_POPUP_TEXT_DOMAIN)?></label>
			</div>
			<div class="col-sm-2">
				<img class="sgpb-add-subscriber-popup-close-btn sgpb-close-media-popup-js" src="<?php echo SG_POPUP_IMG_URL.'subscribers_close.png'; ?>" width='15px'>
			</div>
		</div>
		<div class="sgpb-insert-popup-title-border"></div>
		<div class="row">
			<div class="col-sm-4">
				<label><?php _e('Select popup', SG_POPUP_TEXT_DOMAIN)?></label>:
			</div>
			<div class="col-sm-8">
				<?php echo AdminHelper::createSelectBox($allPopups, '', array('class'=>'sgpb-insert-popup')); ?>
			</div>
		</div>
		<?php if (get_post_type() != SG_POPUP_POST_TYPE): ?>
			<div class="row sgpb-static-padding-top">
				<div class="col-sm-4">
					<label><?php _e('Select event', SG_POPUP_TEXT_DOMAIN)?></label>:
				</div>
				<div class="col-sm-8">
					<?php echo AdminHelper::createSelectBox($defaultData['popupInsertEventTypes'], '', array('class'=>'sgpb-insert-popup-event')); ?>
				</div>
			</div>
		<?php endif;?>
		<div class="row sgpb-static-padding-top">
			<div class="col-sm-3">
				<input type="button" class="btn btn-sm btn-primary sgpb-insert-popup-js sgpb-insert-popup-btns" value="<?php _e('Insert', SG_POPUP_TEXT_DOMAIN)?>">
			</div>
			<div class="col-sm-3">
				<input type="button" class="btn btn-sm btn-default sgpb-close-media-popup-js sgpb-insert-popup-btns" value="<?php _e('Cancel', SG_POPUP_TEXT_DOMAIN)?>">
			</div>
		</div>
	</div>
</div>


<style type="text/css">
	.select2-container {
		z-index: 9999999999;
	}
</style>
