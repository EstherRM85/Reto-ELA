<?php
	use sgpb\AdminHelper;
	$defaultData = ConfigDataHelper::defaultData();

	$deleteData = '';
	if (get_option('sgpb-dont-delete-data')) {
		$deleteData = 'checked';
	}

	$systemInfo = AdminHelper::getSystemInfoText();
	$userSavedRoles = get_option('sgpb-user-roles');
?>

<div class="sgpb-wrapper sgpb-settings">
	<div class="col-md-6">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="postbox-container-2" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox popup-builder-special-postbox">
						<div class="handlediv js-special-title" title="Click to toggle"><br></div>
						<h3 class="hndle ui-sortable-handle js-special-title">
							<span><?php _e('General Settings', SG_POPUP_TEXT_DOMAIN); ?></span>
						</h3>
						<div class="sgpb-options-content">
							<form method="POST" action="<?php echo admin_url().'admin-post.php?action=sgpbSaveSettings'?>">
								<div class="row form-group">
									<div class="col-md-4 sgpb-static-padding-top">
										<label for="sgpb-dont-delete-data"><?php _e('Delete popup data', SG_POPUP_TEXT_DOMAIN)?></label>
									</div>
									<div class="col-md-6">
										<input type="checkbox" name="sgpb-dont-delete-data" class="sgpb-reset-checkbox-margin-top" id="sgpb-dont-delete-data" <?php echo $deleteData; ?>>
										<span class="dashicons dashicons-editor-help sgpb-info-icon"></span>
										<span class="infoSelectRepeat samefontStyle sgpb-info-text">
										<?php _e('All the popup data will be deleted after removing the plugin if this option is checked', SG_POPUP_TEXT_DOMAIN)?>.
									</span>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-4 sgpb-label-align-with-select2">
										<label><?php _e('User role to access the plugin', SG_POPUP_TEXT_DOMAIN)?></label>
									</div>
									<div class="col-md-6">
										<?php  echo AdminHelper::createSelectBox($defaultData['userRoles'], $userSavedRoles, array('name'=>'sgpb-user-roles[]', 'class' => 'js-sg-select2 schedule-start-selectbox sg-margin0', 'multiple'=> 'multiple', 'size'=> count($defaultData['userRoles'])));?>
									</div>
									<div class="col-md-1">
										<span class="dashicons dashicons-editor-help sgpb-info-icon sgpb-info-icon-align"></span>
										<span class="infoSelectRepeat samefontStyle sgpb-info-text">
										<?php _e('In spite of user roles the administrator always has access to the plugin', SG_POPUP_TEXT_DOMAIN)?>.
									</span>
									</div>
								</div>
								<div class="row form-group">
									<div class="col-md-12">
										<input type="submit" value="<?php _e('Save Changes', SG_POPUP_TEXT_DOMAIN)?>" class="button-primary">
									</div>
								</div>
							</form>
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
							<span><?php _e('Debug tools', SG_POPUP_TEXT_DOMAIN); ?></span>
						</h3>
						<div class="sgpb-options-content">
							<div class="row form-group">
								<div class="col-md-4 sgpb-static-padding-top">
									<label for="sgpb-dont-delete-data"><?php _e('System information', SG_POPUP_TEXT_DOMAIN)?></label>:
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-12">
									<textarea onclick="this.select();" rows="10" class="form-control" readonly><?php echo $systemInfo ;?></textarea>
								</div>
							</div>
							<div class="row form-group">
								<div class="col-md-12">
									<input type="button" class="sgpb-download-system-info button-primary" value="<?php _e('Download', SG_POPUP_TEXT_DOMAIN)?>" class="button-primary">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
