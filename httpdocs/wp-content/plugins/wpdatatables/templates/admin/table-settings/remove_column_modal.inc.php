<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<!-- .wdt-remove-column-modal -->
<div class="modal fade" id="wdt-remove-column-modal" data-backdrop="static" data-keyboard="false" tabindex="-1"
     role="dialog" aria-hidden="true">

    <!-- .modal-dialog -->
    <div class="modal-dialog">

        <!-- .modal-content -->
        <div class="modal-content">

            <!-- Preloader -->
            <?php include WDT_TEMPLATE_PATH . 'admin/common/preloader.inc.php'; ?>
            <!-- /Preloader -->

            <!-- .modal-header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><?php _e('Remove Column', 'wpdatatable'); ?></h4>
            </div>
            <!--/ .modal-header -->

            <!-- .modal-body -->
            <div class="modal-body">

                <div class="wdt-remove-column-modal-block">

                    <div class="row">

                        <!-- .form-group -->
                        <div class="form-group col-xs-12">
                            <label for="wdtDeleteColumnSelect"
                                   class="col-sm-3 control-label"><strong><?php _e('Remove Column', 'wpdatatable'); ?></strong></label>
                            <div class="col-sm-9">
                                <div class="fg-line">
                                    <div class="select">
                                        <select id="wdtDeleteColumnSelect" class="selectpicker">
                                            <?php if (isset($tableData)){
                                                foreach ($tableData->table->columns as $column) { ?>
                                                    <?php if ($column->orig_header == 'wdt_ID') {
                                                        continue;
                                                    } ?>
                                                    <option value="<?php echo $column->orig_header; ?>"><?php echo $column->display_header; ?></option>
                                                <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ .form-group -->

                        <!-- .form-group -->
                        <div class="form-group col-xs-12 wdt-remove-column-confirmation">
                            <label for="wdtDeleteColumnSelect"
                                   class="col-sm-3 control-label"><strong><?php _e('Confirm', 'wpdatatable'); ?></strong></label>
                            <div class="col-sm-9">
                                <div class="fg-line checkbox">
                                    <label>
                                        <input id="wdt-remove-column-confirm" type="checkbox" value="">
                                        <i class="input-helper"></i>
                                        <?php _e('Are you sure? There is no undo!', 'wpdatatables'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--/ .form-group -->
                    </div>

                </div>

            </div>
            <!--/ .modal-body -->

            <!-- .modal-footer -->
            <div class="modal-footer">
                <button class="btn btn-danger btn-icon-text waves-effect" data-toggle="modal"
                        data-target="#wdt-remove-column-modal">
                    <i class="zmdi zmdi-close"></i>
                    <?php _e('Cancel', 'wpdatatables'); ?>
                </button>
                <button class="btn btn-success btn-icon-text waves-effect" id="wdt-remove-column-submit">
                    <i class="zmdi zmdi-check"></i>
                    <?php _e('OK', 'wpdatatables'); ?>
                </button>
            </div>
            <!--/ .modal-footer -->
        </div>
        <!--/ .modal-content -->
    </div>
    <!--/ .modal-dialog -->
</div>
<!--/ .wdt-remove-column-modal -->
