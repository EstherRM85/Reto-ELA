<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<?php /** @var WDTBrowseTable $this */ ?>
<!-- .container -->
<div class="container">
    <div class="row m-t-30 m-b-30">
        <div class="col-xs-6">
            <div class="col-sm-6 bulk-action-container">
                <?php $this->display_tablenav('top'); ?>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="col-sm-6 pull-right search-box-container">
                <?php $this->search_box('search', 'search_id'); ?>
            </div>
        </div>
    </div>
</div>
<!--/ .container -->

<!-- .wp-list-table -->
<table class="wp-list-table <?php echo implode(' ', $this->get_table_classes()); ?>">
    <thead>
    <tr>
        <?php $this->print_column_headers(); ?>
    </tr>
    </thead>

    <tbody id="the-list"<?php if ($singular) {
        echo " data-wp-lists='list:$singular'";
    } ?>>
    <?php $this->display_rows_or_placeholder(); ?>
    </tbody>

    <tfoot>
    <tr>
        <?php $this->print_column_headers(false); ?>
    </tr>
    </tfoot>
</table>
<!--/ .wp-list-table -->

<!-- .container -->
<div class="container">
    <div class="row m-t-30 m-b-15">
        <div class="col-xs-6">
            <div class="col-sm-6 bulk-action-container">
                <?php $this->display_tablenav('bottom'); ?>
            </div>
        </div>
        <div class="col-xs-6">
            <?php $this->pagination('bottom'); ?>
        </div>
    </div>
</div>
<!--/ .container -->

<!-- .container -->
<div class="container">
    <div class="row m-t-15 m-b-30 p-l-10 p-r-10">
        <div class="col-xs-12">
            <a class="btn btn-default btn-icon-text waves-effect wdt-documentation" data-doc-page="browse_page">
                <i class="zmdi zmdi-help-outline"></i> Documentation
            </a>
        </div>
    </div>
</div>
<!--/ .container -->