<?php
/**
 * Managing number of groups to show per page.
 * @since 1.0.2
 */
add_filter( 'bp_after_has_groups_parse_args', 'wbcom_theme_alter_groups_parse_args' );

function wbcom_theme_alter_groups_parse_args( $loop ) {
	if ( bp_is_groups_directory() ) {
		global $wbtm_reign_settings;
		if ( isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'groups_per_page' ] ) && !empty( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'groups_per_page' ] ) ) {
			$loop[ 'per_page' ] = intval( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'groups_per_page' ] );
		}
	}
	return $loop;
}

/**
 * Managing number of members to show per page
 * @since 1.0.2
 */
add_filter( 'bp_after_has_members_parse_args', 'wbcom_theme_alter_members_parse_args' );

function wbcom_theme_alter_members_parse_args( $loop ) {
	if ( bp_is_members_directory() ) {
		global $wbtm_reign_settings;
		if ( isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'members_per_page' ] ) && !empty( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'members_per_page' ] ) ) {
			$loop[ 'per_page' ] = intval( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'members_per_page' ] );
		}
	}
	return $loop;
}

/*
 * All the functions related to reign theme settings
 */

add_filter( 'body_class', function( $classes ) {
	if ( is_search() ) {
		return $classes;
	}
	global $wbtm_reign_settings;

	/* top bar support */
	global $wp_query;
	if ( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) {
		$post_id = get_option( 'page_for_posts' );
		$post	 = get_post( $post_id );
	} else {
		global $post;
	}

	if ( $post ) {
		$reign_ele_topbar = get_post_meta( $post->ID, 'reign_ele_topbar', true );
		if ( !empty( $reign_ele_topbar ) && ( $reign_ele_topbar != "-1" ) ) {
			$topbar_id = $reign_ele_topbar;
		} else {
			global $wbtm_reign_settings;
			$topbar_id = isset( $wbtm_reign_settings[ 'reign_pages' ][ 'global_ele_topbar' ] ) ? $wbtm_reign_settings[ 'reign_pages' ][ 'global_ele_topbar' ] : '0';
		}

		if ( !empty( $topbar_id ) && ( $topbar_id != "-1" ) ) {
			$classes = array_merge( $classes, array( 'rg-header-top-bar' ) );
		}
	}


	/* sticky header support */
	$header_design_type = isset( $wbtm_reign_settings[ 'reign_pages' ][ 'header_design_type' ] ) ? $wbtm_reign_settings[ 'reign_pages' ][ 'header_design_type' ] : 'full_width';
	if ( $header_design_type == 'sticky' ) {
		$classes = array_merge( $classes, array( 'rg-sticky-menu' ) );
	}

	/* boxed and fluid layout support */
	// $active_site_layout	 = isset( $wbtm_reign_settings[ 'reign_pages' ][ 'active_site_layout' ] ) ? $wbtm_reign_settings[ 'reign_pages' ][ 'active_site_layout' ] : 'full_width';
	$active_site_layout = get_theme_mod( 'reign_site_layout', 'full_width' );
	if ( $active_site_layout == 'box_width' ) {
		$classes = array_merge( $classes, array( 'rg-boxed-layout' ) );
	}

	/* fallback header support */
	if ( defined( 'WBCOM_ELEMENTOR_ADDONS_VERSION' ) ) {
		$classes[] = 'reign-manage-fallback-header';
	}

	/* content layout support added */
	if ( $post ) {
		$theme_slug			 = apply_filters( 'wbcom_essential_theme_slug', 'reign' );
		$wbcom_metabox_data	 = get_post_meta( $post->ID, $theme_slug . '_wbcom_metabox_data', true );
		$site_layout		 = isset( $wbcom_metabox_data[ 'layout' ][ 'site_layout' ] ) ? $wbcom_metabox_data[ 'layout' ][ 'site_layout' ] : '';
		if ( $site_layout ) {
			$classes[] = 'reign-' . $site_layout;
		}
	}

	/* top cover image support */

	if ( class_exists( 'BuddyPress' ) && ( bp_is_group() || bp_is_user() ) ) {
		$member_header_position	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] : 'inside';
		$member_header_position	 = apply_filters( 'wbtm_rth_manage_member_header_position', $member_header_position );
		$classes[]				 = 'reign-cover-image-' . $member_header_position;
	}

	/**
	 * Manage sidebar sticky or not.
	 *
	 * @since 2.0.2
	 */
	$reign_sticky_sidebar = get_theme_mod( 'reign_sticky_sidebar', true );
	if ( $reign_sticky_sidebar ) {
		$classes[] = 'reign-sticky-sidebar';
	}

	/**
	 * Manage body class when no sidebar.
	 *
	 * @since 2.0.2
	 */
	$reign_post_archive_layout = get_theme_mod( 'reign_post_archive_layout', '' );
	if ( 'full_width' === $reign_post_archive_layout ) {
		$classes[] = 'reign-no-sidebar-active';
	}

	/**
	 * Mobile view hide topbar support.
	 */
	$reign_header_topbar_mobile_view_disable = get_theme_mod( 'reign_header_topbar_mobile_view_disable', false );
	if ( $reign_header_topbar_mobile_view_disable ) {
		$classes[] = 'reign-topbar-hide-mobile';
	}

	$reign_header_sticky_menu_enable				 = get_theme_mod( 'reign_header_sticky_menu_enable', true );
	$reign_header_sticky_menu_custom_style_enable	 = get_theme_mod( 'reign_header_sticky_menu_custom_style_enable', false );
	$sticky_menu_logo								 = get_theme_mod( 'reign_sticky_header_menu_logo', '' );
	if ( $reign_header_sticky_menu_enable && $reign_header_sticky_menu_custom_style_enable && $sticky_menu_logo ) {
		$classes[] = 'reign-custom-sticky-logo';
	}


	if ( function_exists( 'bp_get_theme_package_id' ) ) {
		$theme_package_id = bp_get_theme_package_id();
	} else {
		$theme_package_id = 'legacy';
	}
	if ( 'nouveau' === $theme_package_id ) {
		$bp_nouveau_appearance = bp_get_option( 'bp_nouveau_appearance', array() );
		if ( !isset( $bp_nouveau_appearance[ 'avatar_style' ] ) ) {
			$bp_nouveau_appearance[ 'avatar_style' ] = 0;
		}
		if ( 1 === $bp_nouveau_appearance[ 'avatar_style' ] ) {
			$classes[] = 'round-avatars';
		}
	}

	return $classes;
} );

	/* enqueue custom code to head */
	add_action( 'wp_head', function() {
		// global $wbtm_reign_settings;
		// $reign_tracking_code = isset( $wbtm_reign_settings[ 'reign_pages' ][ 'reign_tracking_code' ] ) ? $wbtm_reign_settings[ 'reign_pages' ][ 'reign_tracking_code' ] : '';
		// $reign_tracking_code = stripslashes( $reign_tracking_code );
		// echo $reign_tracking_code;

		$reign_tracking_code = get_theme_mod( 'reign_tracking_code', '' );
		echo $reign_tracking_code;

		$reign_custom_js_header = get_theme_mod( 'reign_custom_js_header', '' );
		echo '<script type="text/javascript">' . $reign_custom_js_header . '</script>';
	}, 99 );

	add_action( 'wp_footer', function() {
		$reign_custom_js_footer = get_theme_mod( 'reign_custom_js_footer', '' );
		echo '<script type="text/javascript">' . $reign_custom_js_footer . '</script>';
	}, 99 );

	/* managing login and register url in frontend */
	add_filter( 'login_url', 'reign_alter_login_url_at_frontend', 10, 3 );

	function reign_alter_login_url_at_frontend( $login_url, $redirect, $force_reauth ) {
		if ( is_admin() ) {
			return $login_url;
		}

		$reign_login_page_id = get_theme_mod( 'reign_login_page', '0' );
		if ( $reign_login_page_id ) {
			$reign_login_page_url = get_permalink( $reign_login_page_id );
			if ( $reign_login_page_url ) {
				$login_url = $reign_login_page_url;
			}
		}
		return $login_url;
	}

	add_filter( 'register_url', 'reign_alter_register_url_at_frontend', 10, 1 );

	function reign_alter_register_url_at_frontend( $register_url ) {
		if ( is_admin() ) {
			return $register_url;
		}
		$reign_registration_page_id = get_theme_mod( 'reign_registration_page', '0' );
		if ( $reign_registration_page_id ) {
			$reign_registration_page_url = get_permalink( $reign_registration_page_id );
			if ( $reign_registration_page_url ) {
				$register_url = $reign_registration_page_url;
			}
		}
		return $register_url;
	}

	/**
	 *  Redirect to selected login page from options.
	 */
	function reign_redirect_login_page() {

		/* removing conflict with logout url */
		if ( isset( $_GET[ 'action' ] ) && ( $_GET[ 'action' ] == 'logout' ) ) {
			return;
		}

		global $wbtm_reign_settings;
		$login_page_id		 = $wbtm_reign_settings[ 'reign_pages' ][ 'reign_login_page' ];
		$register_page_id	 = $wbtm_reign_settings[ 'reign_pages' ][ 'reign_register_page' ];

		$login_page		 = get_permalink( $login_page_id );
		$register_page	 = get_permalink( $register_page_id );
		$page_viewed_url = basename( $_SERVER[ 'REQUEST_URI' ] );
		$exploded_Url	 = wp_parse_url( $page_viewed_url );

		if ( !isset( $exploded_Url[ 'path' ] ) ) {
			return;
		}

		//For register page
		if ( $register_page && 'wp-login.php' == $exploded_Url[ 'path' ] && 'action=register' == $exploded_Url[ 'query' ] && $_SERVER[ 'REQUEST_METHOD' ] == 'GET' ) {
			wp_redirect( $register_page );
			exit;
		}

		//For login page
		if ( $login_page && $exploded_Url[ 'path' ] == "wp-login.php" && $_SERVER[ 'REQUEST_METHOD' ] == 'GET' ) {
			wp_redirect( $login_page );
			exit;
		}
	}

