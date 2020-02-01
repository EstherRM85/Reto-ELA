<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<div class="wrap wdt-datatables-admin-wrap">

    <?php wp_nonce_field('wdtConstructorNonce', 'wdtNonce'); ?>

    <?php do_action('wpdatatables_admin_before_constructor'); ?>

    <div class="container">

        <div class="row">

            <div class="card wdt-table-constructor">

                <!-- Preloader -->
                <?php include WDT_TEMPLATE_PATH . 'admin/common/preloader.inc.php'; ?>
                <!-- /Preloader -->

                <div class="card-header wdt-admin-card-header ch-alt">
                    <img id="wpdt-inline-logo" style="width: 60px;height: 50px;"
                         src="<?php echo WDT_ROOT_URL; ?>assets/img/logo-large.png"/>
                    <h2>
                    <span class="" id=""
                          title="<?php _e('Create a Table', 'wpdatatables'); ?>"><?php _e('Create a Table', 'wpdatatables'); ?></span>
                        <small><?php _e('Table Creation Wizard', 'wpdatatables'); ?></small>
                    </h2>
                    <ul class="actions p-t-5">
                        <li>
                            <button class="btn bgm-red btn-icon btn-lg waves-effect waves-circle waves-float wdt-backend-close"
                                    title="<?php _e('Cancel', 'wpdatatables'); ?>" data-toggle="tooltip">
                                <i class="zmdi zmdi-close"></i>
                            </button>
                        </li>
                    </ul>
                </div>
                <!-- /.card-header -->

                <div class="card-body card-padding">

                    <?php include WDT_TEMPLATE_PATH . 'admin/constructor/steps/constructor_1.inc.php'; ?>

                    <?php  ?>

                    <div class="row m-t-15 m-b-5 p-l-15 p-r-15">
                        <div class="btn-group wdt-constructor-create-buttons pull-right m-l-5" style="display: none;">
                            <button type="button" class="btn btn-success dropdown-toggle waves-effect"
                                    data-toggle="dropdown" aria-expanded="false">
                                Create the table
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li id="wdt-constructor-create-table">
                                    <a><?php _e('Open in standard editor', 'wpdatatables'); ?></a></li>
                                <li id="wdt-constructor-create-table-excel">
                                    <a><?php _e('Open in Excel-like editor', 'wpdatatables'); ?></a></li>
                            </ul>
                        </div>
                        <button class="btn btn-primary waves-effect pull-right m-l-5"
                                disabled="disabled"
                                id="wdt-constructor-next-step"><?php _e('Next ', 'wpdatatables'); ?></button>
                        <button class="btn btn-primary waves-effect pull-right" id="wdt-constructor-previous-step"
                                disabled="disabled"><?php _e(' Previous', 'wpdatatables'); ?></button>
                        <a class="btn btn-default btn-icon-text waves-effect wdt-documentation"
                           data-doc-page="constructor">
                            <i class="zmdi zmdi-help-outline"></i> <?php _e('Documentation', 'wpdatatables'); ?>
                        </a>
                    </div>

                </div>
                <!-- /.card-body -->

            </div>
            <!-- /.card /.wdt-table-constructor -->

        </div>
        <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- Error message modal -->
    <?php include WDT_TEMPLATE_PATH . 'admin/common/error_modal.inc.php'; ?>
    <!-- /Error message modal -->

    <!-- Close modal -->
    <?php include WDT_TEMPLATE_PATH . 'admin/common/close_modal.inc.php'; ?>
    <!-- /Close modal -->

</div>
<!-- /.wdt-datatables-admin-wrap .wrap -->
