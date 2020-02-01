<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<?php
/**
* Template file for the plain HTML table
* wpDataTables Module
* 
* @author cjbug@ya.ru
* @since 10.10.2012
*
**/
?>
<?php if($wpDataTable->getFilteringForm()) { ?>
<?php do_action('wpdatatables_before_filtering_form', $wpDataTable->getWpId()); ?>
<!-- Filter in form -->
<?php do_action('wpdatatables_after_filtering_form', $wpDataTable->getWpId()); ?>
<?php } ?>

<?php do_action('wpdatatables_before_table', $wpDataTable->getWpId()); ?>
	<input type="hidden" id="<?php echo $wpDataTable->getId() ?>_desc" value='<?php echo $wpDataTable->getJsonDescription(); ?>' />

<?php if( !$wpDataTable->serverSide() ): ?>
	<input type="hidden" id="<?php echo $wpDataTable->getId() ?>_data" value='<?php echo json_encode( $wpDataTable->getDataRows(), JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG  ); ?>' />
<?php endif; ?>
	<div id="<?php echo $wpDataTable->getId() ?>_search_filter" class="wpExcelTable_search_filter">
		<label><?php _e('Search', 'wpdatatables'); ?>:<input type="search" class="" placeholder="" aria-controls="<?php echo $wpDataTable->getId() ?>"></label>
	</div>

	<div id="<?php echo $wpDataTable->getId() ?>" class="<?php echo $wpDataTable->getCSSClasses() ?> wpExcelTable"
		 data-described-by='<?php echo $wpDataTable->getId() ?>_desc'
		 data-wpdatatable_id="<?php echo $wpDataTable->getWpId(); ?>">
	</div>

<?php do_action('wpdatatables_after_table', $wpDataTable->getWpId()); ?>

<br/><br/>