//add_action( 'init', 'reign_redirect_login_page', 12 );

	/**
	 * Add 404 page redirect
	 */
	function reign_404_redirect() {

		//media popup fix
		if ( strpos( $_SERVER[ 'REQUEST_URI' ], "media" ) !== false ) {
			return;
		}
		//media upload fix
		if ( strpos( $_SERVER[ 'REQUEST_URI' ], "upload" ) !== false ) {
			return;
		}

		if ( !is_404() ) {
			return;
		}

		$reign_404_page_id = get_theme_mod( 'reign_404_page', '0' );
		if ( $reign_404_page_id ) {
			$reign_404_page_url = get_permalink( $reign_404_page_id );
			wp_redirect( $reign_404_page_url );
			exit;
		}
	}

	add_action( 'template_redirect', 'reign_404_redirect' );

	/**
	 * Sets BuddyPress defines. The BP_ prefix are from internal BuddyPress defines.
	 *
	 * BuddyPress defines hooked to init before BP is loaded
	 *
	 * @return void
	 */
	function reign_run_extended_settings() {
		global $wbtm_reign_settings;
		if ( !$wbtm_reign_settings ) {
			return;
		} else {
			$options = isset( $wbtm_reign_settings[ 'reign_buddyextender' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ] : array();
		}

		if ( !isset( $wbtm_reign_settings[ 'reign_buddyextender' ] ) ) {
			return;
		}

		$options = $wbtm_reign_settings[ 'reign_buddyextender' ];

		if ( empty( $options ) || !is_array( $options ) ) {
			return;
		}

		foreach ( $options as $key => $value ) {
			switch ( $key ) {
				case 'avatar_thumb_size_select' :
					if ( !defined( 'BP_AVATAR_THUMB_WIDTH' ) )
						define( 'BP_AVATAR_THUMB_WIDTH', (int) $options[ $key ] );
					if ( !defined( 'BP_AVATAR_THUMB_HEIGHT' ) )
						define( 'BP_AVATAR_THUMB_HEIGHT', (int) $options[ $key ] );
					break;
				case 'avatar_full_size_select' :
					if ( !defined( 'BP_AVATAR_FULL_WIDTH' ) )
						define( 'BP_AVATAR_FULL_WIDTH', (int) $options[ $key ] );
					if ( !defined( 'BP_AVATAR_FULL_HEIGHT' ) )
						define( 'BP_AVATAR_FULL_HEIGHT', (int) $options[ $key ] );
					break;
				case 'avatar_max_size_select' :
					if ( !defined( 'BP_AVATAR_ORIGINAL_MAX_WIDTH' ) )
						define( 'BP_AVATAR_ORIGINAL_MAX_WIDTH', (int) $options[ $key ] );
					break;

				case 'group_auto_join_checkbox' :
					if ( 'on' === $options[ $key ] && !defined( 'BP_DISABLE_AUTO_GROUP_JOIN' ) )
						define( 'BP_DISABLE_AUTO_GROUP_JOIN', true );
					break;
				case 'all_autocomplete_checkbox' :
					if ( 'on' === $options[ $key ] && !defined( 'BP_MESSAGES_AUTOCOMPLETE_ALL' ) )
						define( 'BP_MESSAGES_AUTOCOMPLETE_ALL', true );
					break;
			}
		}
	}

	add_action( 'init', 'reign_run_extended_settings', 9 );

	/**
	 * Runs BP configuration filters on bp_include
	 *
	 * @return void
	 */
	function reign_run_bp_included_settings() {

		global $wbtm_reign_settings;
		
		if ( !$wbtm_reign_settings ) {
			return;
		}
		
		$options = isset( $wbtm_reign_settings[ 'reign_buddyextender' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ] : array();

		if ( empty( $options ) || !is_array( $options ) ) {
			return;
		}

		foreach ( $options as $key => $value ) {
			switch ( $key ) {
				case 'profile_autolink_checkbox' :
					if ( 'on' === $options[ $key ] )
						remove_filter( 'bp_get_the_profile_field_value', 'xprofile_filter_link_profile_data', 9, 2 );
					break;
				case 'user_mentions_checkbox' :
					if ( 'on' === $options[ $key ] ){
						add_filter( 'bp_activity_do_mentions', '__return_false', 999 );
						add_filter( 'bp_activity_maybe_load_mentions_scripts', '__return_false', 999 );
					}
					
					break;
				case 'root_profiles_checkbox' :
					if ( 'on' === $options[ $key ] )
						add_filter( 'bp_core_enable_root_profiles', '__return_true' );
					break;
				case 'ldap_username_checkbox' :
					if ( 'on' === $options[ $key ] )
						add_filter( 'bp_is_username_compatibility_mode', '__return_true' );
					break;
				case 'wysiwyg_editor_checkbox' :
					if ( 'on' === $options[ $key ] )
						add_filter( 'bp_xprofile_is_richtext_enabled_for_field', '__return_false' );
					break;
				case 'depricated_code_checkbox' :
					if ( 'on' === $options[ $key ] )
						add_filter( 'bp_ignore_deprecated', '__return_true' );
					break;
			}
		}
	}

	add_action( 'init', 'reign_run_bp_included_settings' );

	function reign_options_enqueue_scripts() {

		if ( 'reign-settings_page_reign-options' == get_current_screen()->id ) {

			wp_enqueue_media();
			wp_register_script( 'reign-admin-js', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery' ) );
			wp_enqueue_script( 'reign-admin-js' );
		}
	}


	/**
	 * Function To Change The Default Group Cover Image
	 */
	add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'reign_bp_before_groups_cover_image_settings_parse_args', 10, 1 );

	function reign_bp_before_groups_cover_image_settings_parse_args( $settings ) {
		global $wbtm_reign_settings;
		$default_group_cover_image_url = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_url' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_url' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-cover.jpg';
		if ( empty( $default_group_cover_image_url ) ) {
			$default_group_cover_image_url = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-cover.jpg';
		}
		if ( !empty( $default_group_cover_image_url ) ) {
			$settings[ 'default_cover' ] = $default_group_cover_image_url;
		}

		$default_group_cover_image_size = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_size' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_size' ] : '';
		if ( !empty( $default_group_cover_image_size ) ) {
			$default_group_cover_image_size = explode( 'x', $default_group_cover_image_size );
			if ( !empty( $default_group_cover_image_size ) && is_array( $default_group_cover_image_size ) && ( count( $default_group_cover_image_size ) == 2 ) ) {
				$settings[ 'width' ]	 = trim( $default_group_cover_image_size[ 0 ] );
				$settings[ 'height' ]	 = trim( $default_group_cover_image_size[ 1 ] );
			}
		}
		return $settings;
	}

	/**
	 * Function To Change The Default xProfile Cover Image
	 */
	add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'reign_bp_before_xprofile_cover_image_settings_parse_args', 10, 1 );

	function reign_bp_before_xprofile_cover_image_settings_parse_args( $settings ) {
		global $wbtm_reign_settings;
		$default_xprofile_cover_image_url = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_xprofile_cover_image_url' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_xprofile_cover_image_url' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-cover.jpg';
		if ( empty( $default_xprofile_cover_image_url ) ) {
			$default_xprofile_cover_image_url = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-cover.jpg';
		}
		if ( !empty( $default_xprofile_cover_image_url ) ) {
			$settings[ 'default_cover' ] = $default_xprofile_cover_image_url;
		}

		$default_xprofile_cover_image_size = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_xprofile_cover_image_size' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_xprofile_cover_image_size' ] : '';
		if ( !empty( $default_xprofile_cover_image_size ) ) {
			$default_xprofile_cover_image_size = explode( 'x', $default_xprofile_cover_image_size );
			if ( !empty( $default_xprofile_cover_image_size ) && is_array( $default_xprofile_cover_image_size ) && ( count( $default_xprofile_cover_image_size ) == 2 ) ) {
				$settings[ 'width' ]	 = trim( $default_xprofile_cover_image_size[ 0 ] );
				$settings[ 'height' ]	 = trim( $default_xprofile_cover_image_size[ 1 ] );
			}
		}

		return $settings;
	}

	/**
	 * @since 1.0.4
	 * changing default image for user :: buddypress
	 */
 add_filter( 'bp_core_fetch_avatar_no_grav', 'reign_alter_bp_core_fetch_avatar_no_grav', 10, 2 );

	function reign_alter_bp_core_fetch_avatar_no_grav( $no_grav, $params ) {
		$userdata = get_userdata( $params[ 'item_id' ] );
		if ( !$userdata ) {
			return $no_grav;
		}
		$email = $userdata->user_email;
		if ( $params[ 'object' ] == 'user' ) {
			$has_validate_gravatar = false;
			if ( $has_validate_gravatar ) {
				return $no_grav;
			}
			global $wbtm_reign_settings;
			$avatar_default_image = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_default_image' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_default_image' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-avatar.png';
			if ( empty( $avatar_default_image ) ) {
				$avatar_default_image = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-avatar.png';
			}
			if ( !empty( $avatar_default_image ) ) {
				$no_grav = true;
			}
		}
		return $no_grav;
	}

	add_filter( 'bp_core_default_avatar_user', 'reign_alter_bp_core_default_avatar_user', 10, 2 );

	function reign_alter_bp_core_default_avatar_user( $avatar_default, $params ) {
		if ( $params[ 'object' ] == 'user' ) {
			global $wbtm_reign_settings;
			$avatar_default_image = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_default_image' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_default_image' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-avatar.png';

			if ( empty( $avatar_default_image ) ) {
				$avatar_default_image = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-avatar.png';
			}
			if ( !empty( $avatar_default_image ) ) {
				$avatar_default = $avatar_default_image;
			}
		}
		return $avatar_default;
	}

	function reign_has_validate_gravatar( $email ) {
			$email = '';
			if ( is_numeric($id_or_email) ) {
				$id = (int) $id_or_email;
				$user = get_userdata($id);
				if ( $user )
					$email = $user->user_email;
			} elseif ( is_object($id_or_email) ) {
				// No avatar for pingbacks or trackbacks
				$allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
				if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
					return false;

				if ( !empty($id_or_email->user_id) ) {
					$id = (int) $id_or_email->user_id;
					$user = get_userdata($id);
					if ( $user)
						$email = $user->user_email;
				} elseif ( !empty($id_or_email->comment_author_email) ) {
					$email = $id_or_email->comment_author_email;
				}
			} else {
				$email = $id_or_email;
			}

			$hashkey = md5(strtolower(trim($email)));
			$uri = 'http://www.gravatar.com/avatar/' . $hashkey . '?d=404';

			$data = wp_cache_get($hashkey);
			if (false === $data) {
				$response = wp_remote_head($uri);
				if( is_wp_error($response) ) {
					$data = 'not200';
				} else {
					$data = $response['response']['code'];
				}
			    wp_cache_set($hashkey, $data, $group = 'has_gravatar', $expire = 60*5);

			}
			if ($data == '200'){
				return true;
			} else {
				return false;
			}
	}

	/**
	 * @since 1.0.4
	 * changing default image for group :: buddypress
	 */
	add_filter( 'bp_core_avatar_default', 'reign_alter_bp_core_avatar_default', 10, 2 );

	function reign_alter_bp_core_avatar_default( $default_grav, $params ) {
		global $wbtm_reign_settings;
		if ( !$params ) {
			return $default_grav;
		}
		if ( $params[ 'object' ] == 'group' ) {
			$group_default_image = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_default_image' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_default_image' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-avatar.png';
			if ( empty( $group_default_image ) ) {
				$group_default_image = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-avatar.png';
			}
			if ( !empty( $group_default_image ) ) {
				$default_grav = $group_default_image;
			}
		}
		return $default_grav;
	}
