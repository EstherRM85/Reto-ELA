<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Wbcom_Postmeta_Mgmt_Header_Footer_Section' ) ) :

	/**
	 * @class Wbcom_Postmeta_Mgmt_Header_Footer_Section
	 */
	class Wbcom_Postmeta_Mgmt_Header_Footer_Section {

		/**
		 * The single instance of the class.
		 *
		 * @var Wbcom_Postmeta_Mgmt_Header_Footer_Section
		 */
		protected static $_instance		 = null;
		protected static $_slug			 = 'header_footer';
		protected static $_theme_slug	 = 'reign';

		/**
		 * Main Wbcom_Postmeta_Mgmt_Header_Footer_Section Instance.
		 *
		 * Ensures only one instance of Wbcom_Postmeta_Mgmt_Header_Footer_Section is loaded or can be loaded.
		 *
		 * @return Wbcom_Postmeta_Mgmt_Header_Footer_Section - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Wbcom_Postmeta_Mgmt_Header_Footer_Section Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_filter( 'wbcom_metabox_add_vertical_tab', array( $this, 'add_vertical_tab' ), 10, 1 );
			add_filter( 'render_wbcom_metabox_content_for_' . self::$_slug, array( $this, 'render_metabox_content' ), 10 );
		}

		public function add_vertical_tab( $tabs ) {
			$tabs[ self::$_slug ] = array(
				'label'		 => __( 'Header/Footer', 'reigntm' ),
				'icon-class' => 'fa fa-columns',
			);
			return $tabs;
		}

		public function render_metabox_content() {
			global $wbcom_render_postmeta_fields;

			/**
			 * render topbar selection :: start
			 */
			$args			 = array(
				'post_type'		 => 'reign-elemtr-header',
				'post_status'	 => 'publish',
				'posts_per_page' => -1,
				'orderby'		 => 'date',
				'order'			 => 'ASC',
				'meta_query'	 => array(
					array(
						'key'		 => 'reign_ele_header_topbar',
						'value'		 => 'topbar',
						'compare'	 => '==',
					),
				),
			);
			$topbar_posts	 = get_posts( $args );
			$options_array	 = array(
				'0' => __( 'Default', 'reigntm' )
			);
			foreach ( $topbar_posts as $topbar_post ) {
				$options_array[ $topbar_post->ID ] = $topbar_post->post_title;
			}
			$options_array[ '-1' ]	 = __( 'Disable', 'reigntm' );
			$args					 = array(
				'label'			 => __( 'Select Topbar', 'reigntm' ),
				'desc'			 => __( 'Select your site topbar here.', 'reigntm' ),
				'section_name'	 => self::$_slug,
				'field_name'	 => 'elementor_topbar',
				'options_array'	 => $options_array,
			);
			$wbcom_render_postmeta_fields->render_dropdown_option( $args );
			/**
			 * render topbar selection :: end
			 */
			/**
			 * render header selection :: start
			 */
			$args					 = array(
				'post_type'		 => 'reign-elemtr-header',
				'post_status'	 => 'publish',
				'posts_per_page' => -1,
				'orderby'		 => 'date',
				'order'			 => 'ASC',
				'meta_query'	 => array(
					array(
						'key'		 => 'reign_ele_header_topbar',
						'value'		 => 'header',
						'compare'	 => '==',
					),
				),
			);
			$header_posts			 = get_posts( $args );
			$options_array			 = array(
				'0' => __( 'Default', 'reigntm' )
			);
			foreach ( $header_posts as $header_post ) {
				$options_array[ $header_post->ID ] = $header_post->post_title;
			}
			$options_array[ '-1' ]	 = __( 'Disable', 'reigntm' );
			$args					 = array(
				'label'			 => __( 'Select Header', 'reigntm' ),
				'desc'			 => __( 'Select your site header here.', 'reigntm' ),
				'section_name'	 => self::$_slug,
				'field_name'	 => 'elementor_header',
				'options_array'	 => $options_array,
			);
			$wbcom_render_postmeta_fields->render_dropdown_option( $args );
			/**
			 * render header selection :: end
			 */
			/**
			 * render footer selection :: start
			 */
			$args					 = array(
				'post_type'		 => 'reign-elemtr-footer',
				'post_status'	 => 'publish',
				'posts_per_page' => -1,
				'orderby'		 => 'date',
				'order'			 => 'ASC',
			);
			$footer_posts			 = get_posts( $args );
			$options_array			 = array(
				'0' => __( 'Default', 'reigntm' )
			);
			foreach ( $footer_posts as $footer_post ) {
				$options_array[ $footer_post->ID ] = $footer_post->post_title;
			}
			$options_array[ '-1' ]	 = __( 'Disable', 'reigntm' );
			$args					 = array(
				'label'			 => __( 'Select footer', 'reigntm' ),
				'desc'			 => __( 'Select your site footer here.', 'reigntm' ),
				'section_name'	 => self::$_slug,
				'field_name'	 => 'elementor_footer',
				'options_array'	 => $options_array,
			);
			$wbcom_render_postmeta_fields->render_dropdown_option( $args );
			/**
			 * render footer selection :: end
			 */
		}

	}

	endif;

/**
 * Main instance of Wbcom_Postmeta_Mgmt_Header_Footer_Section.
 * @return Wbcom_Postmeta_Mgmt_Header_Footer_Section
 */
Wbcom_Postmeta_Mgmt_Header_Footer_Section::instance();
?>