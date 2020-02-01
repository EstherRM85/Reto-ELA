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
                        <span>wpDataTables</span>
                        <small><?php _e('wpDataTables browse', 'wpdatatables'); ?></small>
                    </h2>
                    <ul class="actions">
                        <li>
                            <button onclick="location.href='admin.php?page=wpdatatables-constructor'"
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

                <form method="post" action="<?php echo admin_url('admin.php?page=wpdatatables-administration'); ?>"
                      id="wdt-datatables-browse-table">
                    <?php echo $tableHTML; ?>
                    <?php wp_nonce_field('wdtDeleteTableNonce', 'wdtNonce'); ?>
                </form>
            </div>
            <!--/ .card .wdt-browse-table -->

        </div>
        <!--/ .row -->

    </div>
    <!-- .container -->

    <!-- Delete modal -->
    <?php include WDT_TEMPLATE_PATH . 'common/delete_modal.inc.php'; ?>
    <!-- /Delete modal -->

    <!-- Duplicate table modal -->
    <?php include WDT_TEMPLATE_PATH . 'admin/browse/table/duplicate_modal.inc.php'; ?>
    <!-- /Duplicate table modal -->

</div>
<!--/ .wdt-datatables-admin-wrap -->
