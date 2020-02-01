<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<?php
/**
 * Template for Table Preview widget
 * @author Alexander Gilmanov
 * @since 13.10.2016
 */
?>
<!-- div.column-settings -->
<div class="card column-settings hidden">

    <!-- Preloader -->
    <?php include WDT_TEMPLATE_PATH . 'admin/common/preloader.inc.php'; ?>
    <!-- /Preloader -->

    <div class="card-header wdt-admin-card-header ch-alt">
        <div class="col-sm-8 p-l-0 p-t-5">
            <h2><?php _e('Table preview and columns setup', 'wpdatatables'); ?></h2>
        </div>
        <div class="col-sm-4 wdt-table-action-buttons">
            <button class="btn btn-primary waves-effect waves-float pull-right wdt-add-formula-column "
                    title="<?php _e('Add a formula (calculated) column', 'wpdatatables'); ?>" data-toggle="tooltip"<?php echo ' data-version="full-version-option-click"' ?>>
                <i class="zmdi zmdi-collection-plus"></i> <?php _e('Add a formula column', 'wpdatatables'); ?>
            </button>
            <button class="btn bgm-amber waves-effect waves-float pull-right"
                    title="<?php _e('Complete column list', 'wpdatatables'); ?>" data-toggle="tooltip"
                    id="wdt-open-columns-list">
                <i class="zmdi zmdi-view-column"></i>
            </button>
            <?php if (isset($tableData) && $tableData->table->table_type === 'manual') { ?>
                <button class="btn bgm-green waves-effect waves-float pull-right wdt-add-column"
                        title="<?php _e('Add column', 'wpdatatables'); ?>" data-toggle="tooltip"></button>
                <button class="btn bgm-red waves-effect waves-float pull-right wdt-remove-column"
                        title="<?php _e('Remove column', 'wpdatatables'); ?>" data-toggle="tooltip"></button>
            <?php } ?>
        </div>
        <div class="clear"></div>
    </div>
    <!-- /.card-header -->
    <div class="card-body card-padding">

        <div class="col-sm-12 p-0 wdt-edit-buttons hidden">
            <span class="pull-right"><?php _e('Switch View:', 'wpdatatables'); ?>
                <?php if (isset($_GET['table_view']) && $_GET['table_view'] == 'excel') { ?>
                    <a href="<?php echo admin_url(isset($_GET['table_id']) ? 'admin.php?page=wpdatatables-constructor&source&table_id=' . (int)$_GET['table_id'] : ''); ?>"><?php _e('STANDARD', 'wpdatatables'); ?></a> |
                    <?php _e('EXCEL-LIKE', 'wpdatatables'); ?>
                <?php } else { ?>
                    <?php _e('STANDARD', 'wpdatatables'); ?> |
                    <a href="<?php echo admin_url(isset($_GET['table_id']) ? 'admin.php?page=wpdatatables-constructor&source&table_id=' . (int)$_GET['table_id'] . '&table_view=excel' : ''); ?>"><?php _e('EXCEL-LIKE', 'wpdatatables'); ?></a>
                <?php } ?>
            </span>
        </div>
        <div class="clearfix"></div>

        <div class="row wpDataTableContainer wpDataTables wpDataTablesWrapper" id="wpdatatable-preview-container">
            <?php if (isset($tableData)) {
                echo $tableData->wdtHtml;
            } ?>
        </div>
        <!-- /.wpDataTableContainer -->

        <div class="row">

            <div class="col-md-12">
                <button class="btn btn-default btn-icon-text waves-effect wdt-documentation"
                        data-doc-page="table_preview">
                    <i class="zmdi zmdi-help-outline"></i> <?php _e('Documentation', 'wpdatatables'); ?>
                </button>

                <div class="pull-right">
                    <button class="btn btn-danger btn-icon-text waves-effect wdt-backend-close">
                        <i class="zmdi zmdi-close"></i> <?php _e('Cancel', 'wpdatatables'); ?>
                    </button>
                    <button class="btn btn-success btn-icon-text waves-effect wdt-apply">
                        <i class="zmdi zmdi-check"></i> <?php _e('Apply', 'wpdatatables'); ?>
                    </button>
                </div>
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->

    </div>
    <!-- /.card-body -->
</div>
<!-- /.card /.column-settings -->
