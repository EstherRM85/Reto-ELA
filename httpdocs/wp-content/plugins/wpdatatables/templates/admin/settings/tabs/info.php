<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<?php
/**
 * User: Miljko Milosevic
 * Date: 1/20/17
 * Time: 1:35 PM
 */
?>

<div role="tabpanel" class="tab-pane" id="info">
    <div class="row">
        <div class="col-sm-12">
            <table class="table">
                <tr>
                    <td>
                        <div class="p-5" role="alert"><?php esc_html_e('PHP Version', 'wpdatatables'); ?></div>
                    </td>
                    <td>
                        <label class="p-5"><?php echo phpversion(); ?></label>
                    </td>
                    <?php if (version_compare('phpversion()', '5.4.0', '>')) { ?>
                    <td>
                        <div>
                            <i class="zmdi zmdi-check p-t-5"></i>
                        </div>
                    </td>
                    <td>
                        <?php } else { ?>
                    <td>
                        <div>
                            <i class="zmdi zmdi-close p-t-5"></i>
                        </div>
                    </td>
                    <td>
                        <div class="p-5 alert alert-danger m-0"
                             role="alert"><?php esc_html_e('Minimum PHP version required to run wpDataTables is PHP 5.4. Please update your PHP.', 'wpdatatables'); ?></div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <?php global $wpdb; ?>
                    <td>
                        <div class="p-5" role="alert"><?php esc_html_e('MySQL Version', 'wpdatatables'); ?></div>
                    </td>
                    <td class="">
                        <label class="p-5"><?php echo $wpdb->db_version(); ?></label>
                    </td>
                    <?php
                    if (version_compare($wpdb->db_version(), '5.0.0', '>')) {
                    ?>
                    <td>
                        <div>
                            <i class="zmdi zmdi-check p-t-5"></i>
                        </div>
                    </td>
                    <td>
                        <?php } else { ?>
                    <td>
                        <div>
                            <i class="zmdi zmdi-close p-t-5"></i>
                        </div>
                    </td>
                    <td>
                        <div class="p-5 alert alert-danger m-0"
                             role="alert"><?php esc_html_e('Minimum PHP version required to run wpDataTables is MySQL 5. Please update your MySQL', 'wpdatatables'); ?></div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="p-5" role="alert"><?php esc_html_e('Zip extension ', 'wpdatatables'); ?></div>
                    </td>
                    <?php if (extension_loaded('zlib')) { ?>
                        <td class="">
                            <label class="p-5"><?php esc_html_e('Installed', 'wpdatatables'); ?></label>
                        </td>
                        <td>
                            <div>
                                <i class="zmdi zmdi-check p-t-5"></i>
                            </div>
                        </td>
                        <td>
                        </td>
                    <?php } else { ?>
                        <td class="">
                            <label class="p-5"><?php esc_html_e('Not installed', 'wpdatatables'); ?></label>
                        </td>
                        <td>
                            <div>
                                <i class="zmdi zmdi-close p-t-5"></i>
                            </div>
                        </td>
                        <td>
                            <div class="p-5 alert alert-danger m-0"
                                 role="alert"><?php esc_html_e('Please install or enable PHP Zip Extension on your server', 'wpdatatables'); ?></div>
                        </td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>
                        <div class="p-5" role="alert"><?php esc_html_e('Curl extension ', 'wpdatatables'); ?></div>
                    </td>
                    <td class="">
                        <label class="p-5"><?php $values = curl_version();
                            echo($values["version"]); ?></label>
                    </td>
                    <?php
                    if (extension_loaded('curl')) {
                        ?>
                        <td>
                            <div>
                                <i class="zmdi zmdi-check p-t-5"></i>
                            </div>
                        </td>
                        <td>
                        </td>
                    <?php } else { ?>
                        <td>
                            <div>
                                <i class="zmdi zmdi-close p-t-5"></i>
                            </div>
                        </td>
                        <td>
                            <div class="p-5 alert alert-danger m-0"
                                 role="alert"><?php esc_html_e('Please install or enable PHP cURL Extension on your server', 'wpdatatables'); ?></div>
                        </td>
                    <?php } ?>
                </tr>
            </table>
        </div>
    </div>
</div>
