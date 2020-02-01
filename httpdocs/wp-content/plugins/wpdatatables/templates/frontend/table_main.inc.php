<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<?php
/**
 * Template file for the plain HTML table
 * wpDataTables Module
 * @author cjbug@ya.ru
 * @since 10.10.2012
 *
 **/

/** @var $this WPDataTable */
/** @var string $advancedFilterPosition */
?>
<?php



?>
<?php do_action('wpdatatables_before_table', $this->getWpId()); ?>
<?php wp_nonce_field('wdtFrontendEditTableNonce', 'wdtNonceFrontendEdit'); ?>
    <input type="hidden" id="<?php echo $this->getId() ?>_desc" value='<?php echo $this->getJsonDescription(); ?>'/>

    <table id="<?php echo $this->getId() ?>"
           class="<?php if ($this->isScrollable()) { ?>scroll<?php } ?>
           <?php  ?>
           display nowrap <?php echo $this->getCSSClasses() ?> wpDataTable"
           style="<?php echo $this->getCSSStyle() ?>"
           data-described-by='<?php echo $this->getId() ?>_desc'
           data-wpdatatable_id="<?php echo $this->getWpId(); ?>
">

        <!-- Table header -->
        <?php include WDT_TEMPLATE_PATH . 'frontend/table_head.inc.php'; ?>
        <!-- /Table header -->

        <!-- Table body -->
        <?php include WDT_TEMPLATE_PATH . 'frontend/table_body.inc.php'; ?>
        <!-- /Table body -->

        <?php  ?>

    </table>
<?php do_action('wpdatatables_after_table', $this->getWpId()); ?>

<?php 