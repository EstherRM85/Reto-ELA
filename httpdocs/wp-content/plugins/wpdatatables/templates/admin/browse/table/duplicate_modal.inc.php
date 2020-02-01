<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<!-- #wdt-duplicate-table-modal -->
<div class="modal fade" id="wdt-duplicate-table-modal" data-backdrop="static" data-keyboard="false" tabindex="-1"
     role="dialog" aria-hidden="true">
    <?php wp_nonce_field('wdtDuplicateTableNonce', 'wdtNonce'); ?>

    <!-- .modal-dialog -->
    <div class="modal-dialog">

        <!-- .modal-content -->
        <div class="modal-content">

            <!-- .modal-header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php _e('Duplicate table', 'wpdatatables') ?></h4>
            </div>
            <!--/ .modal-header -->

            <!-- .modal-body -->
            <div class="modal-body">
                <!-- .row -->
                <div class="row">
                    <div class="col-sm-5">
                        <label class="duplicate-table-label"><?php _e('New table title', 'wpdatatables'); ?></label>
                    </div>
                    <div class="col-sm-7">
                        <input type="text" value="" class="wdt-duplicate-table-name form-control input-sm" title=""/>
                    </div>
                </div>
                <!--/ .row -->

                <!--/ .row -->
                <div class="row wdt-duplicate-manual-table">
                    <div class="col-sm-8">
                        <label class="duplicate-table-label">
                            <?php _e('Duplicate database table', 'wpdatatables'); ?>
                        </label>
                        <span class="zmdi zmdi-help-outline duplicate-explain-trigger" data-toggle="tooltip"
                              data-html="true" data-placement="right"
                              title="<strong><?php _e('Unchecked', 'wpdatatables'); ?> -</strong>  <?php _e('will create exact copy of this table which means that all changes made in one table will be reflected in all copies.', 'wpdatatables'); ?><br /><strong><?php _e('Checked', 'wpdatatables'); ?> -</strong>  <?php _e('will create separate database table so changing one table won\'t affect other copies.', 'wpdatatables'); ?>"></span>
                    </div>
                    <div class="col-sm-4">
                        <div class="toggle-switch checkbox" data-ts-color="blue">
                            <input type="checkbox" id="wdt-duplicate-database" name="wdt-duplicate-database"
                                   value="duplicate" title="" hidden="hidden" checked="checked">
                            <label for="wdt-duplicate-database" class="ts-helper"></label>
                        </div>
                    </div>
                </div>
                <!--/ .row -->
            </div>
            <!--/ .modal-body -->

            <!-- .modal-footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-icon-text waves-effect" data-dismiss="modal"><i
                            class="zmdi zmdi-close"></i> <?php _e('Cancel', 'wpdatatables'); ?></button>
                <button type="button"
                        class="btn btn-success btn-icon-text waves-effect wdt-apply duplicate-table-button"><i
                            class="zmdi zmdi-copy"></i> <?php _e('Duplicate', 'wpdatatables'); ?></button>
            </div>
            <!--/ .modal-footer -->
        </div>
        <!--/ .modal-content -->
    </div>
    <!--/ .modal-dialog -->
</div>
<!--/ #wdt-duplicate-table-modal -->