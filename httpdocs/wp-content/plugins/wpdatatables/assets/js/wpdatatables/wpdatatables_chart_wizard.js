if( typeof constructedChartData == 'undefined' ){
    var constructedChartData = {};
}

var wdtChartColumnsData = {};

(function($){
    var wdtChartPickerDragStart = 0;
    var wdtChartPickerDragEnd = 0;
    var wdtChartPickerIsDragging = false;

    // Init selecter
    $('#chart_render_engine, #wpdatatables_chart_source, #wdt_chart_row_range_type, #wdt_chart_series_setting').selecter();

    // Init remodal popup
    $('div.pickRange').remodal({
        type: 'inline',
        preloader: false,
        modal: true
    })

    $('#google_chart_type').imagepicker({ show_label: true });
    $('#highcharts_chart_type').imagepicker({ show_label: true });

    

    /**
     * Pick the chart type
     */
    $('#chart_render_engine').change(function(e){
        e.preventDefault();
        $('tr.charts_type').hide();
        if($(this).val() != ''){
            constructedChartData.chart_engine = $(this).val();
            if( $(this).val() == 'google' ){
                $('tr.google_charts_type').show();
            }
            if( $(this).val() == 'highcharts' ){
                $('tr.highcharts_charts_type').show();
            }
            $('#nextStep').show();
        }else{
            $('#nextStep').hide();
        }
    });

    


})(jQuery);

