
/*
* Plugin Installer Manager Code
*/
jQuery( document ).ready( function( $ ) {

	_check_all_required_plugin_installed();

	$( 'button.plugin-action-button' ).click( function( event ) {
		event.preventDefault();
		var thisRef = $( this );

		if( thisRef.hasClass( 'already-active' ) ) {
			return;
		}

		_show_plugin_installer_loader();
		$.ajax({
			url : wbcom_theme_demo_installer_params.ajax_url,
			type : 'post',
			dataType : 'json',
			data : {
				action : 'wbcom_manage_plugin_installation',
				plugin_action : thisRef.siblings( 'input.plugin-action').val(),
				plugin_slug : thisRef.siblings( 'input.plugin-slug').val()
			},
			success : function( response ) {
				_hide_plugin_installer_loader();
				if( response.success ) {
					thisRef.siblings( 'p.plugin-status').html( 'Active' );
					thisRef.siblings( 'p.plugin-status').addClass( 'already-active' );
					thisRef.html( 'Already Installed & Activated' );
					thisRef.attr( 'class', 'plugin-action-button button already-active' );
					var temp_counter = parseInt( $( 'input#num_of_req_plugins_installed').val() );
					temp_counter++;
					$( 'input#num_of_req_plugins_installed').val( temp_counter );
					_check_all_required_plugin_installed();
				}
				else {
					alert( 'There was a problem performing the action.' );
				}
			},
			'error' : function( response ) {
				_hide_plugin_installer_loader();
				alert( 'There was a problem performing the action.' );
			}
		});
	});

	function _check_all_required_plugin_installed() {
		if( ( parseInt( $( 'input#required_plugins_to_activate').val() ) - parseInt( $( 'input#num_of_req_plugins_installed').val() ) == 0 ) ) {
			$( 'div.goto-install-demo-step').show();
		}
		else {
			$( 'div.goto-install-demo-step').hide();
		}
	}

	function _show_plugin_installer_loader() {
		jQuery( 'body' ).addClass( 'demo_listing_loading' );
	}

	function _hide_plugin_installer_loader() {
		jQuery( 'body' ).removeClass( 'demo_listing_loading' );
	}

});


