/**
 * This file adds some LIVE to the Theme Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and 
 * then make any necessary changes to the page using jQuery.
 */
( function( $ ) {

	//Update site link color in real time...
	wp.customize( 'rtm_link_color', function( value ) {
		value.bind( function( newval ) {
			$( 'a' ).css( 'color', newval );
		} );
	} );

	// wp.customize( 'rtm_link_hover_color', function( value ) {
	// 	value.bind( function( newval ) {
	// 		$('a').hover( function() {
	// 			$( this ).css('color', newval );
	// 		} );
	// 	} );
	// } );

	/* Widget Title Color */
	wp.customize( 'rtm_widget_title_color', function( value ) {
		value.bind( function( newval ) {
			$( '.widget-title' ).css( 'color', newval );
		} );
	} );

	/* Page Title Color */
	wp.customize( 'rtm_page_title_color', function( value ) {
		value.bind( function( newval ) {
			$( '.entry-title' ).css( 'color', newval );
		} );
	} );

	wp.customize( 'rtm_page_title_text_transform', function( value ) {
		value.bind( function( newval ) {
			$( '.entry-title' ).css( 'text-transform', newval );
		} );
	} );

	wp.customize( 'rtm_page_title_font_family', function( value ) {
		value.bind( function( newval ) {
			// alert(newval);
			//&subset=latin
			//view-source:http:
			// var font_url = '//fonts.googleapis.com/css?family=' + newval + '%3A100%2C200%2C300%2C400%2C500%2C600%2C700%2C800%2C900%2C100i%2C200i%2C300i%2C400i%2C500i%2C600i%2C700i%2C800i%2C900i&subset=latin';
			// $.get( font_url, function( data, status ) {
		 //        alert("Data: " + data + "\nStatus: " + status);
		 //        $( '.entry-title' ).css( 'font-family', newval );
		 //    });
			$( '.entry-title' ).css( 'font-family', newval );
		} );
	} );


	wp.customize( 'rtm_page_title_font_weight', function( value ) {
		value.bind( function( newval ) {
			$( '.entry-title' ).css( 'font-weight', newval );
		} );
	} );

	wp.customize( 'rtm_page_title_font_size', function( value ) {
		value.bind( function( newval ) {
			$( '.entry-title' ).css( 'font-size', newval+'px' );
		} );
	} );

	wp.customize( 'rtm_page_title_line_height', function( value ) {
		value.bind( function( newval ) {
			$( '.entry-title' ).css( 'line-height', newval );
		} );
	} );

	

// 	$("div.myclass").hover(function() {
//   $(this).css("background-color","red")
// });

	
	// Update the site title in real time...
	// wp.customize( 'blogname', function( value ) {
	// 	value.bind( function( newval ) {
	// 		$( '#site-title a' ).html( newval );
	// 	} );
	// } );
	//Update the site description in real time...
	// wp.customize( 'blogdescription', function( value ) {
	// 	value.bind( function( newval ) {
	// 		$( '.site-description' ).html( newval );
	// 	} );
	// } );
	//Update site title color in real time...
	// wp.customize( 'header_textcolor', function( value ) {
	// 	value.bind( function( newval ) {
	// 		$('#site-title a').css('color', newval );
	// 	} );
	// } );
	//Update site background color...
	// wp.customize( 'background_color', function( value ) {
	// 	value.bind( function( newval ) {
	// 		$('body').css('background-color', newval );
	// 	} );
	// } );
	
} )( jQuery );


// As you can see from the example above, a single basic handler looks like this:
// wp.customize( 'YOUR_SETTING_ID', function( value ) {
// 	value.bind( function( newval ) {
// 		//Do stuff (newval variable contains your "new" setting data)
// 	} );
// } );
