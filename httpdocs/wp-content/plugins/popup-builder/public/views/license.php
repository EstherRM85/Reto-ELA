<div class="sgpb-wrapper sgpb-settings sgpb-license-wrapper">
<?php
$licenses = $this->getLicenses();
$licenseCount = 0;
foreach ($licenses as $currentLicense) : ?>
<?php
	$key = $currentLicense['key'];
	$license = get_option('sgpb-license-key-'.$key);
	$status = get_option('sgpb-license-status-'.$key);
?>
	<?php if ($licenseCount == 0 || $licenseCount % 2 == 0): ?>
		<div class="row">
	<?php endif; ?>
	<div class="col-md-6">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="postbox-container-2" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox popup-builder-special-postbox">
						<div class="handlediv js-special-title"><br></div>
						<h3 class="hndle ui-sortable-handle js-special-title">
							<span><?php echo $currentLicense['boxLabel'] ?></span>
						</h3>
						<div class="sgpb-options-content">
							<form method="post" action="options.php" class="form-horizontal">
								<div class="form-group">
									<div class="col-md-3">
										<label class="control-label" for="<?php echo 'sgpb-license-key-'.$key?>"><?php _e('License Key', SG_POPUP_TEXT_DOMAIN); ?></label>
									</div>
									<div class="col-sm-5">
										<input id="<?php echo 'sgpb-license-key-'.$key?>" name="<?php echo 'sgpb-license-key-'.$key?>" type="text" class="form-control input-sm" value="<?php esc_attr_e($license); ?>" >
									</div>
									<div class="col-sm-2">
										<?php if($status !== false && $status == 'valid'): ?>
											<div class="col-xs-2">
												<?php wp_nonce_field('sgpb_nonce', 'sgpb_nonce'); ?>
												<input type="submit" class="btn btn-primary btn-sm" name="<?php echo 'sgpb-license-deactivate'.$key; ?>" value="<?php _e('Deactivate', SG_POPUP_TEXT_DOMAIN); ?>">
											</div>
										<?php else: ?>
											<div class="col-xs-2">
												<?php wp_nonce_field('sgpb_nonce', 'sgpb_nonce'); ?>
												<input type="submit" class="btn btn-primary btn-sm" name="<?php echo 'sgpb-license-activate-'.$key; ?>" value="<?php _e('Activate', SG_POPUP_TEXT_DOMAIN); ?>">
											</div>
										<?php endif; ?>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3">
										<label class="control-label" for="sgpb-license-key sgpb-license-status"><?php _e('Status', SG_POPUP_TEXT_DOMAIN); ?></label>
									</div>
									<?php if($status !== false && $status == 'valid'): ?>
										<div class="col-md-1 sgpb-margin-right-status">
											<button type="button" class="btn btn-success btn-sm sgpb-license-status"><?php _e('active', SG_POPUP_TEXT_DOMAIN); ?></button>
										</div>
									<?php else: ?>
										<div class="col-md-1 sgpb-margin-right-status-not-active">
											<button type="button" class="btn btn-danger btn-sm sgpb-license-status"><?php _e('not active', SG_POPUP_TEXT_DOMAIN); ?></button>
										</div>
									<?php endif; ?>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if (($licenseCount % 2 != 0)): ?>
		</div>
	<?php endif; ?>
	<?php ++$licenseCount; ?>
<?php endforeach; ?>
</div>
