<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<!-- #wdt-backend-close-modal -->
<div class="modal fade" id="wdt-backend-close-modal" data-backdrop="static" data-keyboard="false" tabindex="-1"
     role="dialog" aria-hidden="true">

    <!-- .modal-dialog -->
    <div class="modal-dialog">

        <!-- .modal-content -->
        <div class="modal-content">

            <!-- .modal-header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php _e('Are you sure?', 'wpdatatables') ?></h4>
            </div>
            <!--/ .modal-header -->

            <!-- .modal-body -->
            <div class="modal-body">
                <!-- .row -->
                <div class="row">
                    <div class="col-sm-12">
                        <small><?php _e('There is no undo! You will be redirected to wpDataTables page.', 'wpdatatables'); ?></small>
                    </div>
                </div>
                <!--/ .row -->
            </div>
            <!--/ .modal-body -->

            <!-- .modal-footer -->
            <div class="modal-footer">
                <button type="button" class="btn bgm-gray btn-icon-text waves-effect" data-dismiss="modal"><i
                            class="zmdi zmdi-close"></i> <?php _e('Cancel', 'wpdatatables'); ?></button>
                <button type="button" class="btn btn-danger btn-icon-text waves-effect"
                        id="wdt-backend-close-button"><?php _e('Yes', 'wpdatatables'); ?></button>
            </div>
            <!--/ .modal-footer -->
        </div>
        <!--/ .modal-content -->
    </div>
    <!--/ .modal-dialog -->
</div>
<!--/ #wdt-backend-close-modal -->