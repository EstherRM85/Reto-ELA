/*
*  ShiftNav.js
*
*  http://shiftnav.io
*
*  Copyright Chris Mavricos, SevenSpark http://sevenspark.com
*/

;(function($,sr){
  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
      var timeout;
      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null;
          };
          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);
          timeout = setTimeout(delayed, threshold || 100);
      };
  }
  jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'shiftsmartresize');


var shift_supports = (function() {
	var div = document.createElement('div'),
		vendors = 'Khtml Ms O Moz Webkit'.split(' ');


	return function(prop) {

		var len = vendors.length;

		if ( prop in div.style ) return true;

		prop = prop.replace(/^[a-z]/, function(val) {
			return val.toUpperCase();
		});

		while(len--) {
			if ( vendors[len] + prop in div.style ) {
				return true;
			}
		}
		return false;
	};
})();


;(function ( $, window, document, undefined ) {

	var pluginName = "shiftnav",
		defaults = {
			mouseEvents: true,
			retractors: true,
			touchOffClose: true,
			clicktest: false,
			windowstest: false,
			debug: false,

			open_current: false,
			collapse_accordions: false,
			scroll_offset:100,
			disable_transforms: false
		};

	function Plugin ( element, options ) {

		this.element = element;

		this.$shiftnav = $( this.element );
		this.$menu = this.$shiftnav.find( 'ul.shiftnav-menu' );

		this.settings = $.extend( {}, defaults, options );
		this._defaults = defaults;
		this._name = pluginName;

		this.touchenabled = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0);

		if( window.navigator.pointerEnabled ){
			this.touchStart = 'pointerdown';
			this.touchEnd	= 'pointerup';
			this.touchMove	= 'pointermove';
		}
		else if( window.navigator.msPointerEnabled ){
			this.touchStart = 'MSPointerDown';
			this.touchEnd	= 'MSPointerUp';
			this.touchMove	= 'MSPointerMove';
		}
		else{
			this.touchStart = 'touchstart';
			this.touchEnd	= 'touchend';
			this.touchMove	= 'touchmove';
		}

		this.toggleevent = this.touchEnd == 'touchend' ? this.touchEnd + ' click' : this.touchEnd;	//add click except for IE
		//this.toggleevent = 'click';
		this.transitionend = 'transitionend.shiftnav webkitTransitionEnd.shiftnav msTransitionEnd.shiftnav oTransitionEnd.shiftnav';

		//TESTING
		if( this.settings.clicktest ) this.touchEnd = 'click';

		this.init();

	}

	Plugin.prototype = {

		init: function () {


			this.$shiftnav.removeClass( 'shiftnav-nojs' );		//We're off and running

			//this.$toggles = $( '.shiftnav-toggle[data-shiftnav-target="'+this.$shiftnav.attr('id')+'"]' );
			this.$toggles = $( '.shiftnav-toggle[data-shiftnav-target="'+this.$shiftnav.data('shiftnav-id')+'"]' );

			//Initialize user interaction events

			this.initializeShiftNav();

			this.initializeTargets();
			this.initializeSubmenuToggleMouseEvents();
			this.initializeRetractors();
			this.initializeResponsiveToggle();

			//this.initializeTouchoffClose();  //attached on open instead

		},



		/* Initalizers */

		initializeShiftNav: function(){

			var $body = $('body'),
				plugin = this;

			//Only enable the site once
			if( !$body.hasClass( 'shiftnav-enabled' ) ){

				$body.addClass( 'shiftnav-enabled' );
				if( shiftnav_data.lock_body == 'on' ) $body.addClass( 'shiftnav-lock' );
				if( shiftnav_data.lock_body_x == 'on' ) $body.addClass( 'shiftnav-lock-x' );

				if( shiftnav_data.shift_body != 'off' ){
					if( shiftnav_data.shift_body_wrapper != '' ){
						$( shiftnav_data.shift_body_wrapper ).addClass( 'shiftnav-wrap' );
					}
					else{
						$body.wrapInner( '<div class="shiftnav-wrap"></div>' );	//unique
						$( 'video[autoplay]' ).each( function(){
							$(this).get(0).play();
						});
					}
				}
				else $body.addClass( 'shiftnav-disable-shift-body' );

				//Move elements outside of shifter
				$( '#shiftnav-toggle-main, #wpadminbar, .shiftnav-fixed-left, .shiftnav-fixed-right' ).appendTo( 'body' );

				var $wrap = $( '.shiftnav-wrap' );

				//Pad top when either using the Full Bar & Auto Gap, or if override is enabled
				var $main_toggle = $( '#shiftnav-toggle-main' );
				if( ( !$main_toggle.hasClass( 'shiftnav-toggle-style-burger_only') && $main_toggle.hasClass( 'shiftnav-togglebar-gap-auto' ) ) ||
					$main_toggle.hasClass( 'shiftnav-togglebar-gap-on' ) ){
					var toggleHeight = $main_toggle.outerHeight();
					$wrap.css( 'padding-top' , toggleHeight );
					$main_toggle.addClass( 'shiftnav-togglebar-gap-on' );

          //Pad body if wrap doesn't exist because shift body is disabled
          if( shiftnav_data.shift_body == 'off' ){

              //Create style for padding-top on body
              var style = 'body.shiftnav-disable-shift-body{ padding-top:'+ toggleHeight + 'px; }';

              //If the breakpoint is set, set up the media query
              if( shiftnav_data.breakpoint !== '' ){
                style = '@media screen and (max-width:'+(shiftnav_data.breakpoint-1)+'px){ '+style+' }';
              }

              var sheet = null;

              //Get the existing style element in the site head, or create one if it does not exist
              var style_el = document.getElementById( 'shiftnav-dynamic-css' );
              if( style_el ){
                sheet = style_el.sheet;
              }
              else{
                style_el = document.createElement("style");
                style_el.appendChild(document.createTextNode(""));
                document.head.appendChild(style_el);
                sheet = style_el.sheet;
              }

              //Add the rule to the style element
              if( sheet && "insertRule" in sheet ){
                sheet.insertRule( style , 0 );
              }
              // else{
              //   $body.css( 'padding-top' , toggleHeight ); //would need to pair with an extra class and padding-top:0 in PHP generated CSS
              // }
          }
				}
				else if( $( 'body' ).hasClass( 'admin-bar' ) ) $( 'html' ).addClass( 'shiftnav-nogap' );

				//Setup non-transform
				//Some browsers provide false positives for feature detection, so we have to do browser detection as well, sadly
				var fpos = false;	//falsePositive	-
				var ua = navigator.userAgent.toLowerCase();

				//Many mobile Android browsers are dumb
				if( /android/.test( ua ) ){
					fpos = true; //we're going to whitelist mobile Android browsers, so assume false positive on Android

					//always ignore old androids
					if( /android [1-3]/.test( ua ) ) fpos = true;
					//Chrome on 4+ is good
					else if( /chrome/.test( ua ) ) fpos = false;
					//Firefox on 4+ is good
					else if( /firefox/.test( ua ) ) fpos = false;

					//always allow Chrome
					//else if( /chrome/.test( ua ) ) fpos = false;
					//Android 4.4+ is okay
					//else if( /android 4.[4-9]/.test( ua ) ) fpos = false;
					//else fpos = true;
				}


				if( !shift_supports( 'transform' ) || fpos || plugin.settings.disable_transforms ){
					$body.addClass( 'shiftnav-no-transforms' );
				}


				//Handle searchbar toggle
				$( '.shiftnav-searchbar-toggle' ).on( this.toggleevent , function( e ){
					e.stopPropagation();
					e.preventDefault();

					var $drop = $( this ).next( '.shiftnav-searchbar-drop' );

					//Close
					if( $drop.hasClass( 'shiftnav-searchbar-drop-open' ) ){
						$drop.removeClass( 'shiftnav-searchbar-drop-open' );
						$( 'body' ).off( 'click.shiftnav-searchbar-drop' );
					}
					//Open
					else{
						$drop.addClass( 'shiftnav-searchbar-drop-open' );
						$drop.find( '.shiftnav-search-input' ).focus();

						//Close on click-off - can't do this immediately because IE is so damn dumb
            if( plugin.settings.touchOffClose ){
  						setTimeout( function(){
  							$( 'body' ).on( 'click.shiftnav-searchbar-drop' , function( e ){
  								$( '.shiftnav-searchbar-drop' ).removeClass( 'shiftnav-searchbar-drop-open' );
  								$( 'body' ).off( 'click.shiftnav-searchbar-drop' );
  							});
  						}, 100);
            }
					}
				});
				$( '.shiftnav-searchbar-drop' ).on( this.toggleevent , function( e ){
					e.stopPropagation();
				});

        //When the dropdown loses focus, close it it touch off close is enabled
        if( this.settings.touchOffClose ){
  				$( '.shiftnav-searchbar-drop .shiftnav-search-input').on( 'blur' , function( e ){
  					if( $( this ).val() == '' && !toggle_clicked ){
  						$( this ).parents( '.shiftnav-searchbar-drop' ).removeClass( 'shiftnav-searchbar-drop-open' );
  					}
  				});
        }

				var toggle_clicked;
				$( '.shiftnav-searchbar-toggle' ).on( 'mousedown' , function( e ){
					toggle_clicked = true;
				});
				$( '.shiftnav-searchbar-toggle' ).on( 'mouseup' , function( e ){
					toggle_clicked = false;
				});


				//Setup shift panel height
				$( '.shiftnav' ).css( 'max-height' , window.innerHeight );
				$( window ).shiftsmartresize( function(){
					$( '.shiftnav' ).css( 'max-height' , window.innerHeight );
				});

			}

			this.$shiftnav.appendTo( 'body' );

			if( this.$shiftnav.hasClass( 'shiftnav-right-edge' ) ){
				this.edge = 'right';
			}
			else this.edge = 'left';

			this.openclass = 'shiftnav-open shiftnav-open-' + this.edge;

			this.$shiftnav.find( '.shiftnav-panel-close' ).on( 'click' , function(){
				plugin.closeShiftNav();
			});

			//Set retractor heights
			this.$shiftnav.find( '.shiftnav-submenu-activation' ).each( function(){
				//var length = $( this ).outerHeight();
				var length = $( this ).siblings( '.shiftnav-target' ).outerHeight();
				$( this ).css( { 'height' : length , 'width' : length } );

				//$( this ).css( 'height' , $( this ).parent( '.menu-item' ).height() );
			});



			//Current open
			if( plugin.settings.open_current ){
				$( '.shiftnav .shiftnav-sub-accordion.current-menu-item, .shiftnav .shiftnav-sub-accordion.current-menu-ancestor' ).addClass( 'shiftnav-active' );
			}

		},

		initializeTargets: function(){

			var plugin = this;

			this.$shiftnav.find( '.shiftnav-scrollto' )
				.removeClass( 'current-menu-item' )
				.removeClass( 'current-menu-ancestor');

			this.$shiftnav.on( 'click' , '.shiftnav-target' , function( e ){
				var scrolltarget = $(this).data( 'shiftnav-scrolltarget' );
				if( scrolltarget ){
					var $target = $( scrolltarget ).first();
					if( $target.length > 0 ){
						//Make current
						var $li = $(this).parent('.menu-item');
						$li.siblings().removeClass( 'current-menu-item' ).removeClass( 'current-menu-ancestor' );
						$li.addClass( 'current-menu-item' );
						var top = $target.offset().top;
						top = top - plugin.settings.scroll_offset;
						$( 'html,body' ).animate({
							scrollTop: top
						}, 1000 , 'swing' ,
						function(){
							plugin.closeShiftNav();	//close the menu after a successful scroll
						});
						return false; //don't follow any links if this scroll target is present
					}
					//if target isn't present here, redirect with hash
					else{
						var href = $(this).attr( 'href' );
						if( href && href.indexOf( '#' ) == -1 ){				//check that hash does not already exist
							if( scrolltarget.indexOf( '#' ) == -1 ){	//if this is a class, add a hash tag
								scrolltarget = '#'+scrolltarget;
							}
							window.location = href + scrolltarget;		//append hash/scroll target to URL and redirect
							e.preventDefault();
						}
						//No href, no worries
					}
				}
				else if( $( this ).is( 'span' ) ){
					var $li = $( this ).parent( '.menu-item' );
					if( $li.hasClass( 'shiftnav-active' ) ){
						plugin.closeSubmenu( $li , 'disabledLink' , plugin );
					}
					else{
						plugin.openSubmenu( $li , 'disabledLink' , plugin );
					}
				}
			});

		},

		initializeSubmenuToggleMouseEvents: function(){

			//Don't initialize if mouse events are disabled
			if( !this.settings.mouseEvents ) return;
			if( this.settings.clicktest ) return;
			if( this.settings.windowstest ) return;


			if( this.settings.debug ) console.log( 'initializeSubmenuToggleMouseEvents' );

			var plugin = this;

			this.$shiftnav.on( 'mouseup.shift-submenu-toggle' , '.shiftnav-submenu-activation' , function(e){ plugin.handleMouseActivation( e , this , plugin ); } );
			//$shiftnav.on( 'mouseout.shift-submenu-toggle'  , '.menu-item' , this.handleMouseout );	//now only added on mouseover
		},

		disableSubmenuToggleMouseEvents: function(){
			if( this.settings.debug ) console.log( 'disableSubmenuToggleMouseEvents' );
			$shiftnav.off( 'mouseover.shift-submenu-toggle' );
			$shiftnav.off( 'mouseout.shift-submenu-toggle'  );
		},


		initializeRetractors: function() {

			if( !this.settings.retractors ) return;	//Don't initialize if retractors are disabled
			var plugin = this;

			//set up the retractors
			this.$shiftnav.on( 'mouseup.shiftnav' , '.shiftnav-retract' , function(e){ plugin.handleSubmenuRetractorEnd( e , this, plugin); } );
		},


		initializeResponsiveToggle: function(){

			var plugin = this;


			this.$toggles.on( 'click' , 'a', function(e){	//this.toggleevent,
				//allow link to be clicked but don't propagate so toggle won't activate
				e.stopPropagation();
			});

			//Toggle on click
			this.$toggles.on( 'click' , function(e){
				plugin.toggle( $(this) , plugin , e );
			});

		},

		toggle: function( $toggle , plugin , e ){
			e.preventDefault();
			e.stopPropagation();

			//Ignore click events when toggle is disabled to avoid both touch and click events firing
			if( e.originalEvent.type == 'click' && $(this).data( 'disableToggle' ) ){
				return;
			}

			if( plugin.$shiftnav.hasClass( 'shiftnav-open-target' ) ){
				//console.log( 'close shift nav' );
				plugin.closeShiftNav();
			}
			else{
				//console.log('open shift nav');
				var toggle_id = $toggle.attr( 'id' );
				var tag = toggle_id == 'shiftnav-toggle-main' ? '[Main Toggle Bar]' : '"'+$(this).text()+'"';

				//When clicking on main toggle, and the menu is open,
				//but it's not the main panel, close whichever panel is actually open instead
				if( ( ( toggle_id == 'shiftnav-toggle-main-button' ) ||
					  ( toggle_id == 'shiftnav-toggle-main' ) ) &&
					$( 'body' ).hasClass( 'shiftnav-open' ) ){
						//Close all shiftnavs
						$( '.shiftnav.shiftnav-open-target' ).shiftnav( 'closeShiftNav' );
				}
				else{
					plugin.openShiftNav( 'toggle: ' + tag );
				}
			}

			//Temporarily disable toggle for click event when touch is fired
			if( e.originalEvent.type != 'click' ){
				$( this ).data( 'disableToggle' , true );
				setTimeout( function(){
					$( this ).data( 'disableToggle' , false );
				}, 1000 );
			}

			return false;
		},



		openShiftNav: function( tag ){

			tag = tag || '?';

			var plugin = this;

			if( this.settings.debug ) console.log( 'openShiftNav ' + tag );

			$( 'body' )
				.removeClass( 'shiftnav-open-right shiftnav-open-left' )
				.addClass( this.openclass )
				.addClass( 'shiftnav-transitioning' );

			//console.log( 'close ' + $( '.shiftnav-open-target' ).attr( 'id' ) );
			$( '.shiftnav-open-target' ).removeClass( 'shiftnav-open-target' );
			this.$shiftnav
				.addClass( 'shiftnav-open-target' )
				.on( plugin.transitionend, function(){
						//if( plugin.settings.debug ) console.log( 'finished submenu close transition' );
						$( 'body' ).removeClass( 'shiftnav-transitioning' );
						$( this ).off( plugin.transitionend );
					});

			this.disableTouchoffClose();
			this.initializeTouchoffClose();

		},

		closeShiftNav: function(){

			var plugin = this;

			$( 'body' )
				.removeClass( this.openclass )
				.addClass( 'shiftnav-transitioning' );

			this.$shiftnav
				.removeClass( 'shiftnav-open-target' )
				.on( plugin.transitionend, function(){
						//if( plugin.settings.debug ) console.log( 'finished submenu close transition' );
						$( 'body' ).removeClass( 'shiftnav-transitioning' );
						$( this ).off( plugin.transitionend );
					});

			this.disableTouchoffClose();
		},

		initializeTouchoffClose: function(){

			if( !this.settings.touchOffClose ) return;  //Don't initialize if touch off close is disabled

			var plugin = this;
			$( document ).on( 'click.shiftnav ' + this.touchEnd + '.shiftnav' , function( e ){ plugin.handleTouchoffClose( e , this , plugin ); } );

		},
		disableTouchoffClose: function(){
			$( document ).off( '.shiftnav' );
		},

		handleMouseActivation: function( e , activator , plugin ){
			if( plugin.settings.debug ) console.log( 'handleMouseover, add mouseout', e );

			var $li = $( activator ).parent();

			if( $li.hasClass( 'shiftnav-active' ) ){
				plugin.closeSubmenu( $li , 'mouseActivate' , plugin );
			}
			else{
				plugin.openSubmenu( $li , 'mouseActivate' , plugin );
			}

			//Only attach mouseout after mouseover, this way menus opened by touch won't be closed by mouseout
			//$li.on( 'mouseout.shift-submenu-toggle' , function( e ){ plugin.handleMouseout( e , this , plugin ); } );
		},


		handleSubmenuRetractorEnd: function( e , li , plugin ){
			e.preventDefault();
			e.stopPropagation();

			var $li = $(li).parent( 'ul' ).parent( 'li' );
			plugin.closeSubmenu( $li , 'handleSubmenuRetractor' , plugin );

			if( plugin.settings.debug ) console.log( 'handleSubmenuRetractorEnd ' + $li.find('> a').text());

		},



		handleTouchoffClose: function( e , _this , plugin ){

			//Don't fire during transtion
			if( $( 'body' ).is( '.shiftnav-transitioning' ) ) return;

			if( $(e.target).parents().add( $(e.target) ).filter( '.shiftnav, .shiftnav-toggle, .shiftnav-ignore' ).length === 0 ){


				if( plugin.settings.debug ) console.log( 'touchoff close ', e );

				e.preventDefault();
				e.stopPropagation();

				plugin.closeShiftNav();
				plugin.disableTouchoffClose();

			}

		},




		/* Controllers */
		scrollPanel: function( y ){
			if( shiftnav_data.scroll_panel == 'off' ) return 0;

			if( typeof y == 'undefined' ){
				return this.$shiftnav.find( '.shiftnav-inner' ).scrollTop();
			}
			else{
				this.$shiftnav.find( '.shiftnav-inner' ).scrollTop( y );
			}
		},

		openSubmenu: function( $li , tag , plugin ){
			if( !$li.hasClass( 'shiftnav-active' ) ){

				//plugin.setMinimumHeight( 'open' , $li );

				if( $li.hasClass( 'shiftnav-sub-shift' ) ){

					$li.siblings( '.shiftnav-active' ).removeClass( 'shiftnav-active' );

					//switch to position absolute, then delay activation below due to Firefox browser bug
					$li.toggleClass( 'shiftnav-caulk' );

					plugin.$shiftnav.addClass( 'shiftnav-sub-shift-active' );

				}
				else{
					if( plugin.settings.collapse_accordions ){
						$li.siblings( '.shiftnav-active' ).removeClass( 'shiftnav-active' );
					}
				}

				//Active flags
				$li.parents( 'ul' ).removeClass( 'shiftnav-sub-active-current' );
				$li.find( '> ul' )
					.addClass( 'shiftnav-sub-active' )
					.addClass( 'shiftnav-sub-active-current' );

				//A dumb timeout hack to fix this FireFox browser bug https://bugzilla.mozilla.org/show_bug.cgi?id=625289
				setTimeout( function(){
					$li.addClass( 'shiftnav-active' );
					$li.trigger( 'shiftnav-open-submenu' );	//API
					$li.removeClass( 'shiftnav-caulk' );

					//Wait until item has moved before calculating position
					setTimeout( function(){
						//scroll to top of the menu, make note of initial position
						var y = plugin.scrollPanel();
						$li.data( 'scroll-back' , y );
						var scrollPanelTo = $li.offset().top + y;
						plugin.scrollPanel( scrollPanelTo );
						//plugin.scrollPanel( 0 );
					}, 100 );

				}, 1 );
			}
		},

		closeSubmenu: function( $li , tag , plugin ){

			//var plugin = this;

			if( this.settings.debug ) console.log( 'closeSubmenu ' + $li.find( '>a' ).text() + ' [' + tag + ']' );

			//If this menu is currently active and has a submenu, close it
			if( $li.hasClass( 'menu-item-has-children' ) && $li.hasClass( 'shiftnav-active' ) ){

				$li.addClass( 'shiftnav-in-transition' );	//transition class keeps visual flag until transition completes

				$li.each( function(){
					var _$li = $(this);
					var _$ul = _$li.find( '> ul' );

					//Remove the transition flag once the transition is completed
					_$ul.on( plugin.transitionend + '_closesubmenu', function(){
						if( plugin.settings.debug ) console.log( 'finished submenu close transition' );
						_$li.removeClass( 'shiftnav-in-transition' );
						_$ul.off( plugin.transitionend  + '_closesubmenu' );
					});

					//Close all children
					plugin.closeSubmenu( _$li.find( '.shiftnav-active' ) , tag+'_recursive' , plugin );
				});
			}

			//Actually remove the active class, which causes the submenu to close
			$li.removeClass( 'shiftnav-active' );

			//Shift Sub Specific
			if( $li.hasClass( 'shiftnav-sub-shift' ) ){
				if( $li.parents( '.shiftnav-sub-shift' ).length == 0 ) plugin.$shiftnav.removeClass( 'shiftnav-sub-shift-active' );

				//return to original position
				var y = $li.data( 'scroll-back' );
				if( y !== 'undefined' ) plugin.scrollPanel( y );
				//console.log( 'y = ' + y );

			}

			//Active flags
			$li.find( '> ul' )
				.removeClass( 'shiftnav-sub-active' )
				.removeClass( 'shiftnav-sub-active-current' );
			$li.closest( 'ul' ).addClass( 'shiftnav-sub-active-current' );

			$li.trigger( 'shiftnav-close-submenu' );	//API

		},

		closeAllSubmenus: function(){
			$( this.element ).find( 'li.menu-item-has-children' ).removeClass( 'shiftnav-active' );
		},

	};

	$.fn[ pluginName ] = function ( options ) {

		var args = arguments;

		if ( options === undefined || typeof options === 'object' ) {
			return this.each(function() {
				if ( !$.data( this, "plugin_" + pluginName ) ) {
					$.data( this, "plugin_" + pluginName, new Plugin( this, options ) );
				}
			});
		}
		else if ( typeof options === 'string' && options[0] !== '_' && options !== 'init') {
			// Cache the method call to make it possible to return a value
			var returns;

			this.each(function () {
				var instance = $.data(this, 'plugin_' + pluginName);

				// Tests that there's already a plugin-instance and checks that the requested public method exists
				if ( instance instanceof Plugin && typeof instance[options] === 'function') {

					// Call the method of our plugin instance, and pass it the supplied arguments.
					returns = instance[options].apply( instance, Array.prototype.slice.call( args, 1 ) );
				}

				// Allow instances to be destroyed via the 'destroy' method
				if (options === 'destroy') {
					$.data(this, 'plugin_' + pluginName, null);
				}
			});

			// If the earlier cached method gives a value back return the value, otherwise return this to preserve chainability.
			return returns !== undefined ? returns : this;
		}
	};

})( jQuery, window, document );