/*
* Demo Importer Manager Code
*/
jQuery( document ).ready( function( $ ) {

	var wbcom_theme_demo_data = '';
    var thisRef = '';

    var wbcom_tdd_database_tables_count = '';
    var wbcom_tdd_database_tables_done = 0;

    var wbcom_tdd_upload_folders_count = '';
    var wbcom_tdd_upload_folders_done = 0;

    var wbcom_tdd_database_tables_complete = false;
    var wbcom_tdd_upload_folders_complete = false;

    var total_requests = 0;
    var percentage_increment = 0;
    var current_percentage_progress = 0;

	$( 'div.wbcom-demo-importer button#wbcom_get_theme_demo_data' ).click( function( event ) {
		event.preventDefault();
		$( this ).siblings( 'div.loader' ).show();
		thisRef = $( this );
		_wbcom_read_theme_demo_package_file();
    });

    function _wbcom_read_theme_demo_package_file() {
    	wbcom_tdd_show_current_activity( 'Reading Files ...' );
    	$.ajax({
			url : wbcom_theme_demo_installer_params.ajax_url,
			type : 'post',
			data : {
				action : 'wbcom_read_theme_demo_package_file',
				theme_slug : thisRef.siblings( '#theme_slug' ).val(),
				demo_slug : thisRef.siblings( '#demo_slug' ).val(),
				target_url : thisRef.siblings( '#target_url' ).val(),
			},
			success : function( response ) {
				wbcom_tdd_update_progress_bar( Math.floor(current_percentage_progress)+"%" );
				$( '#progress-bar-container' ).show();
				wbcom_theme_demo_data = $.parseJSON( response );
				total_requests = ( wbcom_theme_demo_data.database_tables.length + wbcom_theme_demo_data.upload_folders.length );
				percentage_increment = ( 100 / total_requests );
				_wbcom_read_theme_demo_json_files();
				_wbcom_read_theme_demo_upload_folders();
			}
		});
	}

	function _wbcom_read_theme_demo_json_files() {
		if ( typeof( wbcom_theme_demo_data.database_tables ) === "undefined" ) {
			return;
		}
		wbcom_tdd_database_tables_count = wbcom_theme_demo_data.database_tables.length;
		if( wbcom_tdd_database_tables_count == 0 ) {
			wbcom_tdd_database_tables_complete = true;
		}
		_wbcom_get_theme_demo_data( wbcom_theme_demo_data.database_tables[0], 'database_tables' );
	}

	function _wbcom_read_theme_demo_upload_folders() {
		if ( typeof( wbcom_theme_demo_data.upload_folders ) === "undefined" ) {
			return;
		}
		wbcom_tdd_upload_folders_count = wbcom_theme_demo_data.upload_folders.length;
		if( wbcom_tdd_upload_folders_count == 0 ) {
			wbcom_tdd_upload_folders_complete = true;
		}
		_wbcom_get_theme_demo_data( wbcom_theme_demo_data.upload_folders[0], 'upload_folders' );
	}

	function _wbcom_get_theme_demo_data( url_to_request, action_for ) {
		wbcom_tdd_show_current_activity( 'Reading Files ...' );
		$.ajax({
			url : wbcom_theme_demo_installer_params.ajax_url,
			type : 'post',
			data : {
				action : 'wbcom_get_theme_demo_data',
				url_to_request : url_to_request,
				action_for : action_for,
			},
			success : function( response ) {
				if( action_for == 'database_tables' ) {
					wbcom_tdd_database_tables_done = wbcom_tdd_database_tables_done + 1;
					if( wbcom_tdd_database_tables_done == wbcom_tdd_database_tables_count ) {
						wbcom_tdd_database_tables_complete = true;
						if( wbcom_tdd_database_tables_complete && wbcom_tdd_upload_folders_complete ) {
							current_percentage_progress = 100;
							wbcom_tdd_update_progress_bar( Math.floor(current_percentage_progress)+"%" );
							wbcom_demo_import_done();
						}
					}
					else {
						current_percentage_progress += percentage_increment;
						wbcom_tdd_update_progress_bar( Math.floor(current_percentage_progress)+"%" );
						_wbcom_get_theme_demo_data( wbcom_theme_demo_data.database_tables[wbcom_tdd_database_tables_done], 'database_tables' );
					}
				}
				else {
					wbcom_tdd_upload_folders_done = wbcom_tdd_upload_folders_done + 1;
					if( wbcom_tdd_upload_folders_done == wbcom_tdd_upload_folders_count ) {
						wbcom_tdd_upload_folders_complete = true;
						if( wbcom_tdd_database_tables_complete && wbcom_tdd_upload_folders_complete ) {
							current_percentage_progress = 100;
							wbcom_tdd_update_progress_bar( Math.floor(current_percentage_progress)+"%" );
							wbcom_demo_import_done();
						}
					}
					else {
						current_percentage_progress += percentage_increment;
						wbcom_tdd_update_progress_bar( Math.floor(current_percentage_progress)+"%" );
						_wbcom_get_theme_demo_data( wbcom_theme_demo_data.upload_folders[wbcom_tdd_upload_folders_done], 'upload_folders' );
					}
				}
			},
			error: function ( jqXHR, status, err ) {
				alert( "error in :: " + url_to_request );
				if( action_for == 'database_tables' ) {
					wbcom_tdd_database_tables_done = wbcom_tdd_database_tables_done + 1;
					if( wbcom_tdd_database_tables_done == wbcom_tdd_database_tables_count ) {
						wbcom_tdd_database_tables_complete = true;
						if( wbcom_tdd_database_tables_complete && wbcom_tdd_upload_folders_complete ) {
							current_percentage_progress = 100;
							wbcom_tdd_update_progress_bar( Math.floor(current_percentage_progress)+"%" );
							wbcom_demo_import_done();
						}
					}
					else {
						_wbcom_get_theme_demo_data( wbcom_theme_demo_data.database_tables[wbcom_tdd_database_tables_done], 'database_tables' );
					}
				}
				else {
					wbcom_tdd_upload_folders_done = wbcom_tdd_upload_folders_done + 1;
					if( wbcom_tdd_upload_folders_done == wbcom_tdd_upload_folders_count ) {
						wbcom_tdd_upload_folders_complete = true;
						if( wbcom_tdd_database_tables_complete && wbcom_tdd_upload_folders_complete ) {
							current_percentage_progress = 100;
							wbcom_tdd_update_progress_bar( Math.floor(current_percentage_progress)+"%" );
							wbcom_demo_import_done();
						}
					}
					else {
						_wbcom_get_theme_demo_data( wbcom_theme_demo_data.upload_folders[wbcom_tdd_upload_folders_done], 'upload_folders' );
					}
				}
			}
		});
	}

	function wbcom_demo_import_done() {
		setTimeout( function() {
			window.location = wbcom_theme_demo_installer_params.success_url;
		},
		2000
		);
	}

	function wbcom_tdd_update_progress_bar( progress_percentage ) {
		$( '#progress-bar-container .completed' ).css( 'width', progress_percentage );
		$( '#progress-bar-container .completed' ).html( progress_percentage );
	}

	function wbcom_tdd_show_current_activity( message ) {
		$( '#wbtd-current-action' ).show();
		$( '#wbtd-current-action' ).html( message );
	}

});

jQuery(function () {
		var filterList = {
			init: function () {
				// MixItUp js
				jQuery('#demos_import_filter').mixItUp({
  				selectors: {
    			  target: '.import_filter',
    			  filter: '.demo_filter'
    		  	},
	    		load: {
	      		  filter: '.buddypress'
	      		}
				});
		}
	};
	// Run the show!
	filterList.init();
});
