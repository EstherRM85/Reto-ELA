/**
 * Main jQuery elements controller for the plugin settings page
 *
 * Binds the jQuery control elements for manipulating the config object, binds jQuery plugins
 *
 * @author Miljko Milosevic
 * @since 23.11.2016
 */

(function($) {
    $(function(){

        

        /**
         * Change language on select change - "Interface language"
         */
        $('#wdt-interface-language').change(function(e){
            wpdatatable_plugin_config.setLanguage( $(this).val() );
        });

        /**
         * Change date format - "Date format"
         */
        $('#wdt-date-format').change(function(e){
            wpdatatable_plugin_config.setDateFormat( $(this).val() );
        });

        /**
         * Number of tables on admin page - "Tables per admin page"
         */
        $('#wdt-tables-per-page').change(function(e){
            wpdatatable_plugin_config.setTablesAdmin( $(this).val() );
        });

        /**
         * Change time format - "Date time"
         */
        $('#wdt-time-format').change(function(e){
            wpdatatable_plugin_config.setTimeFormat( $(this).val() );
        });

        /**
         * Change base skin - "Base skin"
         */
        $('#wdt-base-skin').change(function(e){
            wpdatatable_plugin_config.setBaseSkin( $(this).val() );
        });

        /**
         * Change number format - "Number format"
         */
        $('#wdt-number-format').change(function(e){
            wpdatatable_plugin_config.setNumberFormat( $(this).val() );
        });

      /**
       * Change CSV delimiter - "CSV delimiter"
       */
        $('#wdt-csv-delimiter').change(function(e){
           wpdatatable_plugin_config.setCSVDelimiter( $(this).val() );
        });

      /**
         * Change position of advance filter - "Render advanced filter"
         */
        $('#wp-render-filter').change(function(e){
            wpdatatable_plugin_config.setRenderPosition( $(this).val() );
        });

        /**
         * Set number of decimal places - "Decimal places"
         */
        $('#wdt-decimal-places').change(function(e){
            wpdatatable_plugin_config.setDecimalPlaces( $(this).val() );
        });

        /**
         * Set Tablet width - "Tablet width"
         */
        $('#wdt-tablet-width').change(function(e){
            wpdatatable_plugin_config.setTabletWidth( $(this).val() );
        });

        /**
         * Set Mobile width - "Tablet width"
         */
        $('#wdt-mobile-width').change(function(e){
            wpdatatable_plugin_config.setMobileWidth( $(this).val() );
        });

        /**
         * Set Timepicker step in minutes - "Timepicker step"
         */
        $('#wdt-timepicker-range').change(function(e){
            wpdatatable_plugin_config.setTimepickerStep( $(this).val() );
        });

        /**
         * Set Purchase code - "Purchase code"
         */
        $('#wdt-purchase-code').change(function(e){
            wpdatatable_plugin_config.setPurchaseCode( $(this).val() );
        });

        /**
         * Set Include Bootstrap
         */
        $('#wdt-include-bootstrap').change(function(e){
            wpdatatable_plugin_config.setIncludeBootstrap( $(this).is(':checked') ? 1 : 0 );
        });

        /**
         * Set Prevent deleting tables in database
         */
        $('#wdt-prevent-deleting-tables').change(function (e) {
            wpdatatable_plugin_config.setPreventDeletingTables($(this).is(':checked') ? 1 : 0);
        });

        /**
         * Toggle Parse shortcodes in strings
         */
        $('#wdt-parse-shortcodes').change(function(e){
            wpdatatable_plugin_config.setParseShortcodes( $(this).is(':checked') ? 1 : 0 );
        });

        /**
         * Toggle Align numbers
         */
        $('#wdt-numbers-align').change(function(e){
            wpdatatable_plugin_config.setAlignNumber( $(this).is(':checked') ? 1 : 0 );
        });

        /**
         * Change table font
         */
        $('#wdt-table-font').change(function(e){
            wpdatatable_plugin_config.setColorFontSetting( $(this).data('name'), $(this).val() );
        });

        /**
         * Change table font size
         */
        $('#wdt-font-size').change(function (e) {
            wpdatatable_plugin_config.setColorFontSetting( $(this).data('name'), $(this).val() );

        });

        /**
         * Set Include Bootstrap on back-end
        */
        $('#wdt-include-bootstrap-back-end').change(function(e){
           wpdatatable_plugin_config.setIncludeBootstrapBackEnd( $(this).is(':checked') ? 1 : 0 );
        });

        /**
         * Change border input radius
         */
        $('#wdt-border-input-radius').change(function(e){
            wpdatatable_plugin_config.setColorFontSetting( $(this).prop('id'), $(this).val() );
        });

        /**
         * Set Custom Js - "Custom wpDataTables JS"
         */
        $('#wdt-custom-js').change(function(e){
            wpdatatable_plugin_config.setCustomJs( $(this).val() );
        });

        /**
         * Set Custom CSS - "Custom wpDataTables CSS"
         */
        $('#wdt-custom-css').change(function(e){
            wpdatatable_plugin_config.setCustomCss( $(this).val() );
        });

        /**
         * Toggle minified JS - "Use minified wpDataTables Javascript"
         */
        $('#wdt-minified-js').change(function(e){
            wpdatatable_plugin_config.setMinifiedJs( $(this).is(':checked') ? 1 : 0 );
        });

        $('#wdt-site-link').change(function(e){
            wpdatatable_plugin_config.setWdtSiteLink( $(this).is(':checked') ? 1 : 0 );
        })

        /**
         * Load current config on load
         */
        wpdatatable_plugin_config.setSeparateConnection ( wdt_current_config.wdtUseSeparateCon == 1 ? 1 : 0 );



        wpdatatable_plugin_config.setLanguage           ( wdt_current_config.wdtInterfaceLanguage );
        wpdatatable_plugin_config.setDateFormat         ( wdt_current_config.wdtDateFormat );
        wpdatatable_plugin_config.setTablesAdmin        ( wdt_current_config.wdtTablesPerPage );
        wpdatatable_plugin_config.setTimeFormat         ( wdt_current_config.wdtTimeFormat );
        wpdatatable_plugin_config.setBaseSkin           ( wdt_current_config.wdtBaseSkin );
        wpdatatable_plugin_config.setNumberFormat       ( wdt_current_config.wdtNumberFormat );
        wpdatatable_plugin_config.setCSVDelimiter       ( wdt_current_config.wdtCSVDelimiter );

        

        wpdatatable_plugin_config.setDecimalPlaces      ( wdt_current_config.wdtDecimalPlaces );

        

        wpdatatable_plugin_config.setPurchaseCode       ( wdt_current_config.wdtPurchaseCode );
        wpdatatable_plugin_config.setIncludeBootstrap   ( wdt_current_config.wdtIncludeBootstrap == 1 ? 1 : 0 );
        wpdatatable_plugin_config.setIncludeBootstrapBackEnd   ( wdt_current_config.wdtIncludeBootstrapBackEnd == 1 ? 1 : 0 );
        wpdatatable_plugin_config.setPreventDeletingTables(wdt_current_config.wdtPreventDeletingTables == 1 ? 1 : 0);
        wpdatatable_plugin_config.setParseShortcodes    ( wdt_current_config.wdtParseShortcodes == 1 ? 1 : 0 );
        wpdatatable_plugin_config.setAlignNumber        ( wdt_current_config.wdtNumbersAlign == 1 ? 1 : 0  );
        wpdatatable_plugin_config.setCustomCss          ( wdt_current_config.wdtCustomCss );
        wpdatatable_plugin_config.setCustomJs           ( wdt_current_config.wdtCustomJs );
        wpdatatable_plugin_config.setMinifiedJs         ( wdt_current_config.wdtMinifiedJs == 1 ? 1 : 0  );

        

        wpdatatable_plugin_config.setWdtSiteLink   ( wdt_current_config.wdtSiteLink == 1 ? 1 : 0 );

        

        /**
         * Show "Reset colors and fonts to default" when "Color and font settings" tab is active
         */
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            if (target == '#color-and-font-settings') {
                $('.reset-color-settings').show();
            } else {
                $('.reset-color-settings').hide();
            }
        });

        /**
         * Reset color settings
         */
        $('.reset-color-settings').click(function(e){
            e.preventDefault();
            $('#color-and-font-settings input.cp-value').val('').change();
            wdt_current_config.wdtFontColorSettings = _.mapObject(
                    wdt_current_config.wdtFontColorSettings,
                    function( color ){ return ''; }
                );
            $('#color-and-font-settings .selectpicker').selectpicker('val', '');
            $('input#wdt-border-input-radius').val('');
            $('input#wdt-font-size').val('');
        });

        

        /**
         * Save settings on Apply button
         */
        $('button.wdt-apply').click(function(e){

            $('.wdt-preload-layer').animateFadeIn();

            

            savePluginSettings();
        });

        

        function savePluginSettings() {
            $.ajax({
                url: ajaxurl,
                dataType: 'json',
                method: 'POST',
                data: {
                    action: 'wpdatatables_save_plugin_settings',
                    settings: wdt_current_config,
                    wdtNonce: $('#wdtNonce').val()
                },
                success: function () {
                    $('.wdt-preload-layer').animateFadeOut();
                    wdtNotify(
                        wpdatatables_edit_strings.success,
                        wpdatatables_edit_strings.settings_saved_successful,
                        'success'
                    );
                }
            })
        }
    });
})(jQuery);
