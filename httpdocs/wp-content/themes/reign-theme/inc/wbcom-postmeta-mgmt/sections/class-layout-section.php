<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Wbcom_Postmeta_Mgmt_Layout_Section' ) ) :

	/**
	 * @class Wbcom_Postmeta_Mgmt_Layout_Section
	 */
	class Wbcom_Postmeta_Mgmt_Layout_Section {

		/**
		 * The single instance of the class.
		 *
		 * @var Wbcom_Postmeta_Mgmt_Layout_Section
		 */
		protected static $_instance		 = null;
		protected static $_slug			 = 'layout';
		protected static $_theme_slug	 = 'reign';

		/**
		 * Main Wbcom_Postmeta_Mgmt_Layout_Section Instance.
		 *
		 * Ensures only one instance of Wbcom_Postmeta_Mgmt_Layout_Section is loaded or can be loaded.
		 *
		 * @return Wbcom_Postmeta_Mgmt_Layout_Section - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Wbcom_Postmeta_Mgmt_Layout_Section Constructor.
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
				'label'		 => __( 'Layout', 'reigntm' ),
				'icon-class' => 'fa fa-th-large',
			);
			return $tabs;
		}

		public function render_metabox_content() {
			global $wbcom_render_postmeta_fields;

			/**
			 * render content layout selection :: start
			 */
			$options_array		 = array(
				'0'							 => __( 'Default', 'reigntm' ),
				'right_sidebar'				 => __( 'Right Sidebar', 'reigntm' ),
				'left_sidebar'				 => __( 'Left Sidebar', 'reigntm' ),
				'both_sidebar'				 => __( 'Both Sidebars', 'reigntm' ),
				'full_width'				 => __( 'Full Width', 'reigntm' ),
				'full_width_no_title'		 => __( 'Full Width( No Subheader )', 'reigntm' ),
				'stretched_view'			 => __( 'Stretched View', 'reigntm' ),
				'stretched_view_no_title'	 => __( 'Stretched View( No Subheader )', 'reigntm' ),
			);
			$args				 = array(
				'label'			 => __( 'Content Layout', 'reigntm' ),
				'desc'			 => __( 'Select your custom layout.', 'reigntm' ),
				'section_name'	 => self::$_slug,
				'field_name'	 => 'site_layout',
				'options_array'	 => $options_array,
			);
			$wbcom_render_postmeta_fields->render_dropdown_option( $args );
			/**
			 * render content layout selection :: end
			 */
			/**
			 * render sidebar selection :: start
			 */
			global $wp_registered_sidebars;
			$widgets_areas		 = array( '0' => __( 'Default', 'reigntm' ) );
			$get_widget_areas	 = $wp_registered_sidebars;
			if ( !empty( $get_widget_areas ) ) {
				foreach ( $get_widget_areas as $widget_area ) {
					$name	 = isset( $widget_area[ 'name' ] ) ? $widget_area[ 'name' ] : '';
					$id		 = isset( $widget_area[ 'id' ] ) ? $widget_area[ 'id' ] : '';
					if ( $name && $id ) {
						$widgets_areas[ $id ] = $name;
					}
				}
			}
			$args = array(
				'label'			 => __( 'Right Sidebar', 'reigntm' ),
				'desc'			 => __( 'Select your custom right sidebar.', 'reigntm' ),
				'section_name'	 => self::$_slug,
				'field_name'	 => 'primary_sidebar',
				'options_array'	 => $widgets_areas,
			);
			$wbcom_render_postmeta_fields->render_dropdown_option( $args );

			$args = array(
				'label'			 => __( 'Left Sidebar', 'reigntm' ),
				'desc'			 => __( 'Select your custom left sidebar.', 'reigntm' ),
				'section_name'	 => self::$_slug,
				'field_name'	 => 'secondary_sidebar',
				'options_array'	 => $widgets_areas,
			);
			$wbcom_render_postmeta_fields->render_dropdown_option( $args );

			$post_type = get_post_type();
			if( $post_type == 'page' ) {
				$args = array(
					'label'	=>	__( 'Display Page Title', 'reigntm' ),
					'desc'	=>	__( 'Allows you to display page title for this post.', 'reigntm' ),
					'section_name'	=> self::$_slug,
					'field_name'	=> 'display_page_title',
					'option'         => 'on',
				);
				$wbcom_render_postmeta_fields->render_checkbox_option( $args );
			}
			/**
			 * render sidebar selection :: end
			 */
		}

	}

	endif;

/**
 * Main instance of Wbcom_Postmeta_Mgmt_Layout_Section.
 * @return Wbcom_Postmeta_Mgmt_Layout_Section
 */
Wbcom_Postmeta_Mgmt_Layout_Section::instance();
?>