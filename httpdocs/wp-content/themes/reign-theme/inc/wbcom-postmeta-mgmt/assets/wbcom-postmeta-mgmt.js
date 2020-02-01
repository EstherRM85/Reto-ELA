
jQuery( document ).ready( function() {

	jQuery( '.wbcom-metabox-select2' ).select2();
	
	// jQuery( '.wbcom-metabox-content' ).hide();
	jQuery( '.wbcom-metabox-tablinks' ).click( function( event ) {
		event.preventDefault();
		event.stopPropagation();
		jQuery( '.wbcom-metabox-tablinks' ).removeClass( 'active' );
		jQuery( this ).addClass( 'active' );
		var container_id = jQuery( this ).attr( 'data-container-id' );
		jQuery( '.wbcom-metabox-content' ).hide();
		jQuery( '.wbcom-metabox-content.'+ container_id ).show();
	} );
} );