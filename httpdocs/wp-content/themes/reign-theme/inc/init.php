<?php
// Register the three useful image sizes for use in Add Media modal
// add_filter( 'image_size_names_choose', 'wpshout_custom_sizes' );
// function wpshout_custom_sizes( $sizes ) {
//     return array_merge( $sizes, array(
//         'medium-width' => __( 'Medium Width' ),
//         'medium-height' => __( 'Medium Height' ),
//         'medium-something' => __( 'Medium Something' ),
//     ) );
// }


/**
 * Reign functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Reign
 */
if ( !function_exists( 'reign_setup' ) ) {

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function reign_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Reign, use a find and replace
		 * to change 'reign' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'reign', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		add_theme_support( 'custom-logo' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		if ( function_exists( 'add_image_size' ) ) {

			add_image_size( 'reign-featured-large', 1200, 675 );
			add_image_size( 'reign-thumb', 600, 300 );
		}

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'reign' ),
			'menu-2' => esc_html__( 'User Profile', 'reign' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside', 'image', 'video', 'quote', 'link',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'wbcom_custom_background_args', array(
			'default-color'	 => 'ffffff',
			'default-image'	 => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );

		// Add custom editor font sizes.
		add_theme_support( 'editor-font-sizes', array() );


		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		add_theme_support( 'woocommerce' );
	}

	add_action( 'after_setup_theme', 'reign_setup' );
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
if ( !function_exists( 'reign_content_width' ) ) {

	function reign_content_width() {
		$GLOBALS[ 'content_width' ] = apply_filters( 'wbcom_content_width', 640 );
	}

	add_action( 'after_setup_theme', 'reign_content_width', 0 );
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
if ( !function_exists( 'reign_widgets_init' ) ) {

	function reign_widgets_init() {
		register_sidebar( array(
			'name'			 => esc_html__( 'Right Sidebar', 'reign' ),
			'id'			 => 'sidebar-right',
			'description'	 => esc_html__( 'Widgets in this area are used in the right sidebar region.', 'reign' ),
			'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</section>',
			'before_title'	 => '<h2 class="widget-title"><span>',
			'after_title'	 => '</span></h2>',
		) );

		register_sidebar( array(
			'name'			 => esc_html__( 'Left Sidebar', 'reign' ),
			'id'			 => 'sidebar-left',
			'description'	 => esc_html__( 'Widgets in this area are used in the left sidebar region.', 'reign' ),
			'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
			'after_widget'	 => '</section>',
			'before_title'	 => '<h2 class="widget-title"><span>',
			'after_title'	 => '</span></h2>',
		) );

		if ( class_exists( 'WooCommerce' ) ) {
			register_sidebar( array(
				'name'			 => esc_html__( 'WooCommerce Right Sidebar', 'reign' ),
				'id'			 => 'woocommerce-sidebar-right',
				'description'	 => esc_html__( 'Widgets in this area are used in the woocommerce right sidebar region.', 'reign' ),
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );

			register_sidebar( array(
				'name'			 => esc_html__( 'WooCommerce Left Sidebar', 'reign' ),
				'id'			 => 'woocommerce-sidebar-left',
				'description'	 => esc_html__( 'Widgets in this area are used in the woocommerce left sidebar region.', 'reign' ),
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );
		}

		/* Dedicated widget support for BuddyPress */
		if ( class_exists( 'BuddyPress' ) ) {
			register_sidebar( array(
				'name'			 => esc_html__( 'Groups Index', 'reign' ),
				'id'			 => 'group-index',
				'description'	 => esc_html__( 'Add widgets here.', 'reign' ),
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );

			register_sidebar( array(
				'name'			 => esc_html__( 'Member Index', 'reign' ),
				'id'			 => 'member-index',
				'description'	 => esc_html__( 'Add widgets here.', 'reign' ),
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );

			register_sidebar( array(
				'name'			 => esc_html__( 'Activity Index', 'reign' ),
				'id'			 => 'activity-index',
				'description'	 => esc_html__( 'Add widgets here.', 'reign' ),
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );

			register_sidebar( array(
				'name'			 => esc_html__( 'Group Single', 'reign' ),
				'id'			 => 'group-single',
				'description'	 => esc_html__( 'Add widgets here.', 'reign' ),
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );

			register_sidebar( array(
				'name'			 => esc_html__( 'Member Profile', 'reign' ),
				'id'			 => 'member-profile',
				'description'	 => esc_html__( 'Add widgets here.', 'reign' ),
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );
		}

		/* Dedicated widget support for EDD */
		if ( class_exists( 'Easy_Digital_Downloads' ) ) {

			register_sidebar( array(
				'name'			 => esc_html__( 'Download Archive Sidebar', 'reign' ),
				'id'			 => 'edd-download-archive-sidebar',
				'description'	 => esc_html__( 'Widgets in this area are used in the EDD download archive page.', 'reign' ),
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );

			register_sidebar( array(
				'name'			 => esc_html__( 'Single Download Sidebar', 'reign' ),
				'id'			 => 'edd-single-download-sidebar',
				'description'	 => esc_html__( 'Widgets in this area are used in the EDD single download page.', 'reign' ),
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );
		}

		// if ( !defined( 'WBCOM_ELEMENTOR_ADDONS_VERSION' ) ) {
		// }
		register_sidebar( array(
			'name'			 => esc_html__( 'Footer Widget Area', 'reign' ),
			'id'			 => 'footer-widget-area',
			'description'	 => esc_html__( 'Add widgets here.', 'reign' ),
			'before_widget'	 => '<section id="%1$s" class="widget %2$s wb-grid-cell">',
			'after_widget'	 => '</section>',
			'before_title'	 => '<h2 class="widget-title"><span>',
			'after_title'	 => '</span></h2>',
		) );

		// For PeepSo notification icons.
		if ( class_exists( 'PeepSo' ) ) {
			register_sidebar( array(
				'name'			 => esc_html__( 'Header Widget Area', 'reign' ),
				'id'			 => 'reign-header-widget-area',
				'before_widget'	 => '<section id="%1$s" class="widget %2$s">',
				'after_widget'	 => '</section>',
				'before_title'	 => '<h2 class="widget-title"><span>',
				'after_title'	 => '</span></h2>',
			) );
		}
	}

	add_action( 'widgets_init', 'reign_widgets_init' );
}

/**
 * Enqueue scripts and styles.
 */
if ( !function_exists( 'reign_scripts' ) ) {

	function reign_scripts() {

		$css_path = is_rtl() ? '/assets/css/rtl' : '/assets/css';

		// Styles
		global $wbtm_reign_settings;
		wp_deregister_style( 'font-awesome' );
		wp_deregister_style( 'font-awesome-shims' );
		
		
		if ( function_exists( 'bp_get_theme_package_id' ) ) {
			$theme_package_id = bp_get_theme_package_id();
		} else {
			$theme_package_id = 'legacy';
		}
		if ( 'nouveau' === $theme_package_id ) {
			wp_enqueue_style( 'reign_style', get_template_directory_uri() . $css_path . '/nouveau-main.css', '', time() );
		} else {
			wp_enqueue_style( 'reign_style', get_template_directory_uri() . $css_path . '/main.css', '', time() );
		}

		// Font Awesome Files Enqueue
		$font_awesome_version = get_theme_mod( 'reign_font_awesome' );
		if ( $font_awesome_version === 'option-1' ) {
			wp_enqueue_style( 'font-awesome-4', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome-4.7.css', '', time() );
		} elseif ( $font_awesome_version === 'option-2' ) {
			wp_enqueue_style( 'font-awesome-5', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome-5.css', '', time() );
		} else {
			wp_enqueue_style( 'font-awesome-both', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome-both.css', '', time() );
		}

		// Scripts
		$activity_post_popup = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'activity_popup_checkbox' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'activity_popup_checkbox' ] : 'off';

		//wp_enqueue_script( 'wp-main-slidebars', get_template_directory_uri() . '/assets/js/vendors/slidebars.js', array(), time(), true );

		wp_enqueue_script( 'wp-main', get_template_directory_uri() . '/assets/js/main.min.js', array( 'jquery' ), time(), true );

		//Add More Header Script
		$more_menu_enable = get_theme_mod( 'reign_header_main_menu_more_enable', false );

		//Elementor topbar check
		$reign_header_topbar_type = get_theme_mod( 'reign_header_topbar_type', false );

		//Check if topbar is disabled in mobile view
		$reign_header_topbar_mobile_view_disable = get_theme_mod( 'reign_header_topbar_mobile_view_disable', false );

		$rtl = false;
		if ( is_rtl() ) {
			$rtl = true;
		}

		$single_activity_page = false;
		
		if( function_exists('bp_is_single_activity') && bp_is_single_activity() ) {
			$single_activity_page = true;
		}
		$append_text    = apply_filters( 'bp_activity_excerpt_append_text', __( '[Read more]', 'buddypress' ) );
		if( function_exists('bp_activity_get_excerpt_length') ){
			$excerpt_length = bp_activity_get_excerpt_length();
		}else{
			$excerpt_length = 200;
		}
		
		if ( function_exists( 'bp_get_theme_package_id' ) ) {
			$theme_package_id = bp_get_theme_package_id();
		} else {
			$theme_package_id = 'legacy';
		}
		wp_localize_script(
		'wp-main', 'wp_main_js_obj', array(
			'reign_more_menu_enable' => $more_menu_enable,
			'reign_ele_topbar'		 => $reign_header_topbar_type,
			'logged_in'				 => is_user_logged_in(),
			'topbar_mobile_disabled' => $reign_header_topbar_mobile_view_disable,
			'reign_rtl'				 => $rtl,
			'single_activity_page'   => $single_activity_page,
			'append_text'			 => $append_text,
			'excerpt_length'		 => $excerpt_length,
			'theme_package_id'	     => $theme_package_id
		)
		);

		wp_localize_script(
		'wp-main', 'wp_main_param', array(
			'activity_post_popup' => $activity_post_popup
		)
		);

		/* localize sccipt to check if toggle panel first is enabled */
		$display = shiftnav_op( 'display_at_desktop', 'shiftnav-main' );
		if ( $display == '1' ) {
			$desk_mode = true;
		} else {
			$desk_mode = false;
		}
		wp_localize_script(
		'wp-main', 'wp_dsktop_toggle', array(
			'desk_mode' => $desk_mode
		)
		);

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Adds UI scripts.
		if ( !is_admin() ) {
			if ( function_exists( 'bp_is_activity_heartbeat_active' ) && bp_is_activity_heartbeat_active() ) {
				//Heartbeat
				wp_enqueue_script( 'heartbeat' );
			}
		}
	}
	add_action( 'wp_enqueue_scripts', 'reign_scripts',5001 );
}

// if ( !function_exists( 'wbcom_header_masthead' ) ) {
// 	/**
// 	 * Site Header
// 	 */
// 	function wbcom_header_masthead() {
// 		get_template_part( 'template-parts/masthead', apply_filters( 'wbcom_masthead_type', '' ) );
// 	}
// 	add_action( 'wbcom_masthead', 'wbcom_header_masthead' );
// }

if ( !function_exists( 'register_reign_menu_page' ) ) {

	/**
	 * Register Reign Menu Page
	 */
	function register_reign_menu_page() {
		// Set position with odd number to avoid confict with other plugin/theme.
		add_menu_page( __( 'Reign Settings', 'reign' ), __( 'Reign Settings', 'reign' ), 'manage_options', 'reign-settings', '', '', 61.000329 );

		// To remove empty parent menu item.
		add_submenu_page( 'reign-settings', __( 'Reign Settings', 'reign' ), __( 'Reign Settings', 'reign' ), 'manage_options', 'reign-settings' );
		remove_submenu_page( 'reign-settings', 'reign-settings' );
	}

	add_action( 'admin_menu', 'register_reign_menu_page' );
}

/**
 * Remove Secondary Group Icon
 */
function my_remove_secondary_avatars( $bp_legacy ) {
	remove_filter( 'bp_get_activity_action_pre_meta', array( $bp_legacy, 'secondary_avatars' ), 10, 2 );
}

add_action( 'bp_theme_compat_actions', 'my_remove_secondary_avatars' );

/**
 * Heartbeat settings
 */
function reign_heartbeat_settings( $settings ) {
	$settings[ 'interval' ] = 5;
	return $settings;
}

add_filter( 'heartbeat_settings', 'reign_heartbeat_settings' );

/**
 * Sending a heartbeat for notification updates
 */
function reign_notification_count_heartbeat( $response, $data, $screen_id ) {
	$notifications = array();

	if ( function_exists( "bp_friend_get_total_requests_count" ) )
		$friend_request_count	 = bp_friend_get_total_requests_count();
	if ( function_exists( "bp_notifications_get_all_notifications_for_user" ) )
		$notifications			 = bp_notifications_get_all_notifications_for_user( get_current_user_id() );

	$notification_count = count( $notifications );

	if ( function_exists( "bp_notifications_get_all_notifications_for_user" ) ) {
		$notifications			 = bp_notifications_get_notifications_for_user( bp_loggedin_user_id() );
		$notification_content	 = array();
		if ( !empty( $notifications ) ) {
			foreach ( (array) $notifications as $notification ) {
				if ( is_array( $notification ) ) {
					if ( isset( $notification[ 'link' ] ) && isset( $notification[ 'text' ] ) ) {
						$notification_content[] = "<a href='" . esc_url( $notification[ 'link' ] ) . "'>{$notification[ 'text' ]}</a>";
					}
				} else {
					$notification_content[] = $notification;
				}
			}
		}

		if ( empty( $notification_content ) )
			$notification_content[] = '<a href="' . bp_loggedin_user_domain() . '' . BP_NOTIFICATIONS_SLUG . '/">' . __( "No new notifications", "buddypress" ) . '</a>';
	}
	if ( function_exists( "messages_get_unread_count" ) )
		$unread_message_count = messages_get_unread_count();

	$response[ 'reign_notification_count' ] = array(
		'friend_request'		 => @intval( $friend_request_count ),
		'notification'			 => @intval( $notification_count ),
		'notification_content'	 => @$notification_content,
		'unread_message'		 => @intval( $unread_message_count )
	);

	return $response;
}

// Logged in users:
add_filter( 'heartbeat_received', 'reign_notification_count_heartbeat', 10, 3 );

/**
 * Set the global variable for activated color scheme.
 *
 *
 * @global string $color_scheme
 */
if ( !function_exists( 'reign_color_scheme' ) ) {

	function reign_color_scheme() {
		$GLOBALS[ 'rtm_color_scheme' ] = get_theme_mod( 'reign_color_scheme', 'reign_default' );
		$theme_mods = $mods = get_theme_mods();
		
		if ( isset($mods[0]) && $mods[0] == '' ) {
			unset($mods[0]);
		}
		
		$flg = true;
		if ( empty($mods) ) {
			$stylesheet = get_option( 'stylesheet' );
			$theme_mod = 'theme_mods_' . $stylesheet;
			$theme_mods['reign_color_scheme'] = 'reign_clean';
			update_option( $theme_mod, $theme_mods );
			$flg = false;
		}
		
		/* Update Color scheme with new version */
		$update_reign_theme = get_option( 'update_reign_theme' );
		if ( !empty($mods) && !$update_reign_theme && $flg == true ) {
			$reign_color_scheme = ( isset($theme_mods['reign_color_scheme']) && $theme_mods['reign_color_scheme'] != '' ) ? $theme_mods['reign_color_scheme']  : 'reign_default';
			
			$update_theme_mode_colors = array(  'reign_header_topbar_bg_color',
												'reign_header_topbar_text_color',
												'reign_header_topbar_text_hover_color',
												'reign_header_bg_color',
												'reign_header_main_menu_text_hover_color',
												'reign_header_main_menu_text_active_color',
												'reign_header_main_menu_bg_hover_color',
												'reign_header_main_menu_bg_active_color',
												'reign_header_sub_menu_bg_color',												
												'reign_header_sub_menu_text_hover_color',
												'reign_header_sub_menu_bg_hover_color',
												'reign_header_icon_color',
												'reign_header_icon_hover_color',
												'reign_footer_widget_area_bg_color',
												'reign_footer_widget_title_color',
												'reign_footer_widget_text_color',
												'reign_footer_widget_link_color',
												'reign_footer_widget_link_hover_color',
												'reign_footer_copyright_bg_color',
												'reign_footer_copyright_text_color',
												'reign_footer_copyright_link_color',
												'reign_footer_copyright_link_hover_color'
										);
										
			foreach( $update_theme_mode_colors as $colors ) {
				/* Reign reign_header_topbar_bg_color Update with new version */				
				if (isset($theme_mods[$colors] ) && $theme_mods[$colors] != '' && !isset($theme_mods[ $reign_color_scheme . '-' . $colors ]) ) {
					$theme_mods[ $reign_color_scheme . '-' . $colors ] = $theme_mods[$colors];
				}
			}
			$update_theme_mode_colors_garoup = array( 'reign_title_tagline_typography',
													'reign_header_main_menu_font',
													'reign_header_sub_menu_font',
												);
			foreach( $update_theme_mode_colors_garoup as $colors ) {
				/* Reign reign_header_topbar_bg_color Update with new version */				
				if (isset($theme_mods[$colors]['color'] ) && $theme_mods[$colors]['color'] != '' && !isset($theme_mods[ $reign_color_scheme . '-' . $colors ]) ) {
					$theme_mods[ $reign_color_scheme . '-' . $colors ] = $theme_mods[$colors]['color'];
				}
			}
			$theme_mods['reign_color_scheme'] = $reign_color_scheme;
			$stylesheet = get_option( 'stylesheet' );
			$theme_mod = 'theme_mods_' . $stylesheet;			
			update_option( $theme_mod, $theme_mods );			
			update_option( 'update_reign_theme', true );
		}
	}

	add_action( 'after_setup_theme', 'reign_color_scheme' );
}



add_filter( 'reign_alter_display_right_sidebar', 'reign_alter_display_right_sidebar_for_woo', 10, 1 );

/**
 *
 * Function to hide right sideabr at woocommerce cart and shop page.
 *
 * @since 2.6.0
 */
function reign_alter_display_right_sidebar_for_woo( $display ) {
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_cart() || is_checkout() ) {
			$display = false;
		}
	}
	return $display;
}

// add_action( 'woocommerce_before_cart', 'reign_display_breadcrumb_at_checkout' );
// add_action( 'woocommerce_before_checkout_form', 'reign_display_breadcrumb_at_checkout' );
add_action( 'rtm_post_begins', 'reign_display_breadcrumb_at_checkout' );

/**
 *
 * Function to display breadcrumb at woocommerce cart and checkout page.
 *
 * @since 2.6.0
 */
function reign_display_breadcrumb_at_checkout() {
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_cart() || is_checkout() || is_wc_endpoint_url( 'order-received' ) ) {
			?>
			<div class="rg-woo-breadcrumbs-wrapper page-title">
				<nav class="rg-woo-breadcrumbs breadcrumbs heading-font checkout-breadcrumbs h3">
					<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="<?php echo esc_attr( reign_woo_checkout_breadcrumb_class( 'cart' ) ); ?>"><?php _e( 'Shopping Cart', 'reign' ); ?></a>
					<span class="divider hide-for-small"><i class="fa fa-angle-right"></i></span>
					<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="<?php echo esc_attr( reign_woo_checkout_breadcrumb_class( 'checkout' ) ); ?>"><?php _e( 'Checkout details', 'reign' ); ?></a>
					<span class="divider hide-for-small"><i class="fa fa-angle-right"></i></span>
					<a href="#" class="no-click <?php echo esc_attr( reign_woo_checkout_breadcrumb_class( 'order-received' ) ); ?>"><?php esc_html_e( 'Order Complete', 'reign' ); ?></a>
				</nav>
			</div><!-- .page-title -->
			<?php
		}
	}
}

function reign_woo_checkout_breadcrumb_class( $endpoint ) {
	$classes = array();
	if ( $endpoint == 'cart' && is_cart() || $endpoint == 'checkout' && is_checkout() && !is_wc_endpoint_url( 'order-received' ) ||
	$endpoint == 'order-received' && is_wc_endpoint_url( 'order-received' ) ) {
		$classes[] = 'current';
	} else {
		$classes[] = 'hide-for-small';
	}
	return implode( ' ', $classes );
}

add_action( 'woocommerce_before_account_navigation', 'reign_woo_my_account_avatar' );

function reign_woo_my_account_avatar() {
	?>
	<div class="rg-woo-account-user circle">
		<span class="image">
			<?php
			$current_user	 = wp_get_current_user();
			$user_id		 = $current_user->ID;
			echo get_avatar( $user_id, 70 );
			?>
		</span>
		<span class="user-name">
			<?php
			echo $current_user->display_name;
			?>
		</span>
	</div>
	<?php
}

add_filter( 'body_class', 'reign_header_v4_body_class', 10, 1 );

/**
 *
 * Function to add body class when reign header v4 is active.
 *
 */
function reign_header_v4_body_class( $classes ) {
	$reign_header_header_type = get_theme_mod( 'reign_header_header_type', false );
	if ( !$reign_header_header_type ) {
		$header_version = get_theme_mod( 'reign_header_layout', 'v2' );
		if ( 'v4' === $header_version ) {
			$classes[] = 'reign-header-v4';
		}
	}
	if ( !is_user_logged_in() ) {
		$classes[] = 'logged-out';
	}
	return $classes;
}

add_action( 'bp_init', 'reign_restrict_hearbeat_for_bp' );

/**
 *
 * Function to restrict heartbeat request being send on non required bp pages.
 *
 */
function reign_restrict_hearbeat_for_bp() {
	if ( bp_is_activity_component() && !bp_is_activity_directory() ) {
		remove_filter( 'heartbeat_received', 'bp_activity_heartbeat_last_recorded', 10, 2 );
		remove_filter( 'heartbeat_nopriv_received', 'bp_activity_heartbeat_last_recorded', 10, 2 );
	}
}

/**
 *
 * Function to remove read more text when rtmedia images present inside activity content.
 *
 */
add_filter( 'bp_activity_maybe_truncate_entry', 'reign_bp_activity_maybe_truncate_entry', 10, 1 );

function reign_bp_activity_maybe_truncate_entry( $bool ) {
	global $wpdb;
	$activity_id = bp_get_activity_id();
	$table_name	 = $wpdb->prefix . 'rt_rtm_media';
	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) == $table_name ) {
		$result = $wpdb->get_var( $wpdb->prepare( "SELECT activity_id FROM {$wpdb->prefix}rt_rtm_media WHERE activity_id = %d", $activity_id ) );
		if ( $result != NULL ) {
			$bool = false;
		}
	}
	return $bool;
}

if ( class_exists( 'RTMediaActivity' ) ) {

	class reign_RTMediaActivity extends RTMediaActivity {

		var $media			 = array();
		var $activity_text	 = '';
		var $privacy;

		/**
		 * @param $media
		 * @param int $privacy
		 * @param bool $activity_text
		 */
		function __construct( $media, $privacy = 0, $activity_text = false ) {
			if ( !isset( $media ) ) {
				return false;
			}
			if ( !is_array( $media ) ) {
				$media = array( $media );
			}

			$this->media		 = $media;
			$this->activity_text = bp_activity_filter_kses( $activity_text );
			$this->privacy		 = $privacy;
		}

		function create_activity_html( $type = 'activity' ) {
			$activity_container_start	 = sprintf( '<div class="rtmedia-%s-container">', esc_attr( $type ) );
			$activity_container_end		 = '</div>';

			$activity_text = '';

			// Activity text content markup.
			if ( !empty( $this->activity_text ) && '&nbsp;' !== $this->activity_text ) {
				$activity_text .= sprintf(
				'<div class="rtmedia-%s-text">
					<span>%s</span>
					</div>', esc_attr( $type ), $this->activity_text
				);
			}

			global $rtmedia;
			if ( isset( $rtmedia->options[ 'buddypress_limitOnActivity' ] ) ) {
				$limit_activity_feed = $rtmedia->options[ 'buddypress_limitOnActivity' ];
			} else {
				$limit_activity_feed = 0;
			}

			$rtmedia_model	 = new RTMediaModel();
			$media_details	 = $rtmedia_model->get( array( 'id' => $this->media ) );

			if ( intval( $limit_activity_feed ) > 0 ) {
				$media_details = array_slice( $media_details, 0, $limit_activity_feed, true );
			}
			$rtmedia_activity_ul_class = apply_filters( 'rtmedia_' . $type . '_ul_class', 'rtm-activity-media-list' );

			$media_content	 = '';
			$count			 = 0;
			$count_attr		 = count( $media_details );

			foreach ( $media_details as $media ) {
				$add_class			 = '';
				$remain_count_span	 = '';
				if ( 4 == $count && $count_attr > 5 ) {
					$add_class			 = 'rtm-media-plus4';
					$remain_count		 = $count_attr - 5;
					$remain_count_span	 = '<div class="rtmedia-remain-count">+' . $remain_count . '</div>';
				}

				if ( $count > 4 ) {
					$add_class = 'rtm-media-after4';
				}

				$media_content .= sprintf( '<li class="rtmedia-list-item media-type-%1s %2s">', esc_attr( $media->media_type ), esc_attr( $add_class ) );

				if ( 'photo' === $media->media_type ) {
					// Markup for photo media type with anchor tag only on image.
					$media_content .= sprintf(
					'<a href ="%s">
						<div class="rtmedia-item-thumbnail">
						%s
						</div>
						<div class="rtmedia-item-title">
						<h4 title="%s">
						%s
						</h4>
						</div>
						%s
						</a>', esc_url( get_rtmedia_permalink( $media->id ) ), $this->media( $media ), esc_attr( $media->media_title ), $media->media_title, $remain_count_span
					);
				} elseif ( 'music' === $media->media_type || 'video' === $media->media_type ) {
					// Markup for audio and video media type with link only on media (title).
					$media_content .= sprintf(
					'<div class="rtmedia-item-thumbnail">
						%s
						</div>
						<div class="rtmedia-item-title">
						<h4 title="%s">
						<a href="%s">
						%s
						</a>
						</h4>
						</div>', $this->media( $media ), esc_attr( $media->media_title ), esc_url( get_rtmedia_permalink( $media->id ) ), esc_html( $media->media_title )
					);
				} else {
					// Markup for all the other media linke docs and other files where anchor tag the markup is comming from add-on itself.
					$media_content .= sprintf(
					'<div class="rtmedia-item-thumbnail">
						%s
						</div>
						<div class="rtmedia-item-title">
						<h4 title="%s">
						%s
						</h4>
						</div>', $this->media( $media ), esc_attr( $media->media_title ), esc_html( $media->media_title )
					);
				}

				$media_content .= '</li>';
				$count ++;
			}

			$media_container_start_class = 'rtmedia-list';
			if ( 'activity' !== $type ) {
				$media_container_start_class = sprintf( 'rtmedia-%s-list', $type );
			}

			$media_container_start = sprintf(
			'<ul class="%s %s rtmedia-activity-media-length-%s">', esc_attr( $media_container_start_class ), esc_attr( $rtmedia_activity_ul_class ), esc_attr( $count )
			);

			$media_container_end = '</ul>';

			$media_list	 = $media_container_start;
			$media_list	 .= $media_content;
			$media_list	 .= $media_container_end;

			/**
			 * Filters the output of the activity contents before save.
			 *
			 * @param string $activity_content Concatination of $activity_text and $media_list.
			 * @param string $activity_text    HTML markup of activity text.
			 * @param string $media_list       HTML markup of media in ul.
			 */
			$activity_content = apply_filters( 'rtmedia_activity_content_html', $media_list . $activity_text, $activity_text, $media_list );

			$activity	 = $activity_container_start;
			$activity	 .= $activity_content;
			$activity	 .= $activity_container_end;

			// Bypass comment links limit.
			add_filter(
			'option_comment_max_links', function ( $values ) {
				$rtmedia_attached_files = filter_input( INPUT_POST, 'rtMedia_attached_files', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
				// Check  if files available.
				if ( is_array( $rtmedia_attached_files ) && !empty( $rtmedia_attached_files[ 0 ] ) ) {
					// One url of image and other for anchor tag.
					$values = count( $rtmedia_attached_files ) * 3;
				}
				return $values;
			}
			);

			return bp_activity_filter_kses( $activity );
		}

	}

}

//add_action('init', 'reign_wp_media_remove');
function reign_wp_media_remove() {
	remove_filter( 'wp_update_attachment_metadata', 'rtmedia_edit_media_on_database', 10 );
}

//add_filter( 'wp_update_attachment_metadata', 'reign_rtmedia_edit_media_on_database', 10, 2);
global $rtmedia_buddypress_activity;
remove_filter( 'bp_activity_content_before_save', array( $rtmedia_buddypress_activity, 'bp_activity_content_before_save' ) );
add_filter( 'bp_activity_content_before_save', 'rtm_rtmedia_bp_activity_content_before_save' );

/**
 * This function will check for the media file attached to the activity and accordingly will set content.
 *
 * @param string $content Content of the Activity.
 *
 * @return string Filtered value of the activity content.
 */
function rtm_rtmedia_bp_activity_content_before_save( $content ) {

	$rtmedia_attached_files = filter_input( INPUT_POST, 'rtMedia_attached_files', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	if ( (!empty( $rtmedia_attached_files ) ) && is_array( $rtmedia_attached_files ) ) {
		$obj_activity = new reign_RTMediaActivity( $rtmedia_attached_files, 0, $content );

		// Remove action to fix duplication issue of comment content.
		remove_action( 'bp_activity_content_before_save', 'rtmedia_bp_activity_comment_content_callback', 1001, 1 );

		$content = $obj_activity->create_activity_html();
	}
	return $content;
}

/**
 * [rtmedia_edit_media_on_database]
 * Update Media details on database while admin edit reported media
 * @param  [Array]  $data	     Image Details
 * @param  [Number] $post_ID     Media ID
 * @return [array]  $data
 */
function reign_rtmedia_edit_media_on_database( $data, $post_ID ) {

	$post = get_post( $post_ID );

	if ( $_REQUEST ) {

		// @todo need to check why 'context' key is not set in $_REQUEST when user clicks on scale button on edit image.
		if ( isset( $_REQUEST[ 'postid' ] ) && 'image-editor' == $_REQUEST[ 'action' ] && !empty( $_REQUEST[ 'context' ] ) && 'edit-attachment' == $_REQUEST[ 'context' ] ) {

			$media			 = new RTMediaModel();
			$media_available = $media->get_media( array(
				'media_id' => $_REQUEST[ 'postid' ],
			), 0, 1 );

			$media_id = $media_available[ 0 ]->id;

			if ( !empty( $media_available ) ) {
				$rtmedia_filepath_old = rtmedia_image( 'rt_media_activity_image', $media_id, false );

				if ( isset( $rtmedia_filepath_old ) ) {
					$is_valid_url = preg_match( "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $rtmedia_filepath_old );

					if ( $is_valid_url && function_exists( 'bp_is_active' ) && bp_is_active( 'activity' ) ) {
						$thumbnailinfo	 = wp_get_attachment_image_src( $post_ID, 'rt_media_activity_image' );
						$activity_id	 = rtmedia_activity_id( $media_id );

						if ( $post_ID && !empty( $activity_id ) ) {
							global $wpdb, $bp;

							if ( !empty( $bp->activity ) ) {
								$media->model		 = new RTMediaModel();
								$related_media_data	 = $media->model->get( array( 'activity_id' => $activity_id ) );
								$related_media		 = array();
								foreach ( $related_media_data as $activity_media ) {
									$related_media[] = $activity_media->id;
								}
								$activity_text = bp_activity_get_meta( $activity_id, 'bp_activity_text' );

								$activity = new reign_RTMediaActivity( $related_media, 0, $activity_text );

								$activity_content_new = $activity->create_activity_html();

								$activity_content = str_replace( $rtmedia_filepath_old, wp_get_attachment_url( $post_ID ), $activity_content_new );

								$wpdb->update( $bp->activity->table_name, array( 'content' => $activity_content ), array( 'id' => $activity_id ) );
							}
						}
					}
				}
			}
		}
	}

	return $data;
}
