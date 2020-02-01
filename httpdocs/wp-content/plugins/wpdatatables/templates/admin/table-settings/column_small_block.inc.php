<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<script type="text/x-template" id="wdt-column-small-block">

    <div class="wdt-column-block">
        <div class="fg-line m-l-10">
            <input type="text" class="form-control input-sm wdt-column-display-header-edit" value="New wpDataTable">
            <i class="zmdi zmdi-edit column-control"></i>
        </div>
        <div class="pull-right wdt-column-move-arrows m-l-5">
            <span class="pull-right"><i class="zmdi zmdi-unfold-more column-control"></i></span>
        </div>
        <span class="pull-right m-l-5" data-toggle="tooltip" title="<?php _e('Enable/disable editing');?>"<?php echo ' data-version="full-version-option-click"' ?>><i
                class="zmdi zmdi-edit column-control wdt-toggle-enable-editing"></i></span>
        <span class="pull-right m-l-5" data-toggle="tooltip" title="<?php _e('Show/hide filters');?>"<?php echo ' data-version="full-version-option-click"' ?>><i
                class="zmdi zmdi-filter-list column-control wdt-toggle-show-filters"></i></span>
        <span class="pull-right m-l-5" data-toggle="tooltip" title="<?php _e('Show/hide sorting'); ?>"><i
                class="zmdi zmdi-sort-asc column-control toggle-show-sorting"></i></span>
        <span class="pull-right m-l-5" data-toggle="tooltip" title="<?php _e('Show/hide the column'); ?>"><i
                    class="zmdi zmdi-eye column-control toggle-visibility"></i></span>
        <span class="pull-right m-l-5" data-toggle="tooltip" title="<?php _e('Show/hide on mobile'); ?>"<?php echo ' data-version="full-version-option-click"' ?>><i
                    class="zmdi zmdi-smartphone column-control wdt-toggle-show-on-mobile"></i></span>
        <span class="pull-right m-l-5" data-toggle="tooltip" title="<?php _e('Show/hide on tablet'); ?>"<?php echo ' data-version="full-version-option-click"' ?>><i
                    class="zmdi zmdi-smartphone-landscape column-control wdt-toggle-show-on-tablet"></i></span>
        <span class="pull-right m-l-5" data-toggle="tooltip" title="<?php _e('Open column settings'); ?>"><i
                    class="zmdi zmdi-wrench column-control open-settings"></i></span>
    </div>

</script>