<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WBCOM_Demo_Importer_Ajax_Handler' ) ) :

/**
 * @class WBCOM_Demo_Importer_Ajax_Handler
 * @version	1.0.0
 */
class WBCOM_Demo_Importer_Ajax_Handler {

	/**
	 * The single instance of the class.
	 *
	 * @var WBCOM_Demo_Importer_Ajax_Handler
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main WBCOM_Demo_Importer_Ajax_Handler Instance.
	 *
	 * Ensures only one instance of WBCOM_Demo_Importer_Ajax_Handler is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WBCOM_Demo_Importer_Ajax_Handler()
	 * @return WBCOM_Demo_Importer_Ajax_Handler - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	/**
	 * WBCOM_Demo_Importer_Ajax_Handler Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 * @since  1.0.0
	 */
	private function init_hooks() {
		add_action( 'wp_ajax_wbcom_get_theme_demo_data', array( $this, 'wbcom_get_theme_demo_data' ) );
		add_action( 'wp_ajax_wbcom_read_theme_demo_package_file', array( $this, 'wbcom_read_theme_demo_package_file' ) );
	}

	public function wbcom_read_theme_demo_package_file() {
		if( isset( $_POST['action'] ) && ( $_POST['action'] == 'wbcom_read_theme_demo_package_file' ) ) {
			if( isset( $_POST['theme_slug'] ) && isset( $_POST['demo_slug'] ) ) {
				// $url_to_request = WBCOM_Theme_Demo_Installer_URL_TO_REQUEST;
				$url_to_request = $_POST['target_url'] . 'wp-admin/?wbcom_theme_demo_listing=yes';
				$response = wp_remote_post( $url_to_request, array(
					'method' => 'POST',
					'timeout' => 120,
					'headers' => array(),
					'body' => array(
						'theme_slug' => $_POST['theme_slug'],
						'demo_slug' => $_POST['demo_slug'],
					)
				) );
				if ( !is_wp_error( $response ) ) {
					if ( isset( $response['response']['code'] ) &&  ( $response['response']['code'] == 200 ) ) {
						$response = isset( $response['body'] ) ? $response['body'] : '';
						if( !empty( $response ) ) {
							echo $response;
						}
					}
				}
			}
		}
		wp_die();
	}

	public function wbcom_get_theme_demo_data() {
		if( isset( $_POST['action'] ) && ( $_POST['action'] == 'wbcom_get_theme_demo_data' ) ) {

			if( isset( $_POST['action_for'] ) && ( $_POST['action_for'] == 'post_types' ) ) {
				$url_to_request = isset( $_POST['url_to_request'] ) ? $_POST['url_to_request'] : '';
				if( !empty( $url_to_request ) ) {
					$post_slug = end( explode( '/', $url_to_request ) );
					$post_slug = str_replace( '.xml', '', $post_slug );
					$this->clone_post_type( $post_slug, $url_to_request );
				}
				wp_die();
			}

			if( isset( $_POST['action_for'] ) && ( $_POST['action_for'] == 'database_tables' ) ) {
				$url_to_request = isset( $_POST['url_to_request'] ) ? $_POST['url_to_request'] : '';
				if( !empty( $url_to_request ) ) {
					$table_name = end( explode( '/', $url_to_request ) );
					$table_name = str_replace( '.json', '', $table_name );
					$table_name = preg_replace('/[0-9]+/', '', $table_name);
					$this->clone_database_table( $table_name, $url_to_request );
				}
				wp_die();
			}

			if( isset( $_POST['action_for'] ) && ( $_POST['action_for'] == 'upload_folders' ) ) {
				$url_to_request = isset( $_POST['url_to_request'] ) ? $_POST['url_to_request'] : '';
				$this->clone_uploads_folder( $url_to_request );
				wp_die();
			}

		}
		wp_die();
	}

