<?php
use sgpb\AdminHelper;
use sgpb\PopupType;

$popupTypes = sgpb\SGPopup::getPopupTypes();
global $SGPB_POPUP_TYPES;
$labels = $SGPB_POPUP_TYPES['typeLabels'];
?>
	<div class="sgpb-free-wrapper">
		<?php if (SGPB_POPUP_PKG == SGPB_POPUP_PKG_FREE): ?>
			<div class="sgpb-wrapper sgpb-add-new-wrapper">
				<div class="row sgpb-add-new-row">
					<div class="col-xs-6">
						<h2><?php _e('Add New Popup', SG_POPUP_TEXT_DOMAIN); ?></h2>
					</div>
					<div class="col-xs-6">
						<a href="<?php echo SG_POPUP_ALL_EXTENSIONS_URL;?>" target="_blank" class="btn btn-warning sgpb-pro-button"><?php _e('Get More Extensions', SG_POPUP_TEXT_DOMAIN) ?></a>
					</div>
				</div>
			</div>
		<?php else: ?>
			<div class="sgpb-wrapper sgpb-add-new-wrapper">
				<div class="row sgpb-add-new-row">
					<div class="col-xs-6">
						<h2><?php _e('Add New Popup', SG_POPUP_TEXT_DOMAIN); ?></h2>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="sgpb-wrapper">
			<?php
			$extensions = AdminHelper::getAllFreeExtensions();
			foreach ($extensions['noActive'] as $extension) : ?>
				<?php
				$URL = '';
				if (!empty($extension['url'])) {
					$URL = $extension['url'];
				}
				$type = $extension['key'];
				?>
				<a class="create-popup-link popups-div sgpb-<?php echo $type; ?>-div" href="<?php echo esc_attr($URL); ?>" target="_blank">
					<div class="sgpb-type-icon <?php echo $type; ?>-popup-pro"></div>
					<div class="sgpb-type-text-wrapper">
						<p class="sgpb-type-text"><?php echo ucfirst($extension['label']); ?></p>
					</div>
					<div class="sgpb-popup-type-free-pro-ribbon"></div>
				</a>
			<?php endforeach; ?>
			<?php foreach ($popupTypes as $popupType): ?>
				<?php $type = $popupType->getName(); ?>
				<?php
				$isAvaliable = $popupType->isAvailable();
				if (!$isAvaliable) {
					continue;
				}
				?>
				<a class="create-popup-link popups-div sgpb-<?php echo $type; ?>-div" href="<?php echo AdminHelper::buildCreatePopupUrl($popupType); ?>">
					<div class="sgpb-type-icon <?php echo AdminHelper::getPopupThumbClass($popupType); ?>"></div>
					<div class="sgpb-type-text-wrapper">
						<p class="sgpb-type-text"><?php echo $labels[$type]; ?></p>
					</div>
					<?php if (!$isAvaliable): ?>
						<div class="sgpb-popup-type-pro-ribbon"><?php _e('PRO', SG_POPUP_TEXT_DOMAIN); ?></div>
					<?php endif; ?>
				</a>

			<?php endforeach; ?>
		</div>
	</div>

	<div class="sgpb-pro-wrapper">
		<div class="sgpb-wrapper">
		<?php foreach ($popupTypes as $popupType): ?>
			<?php $type = $popupType->getName(); ?>
			<?php
			$isAvaliable = $popupType->isAvailable();
			if ($isAvaliable) {
				continue;
			}
			?>
			<a class="create-popup-link popups-div sgpb-<?php echo $type; ?>-div <?php echo AdminHelper::getPopupThumbClass($popupType); ?>-wrapper" href="<?php echo AdminHelper::buildCreatePopupUrl($popupType); ?>" target="_blank">
				<div class="sgpb-type-icon <?php echo AdminHelper::getPopupThumbClass($popupType); ?>"></div>
				<div class="sgpb-type-text-wrapper">
					<p class="sgpb-type-text"><?php echo $labels[$type]; ?></p>
				</div>
				<div class="sgpb-popup-type-pro-ribbon"></div>
			</a>
		<?php endforeach; ?>
		</div>
	</div>
	<div class="sgpb-free-wrapper">
		<a class="create-popup-link popups-div" href="<?php echo SG_POPUP_TICKET_URL;?>" target="_blank">
			<div class="sgpb-type-icon sgpb-more-ideas"></div>
			<div class="sgpb-type-text-wrapper">
				<p class="sgpb-type-text"><?php _e('More Ideas?', SG_POPUP_TEXT_DOMAIN); ?></p>
			</div>
		</a>
	</div>

<?php
$extensions = AdminHelper::getAllExtensions();
if (!empty($extensions['noActive'])) : ?>
	<div class="sgpb-extensions-wrapper">
		<div class="sgpb-extensions-section-wrapper">
			<div class="sgpb-wrapper">
				<div class="row">
					<div class="col-xs-6">
						<h2><?php _e('Extensions', SG_POPUP_TEXT_DOMAIN); ?></h2>
					</div>
				</div>
			</div>
		</div>
		<?php foreach ($extensions['noActive'] as $extension) : ?>
			<?php if (isset($extension['availability']) && $extension['availability'] == 'free'): ?>
				<?php continue; ?>
			<?php endif; ?>
			<?php
			$URL = '';
			if (!empty($extension['url'])) {
				$URL = $extension['url'];
			}
			$type = $extension['key'];
			?>
			<a class="create-popup-link popups-div sgpb-<?php echo $type; ?>-div" href="<?php echo esc_attr($URL); ?>" target="_blank">
				<div class="sgpb-type-icon <?php echo $type; ?>-popup-pro"></div>
				<div class="sgpb-type-text-wrapper">
					<p class="sgpb-type-text"><?php echo ucfirst($extension['label']); ?></p>
				</div>
				<div class="sgpb-popup-type-pro-ribbon"></div>
			</a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
