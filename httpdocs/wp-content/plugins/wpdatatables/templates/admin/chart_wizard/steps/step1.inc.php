<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<div class="row">

    <div class="alert alert-warning alert-dismissible" role="alert" hidden>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?php _e('This feature is available in the ', 'wpdatatables'); ?><a class="tms-store-checkout-wpdatatables"><?php _e('full version of wpDataTables.', 'wpdatatables'); ?></a>
    </div>
    <div class="alert alert-info alert-dismissible" role="alert" hidden>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?php _e('NEW awesome features!!! From version wpDataTables Lite 2.1 you can create Google charts: Line, Column and Pie. All other chart types are available in the', 'wpdatatables'); ?> <a class="tms-store-checkout-wpdatatables dark"><?php _e('full version of wpDataTables.', 'wpdatatables'); ?></a>
    </div>

    <!-- .col-sm-4 -->
    <div class="col-sm-6 col-md-6 chart-name">
        <h4 class="c-black m-b-20">
            <?php _e('Chart name', 'wpdatatables'); ?>
            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
               title="<?php _e('Please define the title of the chart that you will use to identify it', 'wpdatatables'); ?>"></i>
        </h4>
        <div class="form-group">
            <div class="fg-line">
                <div class="row">
                    <div class="col-sm-12">
                        <input type="text" name="chart-name" id="chart-name" class="form-control input-sm"
                               value="<?php echo empty($chartId) ? __('New wpDataTable Chart', 'wpdatatables') : $chartObj->getTitle(); ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.col-sm-4 -->

    <!-- .col-sm-4 -->
    <div class="col-sm-6 col-md-6 render-engine">
        <h4 class="c-black m-b-20">
            <?php _e('Chart render engine', 'wpdatatables'); ?>

            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
               title="<?php _e('Please choose the render engine.', 'wpdatatables'); ?> <?php _e('Please note that HighCharts license is NOT included with wpDataTables and you must purchase the license separately on', 'wpdatatables'); ?> http://highcharts.com"></i>
        </h4>
        <div class="form-group">
            <div class="fg-line">
                <div class="select">
                    <select class="selectpicker" name="chart-render-engine" id="chart-render-engine">
                        <option value="" <?php echo empty($chartId) ? 'selected="selected"' : ''; ?> ><?php _e('Pick the render engine', 'wpdatatables'); ?></option>
                        <option value="google"
                                <?php if (!empty($chartId) && ($chartObj->getEngine() == 'google')){ ?>selected="selected"<?php } ?> >
                            Google Charts
                        </option>
                        <option value="highcharts"
                                <?php if (!empty($chartId) && ($chartObj->getEngine() == 'highcharts')){ ?>selected="selected"<?php } ?> >
                            HighCharts
                        </option>
                        <option value="chartjs"
                                <?php if (!empty($chartId) && ($chartObj->getEngine() == 'chartjs')){ ?>selected="selected"<?php } ?> >
                            Chart.js
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!-- /.col-sm-4 -->

</div>
<!--/.row -->

<!-- .row -->
<div class="row">

    <!-- div.google-charts-type -->
    <div class="charts-type google-charts-type col-sm-12">

        <?php include WDT_TEMPLATE_PATH . 'admin/chart_wizard/steps/charts_pick/google_charts.inc.php'; ?>

    </div>
    <!-- /div.google-charts-type -->

    <!-- div.highcharts-charts-type -->
    <div class="charts-type highcharts-charts-type col-sm-12 col-md-12">

        <?php include WDT_TEMPLATE_PATH . 'admin/chart_wizard/steps/charts_pick/highcharts.inc.php'; ?>

    </div>
    <!-- /div.highcharts-charts-type -->

    <!-- div.chartjs-charts-type -->
    <div class="charts-type chartjs-charts-type col-sm-12 col-md-12">

        <?php include WDT_TEMPLATE_PATH . 'admin/chart_wizard/steps/charts_pick/chartjs.inc.php'; ?>

    </div>
    <!-- /div.chartjs-charts-type -->

</div>
<!--/.row -->
