
jQuery( document ).ready( function () {

    jQuery( document ).on( 'click', 'body.bp-nouveau.my-account.my-messages ul.subnav li#compose-personal-li a', function ( event ) {
        jQuery( this ).parent().addClass( 'current' );
        jQuery( this ).parent().parent().find( 'li.current.selected' ).removeClass( 'current' ).removeClass( 'selected' );
    } );

    jQuery( '.rg-nouveau-sidebar-menu .rg-nouveau-sidebar-head .rg-toggle' ).click( function () {
        var thisRef = jQuery( this );
        thisRef.parent().parent().next().slideToggle();
        thisRef.toggleClass( 'ico-plus fa fa-plus-circle' ).toggleClass( 'ico-minus fa fa-minus-circle' );
    } );

    /** rtMedia fix :: Start **/
    // jQuery( '#rtmedia-add-media-button-post-update' ).click( function() {
    // 	jQuery( document.body ).find( 'textarea#whats-new' ).focus();
    // } );

    // TO BE USED
    // if( jQuery( 'body.activity' ).length ) {
    // 	var rtmedia_uploader_div = jQuery( '.rtmedia-container.rtmedia-uploader-div' );
    // 	var rtmedia_uploader_div_html = rtmedia_uploader_div.html();
    // 	rtmedia_uploader_div.hide();

    // 	setTimeout( function() {
    // 		jQuery( document.body ).find( '#bp-nouveau-activity-form #whats-new-content' ).after( '<div class="rtmedia-container rtmedia-uploader-div rtmedia-uploader-div-clone" style="opacity: 1; display: block; visibility: visible;">'+rtmedia_uploader_div_html+'</div>' );
    // 	  	// jQuery( document.body ).find( 'textarea#whats-new' ).focus();
    // 	  }, 1000 );

    // 	jQuery( document.body ).on( 'click', '.rtmedia-uploader-div-clone #rtmedia-add-media-button-post-update', function() {
    // 		jQuery( '.rtmedia-container.rtmedia-uploader-div' ).not( ".rtmedia-uploader-div-clone" ).find( '#rtmedia-add-media-button-post-update' ).trigger( 'click' );
    // 		jQuery( document.body ).find( 'textarea#whats-new' ).focus();
    // 	} );
    // }


    // var setFocus = false;
    // jQuery( document.body ).on( 'click', '#aw-whats-new-submit', function() {
    // 	setFocus = true;
    // } );
    // jQuery( document ).ajaxComplete( function( event, xhr, options ) {
    // 	if( setFocus ) {
    // 		setTimeout( function() {
    // 			jQuery( document.body ).find( 'textarea#whats-new' ).focus();
    // 		  }, 500 );
    // 		setFocus = false;
    // 	}
    // } );

    /** rtMedia fix :: End **/

    //rtmedia-activity-text
    jQuery( document ).ajaxComplete( function ( event, xhr, options ) {
        jQuery( '.rtmedia-activity-text > span' ).each( function ( ) {
            jQuery( this ).filter( function () {
                return jQuery.trim( jQuery( this ).text() ) === '' && jQuery( this ).children().length === 0
            } ).remove();
        } );
    } );

    //Show video full width
    jQuery( document ).ajaxComplete( function ( event, xhr, options ) {
        jQuery( "body" ).fitVids( );
    } );

} );

/** Members And Groups Directory Layout Four Action Button Tooltip Effect **/
function reign_nouveau_deParams( str ) {
    return ( str || document.location.search ).replace( /(^\?)/, '' ).split( "&" ).map( function ( n ) {
        return n = n.split( "=" ), this[n[0]] = n[1], this
    }.bind( { } ) )[0];
}

jQuery( document ).ready( function () {

    setTimeout( function () {
        jQuery( '.wbtm-member-directory-type-4 .action .generic-button' ).find( 'a' ).contents().wrap( '<span/>' );
        jQuery( '.wbtm-member-directory-type-4 .action .generic-button' ).find( 'button' ).contents().wrap( '<span/>' );
        jQuery( '.wbtm-group-directory-type-4 .action .generic-button' ).find( 'a' ).contents().wrap( '<span/>' );
        jQuery( '.wbtm-group-directory-type-4 .action .generic-button' ).find( 'button' ).contents().wrap( '<span/>' );
    }, 1000 );

    jQuery( '.reign-members-grid-widget li.friendship-button > .friendship-button, .reign-groups-grid-widget .generic-button .group-button' ).on( 'click', function () {
        var redirect_url = jQuery( this ).attr( 'data-bp-nonce' );
        window.location.href = redirect_url;
    } );

    jQuery( document ).ajaxComplete( function ( event, xhr, settings ) {
        var formdata = reign_nouveau_deParams( settings.data );
        var action = formdata['action'];
        var btn_id = formdata['item_id'];
        if ( 'members_filter' == action || 'groups_filter' == action ) {
            setTimeout( function () {
                jQuery( '.wbtm-member-directory-type-4 .action .generic-button' ).find( 'a' ).contents().wrap( '<span/>' );
                jQuery( '.wbtm-member-directory-type-4 .action .generic-button' ).find( 'button' ).contents().wrap( '<span/>' );
                jQuery( '.wbtm-group-directory-type-4 .action .generic-button' ).find( 'a' ).contents().wrap( '<span/>' );
                jQuery( '.wbtm-group-directory-type-4 .action .generic-button' ).find( 'button' ).contents().wrap( '<span/>' );
            }, 2000 );
        } else if ( 'friends_add_friend' == action || 'friends_withdraw_friendship' == action || 'friends_remove_friend' == action ) {
            setTimeout( function () {
                jQuery( '.wbtm-member-directory-type-4 #friend-' + btn_id ).contents().wrap( '<span/>' );
            }, 2000 );
        } else if ( 'groups_leave_group' == action || 'groups_join_group' == action ) {
            setTimeout( function () {
                jQuery( '.wbtm-group-directory-type-4 #groupbutton-' + btn_id + ' .group-button' ).contents().wrap( '<span/>' );
            }, 2000 );
        }
    } );
} );