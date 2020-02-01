/*! wbcom-elementor-addons - v1.9.3 - 03-10-2017 */
(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
var WbcomElementorAddonsFrontend = function( $ ) {
	var self = this;

	this.config = WbcomElementorAddonsFrontendConfig;

	this.modules = {};

	var handlers = {
		form: require( 'modules/forms/assets/js/frontend/frontend' ),
		countdown: require( 'modules/countdown/assets/js/frontend/frontend' ),
		posts: require( 'modules/posts/assets/js/frontend/frontend' ),
		slides: require( 'modules/slides/assets/js/frontend/frontend' ),
        share_buttons: require( 'modules/share-buttons/assets/js/frontend/frontend' ),
        nav_menu: require( 'modules/nav-menu/assets/js/frontend/frontend' ),
        animatedText: require( 'modules/animated-headline/assets/js/frontend/frontend' ),
		carousel: require( 'modules/carousel/assets/js/frontend/frontend' ),
        social: require( 'modules/social/assets/js/frontend/frontend' )
    };

	var initModules = function() {
		self.modules = {};

		$.each( handlers, function( moduleName ) {
			self.modules[ moduleName ] = new this( $ );
		} );
	};

	this.init = function() {
		$( window ).on( 'elementor/frontend/init', initModules );
	};

	this.init();
};

window.elementorProFrontend = new WbcomElementorAddonsFrontend( jQuery );

},{"modules/animated-headline/assets/js/frontend/frontend":2,"modules/carousel/assets/js/frontend/frontend":4,"modules/countdown/assets/js/frontend/frontend":8,"modules/forms/assets/js/frontend/frontend":10,"modules/nav-menu/assets/js/frontend/frontend":15,"modules/posts/assets/js/frontend/frontend":17,"modules/share-buttons/assets/js/frontend/frontend":21,"modules/slides/assets/js/frontend/frontend":23,"modules/social/assets/js/frontend/frontend":25}],2:[function(require,module,exports){
module.exports = function() {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/animated-headline.default', require( './handlers/animated-headlines' ) );
};

},{"./handlers/animated-headlines":3}],3:[function(require,module,exports){
var AnimatedHeadlineHandler = elementorFrontend.Module.extend( {
	svgPaths: {
		circle: [ 'M325,18C228.7-8.3,118.5,8.3,78,21C22.4,38.4,4.6,54.6,5.6,77.6c1.4,32.4,52.2,54,142.6,63.7 c66.2,7.1,212.2,7.5,273.5-8.3c64.4-16.6,104.3-57.6,33.8-98.2C386.7-4.9,179.4-1.4,126.3,20.7' ],
		underline_zigzag: [ 'M9.3,127.3c49.3-3,150.7-7.6,199.7-7.4c121.9,0.4,189.9,0.4,282.3,7.2C380.1,129.6,181.2,130.6,70,139 c82.6-2.9,254.2-1,335.9,1.3c-56,1.4-137.2-0.3-197.1,9' ],
		x: [ 'M497.4,23.9C301.6,40,155.9,80.6,4,144.4', 'M14.1,27.6c204.5,20.3,393.8,74,467.3,111.7' ],
		strikethrough: [ 'M3,75h493.5' ],
		curly: [ 'M3,146.1c17.1-8.8,33.5-17.8,51.4-17.8c15.6,0,17.1,18.1,30.2,18.1c22.9,0,36-18.6,53.9-18.6 c17.1,0,21.3,18.5,37.5,18.5c21.3,0,31.8-18.6,49-18.6c22.1,0,18.8,18.8,36.8,18.8c18.8,0,37.5-18.6,49-18.6c20.4,0,17.1,19,36.8,19 c22.9,0,36.8-20.6,54.7-18.6c17.7,1.4,7.1,19.5,33.5,18.8c17.1,0,47.2-6.5,61.1-15.6' ],
		diagonal: [ 'M13.5,15.5c131,13.7,289.3,55.5,475,125.5' ],
		'double': [ 'M8.4,143.1c14.2-8,97.6-8.8,200.6-9.2c122.3-0.4,287.5,7.2,287.5,7.2', 'M8,19.4c72.3-5.3,162-7.8,216-7.8c54,0,136.2,0,267,7.8' ],
		double_underline: [ 'M5,125.4c30.5-3.8,137.9-7.6,177.3-7.6c117.2,0,252.2,4.7,312.7,7.6', 'M26.9,143.8c55.1-6.1,126-6.3,162.2-6.1c46.5,0.2,203.9,3.2,268.9,6.4' ],
		underline: [ 'M7.7,145.6C109,125,299.9,116.2,401,121.3c42.1,2.2,87.6,11.8,87.3,25.7' ]
	},

	getDefaultSettings: function() {
		var settings = {
			animationDelay: 2500,
			//letters effect
			lettersDelay: 50,
			//typing effect
			typeLettersDelay: 150,
			selectionDuration: 500,
			//clip effect
			revealDuration: 600,
			revealAnimationDelay: 1500
		};

		settings.typeAnimationDelay = settings.selectionDuration + 800;

		settings.selectors = {
			headline: '.elementor-headline',
			dynamicWrapper: '.elementor-headline-dynamic-wrapper'
		};

		settings.classes = {
			dynamicText: 'elementor-headline-dynamic-text',
			dynamicLetter: 'elementor-headline-dynamic-letter',
			textActive: 'elementor-headline-text-active',
			textInactive: 'elementor-headline-text-inactive',
			letters: 'elementor-headline-letters',
			animationIn: 'elementor-headline-animation-in',
			typeSelected: 'elementor-headline-typing-selected'
		};

		return settings;
	},

	getDefaultElements: function() {
		var selectors = this.getSettings( 'selectors' ),
			classes = this.getSettings( 'classes' );

		return {
			$headline: this.$element.find( selectors.headline ),
			$dynamicWrapper: this.$element.find( selectors.dynamicWrapper )
		};
	},

	getNextWord: function( $word ) {
		return $word.is( ':last-child' ) ? $word.parent().children().eq( 0 ) : $word.next();
	},

	switchWord: function( $oldWord, $newWord ) {
		$oldWord
			.removeClass( 'elementor-headline-text-active' )
			.addClass( 'elementor-headline-text-inactive' );

		$newWord
			.removeClass( 'elementor-headline-text-inactive' )
			.addClass( 'elementor-headline-text-active' );
	},

	singleLetters: function() {
		var classes = this.getSettings( 'classes' );

		this.elements.$dynamicText.each( function() {
			var $word = jQuery( this ),
				letters = $word.text().split( '' ),
				isActive = $word.hasClass( classes.textActive );

			$word.empty();

			letters.forEach( function( letter ) {
				var $letter = jQuery( '<span>', { 'class': classes.dynamicLetter } ).text( letter );

				if ( isActive ) {
					$letter.addClass( classes.animationIn );
				}

				$word.append( $letter );
			} );

			$word.css( 'opacity', 1 );
		} );
	},

	showLetter: function( $letter, $word, bool, duration ) {
		var self = this,
			classes = this.getSettings( 'classes' ),
			animationType = self.getElementSettings( 'animation_type' );

		$letter.addClass( classes.animationIn );

		if ( ! $letter.is( ':last-child' ) ) {
			setTimeout( function() {
				self.showLetter( $letter.next(), $word, bool, duration );
			}, duration );
		} else {
			if ( ! bool ) {
				setTimeout( function() {
					self.hideWord( $word );
				}, self.getSettings( 'animationDelay' ) );
			}
		}
	},

	hideLetter: function( $letter, $word, bool, duration ) {
		var self = this,
			settings = this.getSettings();

		$letter.removeClass( settings.classes.animationIn );

		if ( ! $letter.is( ':last-child' ) ) {
			setTimeout( function() {
				self.hideLetter( $letter.next(), $word, bool, duration );
			}, duration );
		} else if ( bool ) {
			setTimeout( function() {
				self.hideWord( self.getNextWord( $word ) );
			}, self.getSettings( 'animationDelay' ) );
		}
	},

	showWord: function( $word, $duration ) {
		var self = this,
			settings = self.getSettings(),
			animationType = self.getElementSettings( 'animation_type' );

		if ( 'typing' === animationType ) {
			self.showLetter( $word.find( '.' + settings.classes.dynamicLetter ).eq( 0 ), $word, false, $duration );

			$word
				.addClass( settings.classes.textActive )
				.removeClass( settings.classes.textInactive );
		} else if ( 'clip' === animationType ) {
			self.elements.$dynamicWrapper.animate( { 'width': $word.width() + 10 }, settings.revealDuration, function() {
				setTimeout( function() {
					self.hideWord( $word );
				}, settings.revealAnimationDelay );
			} );
		}
	},

	hideWord: function( $word ) {
		var self = this,
			settings = self.getSettings(),
			classes = settings.classes,
			letterSelector = '.' + classes.dynamicLetter,
			animationType = self.getElementSettings( 'animation_type' ),
			nextWord = self.getNextWord( $word );

		if ( 'typing' === animationType ) {
			self.elements.$dynamicWrapper.addClass( classes.typeSelected );

			setTimeout( function() {
				self.elements.$dynamicWrapper.removeClass( classes.typeSelected );

				$word
					.addClass( settings.classes.textInactive )
					.removeClass( classes.textActive )
					.children( letterSelector )
					.removeClass( classes.animationIn );
			}, settings.selectionDuration );
			setTimeout( function() {
				self.showWord( nextWord, settings.typeLettersDelay );
			}, settings.typeAnimationDelay );

		} else if ( self.elements.$headline.hasClass( classes.letters ) ) {
			var bool = $word.children( letterSelector ).length >= nextWord.children( letterSelector ).length;

			self.hideLetter( $word.find( letterSelector ).eq( 0 ), $word, bool, settings.lettersDelay );

			self.showLetter( nextWord.find( letterSelector ).eq( 0 ), nextWord, bool, settings.lettersDelay );

		} else if ( 'clip' === animationType ) {
			self.elements.$dynamicWrapper.animate( { width: '2px' }, settings.revealDuration, function() {
				self.switchWord( $word, nextWord );
				self.showWord( nextWord );
			} );
		} else {
			self.switchWord( $word, nextWord );

			setTimeout( function() {
				self.hideWord( nextWord );
			}, settings.animationDelay );
		}
	},

	animateHeadline: function() {
		var self = this,
			animationType = self.getElementSettings( 'animation_type' ),
			$dynamicWrapper = self.elements.$dynamicWrapper;

		if ( 'clip' === animationType ) {
			$dynamicWrapper.width( $dynamicWrapper.width() + 10 );
		} else if ( 'typing' !== animationType ) {
			//assign to .elementor-headline-dynamic-wrapper the width of its longest word
			var width = 0;

			self.elements.$dynamicText.each( function() {
				var wordWidth = jQuery( this ).width();

				if ( wordWidth > width ) {
					width = wordWidth;
				}
			} );

			$dynamicWrapper.css( 'width', width );
		}

		//trigger animation
		setTimeout( function() {
			self.hideWord( self.elements.$dynamicText.eq( 0 ) );
		}, self.getSettings( 'animationDelay' ) );
	},

	getSvgPaths: function( pathName ) {
		var pathsInfo = this.svgPaths[ pathName ],
			$paths = jQuery();

		pathsInfo.forEach( function( pathInfo ) {
			$paths = $paths.add( jQuery( '<path>', { d: pathInfo } ) );
		} );

		return $paths;
	},

	fillWords: function() {
		var elementSettings = this.getElementSettings(),
			classes = this.getSettings( 'classes' ),
			$dynamicWrapper = this.elements.$dynamicWrapper;

		if ( 'rotate' === elementSettings.headline_style ) {
			var rotatingText = elementSettings.rotating_text.split( '\n' );

			rotatingText.forEach( function( word, index ) {
				var $dynamicText = jQuery( '<span>', { 'class': classes.dynamicText } ).html( word.replace( / /g, '&nbsp;' ) );

				if ( ! index ) {
					$dynamicText.addClass( classes.textActive );
				}

				$dynamicWrapper.append( $dynamicText );
			} );
		} else {
			var $dynamicText = jQuery( '<span>', { 'class': classes.dynamicText + ' ' + classes.textActive } ).text( elementSettings.highlighted_text ),
				$svg = jQuery( '<svg>', {
					xmlns: 'http://www.w3.org/2000/svg',
					viewBox: '0 0 500 150',
					preserveAspectRatio: 'none'
				} ).html( this.getSvgPaths( elementSettings.marker ) );

			$dynamicWrapper.append( $dynamicText, $svg[0].outerHTML );
		}

		this.elements.$dynamicText = $dynamicWrapper.children( '.' + classes.dynamicText );
	},

	rotateHeadline: function() {
		var settings = this.getSettings();

		//insert <span> for each letter of a changing word
		if ( this.elements.$headline.hasClass( settings.classes.letters ) ) {
			this.singleLetters();
		}

		//initialise headline animation
		this.animateHeadline();
	},

	initHeadline: function() {
		if ( 'rotate' === this.getElementSettings( 'headline_style' ) ) {
			this.rotateHeadline();
		}
	},

	onInit: function() {
		elementorFrontend.Module.prototype.onInit.apply( this, arguments );

		this.fillWords();

		this.initHeadline();
	}
} );

module.exports = function( $scope ) {
	new AnimatedHeadlineHandler( { $element: $scope } );
};

},{}],4:[function(require,module,exports){
module.exports = function() {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/media-carousel.default', require( './handlers/media-carousel' ) );
	elementorFrontend.hooks.addAction( 'frontend/element_ready/testimonial-carousel.default', require( './handlers/testimonial-carousel' ) );
};

},{"./handlers/media-carousel":6,"./handlers/testimonial-carousel":7}],5:[function(require,module,exports){
module.exports = elementorFrontend.Module.extend( {

	swipers: {},

	getDefaultSettings: function() {
		return {
			selectors: {
				mainSwiper: '.elementor-main-swiper',
				swiperSlide: '.swiper-slide'
			},
			slidesPerView: {
				desktop: 3,
				tablet: 2,
				mobile: 1
			}
		};
	},

	getDefaultElements: function() {
		var selectors = this.getSettings( 'selectors' );

		var elements = {
			$mainSwiper: this.$element.find( selectors.mainSwiper )
		};

		elements.$mainSwiperSlides = elements.$mainSwiper.find( selectors.swiperSlide );

		return elements;
	},

	getSlidesCount: function() {
		return this.elements.$mainSwiperSlides.length;
	},

	getInitialSlide: function() {
		var editSettings = this.getEditSettings();

		return editSettings.activeItemIndex ? editSettings.activeItemIndex - 1 : Math.floor( ( this.getSlidesCount() - 1 ) / 2 );
	},

	getSlidesPerView: function() {
		return Math.min( this.getSlidesCount(), +this.getElementSettings( 'slides_per_view' ) || this.getSettings( 'slidesPerView.desktop' ) );
	},

	getTabletSlidesPerView: function() {
		return Math.min( this.getSlidesCount(), +this.getElementSettings( 'slides_per_view_tablet' ) || this.getSettings( 'slidesPerView.tablet' ) );
	},

	getMobileSlidesPerView: function() {
		return Math.min( this.getSlidesCount(), +this.getElementSettings( 'slides_per_view_mobile' ) || this.getSettings( 'slidesPerView.mobile' ) );
	},

	getSwiperOptions: function() {
		var elementSettings = this.getElementSettings();

		return {
			pagination: '.swiper-pagination',
			nextButton: '.elementor-swiper-button-next',
			prevButton: '.elementor-swiper-button-prev',
			paginationClickable: true,
			grabCursor: true,
			initialSlide: this.getInitialSlide(),
			slidesPerView: this.getSlidesPerView(),
			spaceBetween: elementSettings.space_between.size,
			paginationType: elementSettings.pagination,
			autoplay: elementorFrontend.isEditMode() ? 0 : elementSettings.autoplay_speed,
			autoplayDisableOnInteraction: !! elementSettings.pause_on_interaction,
			loop: true,
			loopedSlides: this.getSlidesCount(),
			speed: elementSettings.speed,
			effect: elementSettings.effect,
			breakpoints: {
				1024: {
					slidesPerView: this.getTabletSlidesPerView(),
					spaceBetween: elementSettings.space_between_tablet.size
				},
				767: {
					slidesPerView: this.getMobileSlidesPerView(),
					spaceBetween: elementSettings.space_between_mobile.size
				}
			}
		};
	},

	updateSpaceBetween: function( swiper, propertyName ) {
		var newSize = this.getElementSettings( propertyName ).size,
			deviceMatch = propertyName.match( 'space_between_(.*)' );

		if ( deviceMatch ) {
			var breakpointDictionary = {
				tablet: 1024,
				mobile: 767
			};

			swiper.params.breakpoints[ breakpointDictionary[ deviceMatch[1] ] ].spaceBetween = newSize;
		} else {
			swiper.originalParams.spaceBetween = newSize;
		}

		swiper.params.spaceBetween = newSize;

		swiper.onResize();
	},

	onInit: function() {
		elementorFrontend.Module.prototype.onInit.apply( this, arguments );

		if ( 1 >= this.getSlidesCount() ) {
			return;
		}

		this.swipers.main = new Swiper( this.elements.$mainSwiper, this.getSwiperOptions() );
	},

	onElementChange: function( propertyName ) {
		if ( 0 === propertyName.indexOf( 'width' ) ) {
			this.swipers.main.onResize();
		}

		if ( 0 === propertyName.indexOf( 'space_between' ) ) {
			this.updateSpaceBetween( this.swipers.main, propertyName );
		}
	}
} );

},{}],6:[function(require,module,exports){
var Base = require( './base' ),
	MediaCarousel;

MediaCarousel = Base.extend( {

	slideshowSpecialElementSettings: [
		'slides_per_view',
		'slides_per_view_tablet',
		'slides_per_view_mobile'
	],

	isSlideshow: function() {
		return 'slideshow' === this.getElementSettings( 'skin' );
	},

	getDefaultSettings: function() {
		var defaultSettings = Base.prototype.getDefaultSettings.apply( this, arguments );

		if ( this.isSlideshow() ) {
			defaultSettings.selectors.thumbsSwiper = '.elementor-thumbnails-swiper';

			defaultSettings.slidesPerView = {
				desktop: 5,
				tablet: 4,
				mobile: 3
			};
		}

		return defaultSettings;
	},

	getElementSettings: function( setting ) {
		if ( -1 !== this.slideshowSpecialElementSettings.indexOf( setting ) && this.isSlideshow() ) {
			setting = 'slideshow_' + setting;
		}

		return Base.prototype.getElementSettings.call( this, setting );
	},

	getDefaultElements: function() {
		var selectors = this.getSettings( 'selectors' ),
			defaultElements = Base.prototype.getDefaultElements.apply( this, arguments );

		if ( this.isSlideshow() ) {
			defaultElements.$thumbsSwiper = this.$element.find( selectors.thumbsSwiper );
		}

		return defaultElements;
	},

	getSwiperOptions: function() {
		var options = Base.prototype.getSwiperOptions.apply( this, arguments );

		if ( 'coverflow' === this.getElementSettings( 'skin' ) ) {
			options.effect = 'coverflow';
		}

		if ( this.isSlideshow() ) {
			options.slidesPerView = 1;

			delete options.pagination;
			delete options.breakpoints;
		}

		return options;
	},

	getTabletSlidesPerView: function() {
		var elementSettings = this.getElementSettings();

		if ( ! elementSettings.slides_per_view_tablet && 'coverflow' === elementSettings.skin ) {
			return Math.min( this.getSlidesCount(), 3 );
		}

		return Base.prototype.getTabletSlidesPerView.apply( this, arguments );
	},

	onInit: function() {
		Base.prototype.onInit.apply( this, arguments );

		if ( ! this.isSlideshow() || 1 >= this.getSlidesCount() ) {
			return;
		}

		var elementSettings = this.getElementSettings();

		var thumbsSliderOptions = {
			slidesPerView: this.getSlidesPerView(),
			initialSlide: this.getInitialSlide(),
			centeredSlides: true,
			slideToClickedSlide: true,
			spaceBetween: elementSettings.space_between.size,
			loopedSlides: this.getSlidesCount(),
			loop: true,
			onSlideChangeEnd: function( swiper ) {
				swiper.fixLoop();
			},
			breakpoints: {
				1024: {
					slidesPerView: this.getTabletSlidesPerView(),
					spaceBetween: elementSettings.space_between_tablet.size
				},
				767: {
					slidesPerView: this.getMobileSlidesPerView(),
					spaceBetween: elementSettings.space_between_mobile.size
				}
			}
		};

		this.swipers.main.params.control = this.swipers.thumbs = new Swiper( this.elements.$thumbsSwiper, thumbsSliderOptions );

		this.swipers.thumbs.params.control = this.swipers.main;
	},

	onElementChange: function( propertyName ) {
		if ( ! this.isSlideshow() ) {
			Base.prototype.onElementChange.apply( this, arguments );

			return;
		}

		if ( 0 === propertyName.indexOf( 'width' ) ) {
			this.swipers.main.onResize();
			this.swipers.thumbs.onResize();
		}

		if ( 0 === propertyName.indexOf( 'space_between' ) ) {
			this.updateSpaceBetween( this.swipers.thumbs, propertyName );
		}
	}
} );

module.exports = function( $scope ) {
	new MediaCarousel( { $element: $scope } );
};

},{"./base":5}],7:[function(require,module,exports){
var Base = require( './base' ),
	TestimonialCarousel;

TestimonialCarousel = Base.extend( {

	getDefaultSettings: function() {
		var defaultSettings = Base.prototype.getDefaultSettings.apply( this, arguments );

		defaultSettings.slidesPerView = {
			desktop: 1,
			tablet: 1,
			mobile: 1
		};

		return defaultSettings;
	},

    getSwiperOptions: function() {
        var options = Base.prototype.getSwiperOptions.apply( this, arguments );

        options.effect = 'slide';

        return options;
    }
} );

module.exports = function( $scope ) {
	new TestimonialCarousel( { $element: $scope } );
};

},{"./base":5}],8:[function(require,module,exports){
module.exports = function() {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/countdown.default', require( './handlers/countdown' ) );
};

},{"./handlers/countdown":9}],9:[function(require,module,exports){
var Countdown = function( $countdown, endTime, $ ) {
	var timeInterval,
		elements = {
			$daysSpan: $countdown.find( '.elementor-countdown-days' ),
			$hoursSpan: $countdown.find( '.elementor-countdown-hours' ),
			$minutesSpan: $countdown.find( '.elementor-countdown-minutes' ),
			$secondsSpan: $countdown.find( '.elementor-countdown-seconds' )
		};

	var updateClock = function() {
		var timeRemaining = Countdown.getTimeRemaining( endTime );

		$.each( timeRemaining.parts, function( timePart ) {
			var $element = elements[ '$' + timePart + 'Span' ],
				partValue = this.toString();

			if ( 1 === partValue.length ) {
				partValue = 0 + partValue;
			}

			if ( $element.length ) {
				$element.text( partValue );
			}
		} );

		if ( timeRemaining.total <= 0 ) {
			clearInterval( timeInterval );
		}
	};

	var initializeClock = function() {
		updateClock();

		timeInterval = setInterval( updateClock, 1000 );
	};

	initializeClock();
};

Countdown.getTimeRemaining = function( endTime ) {
	var timeRemaining = endTime - new Date(),
		seconds = Math.floor( ( timeRemaining / 1000 ) % 60 ),
		minutes = Math.floor( ( timeRemaining / 1000 / 60 ) % 60 ),
		hours = Math.floor( ( timeRemaining / ( 1000 * 60 * 60 ) ) % 24 ),
		days = Math.floor( timeRemaining / ( 1000 * 60 * 60 * 24 ) );

	if ( days < 0 || hours < 0 || minutes < 0 ) {
		seconds = minutes = hours = days = 0;
	}

	return {
		total: timeRemaining,
		parts: {
			days: days,
			hours: hours,
			minutes: minutes,
			seconds: seconds
		}
	};
};

module.exports = function( $scope, $ ) {
	var $element = $scope.find( '.elementor-countdown-wrapper' ),
		date = new Date( $element.data( 'date' ) * 1000 );

	new Countdown( $element, date, $ );
};

},{}],10:[function(require,module,exports){
module.exports = function() {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/form.default', require( './handlers/form' ) );
	elementorFrontend.hooks.addAction( 'frontend/element_ready/subscribe.default', require( './handlers/form' ) );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/form.default', require( './handlers/recaptcha' ) );
};

},{"./handlers/form":13,"./handlers/recaptcha":14}],11:[function(require,module,exports){
module.exports = elementorFrontend.Module.extend( {
	getDefaultSettings: function() {
		return {
			selectors: {
				form: '.elementor-form'
			}
		};
	},

	getDefaultElements: function() {
		var selectors = this.getSettings( 'selectors' ),
			elements = {};

		elements.$form = this.$element.find( selectors.form );

		return elements;
	},

	bindEvents: function() {
		this.elements.$form.on( 'form_destruct', this.handleSubmit );
	},

	handleSubmit: function( event, response ) {
		if ( 'undefined' !== typeof response.data.redirect_url ) {
			location.href = response.data.redirect_url;
		}
	}
} );

},{}],12:[function(require,module,exports){
module.exports = elementorFrontend.Module.extend( {

	getDefaultSettings: function() {
		return {
			selectors: {
				form: '.elementor-form',
				submitButton: '[type="submit"]'
			},
			action: 'wbcom_elementor_addons_forms_send_form',
			ajaxUrl: elementorProFrontend.config.ajaxurl
		};
	},

	getDefaultElements: function() {
		var selectors = this.getSettings( 'selectors' ),
			elements = {};

		elements.$form = this.$element.find( selectors.form );
		elements.$submitButton = elements.$form.find( selectors.submitButton );

		return elements;
	},

	bindEvents: function() {
		this.elements.$form.on( 'submit', this.handleSubmit );
	},

	beforeSend: function() {
		var $form = this.elements.$form;

		$form
			.animate( {
				opacity: '0.45'
			}, 500 )
			.addClass( 'elementor-form-waiting' );

		$form
			.find( '.elementor-message' )
			.remove();

		$form
			.find( '.elementor-error' )
			.removeClass( 'elementor-error' );

		$form
			.find( 'div.elementor-field-group' )
			.removeClass( 'error' )
			.find( 'span.elementor-form-help-inline' )
			.remove()
			.end()
			.find( ':input' ).attr( 'aria-invalid', 'false' );

		this.elements.$submitButton
			.attr( 'disabled', 'disabled' )
			.find( '> span' )
			.prepend( '<span class="elementor-button-text elementor-form-spinner"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>' );

	},

	getFormData: function() {
		var formData = new FormData( this.elements.$form[ 0 ] );
		formData.append( 'action', this.getSettings( 'action' ) );
		formData.append( 'referrer', location.toString() );

		return formData;
	},

	onSuccess: function( response, status ) {
		var $form = this.elements.$form;

		this.elements.$submitButton
				.removeAttr( 'disabled' )
				.find( '.elementor-form-spinner' )
				.remove();

		$form
			.animate( {
				opacity: '1'
			}, 100 )
			.removeClass( 'elementor-form-waiting' );

			if ( ! response.success ) {
				if ( response.data.errors ) {
					jQuery.each( response.data.errors, function( key, title ) {
						$form
							.find( '#form-field-' + key )
							.parent()
							.addClass( 'elementor-error' )
							.append( '<span class="elementor-message elementor-message-danger elementor-help-inline elementor-form-help-inline" role="alert">' + title + '</span>' )
							.find( ':input' ).attr( 'aria-invalid', 'true' );
					} );

					$form.trigger( 'error' );
				}
				$form.append( '<div class="elementor-message elementor-message-danger" role="alert">' + response.data.message + '</div>' );
			} else {
				$form.trigger( 'submit_success', response.data );

				// For actions like redirect page
				$form.trigger( 'form_destruct', response.data );

				$form.trigger( 'reset' );

				if ( 'undefined' !== typeof response.data.message && '' !== response.data.message ) {
					$form.append( '<div class="elementor-message elementor-message-success" role="alert">' + response.data.message + '</div>' );
				}
			}
	},

	onError: function( xhr, desc ) {
		var $form = this.elements.$form;

		$form.append( '<div class="elementor-message elementor-message-danger" role="alert">' + desc + '</div>' );

		this.elements.$submitButton
			.html( this.elements.$submitButton.text() )
			.removeAttr( 'disabled' );

		$form
			.animate( {
				opacity: '1'
			}, 100 )
			.removeClass( 'elementor-form-waiting' );

		$form.trigger( 'error' );
	},

	handleSubmit: function( event ) {
		var self = this,
			$form = this.elements.$form;

		event.preventDefault();

		if ( $form.hasClass( 'elementor-form-waiting' ) ) {
			return false;
		}

		this.beforeSend();

		jQuery.ajax( {
			url: self.getSettings( 'ajaxUrl' ),
			type: 'POST',
			dataType: 'json',
			data: self.getFormData(),
			processData: false,
			contentType: false,
			success: self.onSuccess,
			error: self.onError
		} );
	}
} );

},{}],13:[function(require,module,exports){
var FormSender = require( './form-sender' ),
	Form = FormSender.extend();

var RedirectAction = require( './form-redirect' );

module.exports = function( $scope ) {
	new Form( { $element: $scope } );
	new RedirectAction( { $element: $scope } );
};

},{"./form-redirect":11,"./form-sender":12}],14:[function(require,module,exports){
module.exports = function( $scope, $ ) {
	var $element = $scope.find( '.elementor-g-recaptcha:last' );

	if ( ! $element.length ) {
		return;
	}

	var addRecaptcha = function( $element ) {
		var widgetId = grecaptcha.render( $element[0], $element.data() ),
			$form = $element.parents( 'form' );

		$element.data( 'widgetId', widgetId );

		$form.on( 'reset error', function() {
			grecaptcha.reset( $element.data( 'widgetId' ) );
		} );
	};

	var onRecaptchaApiReady = function( callback ) {
		if ( window.grecaptcha ) {
			callback();
		} else {
			// If not ready check again by timeout..
			setTimeout( function() {
				onRecaptchaApiReady( callback );
			}, 350 );
		}
	};

	onRecaptchaApiReady( function() {
		addRecaptcha( $element );
	} );
};

},{}],15:[function(require,module,exports){
module.exports = function() {
	if ( jQuery.fn.smartmenus ) {
		// Override the default stupid detection
		jQuery.SmartMenus.prototype.isCSSOn = function() {
			return true;
		};

		if ( elementorFrontend.config.is_rtl  ) {
			jQuery.fn.smartmenus.defaults.rightToLeftSubMenus = true;
		}
	}

	elementorFrontend.hooks.addAction( 'frontend/element_ready/nav-menu.default', require( './handlers/nav-menu' ) );
};

},{"./handlers/nav-menu":16}],16:[function(require,module,exports){
var MenuHandler = elementorFrontend.Module.extend( {

	stretchElement: null,

	getDefaultSettings: function() {
		return {
			selectors: {
				menu: '.elementor-nav-menu',
				dropdownMenu: '.elementor-nav-menu__container.elementor-nav-menu--dropdown',
				menuToggle: '.elementor-menu-toggle'
			}
		};
	},

	getDefaultElements: function() {
		var selectors = this.getSettings( 'selectors' ),
			elements = {};

		elements.$menu = this.$element.find( selectors.menu );

		elements.$dropdownMenu = this.$element.find( selectors.dropdownMenu );

		elements.$menuToggle = this.$element.find( selectors.menuToggle );

		return elements;
	},

	bindEvents: function() {
		var self = this;

		self.elements.$menuToggle.on( 'click', function() {
			self.elements.$menuToggle.toggleClass( 'elementor-active' );

			self.toggleMenu( self.elements.$menuToggle.hasClass( 'elementor-active' ) );
		} );

		elementorFrontend.addListenerOnce( self.$element.data( 'model-cid' ), 'resize', self.stretchMenu );
	},

	initStretchElement: function() {
		this.stretchElement = new elementorFrontend.modules.StretchElement( { element: this.elements.$dropdownMenu } );
	},

	toggleMenu: function( show ) {
		var $dropdownMenu = this.elements.$dropdownMenu;

		if ( show ) {
			$dropdownMenu.hide().slideDown( 250, function() {
				$dropdownMenu.css( 'display', '' );
			} );

			if ( this.getElementSettings( 'full_width' ) ) {
				this.stretchElement.stretch();
			}
		} else {
			$dropdownMenu.show().slideUp( 250, function() {
				$dropdownMenu.css( 'display', '' );
			} );
		}
	},

	stretchMenu: function() {
		if ( this.getElementSettings( 'full_width' ) ) {
			this.stretchElement.stretch();

			this.elements.$dropdownMenu.css( 'top', this.elements.$menuToggle.outerHeight() );
		} else {
			this.stretchElement.reset();
		}
	},

	onInit: function() {
		elementorFrontend.Module.prototype.onInit.apply( this, arguments );

		this.elements.$menu.smartmenus( {
			subIndicatorsText: '',
			subIndicatorsPos: 'append'
		} );

		this.initStretchElement();

		this.stretchMenu();
	},

	onElementChange: function( propertyName ) {
		if ( 'full_width' === propertyName ) {
			this.stretchMenu();
		}
	}
} );

module.exports = function( $scope ) {
	new MenuHandler( { $element: $scope } );
};

},{}],17:[function(require,module,exports){
module.exports = function() {
	var PostsModule = require( './handlers/posts' ),
		CardsModule = require( './handlers/cards' ),
		PortfolioModule = require( './handlers/portfolio' );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/posts.classic', function( $scope ) {
		new PostsModule( { $element: $scope } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/posts.cards', function( $scope ) {
		new CardsModule( { $element: $scope } );
	} );

	elementorFrontend.hooks.addAction( 'frontend/element_ready/portfolio.default', function( $scope ) {
		if ( ! $scope.find( '.elementor-portfolio' ).length ) {
			return;
		}

		new PortfolioModule( { $element: $scope } );
	} );
};

},{"./handlers/cards":18,"./handlers/portfolio":19,"./handlers/posts":20}],18:[function(require,module,exports){
var PostsHandler = require( './posts' );

module.exports = PostsHandler.extend( {
	getSkinPrefix: function() {
		return 'cards_';
	}
} );

},{"./posts":20}],19:[function(require,module,exports){
var PostsHandler = require( './posts' );

module.exports = PostsHandler.extend( {
	getElementName: function() {
		return 'portfolio';
	},

	getSkinPrefix: function() {
		return '';
	},

	getDefaultSettings: function() {
		var settings = PostsHandler.prototype.getDefaultSettings.apply( this, arguments );

		settings.transitionDuration = 450;

		jQuery.extend( settings.classes, {
			active: 'elementor-active',
			item: 'elementor-portfolio-item',
			ghostItem: 'elementor-portfolio-ghost-item'
		} );

		return settings;
	},

	getDefaultElements: function() {
		var elements = PostsHandler.prototype.getDefaultElements.apply( this, arguments );

		elements.$filterButtons = this.$element.find( '.elementor-portfolio__filter' );

		return elements;
	},

	getOffset: function( itemIndex, itemWidth, itemHeight ) {
		var settings = this.getSettings(),
			itemGap = this.elements.$postsContainer.width() / settings.colsCount - itemWidth;

		itemGap += itemGap / ( settings.colsCount - 1 );

		return {
			start: ( itemWidth + itemGap ) * ( itemIndex % settings.colsCount ),
			top: ( itemHeight + itemGap ) * Math.floor( itemIndex / settings.colsCount )
		};
	},

	getClosureMethodsNames: function() {
		var baseClosureMethods = PostsHandler.prototype.getClosureMethodsNames.apply( this, arguments );

		return baseClosureMethods.concat( [ 'onFilterButtonClick' ] );
	},

	filterItems: function( term ) {
		var $posts = this.elements.$posts,
			activeClass = this.getSettings( 'classes.active' ),
			termSelector = '.elementor-filter-' + term;

		if ( '__all' === term ) {
			$posts.addClass( activeClass );

			return;
		}

		$posts.not( termSelector ).removeClass( activeClass );

		$posts.filter( termSelector ).addClass( activeClass );
	},

	removeExtraGhostItems: function() {
		var settings = this.getSettings(),
			$shownItems = this.elements.$posts.filter( ':visible' ),
			emptyColumns = ( settings.colsCount - $shownItems.length % settings.colsCount ) % settings.colsCount,
			$ghostItems = this.elements.$postsContainer.find( '.' + settings.classes.ghostItem );

		$ghostItems.slice( emptyColumns ).remove();
	},

	handleEmptyColumns: function() {
		this.removeExtraGhostItems();

		var settings = this.getSettings(),
			$shownItems = this.elements.$posts.filter( ':visible' ),
			$ghostItems = this.elements.$postsContainer.find( '.' + settings.classes.ghostItem ),
			emptyColumns = ( settings.colsCount - ( ( $shownItems.length + $ghostItems.length ) % settings.colsCount ) ) % settings.colsCount;

		for ( var i = 0; i < emptyColumns; i++ ) {
			this.elements.$postsContainer.append( jQuery( '<div>', { 'class': settings.classes.item + ' ' + settings.classes.ghostItem } ) );
		}
	},

	showItems: function( $activeHiddenItems ) {
		$activeHiddenItems.show();

		setTimeout( function() {
			$activeHiddenItems.css( {
				opacity: 1
			} );
		} );
	},

	hideItems: function( $inactiveShownItems ) {
		$inactiveShownItems.hide();
	},

	arrangeGrid: function() {
		var $ = jQuery,
			self = this,
			settings = self.getSettings(),
			$activeItems = this.elements.$posts.filter( '.' + settings.classes.active ),
			$inactiveItems = this.elements.$posts.not( '.' + settings.classes.active ),
			$shownItems = this.elements.$posts.filter( ':visible' ),
			$activeOrShownItems = $activeItems.add( $shownItems ),
			$activeShownItems = $activeItems.filter( ':visible' ),
			$activeHiddenItems = $activeItems.filter( ':hidden' ),
			$inactiveShownItems = $inactiveItems.filter( ':visible' ),
			itemWidth = $shownItems.outerWidth(),
			itemHeight = $shownItems.outerHeight();

		this.elements.$posts.css( 'transition-duration', settings.transitionDuration + 'ms' );

		self.showItems( $activeHiddenItems );

		if ( elementorFrontend.isEditMode() ) {
			self.fitImages();
		}

		self.handleEmptyColumns();

		if ( self.isMasonryEnabled() ) {
			self.hideItems( $inactiveShownItems );

			self.showItems( $activeHiddenItems );

			self.handleEmptyColumns();

			self.runMasonry();

			return;
		}

		$inactiveShownItems.css( {
			opacity: 0,
			transform: 'scale3d(0.2, 0.2, 1)'
		} );

		$activeShownItems.each( function() {
			var $item = $( this ),
				currentOffset = self.getOffset( $activeOrShownItems.index( $item ), itemWidth, itemHeight ),
				requiredOffset = self.getOffset( $shownItems.index( $item ), itemWidth, itemHeight );

			if ( currentOffset.start === requiredOffset.start && currentOffset.top === requiredOffset.top ) {
				return;
			}

			requiredOffset.start -= currentOffset.start;

			requiredOffset.top -= currentOffset.top;

			if ( elementorFrontend.config.is_rtl ) {
				requiredOffset.start *= -1;
			}

			$item.css( {
				transitionDuration: '',
				transform: 'translate3d(' + requiredOffset.start + 'px, ' + requiredOffset.top + 'px, 0)'
			} );
		} );

		setTimeout( function() {
			$activeItems.each( function() {
				var $item = $( this ),
					currentOffset = self.getOffset( $activeOrShownItems.index( $item ), itemWidth, itemHeight ),
					requiredOffset = self.getOffset( $activeItems.index( $item ), itemWidth, itemHeight );

				$item.css( {
					transitionDuration: settings.transitionDuration + 'ms'
				} );

				requiredOffset.start -= currentOffset.start;

				requiredOffset.top -= currentOffset.top;

				if ( elementorFrontend.config.is_rtl ) {
					requiredOffset.start *= -1;
				}

				setTimeout( function() {
					$item.css( 'transform', 'translate3d(' + requiredOffset.start + 'px, ' + requiredOffset.top + 'px, 0)' );
				} );
			} );
		} );

		setTimeout( function() {
			self.hideItems( $inactiveShownItems );

			$activeItems.css( {
				transitionDuration: '',
				transform: 'translate3d(0px, 0px, 0px)'
			} );

			self.handleEmptyColumns();
		}, settings.transitionDuration );
	},

    activeFilterButton: function( filter ) {
        var activeClass = this.getSettings( 'classes.active' ),
            $filterButtons = this.elements.$filterButtons,
            $button = $filterButtons.filter( '[data-filter="' + filter + '"]' );

        $filterButtons.removeClass( activeClass );

        $button.addClass( activeClass );
    },

	setFilter: function( filter ) {
		this.activeFilterButton( filter );

		this.filterItems( filter );

		this.arrangeGrid();
	},

	refreshGrid: function() {
		this.setColsCountSettings();

		this.arrangeGrid();
	},

	bindEvents: function() {
		PostsHandler.prototype.bindEvents.apply( this, arguments );

		this.elements.$filterButtons.on( 'click', this.onFilterButtonClick );
	},

	isMasonryEnabled: function() {
		return !! this.getElementSettings( 'masonry' );
	},

	run: function() {
		PostsHandler.prototype.run.apply( this, arguments );

		this.setColsCountSettings();

		this.setFilter( '__all' );

		this.handleEmptyColumns();
	},

	onFilterButtonClick: function( event ) {
		this.setFilter( jQuery( event.currentTarget ).data( 'filter' ) );
	},

	onWindowResize: function() {
		PostsHandler.prototype.onWindowResize.apply( this, arguments );

		this.refreshGrid();
	},

	onElementChange: function( propertyName ) {
		PostsHandler.prototype.onElementChange.apply( this, arguments );

		if ( 'classic_item_ratio' === propertyName ) {
			this.refreshGrid();
		}
	}
} );

},{"./posts":20}],20:[function(require,module,exports){
module.exports = elementorFrontend.Module.extend( {
	getElementName: function() {
		return 'posts';
	},

	getSkinPrefix: function() {
		return 'classic_';
	},

	bindEvents: function() {
		var cid = this.getModelCID();

		elementorFrontend.addListenerOnce( cid, 'resize', this.onWindowResize );
	},

	getClosureMethodsNames: function() {
		return elementorFrontend.Module.prototype.getClosureMethodsNames.apply( this, arguments ).concat( [ 'fitImages', 'onWindowResize', 'runMasonry' ] );
	},

	getDefaultSettings: function() {
		return {
			classes: {
				fitHeight: 'elementor-fit-height',
				hasItemRatio: 'elementor-has-item-ratio'
			},
			selectors: {
				postsContainer: '.elementor-posts-container',
				post: '.elementor-post',
				postThumbnail: '.elementor-post__thumbnail',
				postThumbnailImage: '.elementor-post__thumbnail img'
			}
		};
	},

	getDefaultElements: function() {
		var selectors = this.getSettings( 'selectors' );

		return {
			$postsContainer: this.$element.find( selectors.postsContainer ),
			$posts: this.$element.find( selectors.post )
		};
	},

	fitImage: function( $post ) {
		var settings = this.getSettings(),
			$imageParent = $post.find( settings.selectors.postThumbnail ),
			$image = $imageParent.find( 'img' ),
			image = $image[0];

		if ( ! image ) {
			return;
		}

		var imageParentRatio = $imageParent.outerHeight() / $imageParent.outerWidth(),
			imageRatio = image.naturalHeight / image.naturalWidth;

		$imageParent.toggleClass( settings.classes.fitHeight, imageRatio < imageParentRatio );
	},

	fitImages: function() {
		var $ = jQuery,
			self = this,
			itemRatio = getComputedStyle( this.$element[0], ':after' ).content,
			settings = this.getSettings();

		this.elements.$postsContainer.toggleClass( settings.classes.hasItemRatio, !! itemRatio.match( /\d/ ) );

		if ( self.isMasonryEnabled() ) {
			return;
		}

		this.elements.$posts.each( function() {
			var $post = $( this ),
				$image = $post.find( settings.selectors.postThumbnailImage );

			self.fitImage( $post );

			$image.on( 'load', function() {
				self.fitImage( $post );
			} );
		} );
	},

	setColsCountSettings: function() {
		var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
			settings = this.getElementSettings(),
			skinPrefix = this.getSkinPrefix(),
			colsCount;

		switch ( currentDeviceMode ) {
			case 'mobile':
				colsCount = settings[ skinPrefix + 'columns_mobile' ];
				break;
			case 'tablet':
				colsCount = settings[ skinPrefix + 'columns_tablet' ];
				break;
			default:
				colsCount = settings[ skinPrefix + 'columns' ];
		}

		this.setSettings( 'colsCount', colsCount );
	},

	isMasonryEnabled: function() {
		return !! this.getElementSettings( this.getSkinPrefix() + 'masonry' );
	},

	initMasonry: function() {
		this.elements.$posts.imagesLoaded().always( this.runMasonry );
	},

	runMasonry: function() {
		var $ = jQuery,
			elements = this.elements;

        elements.$posts.css( {
            marginTop: '',
            transitionDuration: ''
        } );

		this.setColsCountSettings();

		var colsCount = this.getSettings( 'colsCount' ),
			hasMasonry = this.isMasonryEnabled() && colsCount >= 2;

		elements.$postsContainer.toggleClass( 'elementor-posts-masonry', hasMasonry );

		if ( ! hasMasonry ) {
			elements.$postsContainer.height( '' );

			return;
		}

		var heights = [],
			distanceFromTop = elements.$postsContainer.position().top,
			$shownPosts = elements.$posts.filter( ':visible' );

		$shownPosts.each( function( index ) {
			var row = Math.floor( index / colsCount ),
				indexAtRow = index % colsCount,
				$post = $( this ),
				itemPosition = $post.position(),
				itemHeight = $post.outerHeight();

			if ( row ) {
				$post.css( 'margin-top', '-' + ( itemPosition.top - distanceFromTop - heights[ indexAtRow ] ) + 'px' );

				heights[ indexAtRow ] += itemHeight;
			} else {
				heights.push( itemHeight );
			}
		} );

		elements.$postsContainer.height( Math.max.apply( Math, heights ) );
	},

	run: function() {
		// For slow browsers
		setTimeout( this.fitImages, 0 );

		this.initMasonry();
	},

	onInit: function() {
		elementorFrontend.Module.prototype.onInit.apply( this, arguments );

		this.bindEvents();

		this.run();
	},

	onWindowResize: function() {
		this.fitImages();

		this.runMasonry();
	},

	onElementChange: function() {
		this.fitImages();

		setTimeout( this.runMasonry );
	}
} );

},{}],21:[function(require,module,exports){
module.exports = function() {
	if ( ! elementorFrontend.isEditMode() ) {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/share-buttons.default', require( './handlers/share-buttons' ) );
	}
};

},{"./handlers/share-buttons":22}],22:[function(require,module,exports){
var HandlerModule = elementorFrontend.Module,
	ShareButtonsHandler;

ShareButtonsHandler = HandlerModule.extend( {
	onInit: function() {
		HandlerModule.prototype.onInit.apply( this, arguments );

		var elementSettings = this.getElementSettings(),
			classes = this.getSettings( 'classes' ),
			isCustomURL = elementSettings.share_url && elementSettings.share_url.url,
			shareLinkSettings = {
				classPrefix: classes.shareLinkPrefix
			};

		if ( isCustomURL ) {
			shareLinkSettings.url = elementSettings.share_url.url;
		} else {
			shareLinkSettings.url = location.href;
			shareLinkSettings.title = elementorFrontend.config.post.title;
			shareLinkSettings.text = elementorFrontend.config.post.excerpt;
		}

		this.elements.$shareButton.shareLink( shareLinkSettings );

		var shareCountProviders = jQuery.map( elementorProFrontend.config.shareButtonsNetworks, function( network, networkName ) {
			return network.has_counter ? networkName : null;
		} );

		this.elements.$shareCounter.shareCounter( {
			url:  isCustomURL ? elementSettings.share_url.url : location.href,
			providers: shareCountProviders,
			classPrefix: classes.shareCounterPrefix,
			formatCount: true
		} );
	},
	getDefaultSettings: function() {
		return {
			selectors: {
				shareButton: '.elementor-share-btn',
				shareCounter: '.elementor-share-btn__counter'
			},
			classes: {
				shareLinkPrefix: 'elementor-share-btn_',
				shareCounterPrefix: 'elementor-share-btn__counter_'
			}
		};
	},
	getDefaultElements: function() {
		var selectors = this.getSettings( 'selectors' );

		return {
			$shareButton: this.$element.find( selectors.shareButton ),
			$shareCounter: this.$element.find( selectors.shareCounter )
		};
	}
} );

module.exports = function( $scope ) {
	new ShareButtonsHandler( { $element: $scope } );
};

},{}],23:[function(require,module,exports){
module.exports = function() {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/slides.default', require( './handlers/slides' ) );
};

},{"./handlers/slides":24}],24:[function(require,module,exports){
module.exports = function( $scope, $ ) {
	var $slider = $scope.find( '.elementor-slides' );

	if ( ! $slider.length ) {
		return;
	}

	$slider.slick( $slider.data( 'slider_options' ) );

	// Add and remove animation classes to slide content, on slider change
	if ( '' === $slider.data( 'animation' ) ) {
		return;
	}

	$slider.on( {
		beforeChange: function() {
			var $sliderContent = $slider.find( '.elementor-slide-content' );

			$sliderContent.removeClass( 'animated ' + $slider.data( 'animation' ) ).hide();
		},

		afterChange: function( event, slick, currentSlide ) {
			var $currentSlide = $( slick.$slides.get( currentSlide ) ).find( '.elementor-slide-content' ),
				animationClass = $slider.data( 'animation' );

			$currentSlide
				.show()
				.addClass( 'animated ' + animationClass );
		}
	} );
};

},{}],25:[function(require,module,exports){
var facebookHandler = require( './handlers/facebook-sdk' );

module.exports = function() {
	elementorFrontend.hooks.addAction( 'frontend/element_ready/facebook-button.default', facebookHandler );
	elementorFrontend.hooks.addAction( 'frontend/element_ready/facebook-comments.default', facebookHandler );
	elementorFrontend.hooks.addAction( 'frontend/element_ready/facebook-embed.default', facebookHandler );
	elementorFrontend.hooks.addAction( 'frontend/element_ready/facebook-page.default', facebookHandler );
};

},{"./handlers/facebook-sdk":26}],26:[function(require,module,exports){
var config = WbcomElementorAddonsFrontendConfig.facebook_sdk,
	loadSDK = function() {
	// Don't load in parallel
	if ( config.isLoading || config.isLoaded ) {
		return;
	}

	config.isLoading = true;

	jQuery.ajax( {
		url: 'https://connect.facebook.net/' + config.lang + '/sdk.js',
		dataType: 'script',
		cache: true,
		success: function() {
			FB.init( {
				appId: config.app_id,
				version: 'v2.10',
				xfbml: false
			} );
			config.isLoaded = true;
			config.isLoading = false;
			jQuery( document ).trigger( 'fb:sdk:loaded' );
		}
	} );
};

module.exports = function( $scope, $ ) {
	loadSDK();
	// On FB SDK is loaded, parse current element
	var parse = function() {
		$scope.find( '.elementor-widget-container div' ).attr( 'data-width', $scope.width() + 'px' );
		FB.XFBML.parse( $scope[0] );
	};

	if ( config.isLoaded ) {
		parse();
	} else {
		jQuery( document ).on( 'fb:sdk:loaded', parse );
	}
};

},{}]},{},[1])
//# sourceMappingURL=frontend.js.map
