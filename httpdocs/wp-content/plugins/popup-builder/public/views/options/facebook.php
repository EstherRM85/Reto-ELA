<?php
	use sgpb\AdminHelper;
	$defaultData = ConfigDataHelper::defaultData();
?>
<div class="sgpb-wrapper">
	<div class="row">
		<div class="col-md-8">
			<div class="row form-group">
				<label for="sgpb-fblike-like-url" class="col-md-5 control-label sgpb-static-padding-top">
					<?php _e('URL', SG_POPUP_TEXT_DOMAIN)  ?>:
				</label>
				<div class="col-md-6">
					<input name="sgpb-fblike-like-url" id="sgpb-fblike-like-url" type="url" placeholder="http://" class="form-control sgpb-full-width-events" value="<?php echo esc_html($popupTypeObj->getOptionValue('sgpb-fblike-like-url'))?>" required>
				</div>
			</div>
			<div class="row form-group">
				<label for="sgpb-fblike-layout" class="col-md-5 control-label">
					<?php _e('Layout', SG_POPUP_TEXT_DOMAIN)  ?>:
				</label>
				<div class="col-md-6">
					<?php echo AdminHelper::createSelectBox($defaultData['buttonsType'], esc_html($popupTypeObj->getOptionValue('sgpb-fblike-layout')), array('name' => 'sgpb-fblike-layout', 'class'=>'js-sg-select2', 'id'=>'sgpb-fblike-layout')); ?>
				</div>
			</div>
			<div class="row form-group">
				<label for="fblike-dont-show-share-button" class="col-md-5 control-label">
					<?php _e("Don't show share button", SG_POPUP_TEXT_DOMAIN)  ?>:
				</label>
				<div class="col-md-6">
					<input name="sgpb-fblike-dont-show-share-button" id="fblike-dont-show-share-button" type="checkbox" <?php echo $popupTypeObj->getOptionValue('sgpb-fblike-dont-show-share-button');?>>
				</div>
			</div>

		<!-- col-md-6 end -->
		</div>
	<!-- row end -->
	</div>
<!-- sgpb-wrapper end -->
</div>
