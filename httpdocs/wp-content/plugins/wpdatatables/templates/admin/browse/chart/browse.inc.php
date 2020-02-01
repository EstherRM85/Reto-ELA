<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<!-- .wdt-datatables-admin-wrap -->
<div class="wrap wdt-datatables-admin-wrap">

    <!-- .container -->
    <div class="container">

        <!-- .row -->
        <div class="row">

            <!-- .card .wdt-browse-table -->
            <div class="card wdt-browse-table">

                <!-- Preloader -->
                <?php include WDT_TEMPLATE_PATH . 'admin/common/preloader.inc.php'; ?>
                <!-- /Preloader -->

                <!-- .card-header -->
                <div class="card-header wdt-admin-card-header ch-alt">
                    <img id="wpdt-inline-logo" style="width: 60px;height: 50px;"
                         src="<?php echo WDT_ROOT_URL; ?>assets/img/logo-large.png"/>
                    <h2>
                        <span>wpDataCharts</span>
                        <small><?php _e('wpDataCharts browse', 'wpdatatables'); ?></small>
                    </h2>
                    <ul class="actions">
                        <li>
                            <button onclick="location.href='admin.php?page=wpdatatables-chart-wizard'"
                                    class="btn bgm-blue waves-effect wdt-add-new">
                                <i class="zmdi zmdi-plus"></i>
                                <?php _e('Add New', 'wpdatatables'); ?>
                            </button>
                            <button class="btn bgm-green waves-effect tms-store-checkout-wpdatatables">
                                <i class="zmdi zmdi-shopping-cart"></i>
                                <?php _e('Buy Full Version', 'wpdatatables'); ?>
                            </button>
                        </li>
                    </ul>
                </div>
                <!--/ .card-header -->

                <form method="post" action="<?php echo admin_url('admin.php?page=wpdatatables-charts'); ?>"
                      id="wdt-datatables-browse-table">
                    <div class="alert alert-info alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                        <?php _e('NEW awesome features!!! From version wpDataTables Lite 2.1 you can create Google charts: Line, Column and Pie. All other chart types are available in the', 'wpdatatables'); ?>
                        <a class="tms-store-checkout-wpdatatables dark"><?php _e('full version of wpDataTables.', 'wpdatatables'); ?></a>
                    </div>
                    <?php echo $tableHTML; ?>
                    <?php wp_nonce_field('wdtDeleteChartNonce', 'wdtNonce'); ?>
                </form>
            </div>
            <!--/ .card .wdt-browse-table -->

        </div>
        <!--/ .row -->

    </div>
    <!-- .container -->

    <!-- Duplicate chart modal -->
    <?php include WDT_TEMPLATE_PATH . 'admin/browse/chart/duplicate_chart_modal.inc.php'; ?>
    <!-- /Duplicate chart modal -->

    <!-- Delete modal -->
    <?php include WDT_TEMPLATE_PATH . 'common/delete_modal.inc.php'; ?>
    <!-- /Delete modal -->

</div>
<!--/ .wpDataTablesBrowseWrap -->
