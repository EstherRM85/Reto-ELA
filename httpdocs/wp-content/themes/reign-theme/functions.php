<?php

class REIGN_Theme_Class {

	/**
	 * Main Theme Class Constructor
	 *
	 * @since   1.0.0
	 */
	public function __construct() {
		// Define constants
		add_action( 'after_setup_theme', array( 'REIGN_Theme_Class', 'constants' ), 0 );

		// Load all core theme function files
		add_action( 'after_setup_theme', array( 'REIGN_Theme_Class', 'include_functions' ), 1 );

		/**
		 * Manage bp-nouveau support.
		 */
		add_filter( 'bp_get_template_stack', array( 'REIGN_Theme_Class', 'reign_bp_get_template_stack' ), 10, 1 );
		add_filter( 'rtmedia_media_template_include', array( 'REIGN_Theme_Class', 'nouveau_rtmedia_media_template_include' ), 10, 1 );
		add_filter( 'bp_nouveau_get_loop_classes', array( 'REIGN_Theme_Class', 'reign_bp_nouveau_get_loop_classes' ), 10, 2 );
		add_filter( 'bp_nouveau_get_loop_classes', array( 'REIGN_Theme_Class', 'bpgt_filter_nouveau_get_loop_classes' ), 10, 2 );

		/* Manage bp followers following member page layout */
		add_filter( 'bp_nouveau_get_loop_classes', array( 'REIGN_Theme_Class', 'reign_bp_nouveau_follow_get_loop_classes' ), 10, 2 );

		add_filter( 'bp_nouveau_avatar_args', array( 'REIGN_Theme_Class', 'reign_bp_nouveau_avatar_args_member_layout_1' ), 10, 1 );
		add_action( 'wp_enqueue_scripts', array( 'REIGN_Theme_Class', 'bp_nouveau_enqueue_script' ) );
		// SVG Support.
		add_filter( 'mime_types', array( $this, 'reign_theme_upload_mimes' ) );
	}

	public static function bp_nouveau_enqueue_script() {
		if ( function_exists( 'bp_get_theme_package_id' ) ) {
			$theme_package_id = bp_get_theme_package_id();
		} else {
			$theme_package_id = 'legacy';
		}
		if ( 'nouveau' === $theme_package_id ) {
			wp_register_script(
			'reign-nouveau-js', get_template_directory_uri() . '/assets/js/reign-nouveau.js', array( 'jquery' ), time(), true
			);
			wp_enqueue_script( 'reign-nouveau-js' );
		}
	}

	public static function reign_bp_nouveau_avatar_args_member_layout_1( $args ) {
		if ( bp_is_groups_directory() ) {
			$bp_nouveau_appearance = bp_get_option( 'bp_nouveau_appearance', array() );
			if ( !isset( $bp_nouveau_appearance[ 'groups_layout' ] ) ) {
				$bp_nouveau_appearance[ 'groups_layout' ] = 1;
			}
			if ( 1 !== $bp_nouveau_appearance[ 'groups_layout' ] ) {
				global $wbtm_reign_settings;
				$group_directory_type = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_directory_type' ] : 'wbtm-group-directory-type-2';
				if ( 'wbtm-group-directory-type-1' === $group_directory_type ) {
					$args[ 'width' ]	 = 300;
					$args[ 'height' ]	 = 300;
				}
			}
		}
		return $args;
	}

	public static function reign_bp_nouveau_get_loop_classes( $classes, $component ) {
		if ( bp_is_user() && 'requests' === bp_current_action() ) {
			$index = array_search( 'friends-list', $classes );
			if ( false !== $index ) {
				unset( $classes[ $index ] );
			}
			$index = array_search( 'friends-request-list', $classes );
			if ( false !== $index ) {
				unset( $classes[ $index ] );
			}

			$customizer_option	 = sprintf( '%s_layout', 'members_friends' );
			$layout_prefs		 = bp_nouveau_get_temporary_setting(
			$customizer_option, bp_nouveau_get_appearance_settings( $customizer_option )
			);
			if ( $layout_prefs && (int) $layout_prefs > 1 ) {
				$grid_classes = bp_nouveau_customizer_grid_choices( 'classes' );

				if ( isset( $grid_classes[ $layout_prefs ] ) ) {
					$classes = array_merge( $classes, array(
						'grid',
						$grid_classes[ $layout_prefs ],
					) );
				}
			}

			$classes = array_merge( $classes, array(
				'members-list',
				'rg-nouveau-friends-request-list',
			) );
		}

		return $classes;
	}

