/* global wc_add_to_cart_params */

( function ( $ ) {
    "use strict";
    window.WbcomEssential = {
        init: function ( ) {
            this.Slider( );
        },
        Slider: function ( ) {
            if ( essential_js_obj.reign_rtl ) {
                var rt = true;
            } else {
                var rt = false;
            }
 
            $('.wbcom-slick').each( function(){
            	var scroll_number = $(this).data('scroll-slides');
            	var visible_dd = $(this).data('dd-show-slides');
            	var visible_lg = $(this).data('lg-show-slides');
            	var visible_md = $(this).data('md-show-slides');
            	var visible_sm = $(this).data('sm-show-slides');
            	var visible_xs = $(this).data('xs-show-slides');

            	$(this).slick( {
            		//dots: false,
            		infinite: true,
            		//prevArrow: '<a class="slick-prev slick-arrow"><i class="arrow-left fa fa-angle-left"></i></a>',
            		//nextArrow: '<a class="slick-next slick-arrow"><i class="arrow-right fa fa-angle-right"></i></a>',
            		//speed: 500,
            		slidesToShow: visible_dd,
            		//swipeToSlide: true,
            		slidesToScroll: 4,
            		rtl: false,
            		responsive: [
            		{
            			breakpoint: 1224,
            			settings: {
            				slidesToShow: visible_lg,
            				slidesToScroll: scroll_number,
            				infinite: true,
            				dots: false
            			}
            		},
            		{
            			breakpoint: 1025,
            			settings: {
            				slidesToShow: visible_dd,
            				slidesToScroll: scroll_number,
            				infinite: true,
            				dots: false
            			}
            		},
            		{
            			breakpoint: 767,
            			settings: {
            				infinite: true,
            				slidesToShow: visible_md,
            				slidesToScroll: scroll_number
            			}
            		},
            		{
            			breakpoint: 641,
            			settings: {
            				slidesToShow: visible_sm,
            				slidesToScroll: scroll_number
            			}
            		},
            		{
            			breakpoint: 420,
            			settings: {
            				slidesToShow: visible_xs,
            				slidesToScroll: scroll_number
            			}
            		}
            		]
            	} );
            } );
           
        }
    };

    $( document ).on( 'ready', function ( ) {
        WbcomEssential.init( );
    } );

} )( jQuery );