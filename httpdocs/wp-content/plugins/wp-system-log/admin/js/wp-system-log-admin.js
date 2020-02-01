jQuery( document ).ready(
	function( $ ) {
		'use strict';

		// Turn Off The Debug Mode
		$( document ).on(
			'click', '#wpsl-turn-debug-off', function(){
				var btn_txt = $( this ).html();
				$( this ).html( btn_txt + '  <i class="fa fa-refresh fa-spin"></i>' );
				var data = {
					'action' : 'wpsl_turn_debug_off',
				}
				$.ajax(
					{
						dataType: "JSON",
						url: wpsl_admin_js_object.ajaxurl,
						type: 'POST',
						data: data,
						success: function( response ) {
							$( '.wpsl-wp-debug-mode' ).html( response['data']['html'] );
							console.log( response['data']['msg'] );
						},
					}
				);
			}
		);

		// Turn On The Debug Mode
		$( document ).on(
			'click', '#wpsl-turn-debug-on', function(){
				var btn_txt = $( this ).html();
				$( this ).html( btn_txt + '  <i class="fa fa-refresh fa-spin"></i>' );
				var data = {
					'action' : 'wpsl_turn_debug_on',
				}
				$.ajax(
					{
						dataType: "JSON",
						url: wpsl_admin_js_object.ajaxurl,
						type: 'POST',
						data: data,
						success: function( response ) {
							$( '.wpsl-wp-debug-mode' ).html( response['data']['html'] );
							console.log( response['data']['msg'] );
						},
					}
				);
			}
		);

		// Support Tab
		var acc = document.getElementsByClassName( "wpsl-accordion" );
		var i;
		for (i = 0; i < acc.length; i++) {
			acc[i].onclick = function() {
				this.classList.toggle( "active" );
				var panel = this.nextElementSibling;
				if (panel.style.maxHeight) {
					panel.style.maxHeight = null;
				} else {
					panel.style.maxHeight = panel.scrollHeight + "px";
				}
			}
		}

		$( document ).on(
			'click', '.wpsl-accordion', function(){
				return false;
			}
		);
	}
);
