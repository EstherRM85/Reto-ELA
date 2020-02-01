(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 jQuery(document).ready(function($){
	 	'use strict';

	 	$('ul.bplock-login-shortcode-tabs li').click(function(){
	 		var tab_id = $(this).attr('data-tab');
	 		$('ul.bplock-login-shortcode-tabs li').removeClass('current');
	 		$('.tab-content').removeClass('current');

	 		$(this).addClass('current');
	 		$("#"+tab_id).addClass('current');
	 	});
	 });
	 $('ul.bplock-login-shortcode-tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');
		$('ul.bplock-login-shortcode-tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	});

	 /**
	 * User Login
	 */
	$(document).on('click', '#bplock-login-btn', function(){
		var btn = $(this);
		$('.bplock-message').hide();
		var btn_txt = btn.html();
		var username = $('#bplock-login-username').val();
		var password = $('#bplock-login-password').val();
		if( username == '' || password == '' ){
			$('#bplock-login-details-empty').show();
		} else {
			btn.html('<i class="fa fa-refresh fa-spin"></i> Logging in...');
			var data = {
				'action'	: 'blpro_login',
				'username'	: username,
				'password'	: password
			};
			$.ajax({
				dataType: "JSON",
				url: blpro_public_obj.ajaxurl,
				type: 'POST',
				data: data,
				success: function( response ) {
					btn.html( btn_txt );
					if( response['data']['login_success'] == 'no' ) {
						$('#bplock-login-error').append( response['data']['message'] ).show();
					} else {
						$('#bplock-login-success').append( response['data']['message'] ).show();
						location.reload();
					}
				}
			});
		}
	});

	/**
	 * User Register
	 */
	$(document).on('click', '#bplock-register-btn', function(){
		var btn = $(this);
		$('.bplock-message').hide();
		var btn_txt = btn.html();
		var email = $('#bplock-register-email').val();
		var username = $('#bplock-register-username').val();
		var password = $('#bplock-register-password').val();
		if( email == '' || username == '' || password == '' ){
			$('#bplock-register-details-empty').append('Either of the detail is empty!').show();
		} else {
			var email_regex = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
			if( !email_regex.test( email ) ) {
				$('#bplock-register-details-empty').append('Invalid Email!').show();	
			} else {
				btn.html('<i class="fa fa-refresh fa-spin"></i> Registering...');
				var data = {
					'action'	: 'blpro_register',
					'email'		: email,
					'username'	: username,
					'password'	: password
				};
				$.ajax({
					dataType: "JSON",
					url: blpro_public_obj.ajaxurl,
					type: 'POST',
					data: data,
					success: function( response ) {
						btn.html( btn_txt );
						if( response['data']['register_success'] == 'no' ) {
							$('#bplock-register-error').append( response['data']['message'] ).show();
						} else {
							$('#bplock-register-success').append( response['data']['message'] ).show();
							location.reload();
						}
					}
				});
			}
		}
	});
	$(document).on('click', '#bplock-user-register', function(){
		$('#bplock-register-tab').click();
	});
	$(document).on('click', '#bplock-user-login', function(){
		$('#bplock-login-tab').click();
	});

	// $(document).ajaxSend(function(e, xhr, opt){
	// 	$(".join-group").hide();
	// });

	//code to reload page after join_group action.

	// jQuery( document ).ajaxComplete(function( event, xhr, settings ) {
	// 	var locations = [];
	// 	var formdata = deParams( settings.data );
	// 	var action   = formdata['action'];
	// 	if( 'groups_join_group' == action ) {
	// 		window.location.reload();
	// 	}
	// } );
	// function deParams(str) {
	// 	return (str || document.location.search).replace(/(^\?)/,'').split("&").map(function(n){return n = n.split("="),this[n[0]] = n[1],this}.bind({}))[0];
	// }
})( jQuery );
