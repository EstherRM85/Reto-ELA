(function ($) {
    $(function () {
        $('.wdt-dismiss').on("click", function (event) {
            event.preventDefault();
            $.ajax({
                url: ajaxurl,
                method: "POST",
                data: {
                    'action': 'wdtTempHideRating'
                },
                dataType: "json",
                async: !0,
                success: function (e) {
                    if (e == "success") {
                        $('.wdt-rating-notice').fadeTo(100, 0, function () {
                            $('.wdt-rating-notice').slideUp(100, function () {
                                this.remove();
                            });
                        });
                    }
                }

            });
        });

        $('.wdt-hide-rating').click(function () {
            event.preventDefault();
            $.ajax({
                url: ajaxurl,
                method: "POST",
                data: {
                    'action': 'wdtHideRating'
                },
                dataType: "json",
                async: !0,
                success: function (e) {
                    if (e == "success") {
                        $('.wdt-rating-notice').slideUp('fast');
                    }
                }
            });
        })

    });
})(jQuery);