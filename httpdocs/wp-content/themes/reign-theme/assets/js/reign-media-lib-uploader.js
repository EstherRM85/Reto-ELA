
jQuery( document ).ready( function( $ ) {

	// Add Color Picker to all inputs that have 'reign-color-picker-field' class
    $('.reign-color-picker-field').wpColorPicker();

	var reign_mediaUploader;
	var reign_thisRef;

	$( document.body ).on( 'click', '.reign-upload-button', function( event ) {
		event.preventDefault();

		reign_thisRef = $( this );
	    // If the uploader object has already been created, reopen the dialog
	    if( reign_mediaUploader ) {
	    	reign_mediaUploader.open();
	    	return;
	    }
	    // Extend the wp.media object
	    reign_mediaUploader = wp.media.frames.file_frame = wp.media({
	    	title: 'Choose Image',
	    	button: {
	    		text: 'Choose Image'
	    	}, multiple: false });

	    // When a file is selected, grab the URL and set it as the text field's value
	    reign_mediaUploader.on( 'select', function() {
	    	var attachment = reign_mediaUploader.state().get( 'selection' ).first().toJSON();
	    	reign_thisRef.siblings( '.reign_default_cover_image_url' ).val( attachment.url );
	    	reign_thisRef.siblings( '.reign_default_cover_image').attr( 'src', attachment.url );
	    	reign_thisRef.siblings( '.reign_default_cover_image').show();
	    	reign_thisRef.siblings( '.reign-remove-file-button').show();

	    	reign_thisRef.siblings( '#avatar_default_image' ).val( attachment.url );
	    	reign_thisRef.siblings( '#avatar_default_image_id' ).val( attachment.id );

	    	reign_thisRef.siblings( '#group_default_image' ).val( attachment.url );
	    	reign_thisRef.siblings( '#group_default_image_id' ).val( attachment.id );
	    });
	    // Open the uploader dialog
	    reign_mediaUploader.open();
	});

	$( document.body ).on( 'click', '.reign-remove-file-button', function( event ) {	
		event.preventDefault();
		$( this ).siblings( '.reign_default_cover_image_url' ).val( '' );
		$( this ).siblings( '.reign_default_cover_image').attr( 'src', '' );
	    $( this ).siblings( '.reign_default_cover_image').hide();
	    $( this ).hide();

	    $( this ).siblings( '#avatar_default_image' ).val( '' );
	    $( this ).siblings( '#avatar_default_image_id' ).val( '' );

	    $( this ).siblings( '#group_default_image' ).val( '' );
	    $( this ).siblings( '#group_default_image_id' ).val( '' );

	});

	// $( '.reign-upload-button' ).click( function( event ) {
	// 	event.preventDefault();

	// 	reign_thisRef = $( this );
	//     // If the uploader object has already been created, reopen the dialog
	//     if( reign_mediaUploader ) {
	//     	reign_mediaUploader.open();
	//     	return;
	//     }
	//     // Extend the wp.media object
	//     reign_mediaUploader = wp.media.frames.file_frame = wp.media({
	//     	title: 'Choose Image',
	//     	button: {
	//     		text: 'Choose Image'
	//     	}, multiple: false });

	//     // When a file is selected, grab the URL and set it as the text field's value
	//     reign_mediaUploader.on( 'select', function() {
	//     	var attachment = reign_mediaUploader.state().get( 'selection' ).first().toJSON();
	//     	reign_thisRef.siblings( '.reign_default_cover_image_url' ).val( attachment.url );
	//     	reign_thisRef.siblings( '.reign_default_cover_image').attr( 'src', attachment.url );
	//     	reign_thisRef.siblings( '.reign_default_cover_image').show();
	//     	reign_thisRef.siblings( '.reign-remove-file-button').show();

	//     	reign_thisRef.siblings( '#avatar_default_image' ).val( attachment.url );
	//     	reign_thisRef.siblings( '#avatar_default_image_id' ).val( attachment.id );

	//     	reign_thisRef.siblings( '#group_default_image' ).val( attachment.url );
	//     	reign_thisRef.siblings( '#group_default_image_id' ).val( attachment.id );
	//     });
	//     // Open the uploader dialog
	//     reign_mediaUploader.open();
	// });

	// $( '.reign-remove-file-button' ).click( function( event ) {
	// 	event.preventDefault();
	// 	$( this ).siblings( '.reign_default_cover_image_url' ).val( '' );
	// 	$( this ).siblings( '.reign_default_cover_image').attr( 'src', '' );
	//     $( this ).siblings( '.reign_default_cover_image').hide();
	//     $( this ).hide();

	//     $( this ).siblings( '#avatar_default_image' ).val( '' );
	//     $( this ).siblings( '#avatar_default_image_id' ).val( '' );
	// });

	$( "#reign-support-section" ).accordion( {
        collapsible: true,
        heightStyle: "content"
    } );

});