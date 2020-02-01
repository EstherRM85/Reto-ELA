<?php if (!empty($_GET['sgpbImport'])): ?>
	<?php require_once(SG_POPUP_VIEWS_PATH.'importSettingsView.php'); ?>
<?php else: ?>
	<?php require_once(SG_POPUP_VIEWS_PATH.'settingsOptions.php'); ?>
<?php endif;?>



