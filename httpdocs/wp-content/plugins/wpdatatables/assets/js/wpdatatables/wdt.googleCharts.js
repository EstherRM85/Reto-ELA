google.charts.load('current', {packages: ['corechart', 'bar', 'gauge', 'scatter']});

var wpDataTablesGoogleChart = function () {

    var obj = {
        rows: [],
        columns: [],
        type: 'column',
        containerId: 'google-chart-container',
        columnIndexes: [],
        connectedWPDataTable: null,
        chart: null,
        googleDataTable: null,
        renderCallback: null,
        options: {
            animation: 'none',
            backgroundColor: {
                fill: '#FFFFFF',
                strokeWidth: 0,
                stroke: '#666',
                rx: 0
            },
            chartArea: {
                backgroundColor: {}

            },
            crosshair: {},
            height: 400,
            legend: {
                position: 'right'
            },
            orientation: 'horizontal',
            titlePosition: 'out',
            tooltip: {
                trigger: 'none'
            },
            vAxis: {
                direction: 1,
                viewWindow: {}
            }
        },
        setRows: function (rows) {
            this.rows = rows;
        },
        enableDateTimeAxis: function () {
            this.options.hAxis.gridlines = {
                count: -1,
                units: {
                    days: {format: ['MMM dd']},
                    hours: {format: ['HH:mm', 'ha']}
                }
            }
        },
        detectDates: function () {
            for (var i in this.columns) {
                if (this.columns[i].type == 'date' || this.columns[i].type == 'datetime') {
                    for (var j in this.rows) {
                        var remDate = Date.parse(this.rows[j][i]);
                        if (isNaN(remDate)) {
                            this.rows[j][i] = new Date();
                        } else {
                            this.rows[j][i] = new Date(remDate);
                            if (this.connectedWPDataTable == null) {
                                var timeVal = this.rows[j][i].getTime();
                                if (this.columns[i].type == 'datetime') {
                                    timeVal += this.rows[j][i].getTimezoneOffset() * 60 * 1000;
                                }
                                this.rows[j][i].setTime(timeVal);
                            } else {
                                this.rows[j][i].setTime(this.rows[j][i].getTime());
                            }
                        }
                        if (this.columns[i].type == 'datetime') {
                            this.enableDateTimeAxis();
                        }
                    }
                }
            }
        },
        setColumns: function (columns) {
            this.columns = columns;
        },
        getColumns: function () {
            return this.columns;
        },
        setOptions: function (options) {
            for (var i in options) {
                if (i == 'responsive_width' && options[i] == '1') {
                    obj.options.animation = false;
                    jQuery(window).resize(function () {
                        obj.chart.draw(obj.googleDataTable, obj.options);
                    });
                    continue;
                }
                this.options[i] = options[i];
            }
        },
        getOptions: function () {
            return this.options;
        },
        setType: function (type) {
            this.type = type;
        },
        getType: function () {
            return this.type;
        },
        setContainer: function (containerId) {
            this.containerId = containerId;
        },
        getContainer: function () {
            return this.containerId;
        },
        setRenderCallback: function (callback) {
            this.renderCallback = callback;
        },
        render: function () {
            this.googleDataTable = new google.visualization.DataTable();
            for (var i in this.columns) {
                this.googleDataTable.addColumn(this.columns[i]);
            }
            this.detectDates();

            this.googleDataTable.addRows(this.rows);
            switch (this.type) {
                case 'google_column_chart':
                    this.chart = new google.visualization.ColumnChart(document.getElementById(this.containerId));
                    break;
                case 'google_line_chart':
                    this.chart = new google.visualization.LineChart(document.getElementById(this.containerId));
                    break;
                case 'google_pie_chart':
                    this.chart = new google.visualization.PieChart(document.getElementById(this.containerId));
                    break;
            }
            if (this.renderCallback !== null) {
                this.renderCallback(this);
            }
            this.chart.draw(this.googleDataTable, this.options);
        },
        refresh: function () {
            if (typeof google.visualization.DataTable !== 'undefined' && this.chart != null) {
                this.googleDataTable = new google.visualization.DataTable();
                for (var i in this.columns) {
                    this.googleDataTable.addColumn(this.columns[i]);
                }
                this.detectDates();
                this.googleDataTable.addRows(this.rows);
                if (this.renderCallback !== null) {
                    this.renderCallback(this);
                }
                this.chart.draw(this.googleDataTable, this.options);
            }
        },

        setChartConfig: function (chartConfig) {
            // Chart
            if (chartConfig.responsive_width == 1) {
                this.options.animation = false;
                delete this.options.width;
                jQuery(window).resize(function () {
                    obj.chart.draw(obj.googleDataTable, obj.options);
                });
            } else {
                this.options.width = chartConfig.width;
            }
            chartConfig.height ? this.options.height = chartConfig.height : null;
            this.options.backgroundColor.fill = chartConfig.background_color;
            chartConfig.border_width ? this.options.backgroundColor.strokeWidth = chartConfig.border_width : null;
            this.options.backgroundColor.stroke = chartConfig.border_color;
            chartConfig.border_radius ? this.options.backgroundColor.rx = chartConfig.border_radius : null;
            chartConfig.border_radius ? this.options.backgroundColor.rx = chartConfig.border_radius : null;
            this.options.chartArea.backgroundColor.fill = chartConfig.plot_background_color;
            chartConfig.plot_border_width ? this.options.chartArea.backgroundColor.strokeWidth = chartConfig.plot_border_width : null;
            this.options.chartArea.backgroundColor.stroke = chartConfig.plot_border_color;

            // Series
            var j = 0;
            for (var i in chartConfig.series_data) {
                this.columns[j + 1].label = chartConfig.series_data[i].label;
                if (chartConfig.series_data[i].color != '') {
                    this.options.series[j] = {
                        color: chartConfig.series_data[i].color
                    };
                }
                j++;
            }
            // Axes
            if (chartConfig.show_grid == 0) {
                this.options.hAxis.gridlines = {
                    color: 'transparent'
                };
                this.options.vAxis.gridlines = {
                    color: 'transparent'
                };
            } else {
                delete this.options.hAxis.gridlines;
                delete this.options.vAxis.gridlines;
            }
            chartConfig.horizontal_axis_label ? this.options.hAxis.title = chartConfig.horizontal_axis_label : null;
            chartConfig.vertical_axis_label ? this.options.vAxis.title = chartConfig.vertical_axis_label : null;

            // Title
            chartConfig.show_title == 1 ? this.options.title = chartConfig.chart_title : this.options.title = '';
            chartConfig.title_floating == 1 ? this.options.titlePosition = 'in' : this.options.titlePosition = 'out';
            // Tooltip
            this.options.tooltip.trigger = 'none';
            // Legend
            chartConfig.legend_position ? this.options.legend.position = chartConfig.legend_position : null;
            if (chartConfig.legend_vertical_align == 'bottom') {
                this.options.legend.alignment = 'end';
            } else if (chartConfig.legend_vertical_align == 'middle') {
                this.options.legend.alignment = 'center';
            } else {
                this.options.legend.alignment = 'start';
            }

        },
        setColumnIndexes: function (columnIndexes) {
            this.columnIndexes = columnIndexes;
        },
        getColumnIndexes: function () {
            return this.columnIndexes;
        }

    };

    return obj;

};
