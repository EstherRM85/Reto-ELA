<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Shortcodes' ) ) :

	/**
	 * Includes settings to display PeepSo WooCommerce Integration settings tab
	 *
	 * @class Reign_Shortcodes
	 */
	class Reign_Shortcodes {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Shortcodes
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Shortcodes Instance.
		 *
		 * Ensures only one instance of Reign_Shortcodes is loaded or can be loaded.
		 *
		 * @return Reign_Shortcodes - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Shortcodes Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_shortcode( 'reign_display_posts', array( $this, 'reign_get_posts' ) );
		}

		public function reign_get_posts( $atts ) {
			// global $blog_list_layout, $wbtm_reign_settings;
			// $_blog_list_layout = isset($wbtm_reign_settings[ 'reign_pages' ][ 'blog_list_layout' ]) ? $wbtm_reign_settings[ 'reign_pages' ][ 'blog_list_layout' ] : 'default-view';
			global $blog_list_layout;
			$_blog_list_layout	 = get_theme_mod( 'reign_blog_list_layout', 'default-view' );
			$atts				 = shortcode_atts( array(
				'category'		 => array(),
				'posts_view'	 => $_blog_list_layout,
				'posts_per_page' => -1
			), $atts, 'display_posts' );

			if ( !empty( $atts[ 'category' ] ) ) {
				$category = explode( ',', $atts[ 'category' ] );
			} else {
				$category = $atts[ 'category' ];
			}
			$blog_list_layout = $atts[ 'posts_view' ];

			$global_query			 = $GLOBALS[ 'wp_query' ];
			$paged					 = get_query_var( 'paged', 1 );
			wp_reset_query();
			$args					 = array(
				'posts_per_page' => $atts[ 'posts_per_page' ],
				'cat'			 => $category,
				'paged'			 => $paged
			);
			$args					 = apply_filters( 'alter_reign_display_posts_args', $args );
			$query					 = new WP_Query( $args );
			$GLOBALS[ 'wp_query' ]	 = $query;

			if ( $query->have_posts() ) {
				if ( $blog_list_layout == 'masonry-view' ) {
					echo '<div class="masonry">';
				}
				if ( $blog_list_layout == 'wb-grid-view' ) {
					echo '<div class="wb-grid-view-wrap">';
				}
				while ( $query->have_posts() ) :
					$query->the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				endwhile;

				if ( $blog_list_layout == 'masonry-view' ) {
					echo '</div>';
				}
				if ( $blog_list_layout == 'wb-grid-view' ) {
					echo '</div>';
				}
				the_posts_navigation();
				wp_reset_query();
				$GLOBALS[ 'wp_query' ] = $global_query;
			}
		}

	}

	endif;

/**
 * Main instance of Reign_Shortcodes.
 *
 * @return Reign_Shortcodes
 */
Reign_Shortcodes::instance();
