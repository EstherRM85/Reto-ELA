<?php defined('ABSPATH') or die('Access denied.'); ?>

<!-- #wdt-duplicate-chart-modal -->
<div class="modal fade" id="wdt-duplicate-chart-modal" data-backdrop="static" data-keyboard="false" tabindex="-1"
     role="dialog" aria-hidden="true">
    <?php wp_nonce_field('wdtDuplicateChartNonce', 'wdtNonce'); ?>

    <!-- .modal-dialog -->
    <div class="modal-dialog">

        <!-- .modal-content -->
        <div class="modal-content">

            <!-- .modal-header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php _e('Duplicate chart', 'wpdatatables') ?></h4>
            </div>
            <!--/ .modal-header -->

            <!-- .modal-body -->
            <div class="modal-body">
                <!-- .row -->
                <div class="row">
                    <div class="col-sm-5">
                        <label class="duplicate-chart-label"><?php _e('New chart title', 'wpdatatables'); ?></label>
                    </div>
                    <div class="col-sm-7">
                        <input type="text" value="" class="wdt-duplicate-chart-name form-control input-sm" title=""/>
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
                        class="btn btn-success btn-icon-text waves-effect wdt-apply duplicate-chart-button"><i
                            class="zmdi zmdi-copy"></i> <?php _e('Duplicate', 'wpdatatables'); ?></button>
            </div>
            <!--/ .modal-footer -->
        </div>
        <!--/ .modal-content -->
    </div>
    <!--/ .modal-dialog -->
</div>
<!--/ #wdt-duplicate-chart-modal -->
