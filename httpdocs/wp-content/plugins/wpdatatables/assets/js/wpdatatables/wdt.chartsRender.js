(function ($) {
    $(window).on('load', function () {

        var wdtGoogleCharts = [];

        if (typeof wpDataCharts !== 'undefined') {

            for (var chart_id in wpDataCharts) {

                var wdtChart = new wpDataTablesGoogleChart();
                wdtChart.setType(wpDataCharts[chart_id].render_data.type);
                wdtChart.setColumns(wpDataCharts[chart_id].render_data.columns);
                wdtChart.setRows(wpDataCharts[chart_id].render_data.rows);
                wdtChart.setOptions(wpDataCharts[chart_id].render_data.options);
                wdtChart.setContainer(wpDataCharts[chart_id].container);
                wdtChart.setColumnIndexes(wpDataCharts[chart_id].render_data.column_indexes);
                if (typeof wpDataChartsCallbacks !== 'undefined' && typeof wpDataChartsCallbacks[chart_id] !== 'undefined') {
                    wdtChart.setRenderCallback(wpDataChartsCallbacks[chart_id]);
                }
                wdtGoogleCharts.push(wdtChart);

            }
        }

        // Setting the callback for rendering Google Charts
        if (wdtGoogleCharts.length) {
            var wdtGoogleRenderCallback = function () {
                for (var i in wdtGoogleCharts) {
                    wdtGoogleCharts[i].render();
                }
            }
            google.charts.setOnLoadCallback(wdtGoogleRenderCallback);
        }

    })

})(jQuery);
