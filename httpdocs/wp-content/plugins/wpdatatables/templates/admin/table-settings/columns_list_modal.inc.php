<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<!-- #wdtColumnsListModal -->
<div class="modal fade" id="wdt-columns-list-modal" data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Columns', 'wpdatatables'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <span class="wdt-alert-title f-600"><?php _e('List of the columns in the data source with quickaccess tools.', 'wpdatatables'); ?>
                        <br></span>
                    <span class="wdt-alert-subtitle"><?php _e('Click column header to rename it, toggle column visibility by clicking on the eye icon, open column settings by clicking on the wrench icon, drag and drop blocks to reorder columns.', 'wpdatatables'); ?></span>
                </div>
                <div class="wdt-columns-container">
                    <!-- Column blocks go here -->
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-icon-text waves-effect" data-dismiss="modal">
                    <i class="zmdi zmdi-close"></i>
                    <?php _e('Close', 'wpdatatables'); ?>
                </button>
                <button type="button" class="btn btn-success btn-icon-text waves-effect" id="wdt-apply-columns-list">
                    <i class="zmdi zmdi-check"></i>
                    <?php _e('Save', 'wpdatatables'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- /#wdtColumnsListModal -->