(function($){

	var shiftnav_is_initialized = false;

	jQuery(function($) {
		initialize_shiftnav( 'document.ready' );
	});

	//Backup
	$( window ).on( 'load' , function(){
		initialize_shiftnav( 'window.load' );
	});

	function initialize_shiftnav( init_point ){

		if( shiftnav_is_initialized ) return;

		shiftnav_is_initialized = true;

		if( ( typeof console != "undefined" ) && init_point == 'window.load' ) console.log( 'ShiftNav initialized via ' + init_point );

		//Remove Loading Message
		$( '.shiftnav-loading' ).remove();

		//Run ShiftNav
		jQuery( '.shiftnav' ).shiftnav({
			open_current : shiftnav_data.open_current == 'on' ? true : false,
			collapse_accordions : shiftnav_data.collapse_accordions == 'on' ? true : false,
			breakpoint : parseInt( shiftnav_data.breakpoint ),
			touchOffClose: shiftnav_data.touch_off_close == 'on' ? true : false,
			scroll_offset:  shiftnav_data.scroll_offset,
			disable_transforms: shiftnav_data.disable_transforms == 'on' ? true : false
			//debug: true
			//mouseEvents: false
			//clicktest: true
		});

		//Scroll to non-ID "hashes"
		if( window.location.hash.substring(1,2) == '.' ){
			var $scrollTarget = $( window.location.hash.substring(1) );
			if( $scrollTarget.length ){
				var top = $scrollTarget.offset().top - shiftnav_data.scroll_offset;
				if( $scrollTarget.length ) window.scrollTo( 0 , top );
			}
		}

		if(  window.location.hash ){
			//Highlight item
			var hash = window.location.hash;
			if( hash.substring(1,2) == '.' ) hash = hash.substring(1);

      //Sanitize any hash string, just in case.  These ar all the valid URL fragment characters, anything else gets stripped
      hash = hash.replace( /[^#a-z0-9!$&'()*+,;=]/gi , '' );  //hash = hash.replace(/\W/g,

      //var $li = $( '.shiftnav .shiftnav-target[data-shiftnav-scrolltarget="'+hash+'"]' ).parent();
      //Do it this way to avoid passing user-editable content into the $() function - find() is safer
      var $li = $( '.shiftnav' ).find( '.shiftnav-target[data-shiftnav-scrolltarget="'+hash+'"]' ).parent();
			if( $li.length ){
				//console.log( $li );
				$li.siblings().removeClass( 'current-menu-item' ).removeClass( 'current-menu-ancestor' );
				$li.addClass( 'current-menu-item' );
			}
		}

		$( '.shiftnav' ).trigger( 'shiftnav-loaded' );
	}

  function escapeHtml(str) {
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
  }

})(jQuery);
