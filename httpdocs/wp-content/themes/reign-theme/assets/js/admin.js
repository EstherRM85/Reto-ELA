//Admin js

jQuery( document ).ready( function ( $ ) {

	var mediaUploader;

	$( '.reign-upload-button' ).click( function ( e ) {
		e.preventDefault();
		// If the uploader object has already been created, reopen the dialog
		if ( mediaUploader ) {
			mediaUploader.open();
			return;
		}
		// Extend the wp.media object
		mediaUploader = wp.media.frames.file_frame = wp.media( {
			title: 'Choose Image',
			button: {
				text: 'Choose Image'
			}, multiple: false } );

		// When a file is selected, grab the URL and set it as the text field's value
		mediaUploader.on( 'select', function () {
			var attachment = mediaUploader.state().get( 'selection' ).first().toJSON();
			$( '#avatar_default_image' ).val( attachment.url );
			$( '#avatar_default_image_id' ).val( attachment.id );
			$( '#reign-media-preview' ).html('<img width="350px" style="max-width: 150px; width: 100%; height: auto;" src="'+ attachment.url +'" alt="'+ attachment.filename +'" title="'+ attachment.filename +'"><a href="#" class="reign-remove-file-button" rel="avatar_default_image">Remove Image</a>');
		} );
		// Open the uploader dialog
		mediaUploader.open();
	} );
	
	$( '#reign-media-preview' ).on( 'click', '.reign-remove-file-button', function () {
		$( '#avatar_default_image' ).val( "" );
		$( '#avatar_default_image_id' ).val( "" );
		$( '#reign-media-preview' ).html( "" );
	});

} );