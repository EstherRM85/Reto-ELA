<?php defined('ABSPATH') or die("Cannot access pages directly."); ?>

<?php if( isset( $chartObj ) ) { ?>
    <script type='text/javascript'>var editing_chart_data= {render_data: <?php echo json_encode( $chartObj->getRenderData() ); ?>, highcharts_render_data: <?php echo json_encode( $chartObj->getHighchartsRenderData() ); ?>, engine: "<?php echo $chartObj->getEngine();?>", type: "<?php echo $chartObj->getType(); ?>", selected_columns: <?php echo json_encode( $chartObj->getSelectedColumns() ) ?>, range_type: "<?php echo $chartObj->getRangeType() ?>"<?php if( $chartObj->getRangeType() == 'picked_range' ){ ?>, row_range: <?php echo json_encode( $chartObj->getRowRange() ); } ?>, title: "<?php echo $chartObj->getTitle(); ?>", follow_filtering: <?php echo (int) $chartObj->getFollowFiltering(); ?>, wpdatatable_id: <?php echo $chartObj->getwpDataTableId(); ?>  };</script>
<?php } ?>

<div class="wpDataTables metabox-holder">
    <div id="wdtPreloadLayer" class="overlayed">
    </div>

    <div class="wrap">
        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="postbox-container-1" class="postbox-container">
                    <img src="<?php echo dirname(plugin_dir_url(__FILE__)); ?>/assets/img/wpdatatables-logo.png" class="wpdatatables_logo" />
                    <p><i><?php _e('Please refer to','wpdatatables');?> <a href="http://wpdatatables.com/documentation/wpdatacharts/"><?php _e('wpDataTables documentation on Charts','wpdatatables');?></a> <?php _e('if you have some questions or problems with the plugin.','wpdatatables'); ?></i></p>
                    <h2><?php _e('wpDataTables Chart Wizard','wpdatatables'); ?></h2>

                    <input type="hidden" id="wpDataChartId" value="<?php echo $chart_id ?>" />
                    <input type="hidden" id="wdtBrowseChartsURL" value="<?php echo admin_url( 'admin.php?page=wpdatatables-charts' ); ?>" />

                    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
                        <div class="postbox">
                            <div class="handlediv" title="<?php _e('Click to toggle','wpdatatables'); ?>"><br/></div>
                            <h3 class="hndle">
                                <span><div class="dashicons dashicons-edit"></div> <?php _e('Chart Creation Wizard','wpdatatables'); ?></span>
                            </h3>
                            <div class="inside">

                                <div class="chart_wizard_breadcrumbs">
                                    <div class="chart_wizard_breadcrumbs_block active step1">
                                        <?php _e( 'Chart title & type', 'wpdatatables' ); ?>
                                    </div>
                                    <span class="chart_wizard_breadcrumbs_separator"> &gt; </span>
                                    <div class="chart_wizard_breadcrumbs_block step2">
                                        <?php _e( 'Data source', 'wpdatatables' ); ?>
                                    </div>
                                    <span class="chart_wizard_breadcrumbs_separator"> &gt; </span>
                                    <div class="chart_wizard_breadcrumbs_block step3">
                                        <?php _e( 'Data range', 'wpdatatables' ); ?>
                                    </div>
                                    <span class="chart_wizard_breadcrumbs_separator"> &gt; </span>
                                    <div class="chart_wizard_breadcrumbs_block step4">
                                        <?php _e( 'Formatting', 'wpdatatables' ); ?>
                                    </div>
                                    <span class="chart_wizard_breadcrumbs_separator"> &gt; </span>
                                    <div class="chart_wizard_breadcrumbs_block step5">
                                        <?php _e( 'Preview', 'wpdatatables' ); ?>
                                    </div>
                                    <span class="chart_wizard_breadcrumbs_separator"> &gt; </span>
                                    <div class="chart_wizard_breadcrumbs_block step6">
                                        <?php _e( 'Save and get shortcode', 'wpdatatables' ); ?>
                                    </div>
                                </div>

                                <div class="steps">

                                    <div class="chartWizardStep step1" data-step="step1">
                                        <h3><?php _e('Chart title, rendering engine and type','wpdatatables'); ?></h3>
                                        <fieldset style="margin: 10px;">
                                            <?php echo '<strong style="color: green;">Sorry, this function is available only in FULL version of wpDataTables along with many others! Please go to our <a href="http://wpdatatables.com/">website</a> to see the full list and to purchase!</strong>' ?>
                                            <table>
                                                <tr>
                                                    <td style="width: 250px">
                                                        <label for="chart_name"><span><strong><?php _e('Chart name','wpdatatables');?></strong></span></label><br/>
                                                        <span class="description"><small><?php _e('What is the title of the chart that you will use to identify it?','wpdatatables');?>.</small></span>
                                                    </td>
                                                    <td>
                                                        <input id="chart_name" type="text" value="<?php echo empty( $chart_id ) ? __( 'New wpDataTable Chart', 'wpdatatables' ) : $chartObj->getTitle(); ?>" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                <tr>
                                                    <td>
                                                        <label for="table_name"><span><strong><?php _e('Chart render engine','wpdatatables');?></strong></span></label><br/>
                                                        <span class="description"><small><?php _e('Please choose the render engine.','wpdatatables');?> <strong><?php _e('Please note that HighCharts license is NOT included with wpDataTables and you must purchase the license separately on','wpdatatables');?> <a http://highcharts.com/>http://highcharts.com</a></strong></small></span>
                                                    </td>
                                                    <td>
                                                        <select id="chart_render_engine">
                                                            <option value="" <?php echo empty( $chart_id ) ? 'selected="selected"' : ''; ?> ><?php _e('Pick the render engine','wpdatatables'); ?></option>
                                                            <option value="google" <?php if( !empty( $chart_id ) && ( $chartObj->getEngine() == 'google' ) ){ ?>selected="selected"<?php } ?> >Google Charts</option>
                                                            <option value="highcharts" <?php if( !empty( $chart_id ) && ( $chartObj->getEngine() == 'highcharts' ) ){ ?>selected="selected"<?php } ?> >HighCharts</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr class="charts_type google_charts_type" style="display: none">
                                                    <td colspan="2">
                                                        <label for="google_chart_type"><span><strong><?php _e('Pick a Google chart type','wpdatatables');?></strong></span></label><br/><br/>
                                                        <select id="google_chart_type" style="display: none !important;">
                                                            <option value="google_column_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/google_column_chart.jpg"><?php _e( 'Column chart', 'wpdatatables' ); ?></option>
                                                            <option value="google_histogram" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/google_histogram.jpg"><?php _e( 'Histogram', 'wpdatatables' ); ?></option>
                                                            <option value="google_bar_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/google_bar_chart.jpg"><?php _e( 'Bar chart', 'wpdatatables' ); ?></option>
                                                            <option value="google_area_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/google_area_chart.jpg"><?php _e( 'Area chart', 'wpdatatables' ); ?></option>
                                                            <option value="google_stepped_area_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/google_stepped_area_chart.jpg"><?php _e( 'Stepped area chart', 'wpdatatables' ); ?></option>
                                                            <option value="google_line_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/google_line_chart.jpg"><?php _e( 'Line chart', 'wpdatatables' ); ?></option>
                                                            <option value="google_pie_chart" data-min_columns="2" data-max_columns="2" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/google_pie_chart.jpg"><?php _e( 'Pie chart', 'wpdatatables' ); ?></option>
                                                            <option value="google_bubble_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/google_bubble_chart.jpg"><?php _e( 'Bubble chart', 'wpdatatables' ); ?></option>
                                                            <option value="google_donut_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/google_donut_chart.jpg"><?php _e( 'Donut chart', 'wpdatatables' ); ?></option>
                                                            <option value="google_gauge_chart" data-min_columns="1" data-max_columns="2" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/google_gauge_chart.jpg"><?php _e( 'Gauge chart', 'wpdatatables' ); ?></option>
                                                            <option value="google_scatter_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/google_scatter_chart.jpg"><?php _e( 'Scatter chart', 'wpdatatables' ); ?></option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr class="charts_type highcharts_charts_type" style="display: none !important;">
                                                    <td colspan="2">
                                                        <label for="highcharts_chart_type"><span><strong><?php _e('Pick a Highcharts chart type','wpdatatables');?></strong></span></label><br/><br/>
                                                        <select id="highcharts_chart_type" style="display: none !important;">
                                                            <option value="highcharts_line_chart"  data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_line_chart.jpg"><?php _e( 'Line chart', 'wpdatatables' ); ?></option>
                                                            <option value="highcharts_basic_area_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_basic_area_chart.jpg"><?php _e( 'Basic area chart', 'wpdatatables' ); ?></option>
                                                            <option value="highcharts_stacked_area_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_stacked_area_chart.jpg"><?php _e( 'Stacked area chart', 'wpdatatables' ); ?></option>
                                                            <option value="highcharts_basic_bar_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_basic_bar_chart.jpg"><?php _e( 'Basic bar chart', 'wpdatatables' ); ?></option>
                                                            <option value="highcharts_stacked_bar_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_stacked_bar_chart.jpg"><?php _e( 'Stacked bar chart', 'wpdatatables' ); ?></option>
                                                            <option value="highcharts_basic_column_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_basic_column_chart.jpg"><?php _e( 'Basic column chart', 'wpdatatables' ); ?></option>
                                                            <option value="highcharts_stacked_column_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_stacked_column_chart.jpg"><?php _e( 'Stacked column chart', 'wpdatatables' ); ?></option>
                                                            <option value="highcharts_pie_chart" data-min_columns="2" data-max_columns="2" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_pie_chart.jpg"><?php _e( 'Pie chart', 'wpdatatables' ); ?></option>
                                                            <option value="highcharts_pie_with_gradient_chart" data-min_columns="2" data-max_columns="2" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_pie_with_gradient_chart.jpg"><?php _e( 'Pie with gradient chart', 'wpdatatables' ); ?></option>
                                                            <option value="highcharts_donut_chart" data-min_columns="2" data-max_columns="2" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_donut_chart.jpg"><?php _e( 'Donut chart', 'wpdatatables' ); ?></option>
                                                            <option value="highcharts_scatter_plot" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_scatter_plot.jpg"><?php _e( 'Scatter plot', 'wpdatatables' ); ?></option>
                                                            <option value="highcharts_3d_column_chart" data-min_columns="2" data-max_columns="0" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_3d_column_chart.jpg"><?php _e( '3D column chart', 'wpdatatables' ); ?></option>
                                                            <option value="highcharts_3d_pie_chart" data-min_columns="2" data-max_columns="2" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_3d_pie_chart.jpg"><?php _e( '3D pie chart', 'wpdatatables' ); ?></option>
                                                            <option value="highcharts_3d_donut_chart" data-min_columns="2" data-max_columns="2" data-img-src="<?php echo WDT_ASSETS_PATH?>img/chart-thumbs/highcharts_3d_donut_chart.jpg"><?php _e( '3D donut chart', 'wpdatatables' ); ?></option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </div>
                                    
                                    <?php  ?>

                                </div>

                                <?php  ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php  ?>