	public function clone_post_type( $post_slug = 'post', $url_to_request = '' ) {
		$retrieved_data = file_get_contents( $url_to_request );
		$upload = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$upload_dir = $upload_dir . '/wbcom-temp-folder';
		if ( !is_dir( $upload_dir ) ) {
			wp_mkdir_p( $upload_dir );
		}
		$dir_path = $upload_dir . '/';
		$fp = fopen( $dir_path . "$post_slug.xml", "w" );
		fwrite( $fp, $retrieved_data );
		fclose( $fp );

		global $wbcom_xml_wp_import;
		$wbcom_xml_wp_import->import( $dir_path . "$post_slug.xml" );

		unlink( $dir_path . "$post_slug.xml" );
	}

	public function clone_database_table( $table_name = '', $url_to_request = '' ) {
		$retrieved_data = '';
		$response = wp_remote_get( $url_to_request, array( 'timeout' => 120 ) );

		if ( !is_wp_error( $response ) ) {
			if ( isset( $response['response']['code'] ) &&  ( $response['response']['code'] == 200 ) ) {
				$response = isset( $response['body'] ) ? $response['body'] : '';
				if( !empty( $response ) ) {
					$retrieved_data = json_decode( $response, true );
				}
			}
		}


		if( !empty( $retrieved_data ) && is_array( $retrieved_data ) ) {
			if ($table_name != 'options') {
				$retrieved_data = array_map( function( $value ) { return str_replace( '{{*home_url}}', home_url(), $value ); }, $retrieved_data );
			}

			if( $table_name == 'theme_mods' ) {
				foreach ( $retrieved_data as $key => $value ) {
					set_theme_mod( $key, $value );
				}
				return;
			}

			if( $table_name == 'options' ) {
				$default_options_keys = array (
					'siteurl',
					'home',
					'blogname',
					'blogdescription',
					'users_can_register',
					'admin_email',
					'start_of_week',
					'use_balanceTags',
					'use_smilies',
					'require_name_email',
					'comments_notify',
					'posts_per_rss',
					'rss_use_excerpt',
					'mailserver_url',
					'mailserver_login',
					'mailserver_pass',
					'mailserver_port',
					'default_category',
					'default_comment_status',
					'default_ping_status',
					'default_pingback_flag',
					// 'posts_per_page',
					'date_format',
					'time_format',
					'links_updated_date_format',
					'comment_moderation',
					'moderation_notify',
					// 'permalink_structure',
					'rewrite_rules',
					'hack_file',
					'blog_charset',
					'active_plugins',
					'category_base',
					'ping_sites',
					'comment_max_links',
					'gmt_offset',
					'default_email_category',
					'template',
					'stylesheet',
					'comment_whitelist',
					'comment_registration',
					'html_type',
					'use_trackback',
					'default_role',
					'db_version',
					'uploads_use_yearmonth_folders',
					'upload_path',
					'blog_public',
					'default_link_category',
					// 'show_on_front',
					'tag_base',
					'show_avatars',
					'avatar_rating',
					'upload_url_path',
					'thumbnail_size_w',
					'thumbnail_size_h',
					'thumbnail_crop',
					'medium_size_w',
					'medium_size_h',
					'avatar_default',
					'large_size_w',
					'large_size_h',
					'image_default_link_type',
					'image_default_size',
					'image_default_align',
					'close_comments_for_old_posts',
					'close_comments_days_old',
					'thread_comments',
					'thread_comments_depth',
					'page_comments',
					'comments_per_page',
					'default_comments_page',
					'comment_order',
					'sticky_posts',
					// 'widget_categories',
					// 'widget_text',
					// 'widget_rss',
					'timezone_string',
					// 'page_for_posts',
					// 'page_on_front',
					'default_post_format',
					'link_manager_enabled',
					'finished_splitting_shared_terms',
					'site_icon',
					'medium_large_size_w',
					'medium_large_size_h',
					'initial_db_version',
					'wp_user_roles',
					'fresh_site',
					// 'widget_search',
					// 'widget_recent-posts',
					// 'widget_recent-comments',
					// 'widget_archives',
					// 'widget_meta',
					// 'sidebars_widgets',
					// 'widget_pages',
					// 'widget_calendar',
					// 'widget_media_audio',
					// 'widget_media_image',
					// 'widget_media_video',
					// 'widget_tag_cloud',
					// 'widget_nav_menu',
					// 'widget_custom_html',
					//'reign_options',
					'cron',
					'theme_mods_twentyseventeen',
					//'theme_mods_reign-theme',
					'_transient_is_multi_author',
					'_transient_twentyseventeen_categories',
				);

				foreach ( $retrieved_data as $key => $value ) {
					if( !in_array( $value['option_name'], $default_options_keys ) ) {
						$option_value = maybe_unserialize( $value['option_value'] );
						if( is_array($option_value)) {
							foreach( $option_value as $op_key=>$op_value ) {
								if ( is_string($op_value) ) {
									$option_value[$op_key] = str_replace( '{{*home_url}}', get_site_url(), $op_value);
								}
							}
						}
						update_option( $value['option_name'], $option_value, $value['autoload'] );
					}
				}
				return;
			}

			global $wpdb;
			if( ( $table_name == 'users' ) || ( $table_name == 'usermeta' ) ) {
				$table_name = $wpdb->prefix . $table_name;
				foreach ( $retrieved_data as $key => $value ) {
					if( ( isset( $value['ID'] ) ) && ( $value['ID'] == get_current_user_id() ) ) {
						continue;
					}
					else if( ( isset( $value['user_id'] ) ) && ( $value['user_id'] == get_current_user_id() ) ) {
						continue;
					}

					/** user table strcuture mismatch fix **/
					if( isset( $value['spam'] ) ) {
						unset( $value['spam'] );
					}
					if( isset( $value['deleted'] ) ) {
						unset( $value['deleted'] );
					}
					/** user table strcuture mismatch fix **/

					$wpdb->insert( $table_name, $value );
				}
				return;
			}
			else {
				$table_name = $wpdb->prefix . $table_name;

				$wbcom_theme_demo_import_data = get_option( 'wbcom_theme_demo_import_data', array() );
				if( !isset( $wbcom_theme_demo_import_data[ $table_name.'_done' ] ) ) {
					$sql = "DELETE FROM " . $table_name;
					$results = $wpdb->get_results( $sql );
					$wbcom_theme_demo_import_data[ $table_name.'_done' ] = 'yes';
					update_option( 'wbcom_theme_demo_import_data', $wbcom_theme_demo_import_data );
				}

				foreach ( $retrieved_data as $key => $value ) {
					$wpdb->insert( $table_name, $value );
				}
			}

		}
	}

