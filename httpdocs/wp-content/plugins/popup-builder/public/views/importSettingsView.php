<?php
	use sgpb\AdminHelper;
	$defaultData = ConfigDataHelper::defaultData();

	$deleteData = '';
	if (get_option('sgpb-dont-delete-data')) {
		$deleteData = 'checked';
	}

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
							<span><?php _e('Import popups', SG_POPUP_TEXT_DOMAIN); ?></span>
						</h3>
						<div class="sgpb-options-content">
							<div class="sgpb-import-wrapper">
								<?php
									wp_import_upload_form('admin.php?import='.SG_POPUP_POST_TYPE.'&amp;step=1');
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>