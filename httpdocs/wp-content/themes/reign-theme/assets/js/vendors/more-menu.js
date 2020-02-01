( function ( $ ) {
    $.fn.ReignMore = function ( reduceWidth ) {
        $( this ).each( function () {
            $( this ).addClass( "rg-responsive-menu" );
            alignMenu( this );
            var robj = this;

            $( window ).resize( function () {
                $( robj ).append( $( $( $( robj ).children( 'li.hideshow' ) ).children( 'ul' ) ).html() );
                $( robj ).children( 'li.hideshow' ).remove();
                alignMenu( robj );
            } );

            function alignMenu( obj ) {
                var w = 0;
                var mw = $( obj ).width() - reduceWidth;
                var i = -1;
                var menuhtml = '';
                jQuery.each( $( obj ).children(), function () {
                    i++;
                    w += $( this ).outerWidth( true );
                    if ( mw < w ) {
                        menuhtml += $( '<div>' ).append( $( this ).clone() ).html();
                        $( this ).remove();
                    }
                } );

                $( obj ).append(
                    '<li class="hideshow menu-item-has-children"><a class="rg-more-button" href="#">More</a><ul class="sub-menu">' + menuhtml + '</ul></li>' );
                $( obj ).children( "li.hideshow ul" ).css( "top",
                    $( obj ).children( 'li.hideshow' ).outerHeight( true ) + 'px' );

                if ( $( obj ).find( 'li.hideshow' ).find( 'li' ).length > 0 ) {
                    $( obj ).find( 'li.hideshow' ).show();
                } else {
                    $( obj ).find( 'li.hideshow' ).hide();
                }
            }

        } );

    }
}( jQuery ) );