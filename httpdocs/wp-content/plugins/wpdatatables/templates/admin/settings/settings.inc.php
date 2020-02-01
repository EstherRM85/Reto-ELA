<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<div class="wrap wdt-datatables-admin-wrap">
    <?php do_action('wpdatatables_admin_before_settings'); ?>

    <!-- .container -->
    <div class="container">

        <!-- .row -->
        <div class="row">

            <div class="card plugin-settings">
                <?php wp_nonce_field('wdtSettingsNonce', 'wdtNonce'); ?>
                <!-- Preloader -->
                <?php include WDT_TEMPLATE_PATH . 'admin/common/preloader.inc.php'; ?>
                <!-- /Preloader -->

                <div class="card-header wdt-admin-card-header ch-alt">
                    <img id="wpdt-inline-logo" style="width: 60px;height: 50px;"
                         src="<?php echo WDT_ROOT_URL; ?>assets/img/logo-large.png"/>
                    <h2>
                        <span title="<?php _e('Settings for the plugin', 'wpdatatables'); ?>">wpDataTables Settings</span>
                        <small><?php _e('Settings for the plugin', 'wpdatatables'); ?></small>
                    </h2>
                    <ul class="actions p-t-5">
                        <li>
                            <button class="btn bgm-red btn-icon btn-lg waves-effect waves-circle waves-float wdt-backend-close"
                                    title="<?php _e('Cancel', 'wpdatatables'); ?>" data-toggle="tooltip">
                                <i class="zmdi zmdi-close"></i>
                            </button>
                        </li>
                        <li>
                            <button class="btn bgm-green btn-icon btn-lg waves-effect waves-circle waves-float wdt-apply"
                                    title="<?php _e('Save', 'wpdatatables'); ?>" data-toggle="tooltip">
                                <i class="zmdi zmdi-check"></i>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body card-padding p-t-10">
                    <div role="tabpanel">
                        <ul class="tab-nav" role="tablist">
                            <li class="active main-plugin-settings-tab">
                                <a href="#main-plugin-settings" aria-controls="main-plugin-settings" role="tab"
                                   data-toggle="tab"><?php _e('Main settings', 'wpdatatables'); ?></a>
                            </li>
                            <li class="main-plugin-settings-tab">
                                <a href="#separate-mysql-connection" aria-controls="separate-mysql-connection"
                                   role="tab"
                                   data-toggle="tab"><?php _e('Separate DB connection', 'wpdatatables'); ?></a>
                            </li>
                            <li class="color-and-font-settings-tab">
                                <a href="#color-and-font-settings" aria-controls="color-and-font-settings"
                                   role="tab"
                                   data-toggle="tab"><?php _e('Color and font settings', 'wpdatatables'); ?></a>
                            </li>
                            <li class="custom-js-and-css-tab">
                                <a href="#custom-js-and-css" aria-controls="custom-js-and-css" role="tab"
                                   data-toggle="tab"><?php _e('Custom JS and CSS', 'wpdatatables'); ?></a>
                            </li>
                            <li class="info-tab">
                                <a href="#info" aria-controls="info" role="tab"
                                   data-toggle="tab"><?php _e('Info', 'wpdatatables'); ?></a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Main plugin settings tab -->
                            <?php include 'tabs/main_plugin_settings.php' ?>
                            <!-- /Main plugin settings tab -->

                            <!-- Separate MySQL connection settings tab -->
                            <?php include 'tabs/separate_mysql_connection.php' ?>
                            <!-- /Separate MySQL connection settings tab -->

                            <!-- Color and font settings tab-->
                            <?php include 'tabs/color_and_font_settings.php' ?>
                            <!-- /Color and font settings tab-->

                            <!-- Custom JS and CSS settings tab-->
                            <?php include 'tabs/custom_js_and_css.php' ?>
                            <!-- /Custom JS and CSS settings tab-->

                            <!-- Info tab-->
                            <?php include 'tabs/info.php' ?>
                            <!-- /Info tab-->
                        </div>
                    </div>
                    <div class="row m-t-15 m-b-5 p-l-15 p-r-15">
                        <div class="pull-right">
                            <button class="btn btn-primary waves-effect reset-color-settings" id="reset-color-settings"
                                    style="display: none;">
                                <?php _e('Reset colors and fonts to default', 'wpdatatables'); ?>
                            </button>
                            <button class="btn btn-success btn-icon-text waves-effect wdt-apply">
                                <i class="zmdi zmdi-check"></i><?php _e('Apply', 'wpdatatables'); ?>
                            </button>
                        </div>
                        <a class="btn btn-default btn-icon-text waves-effect wdt-documentation"
                           data-doc-page="settings_page">
                            <i class="zmdi zmdi-help-outline"></i> Documentation
                        </a>
                    </div>
                </div>

            </div>

        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->

    <!-- Modals -->

    <!-- Error message modal -->
    <?php include WDT_TEMPLATE_PATH . 'admin/common/error_modal.inc.php'; ?>
    <!-- /Error message modal -->

    <!-- Close modal -->
    <?php include WDT_TEMPLATE_PATH . 'admin/common/close_modal.inc.php'; ?>
    <!-- /Close modal -->

    <!-- /Modals -->

</div>
<!-- /.wdt-datatables-admin-wrap .wrap -->

