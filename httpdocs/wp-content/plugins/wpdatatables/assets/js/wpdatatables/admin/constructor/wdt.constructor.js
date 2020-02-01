var constructedTableData = {
    name: '',
    method: '',
    columnCount: 0,
    columns: []
};



(function ($) {

    var wdtNonce = $('#wdtNonce').val();
    var customUploader;
    var nextStepButton = $('#wdt-constructor-next-step');
    var previousStepButton = $('#wdt-constructor-previous-step');

    

    $('.wdt-constructor-type-selecter-block .card:not([data-version="full-version-option-click"])').on('click', function () {
        $('.wdt-constructor-type-selecter-block .card').removeClass('selected').addClass('not-selected');
        $(this).addClass('selected').removeClass('not-selected');
        nextStepButton.prop('disabled', false);
    });

    /**
     * Next step handler
     */
    nextStepButton.click(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        var $curStepBlock = $('div.wdt-constructor-step:visible:eq(0)');
        var curStep = $curStepBlock.data('step');

        switch (curStep) {
            case 1:
                $curStepBlock.hide();
                previousStepButton.prop('disabled', false);
                var inputMethod = $('.wdt-constructor-type-selecter-block .card.selected').data('value');
                constructedTableData.method = inputMethod;
                switch (inputMethod) {
                    case 'source':

                    default:

                        window.location.replace(window.location.pathname + '?page=wpdatatables-constructor&source');
                        break;

                    
                }
                break;

            
        }
    });

    

})(jQuery);