	public static function reign_bp_nouveau_follow_get_loop_classes( $classes, $component ) {

		global $bp;
		if ( class_exists( 'BP_Follow_Component' ) ) {
			if ( bp_is_current_component( $bp->follow->followers->slug ) || bp_is_current_component( $bp->follow->following->slug ) ) {


				$layout_prefs = bp_nouveau_get_temporary_setting(
				'members_layout', bp_nouveau_get_appearance_settings( 'members_layout' )
				);
				if ( $layout_prefs && (int) $layout_prefs > 1 ) {
					$grid_classes = bp_nouveau_customizer_grid_choices( 'classes' );

					if ( isset( $grid_classes[ $layout_prefs ] ) ) {
						$classes = array_merge( $classes, array(
							'grid',
							$grid_classes[ $layout_prefs ],
						) );
					}
				}

				$classes = array_merge( $classes, array(
					'members-list',
					'rg-nouveau-friends-request-list',
				) );
			}
		}
		return $classes;
	}

	public static function bpgt_filter_nouveau_get_loop_classes( $classes, $component ) {
		if ( 'groups' === $component ) {
			$layout_prefs = bp_nouveau_get_temporary_setting(
			'groups_layout', bp_nouveau_get_appearance_settings( 'groups_layout' )
			);
			if ( $layout_prefs && (int) $layout_prefs > 1 ) {
				$grid_classes = bp_nouveau_customizer_grid_choices( 'classes' );

				if ( isset( $grid_classes[ $layout_prefs ] ) ) {
					$classes = array_merge( $classes, array(
						'grid',
						$grid_classes[ $layout_prefs ],
					) );
				}
			}
		}

		return $classes;
	}

	public static function reign_bp_get_template_stack( $stack ) {
		if ( function_exists( 'bp_get_theme_package_id' ) ) {
			$theme_package_id = bp_get_theme_package_id();
		} else {
			$theme_package_id = 'legacy';
		}
		if ( 'nouveau' === $theme_package_id ) {
			$index = array_search( get_stylesheet_directory() . '/buddypress', $stack );
			if ( false !== $index ) {
				$stack[ $index ] = get_stylesheet_directory() . '/bp-nouveau';
			}
			$index = array_search( get_template_directory() . '/buddypress', $stack );
			if ( false !== $index ) {
				$stack[ $index ] = get_template_directory() . '/bp-nouveau';
			}
		}
		return $stack;
	}

	public static function nouveau_rtmedia_media_template_include( $template ) {
		if ( function_exists( 'bp_get_theme_package_id' ) ) {
			$theme_package_id = bp_get_theme_package_id();
		} else {
			$theme_package_id = 'legacy';
		}
		if ( 'nouveau' === $theme_package_id ) {
			$template = str_replace( '/rtmedia/main.php', '/rtmedia/main-nouveau.php', $template );
		}
		return $template;
	}

	/**
	 * Define Constants
	 *
	 * @since   1.0.0
	 */
	public static function constants() {

		$theme	 = wp_get_theme( get_template() );
		// Return theme version
		$version = $theme->get( 'Version' );

		// Core Constants
		define( 'REIGN_THEME_DIR', get_template_directory() );
		define( 'REIGN_THEME_URI', get_template_directory_uri() );

		if ( function_exists( 'bp_get_theme_package_id' ) ) {
			$theme_package_id = bp_get_theme_package_id();
		} else {
			$theme_package_id = 'legacy';
		}
		if ( 'legacy' === $theme_package_id ) {
			define( 'BP_AVATAR_THUMB_WIDTH', 150 );
			define( 'BP_AVATAR_THUMB_HEIGHT', 150 );
			define( 'BP_AVATAR_FULL_WIDTH', 300 );
			define( 'BP_AVATAR_FULL_HEIGHT', 300 );
		}

		// Theme version
		define( 'REIGN_THEME_VERSION', $version );

		// Javascript and CSS Paths
		define( 'REIGN_JS_DIR_URI', REIGN_THEME_URI . '/assets/js/' );
		define( 'REIGN_CSS_DIR_URI', REIGN_THEME_URI . '/assets/css/' );

		// Include Paths
		define( 'REIGN_INC_DIR', REIGN_THEME_DIR . '/inc/' );
		define( 'REIGN_INC_DIR_URI', REIGN_THEME_URI . '/inc/' );

		// Check if plugins are active
		define( 'REIGN_ELEMENTOR_ACTIVE', class_exists( 'Elementor\Plugin' ) );
		define( 'REIGN_BEAVER_BUILDER_ACTIVE', class_exists( 'FLBuilder' ) );
		define( 'REIGN_WOOCOMMERCE_ACTIVE', class_exists( 'WooCommerce' ) );

		$optionKey = "reign_theme_is_activated";
		if ( !get_option( $optionKey ) ) {

			$bp_nouveau_appearance = array(
				'members_layout'		 => 3,
				'members_friends_layout' => 2,
				'groups_layout'			 => 3,
				'members_group_layout'	 => 2,
				'group_front_page'		 => 0,
				'group_front_boxes'		 => 0,
				'user_front_page'		 => 0,
				'user_nav_display'		 => 1,
				'group_nav_display'		 => 1,
			);
			update_option( 'bp_nouveau_appearance', $bp_nouveau_appearance );
			update_option( $optionKey, 1 );
		}
	}