	public function clone_uploads_folder( $url_to_request = '' ) {
		$parentFolderName = explode( '/', $url_to_request );
		$parentFolderName = array_filter( $parentFolderName );
		$parentFolderName = array_values( $parentFolderName );
		$parentFolderName = $parentFolderName[ count( $parentFolderName ) - 2 ];

		$response = wp_remote_get( $url_to_request, array( 'timeout' => 120 ) );
		$retrieved_data = array();
		if ( !is_wp_error( $response ) ) {
			if ( isset( $response['response']['code'] ) &&  ( $response['response']['code'] == 200 ) ) {
				$response = isset( $response['body'] ) ? $response['body'] : '';
				if( !empty( $response ) ) {
					$retrieved_data = $response;
				}
			}
		}

		if( !empty( $retrieved_data ) ) {
			$upload = wp_upload_dir();
			$upload_dir = $upload['basedir'] . '/' . 'wbcom-theme-demo.zip';

			$file = fopen( $upload_dir, "w+" );
			fputs( $file, $retrieved_data );
			fclose( $file );

			$zip = new ZipArchive;
			$res = $zip->open( $upload_dir );
			if ( $res === TRUE ) {
				$zip->extractTo( $upload['basedir'] . '/' . $parentFolderName . '/' );
				$zip->close();
			}

			unlink( $upload_dir );
		}
	}

}

endif;

/**
 * Main instance of WBCOM_Demo_Importer_Ajax_Handler.
 * @since  1.0.0
 * @return WBCOM_Demo_Importer_Ajax_Handler
 */
WBCOM_Demo_Importer_Ajax_Handler::instance();
