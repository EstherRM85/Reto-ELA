<?php
/**
 * Checks if Elementor is installed and activated and loads it's own files and actions.
 *
 * @package  reign header-footer-elementor
 */
defined( 'ABSPATH' ) or exit;

/**
 * WBCOM_Elementor_Global_Header_Footer_PostType setup
 *
 * @since 1.0
 */
class WBCOM_Elementor_Global_Header_Footer_PostType {

	/**
	 * Instance of WBCOM_Elementor_Global_Header_Footer_PostType
	 *
	 * @var WBCOM_Elementor_Global_Header_Footer_PostType
	 */
	private static $_instance = null;

	/**
	 * Instance of Elemenntor Frontend class.
	 *
	 * @var \Elementor\Frontend()
	 */
	private static $elementor_frontend;

	/**
	 * Instance of WBCOM_Elementor_Global_Header_Footer_PostType
	 *
	 * @return WBCOM_Elementor_Global_Header_Footer_PostType Instance of WBCOM_Elementor_Global_Header_Footer_PostType
	 */
	public static function instance() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'header_posttype' ) );
		add_action( 'init', array( $this, 'footer_posttype' ) );

		add_filter( 'the_content_export', array( $this, 'remove_content_while_exporting' ), 10, 1 );
		add_filter( 'wp_insert_post_data', array( $this, 'remove_content_while_saving' ), 10, 2 );
	}

	public function import_elementor_dummy_data() {
		$json_string = include WBCOM_ELEMENTOR_ADDONS_PATH . 'dummy-data/reign.php';
		$json_string = strip_tags( $json_string );
		$dummy_data = json_decode( $json_string, true );
		
		foreach ( $dummy_data as $key => $value ) {
			$existing_post_info = get_page_by_title( $page_title = $value['post_title'], $output = 'OBJECT', $post_type = 'reign-elemtr-header' );
			if( is_null( $existing_post_info ) ) {
				$existing_post_info = get_page_by_title( $page_title = $value['post_title'], $output = 'OBJECT', $post_type = 'reign-elemtr-footer' );
			}
			
			if( is_null( $existing_post_info ) ) {
				$postarr = array(
					'post_title'	=>	$value['post_title'],
					'post_content'	=>	$value['post_content'],
					'post_type'        => $value['post_type'],
					'post_status'      => 'publish'
				);
				$post_id = wp_insert_post( $postarr );
				if( $post_id ) {
					foreach ( $value['post_meta'] as $meta_key => $meta_value ) {
						update_post_meta( $post_id, $meta_key, $meta_value );
					}

					$current_ele_post = get_post( $post_id );
					$current_post_slug = $current_ele_post->post_name;

					/** setting up default header/footer for first time installation :: start */
					$theme_slug = apply_filters( 'wbcom_essential_theme_slug', 'reign' );
					$settings = get_option( $theme_slug . '_options', array() );

					$selected_value = get_post_meta( $post_id, 'reign_ele_header_topbar', true );
					
					if( ( $postarr['post_type'] == 'reign-elemtr-header' ) && ( $selected_value == 'header' ) ) {
						$header_id = isset( $settings[ $theme_slug . '_pages' ][ 'global_ele_header' ] ) ? $settings[ $theme_slug . '_pages' ][ 'global_ele_header' ] : '0';
						if( $header_id == '0' ) {
							$settings[ $theme_slug . '_pages' ][ 'global_ele_header' ] = $current_post_slug;
						}
					}
					else if( ( $postarr['post_type'] == 'reign-elemtr-header' ) && ( $selected_value == 'topbar' ) ) {
						$topbar_id = isset( $settings[ $theme_slug . '_pages' ][ 'global_ele_topbar' ] ) ? $settings[ $theme_slug . '_pages' ][ 'global_ele_topbar' ] : '0';
						if( $topbar_id == '0' ) {
							$settings[ $theme_slug . '_pages' ][ 'global_ele_topbar' ] = $current_post_slug;
						}
					}
					else if( ( $postarr['post_type'] == 'reign-elemtr-footer' ) ) {
						$footer_id = isset( $settings[ $theme_slug . '_pages' ][ 'global_ele_footer' ] ) ? $settings[ $theme_slug . '_pages' ][ 'global_ele_footer' ] : '0';
						if( $footer_id == '0' ) {
							$settings[ $theme_slug . '_pages' ][ 'global_ele_footer' ] = $current_post_slug;
						}
					}

					update_option( $theme_slug . '_options', $settings );
					/** setting up default header/footer for first time installation :: end */
				}

			}
		}
	}

	/**
	 * Removing content for Elementor Header and Footer while saving data
	 */
	public function remove_content_while_saving( $data = array(), $post_args = array() ) {
		if( ( $data['post_type'] == 'reign-elemtr-header' ) || ( $data['post_type'] == 'reign-elemtr-footer' ) ) {
			$data['post_content'] = '';
		}
		return $data;
	}

	/**
	 * Removing content for Elementor Header and Footer while exporting data
	 */
	public function remove_content_while_exporting( $post_content ) {
		global $post;
		if( ( $post->post_type == 'reign-elemtr-header' ) || ( $post->post_type == 'reign-elemtr-footer' ) ) {
			$post_content = '';
		}
		return $post_content;
	}

	/**
	 * Add default posts when plugin is activated
	 */
	public function add_header_footer_post() {

		// on activation first regsiter the post type
		$this->header_posttype();
		$this->footer_posttype();

		// add the first and only post
		// $post_data_header = array(
		// 	'post_title' => 'Header' . time(),
		// 	'post_type'		 => 'reign-elemtr-header',
		// 	'post_status'	 => 'publish',
		// 	'post_author'	 => get_current_user_id()
		// );
		// $posts = get_posts( $post_data_header );
		// if ( count( $posts ) == 0 ) { //check if posts exists
		// 	wp_insert_post( $post_data_header );
		// }

		// $post_data_footer = array(
		// 	'post_title' => 'Footer' . time(),
		// 	'post_type'		 => 'reign-elemtr-footer',
		// 	'post_status'	 => 'publish',
		// 	'post_author'	 => get_current_user_id()
		// );
		// $posts = get_posts( $post_data_footer );
		// if ( count( $posts ) == 0 ) { //check if posts exists
		// 	wp_insert_post( $post_data_footer );
		// }
	}

	/**
	 * Register Post type for header footer templates
	 */
	public function header_posttype() {

		$labels = array(
			'name'		 => __( 'Header Template', 'wbcom-essential' ),
			'edit_item'	 => __( 'Edit Header Template', 'wbcom-essential' ),
		);

		$args = array(
			'labels'				 => $labels,
			'public'				 => true,
			'rewrite'				 => false,
			'show_ui'				 => true,
			// 'show_in_menu'			 => false,
			'show_in_menu'			=>	'admin.php?page=reign-options',
			'show_in_nav_menus'		 => false,
			'exclude_from_search'	 => true,
			'capability_type'		 => 'post',
			// 'capabilities'			 => array(
			// 	'create_posts'			 => 'do_not_allow',
			// 	'delete_published_posts' => 'do_not_allow',
			// 	'delete_private_posts'	 => 'do_not_allow',
			// 	'delete_posts'			 => 'do_not_allow',
			// ),
			// 'map_meta_cap'			 => true,
			'hierarchical'			 => false,
			'menu_icon'				 => 'dashicons-editor-kitchensink',
			// 'supports'				 => array( 'elementor' ),
			'supports'				 => array( 'elementor', 'title' ),
		);

		register_post_type( 'reign-elemtr-header', $args );
	}

	/**
	 * Register Post type for header footer templates
	 */
	public function footer_posttype() {

		$labels = array(
			'name'		 => __( 'Footer Template', 'wbcom-essential' ),
			'edit_item'	 => __( 'Edit Footer Template', 'wbcom-essential' ),
		);

		$args = array(
			'labels'				 => $labels,
			'public'				 => true,
			'rewrite'				 => false,
			'show_ui'				 => true,
			// 'show_in_menu'			 => false,
			'show_in_menu'			=>	'admin.php?page=reign-options',
			'show_in_nav_menus'		 => false,
			'exclude_from_search'	 => true,
			'capability_type'		 => 'post',
			// 'capabilities'			 => array(
			// 	'create_posts'			 => 'do_not_allow',
			// 	'delete_published_posts' => 'do_not_allow',
			// 	'delete_private_posts'	 => 'do_not_allow',
			// 	'delete_posts'			 => 'do_not_allow',
			// ),
			// 'map_meta_cap'			 => true,
			'hierarchical'			 => false,
			'menu_icon'				 => 'dashicons-editor-kitchensink',
			// 'supports'				 => array( 'elementor' ),
			'supports'				 => array( 'elementor', 'title' ),
		);

		register_post_type( 'reign-elemtr-footer', $args );
	}

}

WBCOM_Elementor_Global_Header_Footer_PostType::instance();