	/**
	 * Load all core theme function files
	 *
	 * @since 1.0.0
	 */
	public static function include_functions() {

		include_once( 'inc/theme-functions.php' );

		include_once( 'inc/class-reign-theme-structure.php' );
		/**
		 * Include the main plugin file of Kirki.
		 */
		include_once( 'lib/kirki/kirki.php' );
		/**
		 * Include the main plugin file of Kirki.
		 */
		include_once( 'lib/kirki-addon/kirki-addon.php' );


		/* Theme Core Setup */
		require_once(REIGN_INC_DIR . 'init.php');

		/* Theme Added Functionality Setup */
		require_once(REIGN_INC_DIR . 'extras.php');

		/* Support Added For Extra Plugins */
		require_once(REIGN_INC_DIR . 'extra-plugins-support.php');

		/* Custom Widgets */
		require(REIGN_THEME_DIR . '/widgets/active-members.php');
		require(REIGN_THEME_DIR . '/widgets/sidewide-activity-widget.php');
		require(REIGN_THEME_DIR . '/widgets/groups-widget.php');
		require(REIGN_THEME_DIR . '/widgets/latest-news.php');

		if ( class_exists( 'WooCommerce' ) ) {
			require(REIGN_THEME_DIR . '/widgets/woocommerce/class-reign-woo-widget-product-categories.php');
		}

		if ( class_exists( 'PeepSo' ) ) {
			require(REIGN_THEME_DIR . '/widgets/peepso/widgetuserbar.php');
		}

		/* Theme Required Plugins Manager Files */
		require_once(REIGN_INC_DIR . 'required-plugins/class-tgm-plugin-activation.php');
		require_once(REIGN_INC_DIR . 'required-plugins/required-plugins.php');

		/* Theme Options Panel In Admin Dashboard */
		require_once(REIGN_INC_DIR . 'reign-settings/reign-theme-options-manager.php');
		require_once(REIGN_INC_DIR . 'reign-settings/option-functions.php');


		/* Theme License And Update Management */
		require_once(REIGN_INC_DIR . 'edd-updater/theme-updater.php');
		require_once(REIGN_INC_DIR . 'reign-settings/class-reign-license-manager.php');

		/* Include Shortcodes file */
		require_once(REIGN_INC_DIR . 'shortcodes/shortcodes.php');

		/* Include postmeta management file */
		include_once REIGN_THEME_DIR . '/inc/wbcom-postmeta-mgmt/wbcom-postmeta-mgmt.php';

		if ( function_exists( 'bp_get_theme_package_id' ) ) {
			$theme_package_id = bp_get_theme_package_id();
		} else {
			$theme_package_id = 'legacy';
		}

		// if ( TRUE || class_exists( 'BuddyPress' ) && ( 'legacy' === $theme_package_id ) ) {

		/* Special Support To BuddyPress */
		require(REIGN_THEME_DIR . '/inc/buddypress/buddypress-funtions.php');

		/* Social links xprofile customization file */
		include_once REIGN_THEME_DIR . '/inc/buddypress/reign-social-links-xprofile.php';

		/* Header view xprofile customization file */
		include_once REIGN_THEME_DIR . '/inc/buddypress/reign-header-view-xprofile.php';

		/* Include buddypress members customization file */
		include_once REIGN_THEME_DIR . '/inc/buddypress/reign-bp-member-customization.php';

		/* Include buddypress groups customization file */
		include_once REIGN_THEME_DIR . '/inc/buddypress/reign-bp-group-customization.php';


		/* BP Profile Search Compatibility file */
		include_once REIGN_THEME_DIR . '/inc/buddypress/reign-bp-profile-search.php';
		// }

		/**
		 * Plugin specific support.
		 */
		if ( defined( 'PMPRO_VERSION' ) ) {
			include_once 'inc/plugins-support/class-rtm-pmpro-customization.php';
		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			include_once 'inc/plugins-support/class-rtm-edd-customization.php';
		}

		if ( class_exists( 'WooCommerce' ) ) {
			include_once 'inc/plugins-support/class-rtm-woocommerce-customization.php';
		}

		if ( class_exists( 'Mega_Menu' ) ) {
			include_once 'inc/plugins-support/class-rtm-mega-menu-customization.php';
		}

		include_once REIGN_THEME_DIR . '/mobile-menu/shiftnav.php';
	}

	/**
	 * Added support for svg.
	 */
	public function reign_theme_upload_mimes( $file_types ) {
		$file_types[ 'svg' ] = 'image/svg+xml';

		return $file_types;
	}

}

$reigntm_theme_class = new REIGN_Theme_Class;
