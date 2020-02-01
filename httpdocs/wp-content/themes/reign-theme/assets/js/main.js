/* global wc_add_to_cart_params, wp_main_js_obj */

( function ( $ ) {
    "use strict";
    window.Reign = {
        init: function ( ) {
            this.Slider( );
            this.responsiveMenu( );
            this.rtmediaText();
            this.buddupressMenu( );
            this.headerSearch( );
            this.showActivity( );
            this.fitVids( );
            this.WooProductsSwipe( );
            this.EddSupport( );
            this.profileButtons( );
            this.setCounters( );
            this.stickyKit( );
            this.pageLoader();
            this.UpdateNotification( );
            this.groupsMobile();
            this.customMblDropdown();
            this.headerTopBar();
            this.headerScroll();
            this.profileMenuToggle();
            this.activityModal();
            this.addHeaderClass();
            this.addPageHeaderClass();
            this.directoryLayout();
            this.randomPostCatClass();
            this.imageResize();
        },
        Slider: function ( ) {
            if ( wp_main_js_obj.reign_rtl ) {
                var rt = true;
            } else {
                var rt = false;
            }

            $( '.rg-slick-list-container' ).slick( {
                dots: false,
                infinite: false,
                prevArrow: '<a class="rg-arrow slick-prev"><i class="arrow-left fa fa-angle-left"></i></a>',
                nextArrow: '<a class="rg-arrow slick-next"><i class="arrow-right fa fa-angle-right"></i></a>',
                speed: 500,
                slidesToShow: 6,
                swipeToSlide: true,
                rtl: rt,
                responsive: [
                    {
                        breakpoint: 1025,
                        settings: {
                            slidesToShow: 5,
                            slidesToScroll: 2,
                            infinite: true,
                            dots: false
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 641,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 420,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            } );
        },
        responsiveMenu: function ( ) {
            //More Menu

            if ( wp_main_js_obj.reign_more_menu_enable ) {
                $( '.reign-fallback-header.version-one .primary-menu, .reign-fallback-header.version-two .primary-menu, .reign-fallback-header.version-three .primary-menu, #wbcom-ele-masthead .primary-menu' ).ReignMore( 150 );
            }
        },
        rtmediaText: function ( ) {
            //rtmedia-activity-text
            $( '.rtmedia-activity-text > span' ).each( function () {
                $( this ).filter( function () {
                    return $.trim( $( this ).text() ) === '' && $( this ).children().length === 0
                } ).remove();
            } );
        },
        buddupressMenu: function ( ) {
            $( '.wbcom-nav-menu-toggle' ).click( function ( ) {
                $( this ).toggleClass( 'open' );

            } );

            // Filters
            var active = $( '.rg-select-filter :selected' ).text();
            $( '.rg-select-filter option' ).each( function () {
                var current = $( this ).text();
                var activeClass = ( current === active ) ? 'current' : '';
                $( '.rg-filters-wrap' ).append( '<li class="' + activeClass + '"><a href="' + $( this ).attr( 'value' ) + '">' + $( this ).html() + '</a></li>' );
            } );

            $( '.rg-filters-wrap' ).on( 'click', 'li a', function ( e ) {
                e.preventDefault();
                var value = $( this ).attr( 'href' );
                $( '.rg-select-filter' ).val( value ).change();
                $( '.rg-filters-wrap li' ).removeClass( 'current' );
                $( '.rg-filters-wrap li' ).removeClass( 'selected' );
                $( this ).parent().addClass( 'current' );
                return false;
            } );

            // Member & Group front page widgets
            $( ".member-front-page .bp-widget-area h2.widget-title" ).wrapInner( '<span/>' );
            $( ".group-front-page .bp-widget-area h2.widget-title" ).wrapInner( '<span/>' );
        },
        headerSearch: function ( ) {
            $( document ).on( 'click', '.search-wrap .rg-search-icon', function ( e ) {
                var searchParent = $( this ).parent( '.search-wrap' );
                e.preventDefault( );
                searchParent.toggleClass( 'search-active' );
                searchParent.find( '.search-field' ).focus();
            } );
            $( document ).on( 'click', '.search-wrap .rg-search-close', function ( e ) {
                var container = $( "header .search-wrap" );
                container.removeClass( 'search-active' );
            } );
        },
        showActivity: function ( ) {
            $( document ).on( 'click', '.widget_bp_reign_activity_widget div.pagination-links a', function ( e ) {
                e.preventDefault( );
                var parent = $( this ).parents( '.widget_bp_reign_activity_widget' ).get( 0 );
                parent = $( parent ); //cast as jquery object
                var page = get_var_in_url( $( this ).attr( 'href' ), 'acpage' );
                var scope = $( '#reign_scope' ).val( );
                fetch_and_show_activity( page, scope, parent );
            } );
            function get_var_in_url( url, name ) {
                var urla = url.split( '?' );
                var qvars = urla[1].split( '&' ); //so we have an arry of name=val,name=val
                for ( var i = 0; i < qvars.length; i++ ) {
                    var qv = qvars[i].split( '=' );
                    if ( qv[0] === name )
                        return qv[1];
                }
            }

            function fetch_and_show_activity( page, scope, local_scope ) {
                local_scope = $( local_scope );
                var per_page = $( "#reign_per_page", local_scope ).val( );
                var max_items = $( "#reign_max_items", local_scope ).val( );
                var included_components = $( "#reign_included_components", local_scope ).val( );
                var excluded_components = $( "#reign_excluded_components", local_scope ).val( );
                var show_avatar = $( "#reign_show_avatar", local_scope ).val( );
                var show_content = $( "#reign_show_content", local_scope ).val( );
                var show_filters = $( "#reign_show_filters", local_scope ).val( );
                var is_personal = $( "#reign_is_personal", local_scope ).val( );
                var is_blog_admin_activity = $( "#reign_is_blog_admin_activity", local_scope ).val( );
                var show_post_form = $( "#reign_show_post_form", local_scope ).val( );
                var activity_words_count = $( "#reign-activity-words-count", local_scope ).val( );
                $.post( ajaxurl, {
                    action: 'reign_fetch_content',
                    cookie: encodeURIComponent( document.cookie ),
                    page: page,
                    scope: scope,
                    max: max_items,
                    per_page: per_page,
                    show_avatar: show_avatar,
                    show_content: show_content,
                    show_filters: show_filters,
                    is_personal: is_personal,
                    is_blog_admin_activity: is_blog_admin_activity,
                    included_components: included_components,
                    excluded_components: excluded_components,
                    show_post_form: show_post_form,
                    original_scope: $( '#reign-original-scope' ).val( ),
                    activity_words_count: activity_words_count,
                    allow_comment: $( '#reign-activity-allow-comment' ).val( )
                },
                    function ( response ) {
                        $( ".reign-wrap", local_scope ).replaceWith( response );
                        $( 'form.reign-ac-form' ).hide( );
                        $( "#activity-filter-links li#afilter-" + scope, local_scope ).addClass( "selected" );
                    } );
            }

            //for filters
            $( document ).on( 'click', '.widget_bp_reign_activity_widget #activity-filter-links li a', function ( ) {
                var parent = $( this ).parents( '.widget_bp_reign_activity_widget' ).get( 0 );
                parent = $( parent );
                var page = 1;
                var scope = '';
                if ( $( this ).parent( ).attr( 'id' ) === 'afilter-clear' ) {
                    scope = $( '#reign-original-scope', parent ).val( );
                } else {
                    scope = get_var_in_url( $( this ).attr( 'href' ), 'afilter' );
                }

                //update the dom scope
                $( '#reign-scope' ).val( scope );
                fetch_and_show_activity( page, scope, parent );
                //make the current filter selected
                return false;
            } );
        },
        fitVids: function ( ) {
            $( document ).ready( function ( ) {
                // Target your .container, .wrapper, .post, etc.
                $( "body" ).fitVids( );
            } );
        },
        WooProductsSwipe: function ( ) {
            if ( wp_main_js_obj.reign_rtl ) {
                var rt = true;
            } else {
                var rt = false;
            }

            $( ".wc-tabs" ).slick( {
                arrows: false,
                dots: false,
                slidesToShow: 3,
                slidesToScroll: 1,
                variableWidth: true,
                infinite: false,
                swipeToSlide: true,
                rtl: rt
            } );

            $( "table.my_account_orders" ).wrap( '<div class="touch-scroll-table"/>' );
            $( "table.my_account_bookings" ).wrap( '<div class="touch-scroll-table"/>' );
        },
        EddSupport: function ( ) {
            $( window ).load( function () {
                if ( wp_main_js_obj.reign_rtl ) {
                    var rt = true;
                } else {
                    var rt = false;
                }

                if ( $( window ).width() <= 767 ) {
                    $( '.fes-vendor-menu > ul' ).addClass( 'edd-tabs' );

                    var slickGoTo = $( '.fes-vendor-menu-tab.active' ).index( 0 );
                    $( ".edd-tabs" ).slick( {
                        arrows: false,
                        dots: false,
                        touchMove: true,
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        variableWidth: true,
                        rtl: rt
                    } );

                    if ( slickGoTo !== 0 ) {
                        slickGoTo = slickGoTo - 0;
                    }

                    $( '.edd-tabs' ).slick( 'slickGoTo', slickGoTo );
                }

                $( document ).ready( function () {
                    var $rows = $( "nav.fes-vendor-menu" ).addClass( "wb-pageload" );

                    setTimeout( function () {
                        $rows.removeClass( "wb-pageload" );
                    }, 800 );
                } );

            } );

            $( '.fes-vendor-vendor-feedback-tab > a > i' ).addClass( 'icon-user' );

            /**
             * EDD Price Options Label Style
             */
            $( '.edd_price_options, .modal-content' ).find( 'label' ).each( function () {
                $( this ).removeAttr( "for" );
                if ( $( this ).find( 'input' ).is( ':checked' ) ) {
                    $( this ).parent().addClass( 'checked' );
                } else {
                    $( this ).parent().removeClass( 'checked' );
                }
                $( this ).click( function () {
                    if ( $( this ).parents( '.edd_price_options' ).hasClass( 'edd_multi_mode' ) ) {
                        if ( $( this ).find( 'input[type="checkbox"]' ).is( ':checked' ) ) {
                            $( this ).parent().addClass( 'checked' );
                        } else {
                            $( this ).parent().removeClass( 'checked' );
                        }
                    } else {
                        if ( $( this ).find( 'input[type="radio"]' ).is( ':checked' ) ) {
                            $( this ).parent().addClass( 'checked' ).siblings( 'li' ).removeClass( 'checked' );
                        } else {
                            $( this ).parent().removeClass( 'checked' );
                        }
                    }
                } );
            } );
            $( 'label.selectit' ).each( function () {
                $( this ).removeAttr( "for" );
                if ( $( this ).find( 'input' ).is( ':checked' ) ) {
                    $( this ).addClass( 'checked' );
                } else {
                    $( this ).removeClass( 'checked' );
                }
                $( this ).click( function () {
                    if ( $( this ).find( 'input[type="checkbox"]' ).is( ':checked' ) ) {
                        $( this ).addClass( 'checked' );
                    } else {
                        $( this ).removeClass( 'checked' );
                    }
                } );
            } );
        },
        profileButtons: function ( ) {
            $( '.rg-item-buttons' ).on( "click", function ( event ) {
                event.stopPropagation();
                $( this ).toggleClass( 'active' );
            } );

            $( "body" ).on( "click", function ( event ) {
                $( '.rg-item-buttons' ).removeClass( 'active' ); // or something...
            } );
        },
        setCounters: function ( ) {
            $( '#wp-admin-bar-my-account-buddypress' ).find( 'li' ).each( function ( ) {
                var $this = $( this ),
                    $count = $this.children( 'a' ).children( '.count' ),
                    id,
                    $target;
                if ( $count.length != 0 ) {
                    id = $this.attr( 'id' );
                    $target = $( '.bp-menu.bp-' + id.replace( /wp-admin-bar-my-account-/, '' ) + '-nav' );
                    if ( $target.find( '.count' ).length == 0 ) {
                        $target.find( 'a' ).append( '<span class="count">' + $count.html( ) + '</span>' );
                    }
                }
            } );
        },
        stickyKit: function ( ) {
            $( document.body ).trigger( "sticky_kit:recalc" );
            $( 'body.reign-sticky-sidebar .widget-area-inner' ).stick_in_parent( {
                offset_top: 40
            } );
        },
        pageLoader: function ( ) {
            $( window ).load( function () {
                $( 'body' ).addClass( 'rg-page-loaded rg-remove-loader' );
            } );

            setTimeout( function () {
                if ( !( $( 'body' ).hasClass( 'rg-remove-loader' ) ) ) {
                    $( 'body' ).addClass( 'rg-remove-loader' );
                }
            }, 3000 );
        },
        UpdateNotification: function ( ) {

            //Notifications related updates
            $( document ).on( 'heartbeat-tick.reign_notification_count', function ( event, data ) {

                if ( data.hasOwnProperty( 'reign_notification_count' ) ) {
                    data = data['reign_notification_count'];
                    /********notification type**********/
                    if ( data.notification > 0 ) { //has count
                        jQuery( "#ab-pending-notifications" ).text( data.notification ).removeClass( "no-alert" );
                        jQuery( "#ab-pending-notifications-mobile" ).text( data.notification ).removeClass( "no-alert" );
                        jQuery( "#wp-admin-bar-my-account-notifications .ab-item[href*='/notifications/']" ).each( function ( ) {
                            jQuery( this ).append( "<span class='count'>" + data.notification + "</span>" );
                            if ( jQuery( this ).find( ".count" ).length > 1 ) {
                                jQuery( this ).find( ".count" ).first( ).remove( ); //remove the old one.
                            }
                        } );
                    } else {
                        jQuery( "#ab-pending-notifications" ).text( data.notification ).addClass( "no-alert" );
                        jQuery( "#ab-pending-notifications-mobile" ).text( data.notification ).addClass( "no-alert" );
                        jQuery( "#wp-admin-bar-my-account-notifications .ab-item[href*='/notifications/']" ).each( function ( ) {
                            jQuery( this ).find( ".count" ).remove( );
                        } );
                    }
                    //remove from read ..
                    jQuery( ".mobile #wp-admin-bar-my-account-notifications-read, #adminbar-links #wp-admin-bar-my-account-notifications-read" ).each( function ( ) {
                        $( this ).find( "a" ).find( ".count" ).remove( );
                    } );
                    /**********messages type************/
                    if ( data.unread_message > 0 ) { //has count
                        jQuery( "#user-messages" ).find( "span" ).text( data.unread_message );
                        jQuery( ".ab-item[href*='/messages/']" ).each( function ( ) {
                            jQuery( this ).append( "<span class='count'>" + data.unread_message + "</span>" );
                            if ( jQuery( this ).find( ".count" ).length > 1 ) {
                                jQuery( this ).find( ".count" ).first( ).remove( ); //remove the old one.
                            }
                        } );
                    } else {
                        jQuery( "#user-messages" ).find( "span" ).text( data.unread_message );
                        jQuery( ".ab-item[href*='/messages/']" ).each( function ( ) {
                            jQuery( this ).find( ".count" ).remove( );
                        } );
                    }
                    //remove from unwanted place ..
                    jQuery( ".mobile #wp-admin-bar-my-account-messages-default, #adminbar-links #wp-admin-bar-my-account-messages-default" ).find( "li:not('#wp-admin-bar-my-account-messages-inbox')" ).each( function ( ) {
                        jQuery( this ).find( "span" ).remove( );
                    } );
                    /**********messages type************/
                    if ( data.friend_request > 0 ) { //has count
                        jQuery( ".ab-item[href*='/friends/']" ).each( function ( ) {
                            jQuery( this ).append( "<span class='count'>" + data.friend_request + "</span>" );
                            if ( jQuery( this ).find( ".count" ).length > 1 ) {
                                jQuery( this ).find( ".count" ).first( ).remove( ); //remove the old one.
                            }
                        } );
                    } else {
                        jQuery( ".ab-item[href*='/friends/']" ).each( function ( ) {
                            jQuery( this ).find( ".count" ).remove( );
                        } );
                    }
                    //remove from unwanted place ..
                    jQuery( ".mobile #wp-admin-bar-my-account-friends-default, #adminbar-links #wp-admin-bar-my-account-friends-default" ).find( "li:not('#wp-admin-bar-my-account-friends-requests')" ).each( function ( ) {
                        jQuery( this ).find( "span" ).remove();
                    } );

                    //notification content
                    //jQuery( ".user-notifications .rg-notify li" ).html( data.notification_content );
                    jQuery( ".user-notifications .rg-count" ).html( data.notification );
                    if ( data ) {
                        jQuery( '#wp-admin-bar-bp-notifications-default' ).empty();
                        jQuery( '.user-notifications #rg-notify' ).empty();

                        jQuery.each( data.notification_content, function ( i, value ) {
                            jQuery( '#wp-admin-bar-bp-notifications-default' ).append( '<li>' + value + '</li>' );
                            jQuery( "#wp-admin-bar-bp-notifications-default a" ).each( function ( ) {
                                jQuery( this ).addClass( 'ab-item' );
                            } );
                        } );

                        //jQuery('.user-notifications .rg-notify li:not(.rg-view-all)').remove();
                        jQuery.each( data.notification_content, function ( i, value ) {
                            jQuery( '.user-notifications #rg-notify' ).append( '<li>' + value + '</li>' );
                        } );
                    }

                }
            } );
        },
        groupsMobile: function () {
            var win = $( window );
            var groupElem = $( '.widget-groups-by, .widget-groups-orderby, .widget-groups-groupby' );
            var activityElem = $( '.widget-activity-nav, .widget-activity-subnav' );
            var membersElem = $( '.widget-members-nav, .widget-members-subnav' );

            if ( win.width() < 544 ) {
                if ( $( "#mobile-view-aside" ).length == 0 ) {
                    $( '<aside id="mobile-view-aside"></aside>' ).insertBefore( '.groups.dir-list' );
                }

                if ( win.width() < 544 ) {
                    groupElem.prependTo( '#mobile-view-aside' );
                    activityElem.insertAfter( '.activity-content-area .entry-header' );
                    membersElem.insertAfter( '.members-content-area .entry-header' );

                } else {
                    groupElem.prependTo( '#left' );
                    activityElem.prependTo( '#left' );
                    membersElem.prependTo( '#left' );
                }
            }

            $( window ).on( 'resize', function () {
                var win = $( window ); //this = window

                if ( win.width() < 544 ) {
                    groupElem.prependTo( '#mobile-view-aside' );
                    activityElem.insertAfter( '.activity-content-area .entry-header' );
                    membersElem.insertAfter( '.members-content-area .entry-header' );

                } else {
                    groupElem.prependTo( '#left' );
                    activityElem.prependTo( '#left' );
                    membersElem.prependTo( '#left' );
                }
            } );
        },
        customMblDropdown: function () {
            $( document ).on( 'click', '.rg-custom-mbl-menu h2', function () {
                $( this ).parent( '.rg-custom-mbl-menu' ).toggleClass( 'active' );
            } );

            //WooCommerce product categories sidebar
            $( document ).ready( function () {
                $( '.product-categories ul.children' ).slideUp();
                $( '.product-categories li' ).click( function ( e ) {
                    $( this ).toggleClass( 'active' );
                    //$( 'ul.children' ).slideUp();
                    $( 'ul', this ).slideToggle();
                    e.stopPropagation();
                } );
            } );
        },
        headerTopBar: function () {
            // Scroll wbcom-header-topbar

            // $( window ).scroll( function () {
            //     var height = jQuery( 'div#wbcom-header-topbar' ).height();
            //     var headerHeight = height + 32;

            //     if ( $( document ).scrollTop() > 200 ) {
            //         $( '.rg-header-top-bar #wbcom-header-topbar' ).hide();
            //         $( '.admin-bar.rg-sticky-menu #masthead.sticky-header' ).css( "top", "32px" );
            //         $( '.rg-header-top-bar.rg-sticky-menu #masthead.sticky-header' ).css( "top", "0px" );

            //         $( '.admin-bar.rg-header-top-bar.rg-sticky-menu #masthead.sticky-header' ).css( "top", "32px" );

            //     } else {
            //         $( '.rg-header-top-bar #wbcom-header-topbar' ).show();
            //         $( '.rg-header-top-bar.rg-sticky-menu #masthead.sticky-header' ).css( "top", height + "px" );

            //         $( '.admin-bar.rg-header-top-bar.rg-sticky-menu #masthead.sticky-header' ).css( "top", headerHeight + "px" );
            //     }

            // } );

            if ( wp_main_js_obj.reign_ele_topbar ) {

                if ( $( window ).width() < 960 ) {

                    $( '.shiftnav-wrap' ).css( "padding-top", "0px" );
                    if ( wp_main_js_obj.topbar_mobile_disabled ) {
                        var height = 0;
                    } else {
                        var height = jQuery( 'div#wbcom-header-topbar' ).height();
                    }
                    var mobheader_height = jQuery( '#shiftnav-toggle-main' ).height() - 1;
                    var admin_height = jQuery( 'div#wpadminbar' ).height();
                    var headerHeight = height + admin_height - 1;
                    $( '#shiftnav-toggle-main' ).css( "top", headerHeight + "px" );
                    $( '[off-canvas*=rg-slidebar-toggle]' ).css( "top", headerHeight + "px" );
                    $( '.lm-site-header-section .lm-header-banner' ).css( "paddingTop", mobheader_height + "px" );

                    $( window ).resize( function () {
                        $( '.shiftnav-wrap' ).css( "padding-top", "0px" );
                        if ( wp_main_js_obj.topbar_mobile_disabled ) {
                            var height = 0;
                        } else {
                            var height = jQuery( 'div#wbcom-header-topbar' ).height();
                        }
                        var mobheader_height = jQuery( '#shiftnav-toggle-main' ).height() - 1;
                        var admin_height = jQuery( 'div#wpadminbar' ).height();
                        var headerHeight = height + admin_height - 1;
                        $( '#shiftnav-toggle-main' ).css( "top", headerHeight + "px" );
                        $( '[off-canvas*=rg-slidebar-toggle]' ).css( "top", headerHeight + "px" );
                        $( '.lm-site-header-section .lm-header-banner' ).css( "paddingTop", mobheader_height + "px" );
                    } );
                }

            } else {
                if ( $( window ).width() < 960 ) {

                    $( '.shiftnav-wrap' ).css( "padding-top", "0px" );
                    if ( wp_main_js_obj.topbar_mobile_disabled ) {
                        var height = 0;
                    } else {
                        var height = jQuery( 'div.reign-header-top' ).height();
                    }

                    var mobheader_height = jQuery( '#shiftnav-toggle-main' ).height() - 1;
                    var admin_height = jQuery( 'div#wpadminbar' ).height();
                    //var headerHeight = height + admin_height + 5;
                    var headerHeight = height + admin_height - 1;
                    $( '#shiftnav-toggle-main' ).css( "top", headerHeight + "px" );
                    $( '[off-canvas*=rg-slidebar-toggle]' ).css( "top", headerHeight + "px" );
                    $( '.lm-site-header-section .lm-header-banner' ).css( "paddingTop", mobheader_height + "px" );

                    $( window ).resize( function () {
                        $( '.shiftnav-wrap' ).css( "padding-top", "0px" );
                        if ( wp_main_js_obj.topbar_mobile_disabled ) {
                            var height = 0;
                        } else {
                            var height = jQuery( 'div.reign-header-top' ).height();
                        }
                        var mobheader_height = jQuery( '#shiftnav-toggle-main' ).height() - 1;
                        var admin_height = jQuery( 'div#wpadminbar' ).height();
                        //var headerHeight = height + admin_height + 5;
                        var headerHeight = height + admin_height - 1;
                        $( '#shiftnav-toggle-main' ).css( "top", headerHeight + "px" );
                        $( '[off-canvas*=rg-slidebar-toggle]' ).css( "top", headerHeight + "px" );
                        $( '.lm-site-header-section .lm-header-banner' ).css( "paddingTop", mobheader_height + "px" );
                    } );
                }
            }
        },
        headerScroll: function () {
            if ( wp_main_js_obj.reign_ele_topbar ) {

                $( window ).scroll( function () {
                    if ( $( window ).width() < 960 && $( window ).width() > 599 ) {
                        if ( $( document ).scrollTop() > 0 ) {
                            if ( wp_main_js_obj.logged_in ) {
                                var admin_height = jQuery( 'div#wpadminbar' ).height();
                            } else {
                                var admin_height = 0;
                            }
                            var headerHeight = admin_height;
                            $( '#shiftnav-toggle-main' ).css( "top", headerHeight + "px" );
                            $( '[off-canvas*=rg-slidebar-toggle]' ).css( "top", headerHeight + "px" );
                        } else {
                            if ( wp_main_js_obj.topbar_mobile_disabled ) {
                                var height = 0;
                            } else {
                                var height = jQuery( 'div#wbcom-header-topbar' ).height();
                            }
                            var admin_height = jQuery( 'div#wpadminbar' ).height();
                            var headerHeight = height + admin_height - 1;
                            $( '#shiftnav-toggle-main' ).css( "top", headerHeight + "px" );
                            $( '[off-canvas*=rg-slidebar-toggle]' ).css( "top", headerHeight + "px" );
                        }

                    }

                    if ( $( window ).width() < 601 ) {
                        if ( $( document ).scrollTop() > 0 ) {
                            $( '#shiftnav-toggle-main' ).css( "top", "0px" );
                            $( '[off-canvas*=rg-slidebar-toggle]' ).css( "top", "0px" );
                        } else {
                            if ( wp_main_js_obj.topbar_mobile_disabled ) {
                                var height = 0;
                            } else {
                                var height = jQuery( 'div#wbcom-header-topbar' ).height();
                            }
                            var admin_height = jQuery( 'div#wpadminbar' ).height();
                            var headerHeight = height + admin_height - 1;
                            $( '#shiftnav-toggle-main' ).css( "top", headerHeight + "px" );
                            $( '[off-canvas*=rg-slidebar-toggle]' ).css( "top", headerHeight + "px" );
                        }
                    }
                } );

            } else {
                $( window ).scroll( function () {
                    if ( $( window ).width() < 960 && $( window ).width() > 599 ) {
                        if ( $( document ).scrollTop() > 0 ) {
                            if ( wp_main_js_obj.logged_in ) {
                                var admin_height = jQuery( 'div#wpadminbar' ).height();
                            } else {
                                var admin_height = 0;
                            }
                            var headerHeight = admin_height;
                            $( '#shiftnav-toggle-main' ).css( "top", headerHeight + "px" );
                            $( '[off-canvas*=rg-slidebar-toggle]' ).css( "top", headerHeight + "px" );
                        } else {
                            if ( wp_main_js_obj.topbar_mobile_disabled ) {
                                var height = 0;
                            } else {
                                var height = jQuery( 'div.reign-header-top' ).height();
                            }
                            var admin_height = jQuery( 'div#wpadminbar' ).height();
                            //var headerHeight = height + admin_height + 5;
                            var headerHeight = height + admin_height - 1;
                            $( '#shiftnav-toggle-main' ).css( "top", headerHeight + "px" );
                            $( '[off-canvas*=rg-slidebar-toggle]' ).css( "top", headerHeight + "px" );
                        }

                    }

                    if ( $( window ).width() < 601 ) {
                        if ( $( document ).scrollTop() > 0 ) {
                            $( '#shiftnav-toggle-main' ).css( "top", "0px" );
                            $( '[off-canvas*=rg-slidebar-toggle]' ).css( "top", "0px" );
                        } else {
                            if ( wp_main_js_obj.topbar_mobile_disabled ) {
                                var height = 0;
                            } else {
                                var height = jQuery( 'div.reign-header-top' ).height();
                            }
                            var admin_height = jQuery( 'div#wpadminbar' ).height();
                            //var headerHeight = height + admin_height + 5;
                            var headerHeight = height + admin_height - 1;
                            $( '#shiftnav-toggle-main' ).css( "top", headerHeight + "px" );
                            $( '[off-canvas*=rg-slidebar-toggle]' ).css( "top", headerHeight + "px" );
                        }
                    }
                } );
            }

        },
        profileMenuToggle: function () {
            // Dropdown toggle
            $( 'div.wbtm-show-item-buttons' ).click( function () {
                $( this ).next( '#item-buttons' ).toggle();
            } );

            $( document ).click( function ( e ) {
                var target = e.target;
                if ( !$( target ).is( 'div.wbtm-show-item-buttons' ) && !$( target ).parents().is( 'div.wbtm-show-item-buttons' ) ) {
                    $( '#item-buttons' ).hide();
                }
            } );
        },
        activityModal: function () {
            if ( 'on' != wp_main_param.activity_post_popup ) {
                $( '#whats-new' ).on( 'focus', function () {
                    $( 'body' ).addClass( 'activity-modal' );
                } );

                $( document ).on( 'click', '#aw-whats-new-submit, #aw-whats-new-reset', function () {
                    $( 'body' ).removeClass( 'activity-modal' );
                } );

                $( document ).mouseup( function ( e ) {
                    var container = $( "#whats-new-form" );
                    if ( !container.is( e.target ) && container.has( e.target ).length === 0 ) {
                        $( 'body' ).removeClass( 'activity-modal' );
                        $( '#whats-new' ).focusout();
                    }
                } );

                $( document ).keyup( function ( e ) {
                    if ( e.keyCode === 27 ) {
                        $( 'body' ).removeClass( 'activity-modal' );
                        $( '#whats-new' ).blur();
                    }
                } );
            }
        },
        addHeaderClass: function () {
            if ( wp_dsktop_toggle.desk_mode ) {
                $( '.reign-fallback-header' ).addClass( 'reign-fdesktop-toggle' );
                $( '#wbcom-ele-masthead' ).addClass( 'reign-fdesktop-toggle' );
            }

            //header icon wrap remove spacing
            $( '.rg-mobile-header-icon-wrap' ).each( function () {
                $( this ).filter( function () {
                    return $.trim( $( this ).text() ) === '' && $( this ).children().length === 0
                } ).remove();
            } );
        },
        addPageHeaderClass: function () {
            if ( $( ".lm-site-header-section" ).length != 0 ) {
                $( 'body' ).addClass( 'lm-site-header-section-enabled' );
            }
        },
        directoryLayout: function () {
            $( '.bp-legacy .wbtm-member-directory-type-4 .action .generic-button' ).find( 'a' ).contents().wrap( '<span/>' );
            $( '.bp-legacy.manage-members .wbtm-member-directory-type-4 .action > div' ).find( 'a.button' ).contents().wrap( '<span/>' );
            $( '.bp-legacy .wbtm-group-directory-type-4 .action .generic-button' ).find( 'a' ).contents().wrap( '<span/>' );
        },
        randomPostCatClass: function () {
            var classes = [ 'cat-green', 'cat-emerald', 'cat-blue', 'cat-violet', 'cat-salmon', 'cat-magenta', 'cat-sky', 'cat-sapphire', 'cat-brown', 'cat-red' ];
            $( '.cat-links a' ).addClass( 'cat' );
            $( '.cat-links a' ).each( function () {
                if ( classes.length === 0 )
                    return false; // break jQuery each

                var index = Math.floor( Math.random() * classes.length );
                var className = classes[index];

                console.log( className );
                $( this ).addClass( className );
            } );
        },
        imageResize: function () {
            var photoContainer = $( ".aspect-ratio .img-card" );

            photoContainer.each( function () {
                var wrapperWidth = $( this ).width();
                var wrapperHeight = $( this ).height();
                var wrapperRatio = wrapperWidth / wrapperHeight;

                console.log( "Ww: " + wrapperWidth + "Wh: " + wrapperHeight );

                var imageWidth = $( this ).find( "img" ).width();
                var imageHeight = $( this ).find( "img" ).height();
                var imageRatio = imageWidth / imageHeight;

                /*if (wrapperWidth === 0 || wrapperHeight === 0) {
                 return false;
                 }*/

                if ( imageRatio <= wrapperRatio ) {
                    var newImageHeight = wrapperWidth / imageRatio;
                    var newImageWidth = wrapperWidth;
                    var semiImageHeight = newImageHeight / 2;

                    $( this ).find( "img" ).css( {
                        width: newImageWidth + 1,
                        height: newImageHeight + 1,
                        marginTop: -semiImageHeight,
                        marginLeft: 0,
                        top: "50%",
                        left: "0"
                    } );

                } else {
                    var newImageHeight = wrapperHeight;
                    var newImageWidth = wrapperHeight * imageRatio;
                    var semiImageWidth = newImageWidth / 2;

                    $( this ).find( "img" ).css( {
                        width: newImageWidth + 1,
                        height: newImageHeight + 1,
                        marginTop: 0,
                        marginLeft: -semiImageWidth,
                        top: "0",
                        left: "50%"
                    } );
                }

                $( this ).css( "opacity", "1" );

            } );
        }
    };

    $( document ).on( 'ready', function ( ) {
        Reign.init( );
    } );

} )( jQuery );

/* topbar first time render fix */
jQuery( document ).ready( function () {
    if ( jQuery( 'div#wbcom-header-topbar' ).length ) {
        var topbar_height = jQuery( 'div#wbcom-header-topbar' ).height();
        var headerHeight = topbar_height + 32;

        var header_height = jQuery( '.rg-sticky-menu #masthead.sticky-header' ).height();
        var body_padding_top = topbar_height + header_height;

        jQuery( '.rg-header-top-bar #wbcom-header-topbar' ).show();
        jQuery( '.rg-header-top-bar.rg-sticky-menu #masthead.sticky-header' ).css( "top", topbar_height + "px" );
        jQuery( '.admin-bar.rg-header-top-bar.rg-sticky-menu #masthead.sticky-header' ).css( "top", headerHeight + "px" );
        jQuery( '.rg-sticky-menu' ).css( "padding-top", header_height + "px" );
        jQuery( '.rg-header-top-bar.rg-sticky-menu' ).css( "padding-top", body_padding_top + "px" );
    }
} );

/* compatibilty with BP Create Type Plugin */
jQuery( document ).ready( function () {
    jQuery( '.wb-group-type-filters-wrap' ).on( 'click', 'li a', function ( e ) {
        e.preventDefault();
        var value = jQuery( this ).attr( 'data-group-slug' );
        jQuery( '.wb-group-type-filters-wrap li' ).removeClass( 'current' );
        jQuery( this ).parent().addClass( 'current' );

        var object = 'groups';
        bp_filter_request(
            object,
            jq.cookie( 'bp-' + object + '-filter' ),
            jq.cookie( 'bp-' + object + '-scope' ),
            'div.' + object,
            jQuery( '#' + object + '_search' ).val(), //( '#bpgt-groups-search-text' ).val(),
            1,
            'group_type=' + value,
            '',
            ''
            );

        return false;
    } );
} );

/** iphone toggle issue **/
//jQuery( document ).ready( function () {
//    jQuery( ".rg-responsive-menu" ).click( function () {
//    } );
//} );

/* WooCommerce quantity +/- managed */
jQuery( document ).ready( function () {
    QtyChngMinus();
    QtyChngPlus();
} );

// Make the code work after executing AJAX.
jQuery( document ).ajaxComplete( function () {
    QtyChngMinus();

} );
jQuery( document ).ajaxComplete( function () {
    QtyChngPlus();

} );

function QtyChngMinus() {
    jQuery( document ).off( "click", ".product_quantity_minus" ).on( "click", ".product_quantity_minus", function () {

        var qty = jQuery( this ).next( 'input.qty' ).val();
        qty = parseInt( qty );
        if ( qty > 1 ) {
            qty = qty - 1;
            jQuery( this ).next( 'input.qty' ).val( qty ).trigger( 'change' );

        }
    } );
}
function QtyChngPlus() {
    jQuery( document ).off( "click", ".product_quantity_plus" ).on( "click", ".product_quantity_plus", function () {

        var qty = jQuery( this ).prev( 'input.qty' ).val();
        qty = parseInt( qty );
        qty = qty + 1;
        jQuery( this ).prev( 'input.qty' ).val( qty ).trigger( 'change' );

    }
    );
}


/** category slick slider **/
jQuery( '.rg-woo-category-slider-wrap' ).each( function () {
    if ( wp_main_js_obj.reign_rtl ) {
        var rt = true;
    } else {
        var rt = false;
    }

    jQuery( this ).slick( {
        rtl: rt,
        responsive: [
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 543,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    } );
} );

/**
 * Managing action buttons of profile header for groups and memebers.
 */
jQuery( document ).ready( function () {
    if ( !( jQuery( '.bp-legacy .wbtm-item-buttons-wrapper #item-buttons > div' ).length ) ) {
        jQuery( '.bp-legacy .wbtm-item-buttons-wrapper' ).hide();
    }
} );

jQuery( document ).ready( function () {
    if ( !( jQuery( '.bp-nouveau .wbtm-item-buttons-wrapper #item-buttons button' ).length ) ) {
        jQuery( '.bp-nouveau .wbtm-item-buttons-wrapper' ).hide();
    }
} );

( function ( $ ) {
    $( document ).ready( function () {

        if ( !wp_main_js_obj.single_activity_page ) {
            //append read more on page with rtmedia read more text
            var maxLength = wp_main_js_obj.excerpt_length;
            if ( wp_main_js_obj.theme_package_id === 'nouveau' ) {
                jQuery( document ).ajaxComplete( function ( event, xhr, settings ) {
                    if ( settings.data ) {
                        var formdata = deParams( settings.data );
                        var action = formdata['action'];
                        if ( action === 'activity_filter' ) {
                            setTimeout( function () {
                                $( ".rtmedia-activity-text" ).each( function () {
                                    var myStr = $( this ).find( 'span' ).text();
                                    if ( $.trim( myStr ).length > maxLength ) {
                                        var newStr = myStr.substring( 0, maxLength );
                                        var removedStr = myStr.substring( maxLength, $.trim( myStr ).length );
                                        $( this ).empty().html( newStr );
                                        $( this ).append( '<a href="javascript:void(0);" class="read-more activity-read-more">' + wp_main_js_obj.append_text + '</a>' );
                                        $( this ).append( '<span class="rtmedia-more-text">' + removedStr + '</span>' );
                                    }
                                } );
                            }, 1000 );
                        }
                    }
                } );
            } else {
                $( ".rtmedia-activity-text" ).each( function () {
                    var myStr = $( this ).text();
                    if ( $.trim( myStr ).length > maxLength ) {
                        var newStr = myStr.substring( 0, maxLength );
                        var removedStr = myStr.substring( maxLength, $.trim( myStr ).length );
                        $( this ).empty().html( newStr );
                        $( this ).append( '<a href="javascript:void(0);" class="read-more activity-read-more">' + wp_main_js_obj.append_text + '</a>' );
                        $( this ).append( '<span class="rtmedia-more-text">' + removedStr + '</span>' );
                    }
                } );
            }
            $( document ).on( 'click', '.activity-content .read-more', function () {
                $( this ).siblings( ".rtmedia-more-text" ).contents().unwrap();
                $( this ).remove();
            } );

            jQuery( document ).ajaxComplete( function ( event, xhr, settings ) {
                if ( settings.data ) {
                    var formdata = deParams( settings.data );
                    var action = formdata['action'];
                    if ( 'post_update' === action ) {
                        var maxLength = wp_main_js_obj.excerpt_length;
                        if ( wp_main_js_obj.theme_package_id === 'nouveau' ) {
                            setTimeout( function () {
                                $( ".rtmedia-activity-text" ).each( function () {
                                    var myStr = $( this ).find( 'span' ).text();
                                    if ( $.trim( myStr ).length > maxLength ) {
                                        var newStr = myStr.substring( 0, maxLength );
                                        var removedStr = myStr.substring( maxLength, $.trim( myStr ).length );
                                        $( this ).empty().html( newStr );
                                        $( this ).append( '<a href="javascript:void(0);" class="read-more activity-read-more">' + wp_main_js_obj.append_text + '</a>' );
                                        $( this ).append( '<span class="rtmedia-more-text">' + removedStr + '</span>' );
                                    }
                                } );
                            }, 1000 );
                        } else {
                            $( ".rtmedia-activity-text" ).each( function () {
                                var myStr = $( this ).text();
                                if ( $.trim( myStr ).length > maxLength ) {
                                    var newStr = myStr.substring( 0, maxLength );
                                    var removedStr = myStr.substring( maxLength, $.trim( myStr ).length );
                                    $( this ).empty().html( newStr );
                                    $( this ).append( '<a href="javascript:void(0);" class="read-more activity-read-more">' + wp_main_js_obj.append_text + '</a>' );
                                    $( this ).append( '<span class="rtmedia-more-text">' + removedStr + '</span>' );
                                }
                            } );
                        }
                    }
                }
            } );
        }
        function deParams( str ) {
            return ( str ).replace( /(^\?)/, '' ).split( "&" ).map( function ( n ) {
                return n = n.split( "=" ), this[n[0]] = n[1], this;
            }.bind( { } ) )[0];
        }
    } );
} )( jQuery );