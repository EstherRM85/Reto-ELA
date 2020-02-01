jQuery( document ).ready( function( $ ) {
	$( 'form.wbcom-forgot-password-form' ).hide();
	var redirect_to = '';
	$( 'form.wbcom-elementor-login-form button.elementor-button' ).click( function( event ) {
		event.preventDefault();
		var serialize_form_data = $( this ).closest( 'form.wbcom-elementor-login-form' ).serialize();
		redirect_to = $( this ).closest( 'form.wbcom-elementor-login-form' ).find( 'input:hidden[name="redirect_to"]' ).val();
		var action_to_to = $( this ).closest( 'form.wbcom-elementor-login-form' ).find( 'input:hidden[name="wbcom_action_to_to"]' ).val();
		wbcom_process_elementor_login_form( serialize_form_data, action_to_to, $( this ) );
    });
    $( 'form.wbcom-elementor-registration-form button.elementor-button' ).click( function( event ) {
		event.preventDefault();
		var serialize_form_data = $( this ).closest( 'form.wbcom-elementor-registration-form' ).serialize();
		redirect_to = $( this ).closest( 'form.wbcom-elementor-registration-form' ).find( 'input:hidden[name="redirect_to"]' ).val();
		var action_to_to = $( this ).closest( 'form.wbcom-elementor-registration-form' ).find( 'input:hidden[name="wbcom_action_to_to"]' ).val();
		wbcom_process_elementor_login_form( serialize_form_data, action_to_to, $( this ) );
    });
    function wbcom_process_elementor_login_form( serialize_form_data, action_to_to, thisRef  ) {
    	$( 'img#wb-ele-ajax-loader' ).show();
		$.ajax({
			url : wbcom_elementor_login_module_params.ajax_url,
			type : 'post',
			data : {
				action : 'wbcom_process_elementor_login_form',
				serialize_form_data : serialize_form_data,
				action_to_to : action_to_to
			},
			success : function( response ) {
				$( 'img#wb-ele-ajax-loader' ).hide();
				response = $.parseJSON( response );
				if( action_to_to == 'login' ) {
					if( response.key == 'success' ) {
						window.location.href = redirect_to;
					}
					else {
						thisRef.parent().parent().parent().find( 'div.alert span.error-message' ).html( response.data );
						thisRef.parent().parent().parent().find( 'div.alert' ).show();
					}
				}
				else if( action_to_to == 'registration' ) {
					if( response.key== 'success' ) {
						window.location.href = redirect_to;
					}
					else {
						thisRef.parent().parent().parent().find( 'div.alert span.error-message' ).html( response.data );
						thisRef.parent().parent().parent().find( 'div.alert' ).show();
					}
				}
			}
		});
	}
	// $( 'form.wbcom-elementor-login-form a.elementor-lost-password' ).click( function( event ) {
	// 	event.preventDefault();
	// 	$( this ).closest( 'form.wbcom-elementor-login-form' ).prev( 'form.wbcom-forgot-password-form' ).show();
	// 	$( this ).closest( 'form.wbcom-elementor-login-form' ).hide();
	// });
});
