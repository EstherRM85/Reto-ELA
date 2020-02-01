<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Kirki_Post_Types_Support' ) ) :

	/**
	 * @class Reign_Kirki_Post_Types_Support
	 */
	class Reign_Kirki_Post_Types_Support {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Kirki_Post_Types_Support
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Kirki_Post_Types_Support Instance.
		 *
		 * Ensures only one instance of Reign_Kirki_Post_Types_Support is loaded or can be loaded.
		 *
		 * @return Reign_Kirki_Post_Types_Support - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Kirki_Post_Types_Support Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_action( 'customize_register', array( $this, 'add_panels_and_sections' ) );
			add_filter( 'kirki/fields', array( $this, 'add_fields' ) );
		}

		public function get_post_types_to_support() {
			$post_types = array(
				array(
					'slug'	 => 'post',
					'name'	 => __( 'Blog', 'reign' ),
				),
				array(
					'slug'	 => 'page',
					'name'	 => __( 'Page', 'reign' ),
				),
			);

			if ( class_exists( 'WooCommerce' ) ) {
				$post_types[] = array(
					'slug'	 => 'product',
					'name'	 => __( 'Product', 'reign' ),
				);
			}

			if ( class_exists( 'bbPress' ) ) {
				$post_types[]	 = array(
					'slug'	 => 'forum',
					'name'	 => __( 'bbPress Forum', 'reign' ),
				);
				$post_types[]	 = array(
					'slug'	 => 'topic',
					'name'	 => __( 'bbPress Topic', 'reign' ),
				);
			}

			$post_types = apply_filters( 'reign_customizer_supported_post_types', $post_types );
			return $post_types;
		}

		public function add_panels_and_sections( $wp_customize ) {

			$post_types = $this->get_post_types_to_support();

			foreach ( $post_types as $post_type ) {
				$wp_customize->add_panel(
				'reign_' . $post_type[ 'slug' ] . '_panel', array(
					'priority'		 => 100,
					'title'			 => $post_type[ 'name' ],
					'description'	 => '',
				)
				);

				if ( 'page' !== $post_type[ 'slug' ] ) {
					$wp_customize->add_section(
					'reign_' . $post_type[ 'slug' ] . '_archive', array(
						'title'			 => __( 'Archive', 'reign' ),
						'priority'		 => 10,
						'panel'			 => 'reign_' . $post_type[ 'slug' ] . '_panel',
						'description'	 => '',
					)
					);
				}

				$wp_customize->add_section(
				'reign_' . $post_type[ 'slug' ] . '_single', array(
					'title'			 => __( 'Single', 'reign' ),
					'priority'		 => 10,
					'panel'			 => 'reign_' . $post_type[ 'slug' ] . '_panel',
					'description'	 => '',
				)
				);
			}
		}

		public function add_fields( $fields ) {

			$post_types = $this->get_post_types_to_support();

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

			foreach ( $post_types as $post_type ) {
				$fields[] = array(
					'type'			 => 'radio-image',
					'settings'		 => 'reign_' . $post_type[ 'slug' ] . '_archive_layout',
					'label'			 => esc_attr__( 'Layout', 'reign' ),
					'description'	 => esc_attr__( 'Allows you to choose a layout for all archive pages.', 'reign' ),
					'section'		 => 'reign_' . $post_type[ 'slug' ] . '_archive',
					'default'		 => 'right_sidebar',
					'priority'		 => 10,
					'choices'		 => array(
						'left_sidebar'	 => REIGN_THEME_URI . '/lib/images/sidebar-left.jpg',
						'right_sidebar'	 => REIGN_THEME_URI . '/lib/images/sidebar-right.jpg',
						'both_sidebar'	 => REIGN_THEME_URI . '/lib/images/sidebar-both.jpg',
						'full_width'	 => REIGN_THEME_URI . '/lib/images/sidebar-none.jpg',
					),
				);

				$fields[] = array(
					'type'			 => 'switch',
					'settings'		 => 'reign_' . $post_type[ 'slug' ] . '_archive_enable_header_image',
					'label'			 => esc_attr__( 'Enable Header Image', 'reign' ),
					'description'	 => '',
					'section'		 => 'reign_' . $post_type[ 'slug' ] . '_archive',
					'default'		 => 1,
					'priority'		 => 10,
					'choices'		 => array(
						'on'	 => esc_attr__( 'Enable', 'reign' ),
						'off'	 => esc_attr__( 'Disable', 'reign' ),
					),
				);

				$fields[] = array(
					'type'				 => 'image',
					'settings'			 => 'reign_' . $post_type[ 'slug' ] . '_archive_header_image',
					'label'				 => esc_attr__( 'Blog Header Image', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to set page header image for blog page.', 'reign' ),
					'section'			 => 'reign_' . $post_type[ 'slug' ] . '_archive',
					'priority'			 => 10,
					'default'			 => reign_get_default_page_header_image(),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_' . $post_type[ 'slug' ] . '_archive_enable_header_image',
							'operator'	 => '===',
							'value'		 => true,
						),
					),
				);

				$fields[] = array(
					'type'			 => 'select',
					'settings'		 => 'reign_' . $post_type[ 'slug' ] . '_archive_left_sidebar',
					'label'			 => esc_attr__( 'Left Sidebar', 'reign' ),
					'description'	 => esc_attr__( 'Allows you to set left sidebar.', 'reign' ),
					'section'		 => 'reign_' . $post_type[ 'slug' ] . '_archive',
					'priority'		 => 10,
					'default'		 => '0',
					'priority'		 => 10,
					'choices'		 => $widgets_areas,
				);

				$fields[] = array(
					'type'			 => 'select',
					'settings'		 => 'reign_' . $post_type[ 'slug' ] . '_archive_right_sidebar',
					'label'			 => esc_attr__( 'Right Sidebar', 'reign' ),
					'description'	 => esc_attr__( 'Allows you to set right sidebar.', 'reign' ),
					'section'		 => 'reign_' . $post_type[ 'slug' ] . '_archive',
					'priority'		 => 10,
					'default'		 => '0',
					'priority'		 => 10,
					'choices'		 => $widgets_areas,
				);

				$fields[] = array(
					'type'			 => 'radio-image',
					'settings'		 => 'reign_' . $post_type[ 'slug' ] . '_single_layout',
					'label'			 => esc_attr__( 'Layout', 'reign' ),
					'description'	 => esc_attr__( 'Allows you to choose a layout to display for all single post pages.', 'reign' ),
					'section'		 => 'reign_' . $post_type[ 'slug' ] . '_single',
					'default'		 => 'right_sidebar',
					'priority'		 => 10,
					'choices'		 => array(
						'left_sidebar'	 => REIGN_THEME_URI . '/lib/images/sidebar-left.jpg',
						'right_sidebar'	 => REIGN_THEME_URI . '/lib/images/sidebar-right.jpg',
						'both_sidebar'	 => REIGN_THEME_URI . '/lib/images/sidebar-both.jpg',
						'full_width'	 => REIGN_THEME_URI . '/lib/images/sidebar-none.jpg',
					),
				);

				if ( 'page' !== $post_type[ 'slug' ] ) {
					$fields[] = array(
						'type'			 => 'switch',
						'settings'		 => 'reign_' . $post_type[ 'slug' ] . '_single_header_enable',
						'label'			 => esc_attr__( 'Hide '. $post_type[ 'name' ] .' Header', 'reign' ),
						'description'	 => esc_attr__( 'Allows you to hide page header for this post type.', 'reign' ),
						'section'		 => 'reign_' . $post_type[ 'slug' ] . '_single',
						'default'		 => 1,
						'priority'		 => 10,
						'choices'		 => array(
							'on'	 => esc_attr__( 'Enable', 'reign' ),
							'off'	 => esc_attr__( 'Disable', 'reign' ),
						),
					);
				} else {
					$fields[] = array(
						'type'			 => 'switch',
						'settings'		 => 'reign_' . $post_type[ 'slug' ] . '_single_header_enable',
						'label'			 => esc_attr__( 'Hide Page Header', 'reign' ),
						'description'	 => esc_attr__( 'Allows you to hide page header.', 'reign' ),
						'section'		 => 'reign_' . $post_type[ 'slug' ] . '_single',
						'default'		 => 0,
						'priority'		 => 10,
						'choices'		 => array(
							'on'	 => esc_attr__( 'Enable', 'reign' ),
							'off'	 => esc_attr__( 'Disable', 'reign' ),
						),
					);
				}

				if ( 'page' == $post_type[ 'slug' ] ) {
					$fields[] = array(
						'type'			 => 'switch',
						'settings'		 => 'reign_' . $post_type[ 'slug' ] . '_single_pagetitle_enable',
						'label'			 => esc_attr__( 'Hide '. $post_type[ 'name' ] .' Title', 'reign' ),
						'description'	 => esc_attr__( 'Allows you to hide page title for this post type.', 'reign' ),
						'section'		 => 'reign_' . $post_type[ 'slug' ] . '_single',
						'default'		 => 1,
						'priority'		 => 10,
						'choices'		 => array(
							'on'	 => esc_attr__( 'Enable', 'reign' ),
							'off'	 => esc_attr__( 'Disable', 'reign' ),
						),
					);
				}		

				$fields[] = array(
					'type'				 => 'switch',
					'settings'			 => 'reign_' . $post_type[ 'slug' ] . '_single_enable_header_image',
					'label'				 => esc_attr__( 'Enable Header Image', 'reign' ),
					'description'		 => '',
					'section'			 => 'reign_' . $post_type[ 'slug' ] . '_single',
					'default'			 => 1,
					'priority'			 => 10,
					'choices'			 => array(
						'on'	 => esc_attr__( 'Enable', 'reign' ),
						'off'	 => esc_attr__( 'Disable', 'reign' ),
					),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_' . $post_type[ 'slug' ] . '_single_header_enable',
							'operator'	 => '===',
							'value'		 => true,
						),
					),
				);

				$fields[] = array(
					'type'				 => 'image',
					'settings'			 => 'reign_' . $post_type[ 'slug' ] . '_single_header_image',
					'label'				 => esc_attr__( 'Page Header Image', 'reign' ),
					'description'		 => esc_attr__( 'Allows you to set page header image for single post page.', 'reign' ),
					'section'			 => 'reign_' . $post_type[ 'slug' ] . '_single',
					'priority'			 => 10,
					'default'			 => reign_get_default_page_header_image(),
					'active_callback'	 => array(
						array(
							'setting'	 => 'reign_' . $post_type[ 'slug' ] . '_single_header_enable',
							'operator'	 => '===',
							'value'		 => true,
						),
						array(
							'setting'	 => 'reign_' . $post_type[ 'slug' ] . '_single_enable_header_image',
							'operator'	 => '===',
							'value'		 => true,
						),
					),
				);

				if ( 'post' === $post_type[ 'slug' ] ) {
					$fields[] = array(
						'type'			 => 'select',
						'settings'		 => 'reign_blog_list_layout',
						'label'			 => esc_attr__( 'Blog Listing Layout', 'reign' ),
						'description'	 => esc_attr__( 'Select your log listing layout here. We have option to choose from 4 different views.', 'reign' ),
						'section'		 => 'reign_' . $post_type[ 'slug' ] . '_archive',
						'default'		 => 'default-view',
						'priority'		 => 10,
						'choices'		 => array(
							'default-view'	 => esc_attr__( 'Default View', 'reign' ),
							'thumbnail-view' => esc_attr__( 'Thumbnail View', 'reign' ),
							'wb-grid-view'	 => esc_attr__( 'Grid View', 'reign' ),
							'masonry-view'	 => esc_attr__( 'Masonry View', 'reign' ),
						),
					);

					$fields[] = array(
						'type'				 => 'number',
						'settings'			 => 'reign_blog_per_row',
						'label'				 => esc_attr__( 'Blogs Per Row', 'reign' ),
						'description'		 => '',
						'section'			 => 'reign_' . $post_type[ 'slug' ] . '_archive',
						'default'			 => '3',
						'priority'			 => 10,
						'active_callback'	 => array(
							array(
								'setting'	 => 'reign_blog_list_layout',
								'operator'	 => 'contains',
								'value'		 => array( 'wb-grid-view', 'masonry-view' ),
							),
						),
					);

					$fields[] = array(
						'type'			 => 'number',
						'settings'		 => 'reign_blog_excerpt_length',
						'label'			 => esc_attr__( 'Excerpt Length (words)', 'reign' ),
						'description'	 => '',
						'section'		 => 'reign_' . $post_type[ 'slug' ] . '_archive',
						'default'		 => '20',
						'priority'		 => 10,
					);

					$fields[] = array(
						'type'			 => 'switch',
						'settings'		 => 'reign_single_post_switch_header_image',
						'label'			 => esc_attr__( 'Switch Header Image With Featured Image', 'reign' ),
						'description'	 => esc_attr__( 'This will show post featured image on top header section and featured image will be removed from post content.', 'reign' ),
						'section'		 => 'reign_' . $post_type[ 'slug' ] . '_single',
						'default'		 => 0,
						'priority'		 => 10,
						'choices'		 => array(
							'on'	 => esc_attr__( 'Enable', 'reign' ),
							'off'	 => esc_attr__( 'Disable', 'reign' ),
						),
					);

					$fields[] = array(
						'type'			 => 'select',
						'settings'		 => 'reign_single_post_meta_alignment',
						'label'			 => esc_attr__( 'Post Meta Alignment', 'reign' ),
						'description'	 => esc_attr__( 'Select alignment for post-meta information on single post page.', 'reign' ),
						'section'		 => 'reign_' . $post_type[ 'slug' ] . '_single',
						'default'		 => 'left',
						'priority'		 => 10,
						'choices'		 => array(
							'left'	 => esc_attr__( 'Left', 'reign' ),
							'center' => esc_attr__( 'Center', 'reign' ),
							'right'	 => esc_attr__( 'Right', 'reign' ),
						),
					);
				}

				$fields[] = array(
					'type'			 => 'select',
					'settings'		 => 'reign_' . $post_type[ 'slug' ] . '_single_left_sidebar',
					'label'			 => esc_attr__( 'Left Sidebar', 'reign' ),
					'description'	 => esc_attr__( 'Allows you to set left sidebar.', 'reign' ),
					'section'		 => 'reign_' . $post_type[ 'slug' ] . '_single',
					'priority'		 => 10,
					'default'		 => '0',
					'priority'		 => 10,
					'choices'		 => $widgets_areas,
				);

				$fields[] = array(
					'type'			 => 'select',
					'settings'		 => 'reign_' . $post_type[ 'slug' ] . '_single_right_sidebar',
					'label'			 => esc_attr__( 'Right Sidebar', 'reign' ),
					'description'	 => esc_attr__( 'Allows you to set right sidebar.', 'reign' ),
					'section'		 => 'reign_' . $post_type[ 'slug' ] . '_single',
					'priority'		 => 10,
					'default'		 => '0',
					'priority'		 => 10,
					'choices'		 => $widgets_areas,
				);
			}

			return $fields;
		}

	}

	endif;

/**
 * Main instance of Reign_Kirki_Post_Types_Support.
 * @return Reign_Kirki_Post_Types_Support
 */
Reign_Kirki_Post_Types_Support::instance();
