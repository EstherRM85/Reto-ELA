<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Wbcom_Postmeta_Management' ) ) :

/**
 * @class Wbcom_Postmeta_Management
 */
class Wbcom_Postmeta_Management {
	
	/**
	 * The single instance of the class.
	 *
	 * @var Wbcom_Postmeta_Management
	 */
	protected static $_instance = null;
	protected static $_theme_slug = 'reign';
	
	/**
	 * Main Wbcom_Postmeta_Management Instance.
	 *
	 * Ensures only one instance of Wbcom_Postmeta_Management is loaded or can be loaded.
	 *
	 * @return Wbcom_Postmeta_Management - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Wbcom_Postmeta_Management Constructor.
	 */
	public function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	public function includes() {
		include_once 'class-wbcom-render-postmeta.php';
		include_once 'sections/class-layout-section.php';
		if ( defined( 'ELEMENTOR_VERSION' ) && defined( 'WBCOM_ELEMENTOR_ADDONS_VERSION' ) ) {
			include_once 'sections/class-header-footer-section.php';
		}
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		add_action( 'add_meta_boxes', array( $this, 'wbcom_add_meta_box' ) );
		add_action( 'save_post', array( $this, 'wbcom_save_post_meta' ), 10, 1 );

		add_action( 'admin_print_scripts-post-new.php', array( $this, 'enqueue_wbcom_metabox_style_n_script' ), 11 );
		add_action( 'admin_print_scripts-post.php', array( $this, 'enqueue_wbcom_metabox_style_n_script' ), 11 );
	}

	public function enqueue_wbcom_metabox_style_n_script() {
		$url_prefix = get_template_directory_uri() . '/inc/wbcom-postmeta-mgmt';
		wp_register_style(
			$handle	 = 'wbcom-postmeta-mgmt-css',
			$src	 = $url_prefix . '/assets/wbcom-postmeta-mgmt.css',
			$deps	 = array(),
			$ver	 = time(),
			$media	 = 'all'
		);
		wp_enqueue_style( 'wbcom-postmeta-mgmt-css' );

		wp_register_style(
			$handle	 = 'reign-tooltip-css',
			$src	 = get_template_directory_uri() . '/assets/css/reign-tooltip.css',
			$deps	 = array(),
			$ver	 = time(),
			$media	 = 'all'
		);
		wp_enqueue_style( 'reign-tooltip-css' );
		
		wp_register_style(
			$handle	 = 'select2.min.css',
			$src	 = $url_prefix . '/assets/select2.min.css',
			$deps	 = array(),
			$ver	 = time(),
			$media	 = 'all'
		);
		wp_enqueue_style( 'select2.min.css' );


		wp_register_script(
			$handle	 = 'select2.min.js',
			$src	 = $url_prefix . '/assets/select2.min.js',
			$deps	 = array( 'jquery' ),
			$ver	 = time(),
			$in_footer = true
		);
		wp_enqueue_script( 'wbcom-postmeta-mgmt-js' );
		wp_register_script(
			$handle	 = 'wbcom-postmeta-mgmt-js',
			$src	 = $url_prefix . '/assets/wbcom-postmeta-mgmt.js',
			$deps	 = array( 'jquery', 'select2.min.js' ),
			$ver	 = time(),
			$in_footer = true
		);
		wp_enqueue_script( 'wbcom-postmeta-mgmt-js' );
		wp_enqueue_script( 'select2.min.js' );
	}

	public function render_wbcom_add_meta_box( $post ) {

		$current_active_tab = 0;
		$vertical_tabs = apply_filters( 'wbcom_metabox_add_vertical_tab', array() );
		
		echo '<div class="wbcom-metabox-wrapper">';
			if ( !empty( $vertical_tabs ) && is_array( $vertical_tabs ) ) {
				echo '<div class="wbcom-metabox-tab">';
					$counter = 0;
					foreach ( $vertical_tabs as $key => $value ) {
						$active = ( $current_active_tab == $counter ) ? ' active' : '';
						// echo '<span class="' . $value['icon-class'] . '"></span>';
						echo '<button class="wbcom-metabox-tablinks ' . $key . ' ' . $active . '" data-container-id="' . $key . '"><span class="' . $value['icon-class'] . '"></span>' . $value['label'] . '</button>';
						$counter++;
					}
				echo '</div>';
			}
			if ( !empty( $vertical_tabs ) && is_array( $vertical_tabs ) ) {
				$inline_css = '';
				foreach ( $vertical_tabs as $key => $value ) {
					echo '<div class="wbcom-metabox-content ' . $key . '" ' . $inline_css . '>';
						do_action( 'render_wbcom_metabox_content_for_' . $key );
					echo '</div>';
					$inline_css = 'style="display:none;"';
				}
			}
		echo '</div>';
	}

	public function wbcom_add_meta_box() {
		global $post;
		if ( $post->ID == get_option( 'page_for_posts' ) && empty( $post->post_content ) ) {
			return;
		}
		add_meta_box(
			self::$_theme_slug . '_postmeta_settings',
			ucfirst( self::$_theme_slug ) . __( ' Theme Settings', 'reigntm' ),
			array( $this, 'render_wbcom_add_meta_box' ),
			array( 'post', 'page' ),
			'normal',
			'high'
		);
	}

	public function wbcom_save_post_meta( $post_id ) {
		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// if our current user can't edit this post, bail.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}
		if( isset( $_POST['post_type'] ) && ( ( $_POST['post_type'] == 'post' ) || ( $_POST['post_type'] == 'page' ) ) ) {
			$vertical_tabs = apply_filters( 'wbcom_metabox_add_vertical_tab', array() );
			$wbcom_metabox_data = array();
			foreach ( $vertical_tabs as $key => $value ) {
				if ( isset( $_POST[ $key ] ) && !empty( $_POST[ $key ] ) && is_array( $_POST[ $key ] ) ) {
					foreach ( $_POST[ $key ] as $_key => $_value ) {
						$wbcom_metabox_data[  $key ][ $_key ] = esc_attr( $_value );
					}
				}
			}
			$wbcom_metabox_data = apply_filters( 'modify_wbcom_metabox_data_before_update', $wbcom_metabox_data, $vertical_tabs, $_POST );
			update_post_meta( $post_id, self::$_theme_slug . '_wbcom_metabox_data', $wbcom_metabox_data );
		}
	}
		
}

endif;

/**
 * Main instance of Wbcom_Postmeta_Management.
 * @return Wbcom_Postmeta_Management
 */
Wbcom_Postmeta_Management::instance();
?>