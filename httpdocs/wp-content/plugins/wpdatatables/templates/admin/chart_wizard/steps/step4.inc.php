<?php defined('ABSPATH') or die('Access denied.'); ?>

<div class="row">
    <div class="alert alert-warning alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
<?php _e('All options that are disabled are available in the','wpdatatables')?> <a class="tms-store-checkout-wpdatatables"><?php _e('full version of wpDataTables.','wpdatatables')?></a>
    </div>

    <div class="col-sm-5 col-md-5 col-lg-5 m-b-20">
        <div id="chart-container-tabs" class=" settings">

            <div class="col-sm-3 col-md-3 col-lg-4">
                <ul class="tab-nav settings">
                    <li class="chart-container active"><a href="#chart-container-tabs-1"
                                                          data-toggle="tab"><?php _e('Chart', 'wpdatatables'); ?></a>
                    </li>
                    <li class="chart-container series"><a href="#chart-container-tabs-2" data-toggle="tab"
                                                          class=""><?php _e('Series', 'wpdatatables'); ?></a></li>
                    <li class="chart-container axes"><a href="#chart-container-tabs-3" data-toggle="tab"
                                                        class=""><?php _e('Axes', 'wpdatatables'); ?></a></li>
                    <li class="chart-container title"><a href="#chart-container-tabs-4" data-toggle="tab"
                                                         class=""><?php _e('Title', 'wpdatatables'); ?></a></li>
                    <li class="chart-container tooltips"><a href="#chart-container-tabs-5" data-toggle="tab"
                                                            class=""><?php _e('Tooltip', 'wpdatatables'); ?></a></li>
                    <li class="chart-container legend"><a href="#chart-container-tabs-6" data-toggle="tab"
                                                          class=""><?php _e('Legend', 'wpdatatables'); ?></a></li>
                    <li class="chart-container highcharts"><a href="#chart-container-tabs-7" data-toggle="tab"
                                                              class=""><?php _e('Exporting', 'wpdatatables'); ?></a>
                    </li>
                    <li class="chart-container highcharts"><a href="#chart-container-tabs-8" data-toggle="tab"
                                                              class=""><?php _e('Credits', 'wpdatatables'); ?></a></li>
                </ul>
            </div>

            <div class="tab-content p-0">
                <div id="chart-container-tabs-2"
                     class="col-sm-9 col-md-9 col-lg-8 chart-container chart-options-container tab-pane">
                    <div>
                        <h4 class="c-black m-b-20">
                            <?php _e('Series settings', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('If you want to redefine the series labels and colors you can do it here.', 'wpdatatables'); ?>"></i>
                        </h4>
                    </div>
                    <div>
                        <div id="series-settings-container">

                        </div>
                    </div>
                    <div class="chartjs google disabled" id="curve-type-row">
                        <h4 class="c-black m-b-20">
                            <?php _e('Curve type', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('Controls the curve of the lines', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="toggle-switch p-b-20 p-t-5" data-ts-color="blue">
                            <label for="curve-type"><?php _e('Check for smoothed lines', 'wpdatatables'); ?></label>
                            <input id="curve-type" type="checkbox" hidden="hidden">
                            <label for="curve-type" class="ts-helper"></label>
                        </div>
                    </div>
                </div>
                <div id="chart-container-tabs-1"
                     class="col-sm-9 col-md-9 col-lg-8 chart-container chart-options-container tab-pane active">
                    <div class="chart-width">
                        <h4 class="c-black m-b-20">
                            <?php _e('Chart width', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('The width of the chart.', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="form-group">
                            <div class="fg-line">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="number" name="chart-width" id="chart-width"
                                               class="form-control input-sm" value="400"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="responsive-width disabled">
                        <h4 class="c-black m-b-20">
                            <?php _e('Responsive width', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('If you tick this chart width will always adjust to 100% width of the container', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="toggle-switch p-b-20 p-t-5" data-ts-color="blue">
                            <label for="chart-responsive-width"><?php _e('Responsive chart width', 'wpdatatables'); ?></label>
                            <input id="chart-responsive-width" type="checkbox" hidden="hidden">
                            <label for="chart-responsive-width" class="ts-helper"></label>
                        </div>
                    </div>
                    <div class="chart-height">
                        <h4 class="c-black m-b-20">
                            <?php _e('Chart height', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('The height of the chart.', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="form-group">
                            <div class="fg-line">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="number" name="chart-height" id="chart-height"
                                               class="form-control input-sm" value="400"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="group-chart disabled">
                        <h4 class="c-black m-b-20">
                            <?php _e('Group chart', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('If you tick this checkbox, the values of the rows with same label will be summed up and rendered as a single series. If you leave it unticked all rows will be rendered as separate series.', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="toggle-switch p-b-20 p-t-5" data-ts-color="blue">
                            <label for="group-chart"><?php _e('Enable grouping', 'wpdatatables'); ?></label>
                            <input id="group-chart" type="checkbox" hidden="hidden">
                            <label for="group-chart" class="ts-helper"></label>
                        </div>
                    </div>
                    <div class="background-color-container" id="background-color-container">
                        <h4 class="c-black m-b-20">
                            <?php _e('Background color', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('The background color for the outer chart area.', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="cp-container">
                            <div class="form-group">
                                <div class="fg-line dropdown">
                                    <div id="cp"
                                         class="input-group colorpicker-component colorpicker-element color-picker wpcolorpicker">
                                        <input type="text" id="background-color" value=""
                                               class="form-control cp-value background-color"/>
                                        <span class="input-group-addon wpcolorpicker-icon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-width">
                        <h4 class="c-black m-b-20">
                            <?php _e('Border width', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('The pixel width of the outer chart border.', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="form-group">
                            <div class="fg-line">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="number" name="border-width" id="border-width"
                                               class="form-control input-sm" value="0"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-color-container" id="border-color-container">
                        <h4 class="c-black m-b-20">
                            <?php _e('Border color', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('The color of the outer chart border.', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="cp-container">
                            <div class="form-group">
                                <div class="fg-line dropdown">
                                    <div id="cp"
                                         class="input-group colorpicker-component colorpicker-element color-picker wpcolorpicker">
                                        <input type="text" id="border_color" value=""
                                               class="form-control cp-value plot border_color"/>
                                        <span class="input-group-addon wpcolorpicker-icon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-radius">
                        <h4 class="c-black m-b-20">
                            <?php _e('Border radius', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('The corner radius of the outer chart border.', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="form-group">
                            <div class="fg-line">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="number" name="border-radius" id="border-radius"
                                               class="form-control input-sm" value="0"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="google highcharts" id="plot-background-color-container">
                        <h4 class="c-black m-b-20">
                            <?php _e('Plot background color', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('The background color or gradient for the plot area.', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="cp-container">
                            <div class="form-group">
                                <div class="fg-line dropdown">
                                    <div id="cp"
                                         class="input-group colorpicker-component colorpicker-element color-picker wpcolorpicker">
                                        <input type="text" id="plot-background-color" value=""
                                               class="form-control cp-value plot-background-color"/>
                                        <span class="input-group-addon wpcolorpicker-icon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="google highcharts" id="plot-border-width-row">
                        <h4 class="c-black m-b-20">
                            <?php _e('Plot border width', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('The corner radius of the outer chart border.', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="form-group">
                            <div class="fg-line">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="number" name="plot-border-width" id="plot-border-width"
                                               class="form-control input-sm plot-border-width" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="google highcharts" id="plot-border-color-container">
                        <h4 class="c-black m-b-20">
                            <?php _e('Plot border color', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('The color of the inner chart or plot area border.', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="cp-container">
                            <div class="form-group">
                                <div class="fg-line dropdown">
                                    <div id="cp"
                                         class="input-group colorpicker-component colorpicker-element color-picker wpcolorpicker">
                                        <input type="text" id="plot-border-color" value=""
                                               class="form-control cp-value plot-border-color"/>
                                        <span class="input-group-addon wpcolorpicker-icon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="google chartjs disabled" id="font-size-row">
                        <h4 class="c-black m-b-20">
                            <?php _e('Font size', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('The default font size, in pixels, of all text in the chart.', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="form-group">
                            <div class="fg-line">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="number" min="0" name="font-size" id="font-size"
                                               class="form-control input-sm" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="google chartjs disabled" id="font-name-row">
                        <h4 class="c-black m-b-20">
                            <?php _e('Font name', 'wpdatatables'); ?>
                            <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                               title="<?php _e('The default font face for all text in the chart.', 'wpdatatables'); ?>"></i>
                        </h4>
                        <div class="form-group">
                            <div class="fg-line">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="text" name="font-name" id="font-name" value="Arial"
                                               class="form-control input-sm"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="chart-container-tabs-3"
                     class="col-sm-9 col-md-9 col-lg-8 chart-container chart-options-container tab-pane">
                    <div class="inside">
                        <div id="show-grid-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Grid', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('Controls the curve of the lines', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="toggle-switch p-b-20 p-t-5" data-ts-color="blue">
                                <label for="show-grid"><?php _e('Do you want to show grid on the chart', 'wpdatatables'); ?></label>
                                <input id="show-grid" type="checkbox" hidden="hidden" checked>
                                <label for="show-grid" class="ts-helper"></label>
                            </div>
                        </div>
                        <div id="horizontal-axis-label-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Horizontal axis label', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('Name of the horizontal axis.', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="form-group">
                                <div class="fg-line">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input type="text" id="horizontal-axis-label" value=""
                                                   class="form-control input-sm"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="google highcharts disabled" id="horizontal-axis-crosshair-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Horizontal crosshair', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('Configure a horizontal crosshair that follows either the mouse pointer or the hovered point lines', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="toggle-switch p-b-20 p-t-5" data-ts-color="blue">
                                <label for="horizontal-axis-crosshair"><?php _e('Show x-Axis crosshair', 'wpdatatables'); ?></label>
                                <input id="horizontal-axis-crosshair" type="checkbox" hidden="hidden">
                                <label for="horizontal-axis-crosshair" class="ts-helper"></label>
                            </div>
                        </div>
                        <div class="google disabled" id="horizontal-axis-direction-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Horizontal axis direction', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('The direction in which the values along the horizontal axis grow. Specify -1 to reverse the order of the values', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="form-group">
                                <div class="fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="horizontal-axis-direction"
                                                id="horizontal-axis-direction">
                                            <option selected="selected" value="1">1</option>
                                            <option value="-1">-1</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="vertical-axis-label-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Vertical axis label', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('Name of the vertical axis.', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="form-group">
                                <div class="fg-line">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input type="text" id="vertical-axis-label" value=""
                                                   class="form-control input-sm"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="google highcharts disabled" id="vertical-axis-crosshair-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Vertical crosshair', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('Configure a vertical crosshair that follows either the mouse pointer or the hovered point lines', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="toggle-switch p-b-20 p-t-5" data-ts-color="blue">
                                <label for="vertical-axis-crosshair"><?php _e('Show y-Axis crosshair', 'wpdatatables'); ?></label>
                                <input id="vertical-axis-crosshair" type="checkbox" hidden="hidden">
                                <label for="vertical-axis-crosshair" class="ts-helper"></label>
                            </div>
                        </div>
                        <div class="google disabled" id="vertical-axis-direction-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Vertical axis direction', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('The direction in which the values along the vertical axis grow. Specify -1 to reverse the order of the values', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="form-group">
                                <div class="fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="vertical-axis-direction"
                                                id="vertical-axis-direction">
                                            <option selected="selected" value="1">1</option>
                                            <option value="-1">-1</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="disabled" id="vertical-axis-min-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Vertical axis min value', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('The minimum value of the axis.', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="form-group">
                                <div class="fg-line">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input type="number" min="0" id="vertical-axis-min" value=""
                                                   class="form-control input-sm"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="disabled" id="vertical-axis-max-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Vertical axis max value', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('The maximum value of the axis.', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="form-group">
                                <div class="fg-line">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input type="number" min="0" id="vertical-axis-max" value=""
                                                   class="form-control input-sm"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="google highcharts disabled" id="inverted-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Invert', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('Whether to invert the axes so that the x axis is vertical and y axis is horizontal', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="toggle-switch p-b-20 p-t-5" data-ts-color="blue">
                                <label for="inverted"><?php _e('Invert chart axes', 'wpdatatables'); ?></label>
                                <input id="inverted" type="checkbox" hidden="hidden">
                                <label for="inverted" class="ts-helper"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="chart-container-tabs-4"
                     class="col-sm-9 col-md-9 col-lg-8 chart-container chart-options-container tab-pane">
                    <div class="inside">
                        <div id="show-chart-title-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Chart title', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('Do you want to show the chart title on the page', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="toggle-switch p-b-20 p-t-5" data-ts-color="blue">
                                <label for="show-chart-title"><?php _e('Show title', 'wpdatatables'); ?></label>
                                <input id="show-chart-title" type="checkbox" hidden="hidden" checked>
                                <label for="show-chart-title" class="ts-helper"></label>
                            </div>
                        </div>
                        <div class="google highcharts" id="title-floating-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Title floating', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('When the title is floating, the plot area will not move to make space for it', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="toggle-switch p-b-20 p-t-5" data-ts-color="blue">
                                <label for="title-floating"><?php _e('Enable floating', 'wpdatatables'); ?></label>
                                <input id="title-floating" type="checkbox" hidden="hidden">
                                <label for="title-floating" class="ts-helper"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="chart-container-tabs-5"
                     class="col-sm-9 col-md-9 col-lg-8 chart-container chart-options-container tab-pane">
                    <div class="inside">
                        <div class="disabled" id="tooltip-enabled-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Tooltip', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('Enable or disable the tooltip', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="toggle-switch p-b-20 p-t-5" data-ts-color="blue">
                                <label for="tooltip-enabled"><?php _e('Show tooltip', 'wpdatatables'); ?></label>
                                <input id="tooltip-enabled" type="checkbox" hidden="hidden">
                                <label for="tooltip-enabled" class="ts-helper"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="chart-container-tabs-6"
                     class="col-sm-9 col-md-9 col-lg-8 chart-container chart-options-container tab-pane">
                    <div class="inside">

                        <div class="google" id="legend-position-row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Position', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('Position of the legend', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="form-group">
                                <div class="fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="legend_position" id="legend_position">
                                            <option value="right">Right</option>
                                            <option selected="selected" value="bottom">Bottom</option>
                                            <option value="top">Top</option>
                                            <option value="in">In</option>
                                            <option value="none">None</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="google highcharts" id="legend_vertical_align_row">
                            <h4 class="c-black m-b-20">
                                <?php _e('Vertical align', 'wpdatatables'); ?>
                                <i class="zmdi zmdi-help-outline" data-toggle="tooltip" data-placement="right"
                                   title="<?php _e('The vertical alignment of the legend box', 'wpdatatables'); ?>"></i>
                            </h4>
                            <div class="form-group">
                                <div class="fg-line">
                                    <div class="select">
                                        <select class="selectpicker" name="legend_vertical_align"
                                                id="legend_vertical_align">
                                            <option selected="selected" value="bottom">Bottom</option>
                                            <option value="middle">Middle</option>
                                            <option value="top">Top</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="col-sm-7 col-md-7 col-lg-7">
        <div class="chart-preview-container">
            <div id="google-chart-container"></div>
        </div>
    </div>

</div>
