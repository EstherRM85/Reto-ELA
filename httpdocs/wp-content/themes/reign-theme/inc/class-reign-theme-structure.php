<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists( 'Reign_Theme_Structure' ) ) :

	/**
	 * @class Reign_Theme_Structure
	 */
	class Reign_Theme_Structure {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Theme_Structure
		 */
		protected static $_instance = null;

		/**
		 * Main Reign_Theme_Structure Instance.
		 *
		 * Ensures only one instance of Reign_Theme_Structure is loaded or can be loaded.
		 *
		 * @return Reign_Theme_Structure - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Theme_Structure Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_action( 'wbcom_before_content_section', array( $this, 'render_left_sidebar_area' ) );
			add_action( 'wbcom_after_content_section', array( $this, 'render_right_sidebar_area' ) );

			/**
			 * WooCommerce left sidebar.
			 */
			add_action( 'woocommerce_before_main_content', array( $this, 'render_left_sidebar_area' ), 8 );
			/**
			 * WooCommerce right sidebar.
			 */
			add_action( 'woocommerce_sidebar', array( $this, 'render_right_sidebar_area' ) );

			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

			/**
			 * Render page header.
			 */
			add_action( 'wbcom_before_content', array( $this, 'render_page_header' ) );

			/**
			 * Render website topbar.
			 */
			add_action( 'wbcom_before_masthead', array( $this, 'render_theme_topbar' ), 20 );

			/**
			 * Render website header desktop.
			 */
			add_action( 'wbcom_masthead', array( $this, 'render_theme_header_desktop' ), 20 );

			/**
			 * Render website header mobile.
			 */
			//add_action( 'wbcom_masthead', array( $this, 'render_theme_header_mobile' ), 25 );


			/**
			 * Render website footer.
			 */
			add_action( 'wbcom_footer', array( $this, 'render_theme_footer' ), 20 );

			/**
			 * Render post tags at bottom.
			 */
			add_action( 'reign_extra_info_on_single_post_end', array( $this, 'render_post_tags_at_bottom' ) );




			add_action( 'wp_head', array( $this, 'apply_theme_color' ) );

			add_action( 'wp_loaded', array( $this, 'remove_theme_mod_values' ) );

			/**
			 * Add placeholder to comment form.
			 */
			add_filter( 'comment_form_defaults', array( $this, 'reign_comment_form_defaults' ), 10, 1 );

			/**
			 * Set post excerpt.
			 */
			add_filter( 'the_excerpt', array( $this, 'reign_the_excerpt' ), 20, 1 );


			/**
			 * Render post meta section.
			 */
			add_action( 'reign_post_content_begins', array( $this, 'render_post_meta_section' ) );

			/**
			 * Render post comment section.
			 */
			add_action( 'reign_single_post_comment_section', array( $this, 'render_post_comment_section' ) );
		}

		public function render_post_comment_section() {
			$reign_comment = get_theme_mod( 'reign_comment', '' );
			if ( !is_array($reign_comment)) {
				$reign_comment = array( 'post' );				
			}
			if ( in_array( get_post_type(), $reign_comment ) ) {
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			}
		}

		public function render_post_meta_section() {
			if ( is_singular( 'post' ) ) {
				$post_meta_alignment = get_theme_mod( 'reign_single_post_meta_alignment', 'left' );
				?>
				<div class="rg-post-meta-info-wrapper align-<?php echo esc_attr( $post_meta_alignment ); ?>">
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .entry-header -->
					<div class="post-meta-info">
						<div class="entry-meta"><?php reign_entry_list_footer(); ?></div>
						<?php do_action( 'reign_extra_info_on_single_post_start' ); ?>
					</div>
				</div>
				<?php
			}
		}

		public function reign_the_excerpt( $excerpt ) {
			$length			 = get_theme_mod( 'reign_blog_excerpt_length', 20 );
			$length			 = apply_filters( 'reign_excerpt_length', $length );
			$excerpt_more	 = apply_filters( 'reign_excerpt_more', '...' );
			$excerpt		 = wp_trim_words( get_the_content(), $length, $excerpt_more );
			return $excerpt;
		}

		public function reign_comment_form_defaults( $defaults ) {
			$fields		 = $defaults[ 'fields' ];
			$commenter	 = wp_get_current_commenter();
			$req		 = get_option( 'require_name_email' );
			$html_req	 = ( $req ? " required='required'" : '' );
			// $html5    = 'html5' === $args['format'];
			$html5		 = 'html5';

			$fields[ 'author' ]	 = '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'reign' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
			'<input placeholder="' . __( 'Name *', 'reign' ) . '" id="author" name="author" type="text" value="' . esc_attr( $commenter[ 'comment_author' ] ) . '" size="30" maxlength="245"' . $html_req . ' /></p>';
			$fields[ 'email' ]	 = '<p class="comment-form-email"><label for="email">' . __( 'Email', 'reign' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
			'<input placeholder="' . __( 'Email *', 'reign' ) . '" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr( $commenter[ 'comment_author_email' ] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $html_req . ' /></p>';
			$fields[ 'url' ]	 = '<p class="comment-form-url"><label for="url">' . __( 'Website', 'reign' ) . '</label> ' .
			'<input placeholder="' . __( 'Website', 'reign' ) . '" id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter[ 'comment_author_url' ] ) . '" size="30" maxlength="200" /></p>';

			$defaults[ 'fields' ] = $fields;

			$defaults[ 'comment_field' ] = '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun', 'reign' ) . '</label> <textarea placeholder="' . __( 'Comment *', 'reign' ) . '" id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required"></textarea></p>';

			return $defaults;
		}

		public function render_post_tags_at_bottom() {
			/* list of tags assigned to post */
			$tags_list	 = get_the_term_list( get_the_ID(), 'post_tag', $before		 = '', $sep		 = '', $after		 = '' );
			if ( $tags_list ) {
				$tags_list = '<span class="tag-links">' . $tags_list . '</span>';
			}
			echo '<div class="rg-post-tags-wrapper">';
			echo apply_filters( 'reign_post_tags', $tags_list );
			echo '</div>';
		}

		public function remove_theme_mod_values() {
			if ( isset( $_GET[ 'devmod_remove_theme_mod' ] ) ) {
				reign_reset_customizer_to_default();
			} else if ( isset( $_GET[ 'devmod_remove_theme_mod_complete' ] ) ) {
				remove_theme_mods();
			}
		}

		public function apply_theme_color() {
			global $rtm_color_scheme;


			$color_schemes_set	 = reign_color_scheme_set();
			$default_theme_cs	 = $color_schemes_set[ $rtm_color_scheme ][ 'reign_colors_theme' ];
			$theme_color		 = get_theme_mod( $rtm_color_scheme . '-' . 'reign_colors_theme', $default_theme_cs );
			//$theme_color = get_theme_mod( 'reign_colors_theme', '#3b5998' );
			$selector_for_color	 = '';
			$selector_for_color	 = apply_filters( 'reign_selector_set_to_apply_theme_color', $selector_for_color );
			$selector_for_color	 = trim( $selector_for_color, ',' );

			$selector_for_background = '';
			$selector_for_background = apply_filters( 'reign_selector_set_to_apply_theme_color_to_background', $selector_for_background );
			$selector_for_background = trim( $selector_for_background, ',' );

			$selector_for_border = '';
			$selector_for_border = apply_filters( 'reign_selector_set_to_apply_theme_color_to_border', $selector_for_border );
			$selector_for_border = trim( $selector_for_border, ',' );

			$reign_preloading_icon		 = get_theme_mod( 'reign_preloading_icon', REIGN_THEME_URI . '/lib/images/loader-1.svg' );
			$reign_preloading_bg_color	 = get_theme_mod( 'reign_preloading_bg_color', '#ffffff' );

			$reign_title_tagline_typography_size = get_theme_mod( 'reign_title_tagline_typography_size', '18' );

			$reign_blog_per_row	 = get_theme_mod( 'reign_blog_per_row', '3' );
			$width				 = ( 100 / $reign_blog_per_row );

			$reign_site_header_sub_header_height = get_theme_mod( 'reign_site_header_sub_header_height', '286' );
			?>
			<style type="text/css">

				.blog .wb-grid-view,
				.archive .wb-grid-view {
					width: calc(<?php echo $width; ?>% - 30px);
				}

				.masonry {
					column-count: <?php echo $reign_blog_per_row; ?>;
				}

				<?php echo $selector_for_color; ?> {
					color: <?php echo $theme_color; ?>;
				}
				<?php echo $selector_for_background; ?> {
					background: <?php echo $theme_color; ?>;
				}
				<?php echo $selector_for_border; ?> {
					border-color: <?php echo $theme_color; ?>;
				}
				.rg-page-loader {
					background: url(<?php echo $reign_preloading_icon; ?>) center no-repeat <?php echo $reign_preloading_bg_color; ?>;
				}

				.lm-site-header-section .lm-header-banner {
					height: <?php echo $reign_site_header_sub_header_height; ?>px;
				}

				@media only screen and ( max-width: 990px ) {
					.site-branding .site-title a {
						font-size: <?php echo $reign_title_tagline_typography_size; ?>px;
					}
				}

			</style>
			<?php
		}

		public function render_theme_header_desktop() {
			$reign_header_header_type = get_theme_mod( 'reign_header_header_type', false );
			if ( !$reign_header_header_type ) {
				$header_version = get_theme_mod( 'reign_header_layout', 'v2' );
				get_template_part( 'template-parts/header/header', $header_version );
			}
		}

		public function render_theme_header_mobile() {
			get_template_part( 'template-parts/header/header-mobile', '' );
		}

		public function render_theme_footer() {
			$reign_footer_footer_type = get_theme_mod( 'reign_footer_footer_type', false );
			if ( !$reign_footer_footer_type ) {
				get_template_part( 'template-parts/footer/footer', '' );
			}
		}

		public function render_theme_topbar() {
			$topbar_enable = get_theme_mod( 'reign_header_topbar_enable', '1' );
			if ( $topbar_enable ) {
				get_template_part( 'template-parts/header/header', 'topbar' );
			}
		}

		public function render_page_header() {

			global $wp_query;
			if ( is_front_page() && is_home() ) {
				// Default homepage
				return;
			} elseif ( is_front_page() ) {
				// static homepage
				return;
			}

			/* BuddyPress support added */
			if ( function_exists( 'is_buddypress' ) && is_buddypress() ) {
				return;
			}

			/* PeepSo support added */
			if ( class_exists( 'PeepSo' ) ) {
				$shortcodes = PeepSo::get_instance()->all_shortcodes();
				foreach ( $shortcodes as $sc => $method ) {
					$page		 = str_ireplace( 'peepso_', '', $sc );
					$page_key	 = 'page_' . $page;
					if ( isset( $wp_query->queried_object ) && isset( $wp_query->queried_object->post_name ) ) {
						if ( PeepSo::get_option( $page_key ) === $wp_query->queried_object->post_name ) {
							return;
						}
					}
				}
			}

			/* content layout support added */
			global $post;
			if ( $post ) {
				$theme_slug			 = apply_filters( 'wbcom_essential_theme_slug', 'reign' );
				$wbcom_metabox_data	 = get_post_meta( $post->ID, $theme_slug . '_wbcom_metabox_data', true );
				$site_layout		 = isset( $wbcom_metabox_data[ 'layout' ][ 'site_layout' ] ) ? $wbcom_metabox_data[ 'layout' ][ 'site_layout' ] : '';
				if ( ( 'stretched_view_no_title' === $site_layout ) || ( 'full_width_no_title' === $site_layout ) ) {
					return;
				}
			}

			$post_type = get_post_type();

			$kirki_post_types_support_class	 = new Reign_Kirki_Post_Types_Support();
			$supported_post_types			 = $kirki_post_types_support_class->get_post_types_to_support();

			if ( is_singular() ) {
				if ( !in_array( $post_type, array_column( $supported_post_types, 'slug' ) ) ) {
					$single_header_enable = get_theme_mod( 'reign_cpt_default_sub_header_switch', true );
				} else {
					$single_header_enable = get_theme_mod( 'reign_' . $post_type . '_single_header_enable', true );
				}
				if ( $single_header_enable ) {
					get_template_part( 'template-parts/reign', 'page-header' );
				}
			} else {
				get_template_part( 'template-parts/reign', 'page-header' );
			}
		}

		public function render_left_sidebar_area() {
			if ( is_search() ) {
				$sidebar_id = apply_filters( 'reign_sidebar_id_for_left_sidebar', 'left' );
				echo get_sidebar( $sidebar_id );
				return;
			}
			global $wp_query;
			if ( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
				$post_id = get_option( 'page_for_posts' );
				$post	 = get_post( $post_id );
			} else {
				global $post;
				
				if ( function_exists( 'bp_is_current_component' )  ) {
					$bp_pages = get_option( 'bp-pages' );
					if ( bp_is_current_component( 'groups' ) && !bp_is_group() && !bp_is_user() && !bp_is_group_create() ){
						$post	 = get_post( $bp_pages['groups'] );
					} elseif ( bp_is_current_component( 'members' ) && !bp_is_user() ) {
						$post	 = get_post( $bp_pages['members'] );
					} elseif ( bp_is_current_component( 'activity' ) && !bp_is_user() ) {
						$post	 = get_post( $bp_pages['activity'] );
					} elseif ( bp_is_user() || bp_is_group() ) {
						return;
					}
				}
				
				if ( class_exists( 'woocommerce' ) ) {
					if (is_woocommerce() && is_archive() && get_post_type() == 'product') {
						$shop_page_id = get_option( 'woocommerce_shop_page_id' ); 
						$post	 = get_post( $shop_page_id );
					}
					if ( is_woocommerce() && is_cart() ) {
						$cart_page_id = get_option( 'woocommerce_cart_page_id' ); 
						$post	 = get_post( $cart_page_id );
					}
					if ( is_woocommerce() && is_checkout() ) {
						$checkout_page_id = get_option( 'woocommerce_checkout_page_id' ); 
						$post	 = get_post( $checkout_page_id );
					}
					if ( is_woocommerce() && is_account_page() ) {
						$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' ); 
						$post	 = get_post( $myaccount_page_id );
					}
				}
				
			}
			
			if ( $post ) {
				$theme_slug			 = apply_filters( 'wbcom_essential_theme_slug', 'reign' );
				$wbcom_metabox_data	 = get_post_meta( $post->ID, $theme_slug . '_wbcom_metabox_data', true );
				$site_layout		 = isset( $wbcom_metabox_data[ 'layout' ][ 'site_layout' ] ) ? $wbcom_metabox_data[ 'layout' ][ 'site_layout' ] : '';
				if ( ( $site_layout == 'both_sidebar' ) || ( $site_layout == 'left_sidebar' ) ) {
					$sidebar_id = apply_filters( 'reign_sidebar_id_for_left_sidebar', 'left' );
					echo get_sidebar( $sidebar_id );
					return;
				}
				if ( $site_layout ) {
					return;
				}
			}

			// global $wbtm_reign_settings;
			// $active_content_layout	 = isset( $wbtm_reign_settings[ 'reign_pages' ][ 'active_content_layout' ] ) ? $wbtm_reign_settings[ 'reign_pages' ][ 'active_content_layout' ] : 'right_sidebar';

			$post_type = get_post_type();
			if ( is_singular() ) {
				$active_content_layout = get_theme_mod( 'reign_' . $post_type . '_single_layout', 'right_sidebar' );
			} else {
				$active_content_layout = get_theme_mod( 'reign_' . $post_type . '_archive_layout', 'right_sidebar' );
			}

			if ( ( $active_content_layout == 'both_sidebar' ) || ( $active_content_layout == 'left_sidebar' ) ) {
				$sidebar_id = apply_filters( 'reign_sidebar_id_for_left_sidebar', 'left' );
				echo get_sidebar( $sidebar_id );
				return;
			}
		}

		public function render_right_sidebar_area() {
			if ( is_search() ) {
				echo get_sidebar();
				return;
			}
			global $wp_query;
			if ( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
				$post_id = get_option( 'page_for_posts' );
				$post	 = get_post( $post_id );
			} else {
				global $post;
				
				if ( class_exists( 'woocommerce' ) ) {
					if (is_woocommerce() && is_archive() && get_post_type() == 'product') {
						$shop_page_id = get_option( 'woocommerce_shop_page_id' ); 
						$post	 = get_post( $shop_page_id );
					}
					if ( is_woocommerce() && is_cart() ) {
						$cart_page_id = get_option( 'woocommerce_cart_page_id' ); 
						$post	 = get_post( $cart_page_id );
					}
					if ( is_woocommerce() && is_checkout() ) {
						$checkout_page_id = get_option( 'woocommerce_checkout_page_id' ); 
						$post	 = get_post( $checkout_page_id );
					}
					if ( is_woocommerce() && is_account_page() ) {
						$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' ); 
						$post	 = get_post( $myaccount_page_id );
					}
				}
			}
			if ( $post ) {
				$theme_slug			 = apply_filters( 'wbcom_essential_theme_slug', 'reign' );
				$wbcom_metabox_data	 = get_post_meta( $post->ID, $theme_slug . '_wbcom_metabox_data', true );
				$site_layout		 = isset( $wbcom_metabox_data[ 'layout' ][ 'site_layout' ] ) ? $wbcom_metabox_data[ 'layout' ][ 'site_layout' ] : '';

				if ( ( $site_layout == 'both_sidebar' ) || ( $site_layout == 'right_sidebar' ) ) {
					echo get_sidebar();
					return;
				}

				if ( $site_layout ) {
					return;
				}
			}

			// global $wbtm_reign_settings;
			// $active_content_layout	 = isset( $wbtm_reign_settings[ 'reign_pages' ][ 'active_content_layout' ] ) ? $wbtm_reign_settings[ 'reign_pages' ][ 'active_content_layout' ] : 'right_sidebar';

			$post_type = get_post_type();
			if ( is_singular() ) {
				$active_content_layout = get_theme_mod( 'reign_' . $post_type . '_single_layout', 'right_sidebar' );
			} else {
				$active_content_layout = get_theme_mod( 'reign_' . $post_type . '_archive_layout', 'right_sidebar' );
			}

			if ( ( $active_content_layout == 'both_sidebar' ) || ( $active_content_layout == 'right_sidebar' ) ) {
				echo get_sidebar();
				return;
			}
		}

	}

	endif;

/**
 * Main instance of Reign_Theme_Structure.
 * @return Reign_Theme_Structure
 */
Reign_Theme_Structure::instance();
