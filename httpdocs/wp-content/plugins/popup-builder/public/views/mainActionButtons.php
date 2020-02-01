<?php
use sgpb\AdminHelper;
?>
<div class="sgpb-wrapp">
	<h1 class="wp-heading-inline"><?php _e('Popups', SG_POPUP_TEXT_DOMAIN)?></h1>
	<a href="<?php echo AdminHelper::getPopupTypesPageURL(); ?>" class="page-title-action">
		<?php _e('Add New', SG_POPUP_TEXT_DOMAIN); ?>
	</a>
	<a href="<?php echo AdminHelper::getPopupExportURL(); ?>" class="page-title-action">
		<?php _e('Export', SG_POPUP_TEXT_DOMAIN); ?>
	</a>
	<a href="<?php echo AdminHelper::getSettingsURL(array('sgpbImport' => 1)); ?>" class="page-title-action">
		<?php _e( 'Import', 'easy-digital-downloads' ); ?>
	</a>
</div>
<style>
	#wpbody-content > div.wrap > h1,
	#wpbody-content > div.wrap > a {
		display: none !important;
	}
</style>