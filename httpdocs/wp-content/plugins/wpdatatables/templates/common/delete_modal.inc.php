<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<!-- .wpdt-c -->
<div class="wpdt-c">
    <!-- #wdt-delete-modal -->
    <div class="modal fade in" id="wdt-delete-modal" style="display: none" data-backdrop="static" data-keyboard="false"
         tabindex="-1" role="dialog" aria-hidden="true">

        <!-- .modal-dialog -->
        <div class="modal-dialog">

            <!-- .modal-content -->
            <div class="modal-content">

                <!-- .modal-header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php _e('Are you sure?', 'wpdatatables') ?></h4>
                </div>
                <!--/ .modal-header -->

                <!-- .modal-body -->
                <div class="modal-body">
                    <!-- .row -->
                    <div class="row">
                        <div class="col-sm-12">
                            <small><?php _e('Please confirm deletion. There is no undo!', 'wpdatatables'); ?></small>
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
                            id="wdt-browse-delete-button"><i
                                class="zmdi zmdi-delete"></i> <?php _e('Delete', 'wpdatatables'); ?></button>
                </div>
                <!--/ .modal-footer -->
            </div>
            <!--/ .modal-content -->
        </div>
        <!--/ .modal-dialog -->
    </div>
    <!--/ #wdt-delete-modal -->
</div>
<!--/ .wpdt-c -->