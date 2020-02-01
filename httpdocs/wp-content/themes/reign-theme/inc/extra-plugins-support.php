<?php
/* manage title tag in theme head */

// add_action( 'wp_head', 'reign_slug_render_title', 0 );
function reign_slug_render_title() {
	remove_action( 'wp_head', '_wp_render_title_tag', 1 );
	?>
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<?php
}

/* Resolving Changeset Related Issue In Theme Customizer */
add_filter( 'get_post_status', function( $post_status, $post ) {
	if ( ( $post->post_type == 'customize_changeset' ) && is_admin() ) {
		$post_status = '';
	}
	return $post_status;
}, 10, 2 );

/**
 * Compatibility with BP Create Group Type plugin
 * Return default group search form html
 * filter defined in BP Create Group Type plugin
 * @since   1.0.0
 */
add_filter( 'bpgt_modified_group_search_form', function( $altered_search_form_html, $search_form_html ) {
	return $search_form_html;
}, 10, 2 );


/*
 * Support Added For WordPress Customizer API
 */
/**
 * Store current post ID
 *
 * @since 1.0.0
 */
if ( !function_exists( 'reigntm_post_id' ) ) {

	function reigntm_post_id() {

		// Default value
		$id = '';

		// If singular get_the_ID
		if ( is_singular() ) {
			$id = get_the_ID();
		}

		// Get ID of WooCommerce product archive
		elseif ( REIGN_WOOCOMMERCE_ACTIVE && is_shop() ) {
			$shop_id = wc_get_page_id( 'shop' );
			if ( isset( $shop_id ) ) {
				$id = $shop_id;
			}
		}

		// Posts page
		elseif ( is_home() && $page_for_posts = get_option( 'page_for_posts' ) ) {
			$id = $page_for_posts;
		}

		// Apply filters
		$id = apply_filters( 'wbcom_post_id', $id );

		// Sanitize
		$id = $id ? $id : '';

		// Return ID
		return $id;
	}

}


/*
 * Support For Elementor
 */
if ( empty( get_option( 'elementor_disable_color_schemes' ) ) ) {
	// add_action( 'wp_head', 'wbcom_elementor_color_scheme_css' );
}

function wbcom_elementor_color_scheme_css() {
	if ( class_exists( 'Elementor\Scheme_Color' ) ) {
		$color	 = new Elementor\Scheme_Color();
		$values	 = $color->get_scheme_value();
		if ( !empty( $values ) ) {
			?>
			<style>
				#masthead,
				#bbpress-forums li.bbp-header {
					background: <?php echo esc_attr( $values[ Elementor\Scheme_Color::COLOR_1 ] ); ?>;
				}
			</style>
			<?php
		}
	}
}

$diable_typo = get_option( 'elementor_disable_typography_schemes' );
if ( isset( $diable_typo ) && ($diable_typo != 'yes' ) ) {
	// add_action( 'wp_head', 'wbcom_elementor_font_scheme_css' );
}

function wbcom_elementor_font_scheme_css() {
	if ( class_exists( 'Elementor\Scheme_Typography' ) ) {
		$font	 = new Elementor\Scheme_Typography();
		$values	 = $font->get_scheme_value();
		if ( !empty( $values ) ) {
			?>
			<style>
				html {
					font-family: <?php echo $values[ 4 ][ 'font_family' ]; ?>;
					font-weight: <?php echo $values[ 4 ][ 'font_weight' ]; ?>;
				}
				h1, h2, h3, h4, h5, h6 {
					font-family: <?php echo $values[ 1 ][ 'font_family' ]; ?>;
					font-weight: <?php echo $values[ 1 ][ 'font_weight' ]; ?>;
				}
			</style>
			<?php
		}
	}
}

/*
 * Support For WooCommerce
 */
/* Add Cart icon and count to header if WC is active */

function my_wc_cart_count() {
	if ( is_admin() ) {
		return;
	}
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_admin() ) {
			$count = '';
		} else {
			$count = WC()->cart->get_cart_contents_count();
		}
		?>
		<div class="woo-cart-wrapper">
			<a class="rg-icon-wrap woo-cart-wrap" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php _e( 'View your shopping cart', 'reign' ); ?>">
				<span class="fa fa-shopping-cart"></span>
				<span class="cart-contents-count rg-count"><?php echo esc_html( $count ); ?></span>
			</a>

			<div class="rg-woocommerce_mini_cart">
				<?php woocommerce_mini_cart() ?>
			</div>
		</div>
		<?php
	}

	if ( class_exists( 'Easy_Digital_Downloads' ) ) {
		if ( is_admin() ) {
			$count = '0';
		} else {
			$count = edd_get_cart_quantity();
		}
		?>
		<div class="edd-cart-wrapper">
			<a class="rg-icon-wrap edd-cart-wrap" href="<?php echo edd_get_checkout_uri(); ?>" title="<?php _e( 'View your shopping cart', 'reign' ); ?>">
				<span class="fa fa-shopping-cart"></span>
				<span class="cart-contents-count rg-count edd-cart-quantity"><?php echo esc_html( $count ); ?></span>
			</a>
			<div class="rg-edd_mini_cart">
				<?php echo do_shortcode( '[download_cart]' ); ?>
			</div>
		</div>
		<?php
	}
}

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart_form', 'woocommerce_cross_sell_display', 10 );

/**
 * WooCommerce Mini Cart
 */
// if ( !function_exists( 'woocommerce_mini_cart' ) ) {
// 	function woocommerce_mini_cart( $args = array() ) {
// 		$defaults = array(
// 			'list_class' => '',
// 		);
// 		$args = wp_parse_args( $args, $defaults );
// 		wc_get_template( 'cart/mini-cart.php', $args );
// 	}
// }

/* Ensure cart contents update when products are added to the cart via AJAX */
add_filter( 'woocommerce_add_to_cart_fragments', 'my_header_add_to_cart_fragment' );

function my_header_add_to_cart_fragment( $fragments ) {
	$count										 = WC()->cart->get_cart_contents_count();
	ob_start();
	?>
	<a class="rg-icon-wrap woo-cart-wrap" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php _e( 'View your shopping cart', 'reign' ); ?>">
		<span class="fa fa-shopping-cart"></span>
		<span class="cart-contents-count rg-count"><?php echo esc_html( $count ); ?></span>
	</a>
	<?php
	$fragments[ 'a.rg-icon-wrap.woo-cart-wrap' ] = ob_get_clean();
	return $fragments;
}

/* Ensure mini cart contents update when products are added to the cart via AJAX */
add_filter( 'woocommerce_add_to_cart_fragments', function($fragments) {

	ob_start();
	?>

	<div class="rg-woocommerce_mini_cart">
		<?php woocommerce_mini_cart(); ?>
	</div>

	<?php
	$fragments[ '.rg-woocommerce_mini_cart' ] = ob_get_clean();

	return $fragments;
} );

add_shortcode( 'reign_woo_mini_cart', 'reign_woo_mini_cart_render' );

function reign_woo_mini_cart_render() {

	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_admin() ) {
			$count = '0';
		} else {
			$count = WC()->cart->get_cart_contents_count();
		}
		?>
		<div id="rg-mobile-icon-toggle" data-id="rg-slidebar-toggle">
			<a class="rg-icon-wrap woo-cart-wrap" href="#" title="<?php _e( 'View your shopping cart', 'reign' ); ?>">
				<span class="fa fa-shopping-cart"></span>
				<span class="cart-contents-count rg-count"><?php echo esc_html( $count ); ?></span>
			</a>
		</div>
		<?php
	}
}

add_action( 'wbcom_before_page', 'reign_add_canvas_for_toggle_slidebars' );

function reign_add_canvas_for_toggle_slidebars() {

	$toggle_content_right = shiftnav_op( 'toggle_content_right', 'togglebar' );
	?>
	<div off-canvas="rg-slidebar-toggle right overlay" id="off-canvas-rg-slidebar-toggle">
		<i class="fa fa-times-circle-o rg-cancel" data-id="rg-slidebar-toggle"></i>
		<?php
		if ( $toggle_content_right == '[reign_woo_mini_cart]' && class_exists('WooCommerce') ) {
			?>
			<div class="rg-woocommerce_mini_cart">
				<?php woocommerce_mini_cart(); ?>
			</div>
			<?php
		} elseif ( $toggle_content_right == '[reign_bp_user_menu]' && class_exists('BuddyPress') ) {
			?>
			<div class="rg-bp_user_menu">
				<?php
				if ( is_user_logged_in() ) {
					wp_nav_menu( array( 'theme_location' => 'menu-2', 'menu_id' => 'user-profile-menu', 'fallback_cb' => '', 'container' => false, 'menu_class' => 'user-profile-menu', ) );
				}
				?>
			</div>
			<?php
		} elseif ( $toggle_content_right == '[reign_download_cart]' && class_exists('Easy_Digital_Downloads') ) {
			?>
			<div class="rg-edd_mini_cart">
				<?php echo do_shortcode( '[download_cart]' ); ?>
			</div>
			<?php
		} else {

		}
		?>
	</div>
	<?php
}

add_shortcode( 'reign_bp_user_menu', 'reign_bp_user_menu_toggle_render' );

function reign_bp_user_menu_toggle_render() {
	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		if ( ($current_user instanceof WP_User ) ) {
			$user_link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( get_current_user_id() ) : '#';
			echo '<div id="rg-mobile-icon-toggle" data-id="rg-slidebar-toggle">';
			echo '<div class="user-link">';
			echo get_avatar( $current_user->user_email, 200 );
			echo '</div>';
			echo '</div>';
		}
	} else {
		global $wbtm_reign_settings;
		$login_page_url = wp_login_url();
		if ( isset( $settings[ 'reign_pages' ][ 'reign_login_page' ] ) && ( $wbtm_reign_settings[ 'reign_pages' ][ 'reign_login_page' ] != '-1' ) ) {
			$login_page_id	 = $wbtm_reign_settings[ 'reign_pages' ][ 'reign_login_page' ];
			$login_page_url	 = get_permalink( $login_page_id );
		}
		$registration_page_url = wp_registration_url();
		if ( isset( $wbtm_reign_settings[ 'reign_pages' ][ 'reign_register_page' ] ) && ( $wbtm_reign_settings[ 'reign_pages' ][ 'reign_register_page' ] != '-1' ) ) {
			$registration_page_id	 = $wbtm_reign_settings[ 'reign_pages' ][ 'reign_register_page' ];
			$registration_page_url	 = get_permalink( $registration_page_id );
		}
		?>
		<div class="rg-icon-wrap">
			<a href="<?php echo $login_page_url; ?>" class="btn-login" title="Login">	<span class="fa fa-sign-in"></span>
			</a>
		</div>
		<?php
		if ( get_option( 'users_can_register' ) ) {
			?>
			<span class="sep">|</span>
			<div class="rg-icon-wrap">
				<a href="<?php echo $registration_page_url; ?>" class="btn-register" title="Register">
					<span class="fa fa-address-book-o"></span>
				</a>
			</div>
			<?php
		}
	}
}

/**
 *
 * To enable toggle for first panel at desktop view.
 *
 */
function reign_desktop_enable_first_panel() {
	$display = shiftnav_op( 'display_at_desktop', 'shiftnav-main' );

	if ( $display == '1' ) {
		echo '<div class="reign-first-desktop-toggle">';
		echo do_shortcode( '[shiftnav_toggle target="shiftnav-main" class="shiftnav-toggle-button" icon="bars"][/shiftnav_toggle]' );
		echo '</div>';
	}
}

add_action( 'wbcom_begin_masthead', 'reign_desktop_enable_first_panel' );

function reign_get_image_id_from_url( $image_url ) {
	global $wpdb;
	$attachment_id	 = '';
	$attachment		 = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
	if ( $attachment ) {
		$attachment_id = $attachment[ 0 ];
	}
	return $attachment_id;
}

add_shortcode( 'reign_download_cart', 'reign_edd_download_cart_render' );

function reign_edd_download_cart_render() {
	if( class_exists('Easy_Digital_Downloads') ) {
		if ( is_admin() ) {
			$count = '0';
		} else {
			$count = edd_get_cart_quantity();
		}
		?>
		<div id="rg-mobile-icon-toggle" data-id="rg-slidebar-toggle">
			<a class="rg-icon-wrap edd-cart-wrap" href="#" title="<?php _e( 'View your shopping cart', 'reign' ); ?>">
				<span class="fa fa-shopping-cart"></span>
				<span class="cart-contents-count rg-count edd-cart-quantity"><?php echo esc_html( $count ); ?></span>
			</a>
		</div>
		<?php
	} else {

	}
}

add_action( 'after_switch_theme', 'wbcom_peepso_set_default_social_fields' );
function wbcom_peepso_set_default_social_fields () {
	global $wbtm_reign_settings;
	
	$wbtm_peepso_social_links	 = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'wbtm_social_links' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'wbtm_social_links' ] : array();
	if( empty( $wbtm_peepso_social_links ) ) {
		$wbtm_peepso_social_links = array(
			'facebook' => array(
				'img_url'	=>	'',
				'name'	=>	__( 'Facebook', 'reign' ),
			),
			'twitter' => array(
				'img_url'	=>	'',
				'name'	=>	__( 'Twitter', 'reign' ),
			),
			'linkedin' => array(
				'img_url'	=>	'',
				'name'	=>	__( 'Linkedin', 'reign' ),
			)
		);
		$wbtm_reign_settings[ 'reign_peepsoextender' ][ 'wbtm_social_links' ] = $wbtm_peepso_social_links;
		update_option( "reign_options", $wbtm_reign_settings );
		$wbtm_reign_settings = get_option( "reign_options", array() );
	}
	
	/*
	 * Set Default value when activate reign theme
	 */
	if ( empty($wbtm_reign_settings['reign_buddyextender']) ) {
		$wbtm_reign_settings['reign_buddyextender']['avatar_thumb_size_select'] = '50';
		$wbtm_reign_settings['reign_buddyextender']['avatar_full_size_select'] = '150';
		$wbtm_reign_settings['reign_buddyextender']['avatar_max_size_select'] = '320';
		
		$wbtm_reign_settings['reign_buddyextender']['member_header_position'] = 'top';
		$wbtm_reign_settings['reign_buddyextender']['member_header_type'] = 'wbtm-cover-header-type-3';
		$wbtm_reign_settings['reign_buddyextender']['group_header_type'] = 'wbtm-cover-header-type-3';
		$wbtm_reign_settings['reign_buddyextender']['member_directory_type'] = 'wbtm-member-directory-type-2';
		$wbtm_reign_settings['reign_buddyextender']['group_directory_type'] = 'wbtm-group-directory-type-2';
		update_option( "reign_options", $wbtm_reign_settings );
		$wbtm_reign_settings = get_option( "reign_options", array() );
	}
}


/**
 * Showing PeepSo group cover image.
 */
if( !function_exists( 'wbtm_render_peepso_group_cover_image' ) ) {
	function wbtm_render_peepso_group_cover_image() {
		global $wbtm_reign_settings;
		$cover_img_url = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'default_group_cover_image_url' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'default_group_cover_image_url' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-cover.jpg';
		if( empty( $cover_img_url ) ) {
			$cover_img_url = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-cover.jpg';
		}
		return $cover_img_url;
	}
}

/**
 * Showing PeepSo member cover image.
 */
if( !function_exists( 'wbtm_render_peepso_member_cover_image' ) ) {
	function wbtm_render_peepso_member_cover_image() {
		global $wbtm_reign_settings;
		$cover_img_url = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'default_profile_cover_image_url' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'default_profile_cover_image_url' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-cover.jpg';
		if( empty( $cover_img_url ) ) {
			$cover_img_url = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-cover.jpg';
		}
		return $cover_img_url;
	}
}

/**
 * Get PeepSo member cover image.
 */
if( !function_exists( 'wbtm_get_peepso_member_cover_image' ) ) {
	function wbtm_get_peepso_member_cover_image( $size = 0 ) {
		$cover         = NULL;
	    $PeepSoProfile = PeepSoProfile::get_instance();
        $PeepSoUser    = $PeepSoProfile->user;
		$cover_hash    = get_user_meta( $PeepSoUser->get_id(), 'peepso_cover_hash', TRUE);

		if ( $cover_hash ) {
	        $cover_hash = $cover_hash . '-';
	    }
		$filename = $cover_hash . 'cover.jpg';
	    if(file_exists($PeepSoUser->get_image_dir() . $filename)) {
	        $cover = $PeepSoUser->get_image_url() . $filename;

	        if (is_int($size) && $size > 0) {
	            $filename_scaled = $cover_hash . 'cover-' . $size . '.jpg';
	            if (!file_exists($PeepSoUser->get_image_dir() . $filename_scaled)) {
	                $si = new PeepSoSimpleImage();
	                $si->png_to_jpeg($PeepSoUser->get_image_dir() . $filename);
	                $si->load($PeepSoUser->get_image_dir() . $filename);
	                $si->resizeToWidth($size);
	                $si->save($PeepSoUser->get_image_dir() . $filename_scaled, IMAGETYPE_JPEG);
	            }

	            $cover = $PeepSoUser->get_image_url() . $filename_scaled;
	        }
	    }

		return $cover;
	}
}

/**
 * Get all social fields added in backend.
 */
function wbcom_get_peepso_user_social_array() {
	global $wbtm_reign_settings;
	$wbtm_social_links	 = isset( $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'wbtm_social_links' ] ) ? $wbtm_reign_settings[ 'reign_peepsoextender' ][ 'wbtm_social_links' ] : array();
	return $wbtm_social_links;
}

/**
 * Added a class for group directory in body class.
 */
add_filter( 'body_class', 'reign_peepso_body_class', 999, 2 );
function reign_peepso_body_class( $classes, $class ) {
	if ( class_exists('PeepSo') ) {
		array_push( $classes, 'reign_peepso_active' );
		$peepso_url_segments = PeepSoUrlSegments::get_instance();
	   	if ( ( 'peepso_groups' === $peepso_url_segments->_shortcode ) && ( sizeof( $peepso_url_segments->_segments ) == 1 ) ) {
	   		if ( is_array( $classes ) ) {
	   			array_push( $classes, 'reign_peepso_group_directory_page' );	
	    	}
	   }
	}
	return $classes; 	
}

/**
 * Added social links fields in user profile.
 */
add_filter( 'peepso_profile_edit_form', 'reign_peepso_profile_edit_form', 10, 1 );
function reign_peepso_profile_edit_form( $form ) {
	$user_id = get_current_user_id();
	if ( ! empty( $form ) ) {
		if ( isset( $form['fields'] ) ) {
			$fields = $form['fields'];
			$social_fields = wbcom_get_peepso_user_social_array();
			if ( ! empty( $social_fields ) ) {
				foreach ( $social_fields as $field_slug => $social ) {
					$social_link = get_user_meta( $user_id, 'wbcom_social_'. $field_slug, true );
					if( empty( $social_link ) ) {
						$social_link = '';
					}
					$val = array
	                (
	                	'section' => esc_html__('Your Account', 'reign'),
	                    'label'   => $social[ 'name' ],
	                    'type'    => 'text',
	                    'value'   => $social_link,
	                    'html'    => $social_link,
	                );				
					$fields = array_slice( $fields, 0, count( $fields ) - 2, true ) +
								array( 'wbcom_social_'. $field_slug => $val ) +
								array_slice( $fields, count( $fields ) - 2 , count( $fields ) - ( count( $fields ) - 2 ), true );
				} 
				$form['fields'] = $fields;
			}	
		}
	}
	return $form;
}

add_action( 'peepso_save_profile_form', 'reign_peepso_profile_after_save', 10, 1 );
function reign_peepso_profile_after_save( $userid ) {
	$form_arr = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
	if ( filter_input( INPUT_POST, 'account' ) ) {
		foreach ( $form_arr as $key => $value ) {
	 		if ( strpos( $key, 'wbcom_social_' ) !== false ) {
				$social_link = filter_input( INPUT_POST, $key, FILTER_SANITIZE_STRING );
				update_user_meta( $userid, $key, $form_arr[$key] ); 
			}
		}
	}
}

function reign_peepso_social_not_all_empty( $userid ) {
	$social_fields = wbcom_get_peepso_user_social_array();
	if ( ! empty( $social_fields ) ) {
		foreach ( $social_fields as $field_slug => $social ) {
			$social_link = get_user_meta( $userid, 'wbcom_social_'. $field_slug, true );
			if ( ! empty( $social_link ) ) {
				return true;
			}
		}
	}
	return false;
}

/**
 * Added social links fields in user profile.
 */
function reign_peepso_user_social_links( $userid ) {
	$social_fields = wbcom_get_peepso_user_social_array();
	if ( ! empty( $social_fields ) && reign_peepso_social_not_all_empty( $userid ) ) {
		
		$html_to_render = '';
		$counter = 0;
		$first_time = true;
		foreach ( $social_fields as $field_slug => $social ) {
			$counter++;
			$social_link = get_user_meta( $userid, 'wbcom_social_'. $field_slug, true );
			if( !isset( $social_link ) || empty( $social_link ) ) { continue; }
			if( $first_time ) {
				$html_to_render .= '<ul>';
				$first_time = false;
			}
			$html_to_render .= '<li>';
			$html_to_render .= '<a href="'. $social_link .'" title="'. $social['name'] .'">';
			if( empty( $social['img_url'] ) ) {
				$html_to_render .= '<i class="fa fa-' . strtolower( trim( $social['name'] ) ). '"></i>';
			} else {
				$html_to_render .= '<img src="' . $social['img_url'] . '" />';
			}
			$html_to_render .= '</a>';	
			$html_to_render .= '</li>';
			if( $counter == count( $social_fields ) ) {
				$html_to_render .= '</ul>';
			}
		}
		echo $html_to_render;
	}			
}

add_action( 'reign_header_v4_middle_section_html', 'reign_header_v4_middle_section_search' );
function reign_header_v4_middle_section_search() {
	if( class_exists('WooCommerce') && class_exists('WC_Widget_Product_Search') ) {
		the_widget('WC_Widget_Product_Search');
	} else {
		if( function_exists('get_search_form') ) {
			get_search_form();
		}
	}
}

add_action( 'init', 'reign_peepso_default_widgets', 15 );
/**
 * Set default widgets in Left, Right sidebars and Header Widget area.
 */
function reign_peepso_default_widgets() {
	$active_widgets        = get_option( 'sidebars_widgets' );
	$default_reign_widget  = get_option( 'set_default_peepso_reign_widgets' );

	if ( empty( $default_reign_widget ) ) {
		$default_reign_widget = array();
	}
	if ( class_exists('PeepSo') ) {

		// Set default widgets in Header Area.
		if ( ! array_key_exists( 'peepso_reign_header_widget', $default_reign_widget ) ) {
			$default_widget_content = array();
			$counter = count( $active_widgets['reign-header-widget-area'] ) + 1;
			if ( empty ( $active_widgets['reign-header-widget-area'] ) ) {
				$active_widgets['reign-header-widget-area'][0] = 'peepsowidgetuserbar-' . $counter; 
			} else {
				array_push( $active_widgets['reign-header-widget-area'], 'peepsowidgetuserbar-' . $counter );
			}
			$default_widget_content[ $counter ] = array( 'content_position' => 'left', 'show_avatar' => 1, 'show_name' => 1, 'show_notifications' => 1, 'show_usermenu' => 1, 'show_logout' => 1 );
			update_option( 'widget_peepsowidgetuserbar', $default_widget_content );
			$default_reign_widget['peepso_reign_header_widget'] = 1;
		}

		//Set default widgets in left sidebar.
		if ( ! array_key_exists( 'peepso_reign_sidebar_left_profile', $default_reign_widget ) ) {
			$default_widget_content = array();
			$counter = count( $active_widgets['sidebar-left'] ) + 1;
			if ( empty ( $active_widgets['sidebar-left'] ) ) {
				$active_widgets['sidebar-left'][0] = 'peepsowidgetme-' . $counter;		
			} else {
				array_unshift( $active_widgets['sidebar-left'], 'peepsowidgetme-' . $counter );
			}
			$default_widget_content[ $counter ] = array( 'show_notifications' => 1, 'show_community_links' => 1, 'show_cover' => 1 );
			update_option( 'widget_peepsowidgetme', $default_widget_content );
			$default_reign_widget['peepso_reign_sidebar_left_profile'] = 1;
		}

	    // Set default widget in right sidebar.
		// 1. Set online members widget.
		if ( ! array_key_exists( 'peepso_reign_sidebar_right_online_members', $default_reign_widget ) ) {
			$default_widget_content = array();
			$counter = 1;
			if ( empty ( $active_widgets['sidebar-right'] ) ) {
				$active_widgets['sidebar-right'][0] = 'peepsowidgetonlinemembers-' . $counter;
			} else {
				array_unshift( $active_widgets['sidebar-right'], 'peepsowidgetonlinemembers-' . $counter );
			}
			$default_widget_content[ $counter ] = array( 'limit' => 12 );
			update_option( 'widget_peepsowidgetonlinemembers', $default_widget_content );
			$default_reign_widget['peepso_reign_sidebar_right_online_members'] = 1;
		}

		// 2. Set community audio-video widget.
		if ( class_exists( 'PeepSoVideos' ) ) {
			if ( ! array_key_exists( 'peepso_reign_sidebar_right_community_videos', $default_reign_widget ) ) {
				$default_widget_content = array();
				$counter = 1;
				if ( empty ( $active_widgets['sidebar-right'] ) ) {
					$active_widgets['sidebar-right'][0] = 'peepsowidgetcommunityvideos-' . $counter;
				} else {
					array_unshift( $active_widgets['sidebar-right'], 'peepsowidgetcommunityvideos-' . $counter );
				}
				$default_widget_content[ $counter ] = array( 'limit' => 12, 'media_type' => 'video', 'hideempty' => 0 );
				update_option( 'widget_peepsowidgetcommunityvideos', $default_widget_content );
				$default_reign_widget['peepso_reign_sidebar_right_community_videos'] = 1;
			}
		}

		// 3. Set photos widget.
		if ( class_exists( 'PeepSoSharePhotos' ) ) {
			if ( ! array_key_exists( 'peepso_reign_sidebar_right_photos', $default_reign_widget ) ) {
				$default_widget_content = array();
				$counter = 1;
				if ( empty ( $active_widgets['sidebar-right'] ) ) {
					$active_widgets['sidebar-right'][0] = 'peepsowidgetphotos-' . $counter;
				} else {
					array_unshift( $active_widgets['sidebar-right'], 'peepsowidgetphotos-' . $counter );
				}
				$default_widget_content[ $counter ] = array( 'limit' => 12, 'hideempty' => 0 );
				update_option( 'widget_peepsowidgetphotos', $default_widget_content );
				$default_reign_widget['peepso_reign_sidebar_right_photos'] = 1;
			}
		}

		// 4. Set hashtag widget.
		if ( class_exists( 'PeepSoWidgetHashtags' ) ) {
			if ( ! array_key_exists( 'peepso_reign_sidebar_right_hashtags', $default_reign_widget ) ) {
				$default_widget_content = array();
				$counter = 1;
				if ( empty ( $active_widgets['sidebar-right'] ) ) {
					$active_widgets['sidebar-right'][0] = 'peepsowidgethashtags-' . $counter;
				} else {
					array_unshift( $active_widgets['sidebar-right'], 'peepsowidgethashtags-' . $counter );
				}
				$default_widget_content[ $counter ] = array( 'limit' => 12 );
				update_option( 'widget_peepsowidgethashtags', $default_widget_content );
				$default_reign_widget['peepso_reign_sidebar_right_hashtags'] = 1;
			}
		}

		update_option( 'sidebars_widgets', $active_widgets );
		update_option( 'set_default_peepso_reign_widgets', $default_reign_widget );
	}		
}

add_action( 'peepso_init', 'reign_peepso_page_default_sidebar', 15 );
/**
 * Set default sidebar and page template in PeepSo pages.
 */
function reign_peepso_page_default_sidebar() {
	$pages = array(
		'page_activity' => PeepSo::get_option('page_activity'),
		'page_members'  => PeepSo::get_option('page_members'),
		'page_profile'  => PeepSo::get_option('page_profile'),
		'page_groups'   => PeepSo::get_option('page_groups'),
		'page_messages' => PeepSo::get_option('page_messages'),
		'page_wpadverts' => 'wpadverts',
	);	
	$updated_pages = get_option( 'set_default_peepso_reign_page_sidebar' );
	$theme_slug	   = apply_filters( 'wbcom_essential_theme_slug', 'reign' );

	if ( empty( $updated_pages ) ) {
		$updated_pages = array();
	}
	
	foreach( $pages as $key => $slug ) {
		if ( PeepSo::get_page( $slug ) ) {
			if ( ! isset( $updated_pages[$slug] ) ) {
				$wbcom_metabox_data	 = get_post_meta( url_to_postid( PeepSo::get_page( $slug ) ), $theme_slug . '_wbcom_metabox_data', true );
				if ( empty( $wbcom_metabox_data ) ) {
					$wbcom_metabox_data = array();
				}
				$wbcom_metabox_data['layout'] = array
			        (
			            'site_layout'       => 'both_sidebar',
			            'primary_sidebar'   => 'sidebar-right',
			            'secondary_sidebar' => 'sidebar-left'
			        );
			    update_post_meta( url_to_postid( PeepSo::get_page( $slug ) ), $theme_slug . '_wbcom_metabox_data', $wbcom_metabox_data );
			    $updated_pages[$slug] = 1;
			}

			// Set Page template.
			if ( 'page_profile' === $key || 'page_groups' === $key ) {
				if ( ! isset( $updated_pages[$key.'_template'] ) ) {
					$template = update_post_meta( url_to_postid( PeepSo::get_page( $slug ) ), '_wp_page_template', 'page-peepso-single-layout.php' );
					$updated_pages[$key.'_template'] = 1;
				}
			}
		}
		
	}
    update_option( 'set_default_peepso_reign_page_sidebar', $updated_pages );
}

add_filter( 'reign_alter_display_right_sidebar', 'reign_peepso_display_right_sidebar_for_woo', 11, 1 );
/**
 * Display Right sidebar for cart and checkout tab in PeepSo pages.
 */
function reign_peepso_display_right_sidebar_for_woo( $display ) {
	if ( class_exists( 'PeepSo' ) ) {
		$peepso_url_segments = PeepSoUrlSegments::get_instance();
		if ( ( 'peepso_profile' === $peepso_url_segments->_shortcode ) ) {
			if ( class_exists( 'WooCommerce' ) ) {
				if ( is_cart() || is_checkout() ) {
					$display = true;
				}
			}
		}
	}
	return $display;
}

add_filter( 'peepso_hovercard', 'reign_peepso_member_hovercard', 10, 2 );
function reign_peepso_member_hovercard( $res, $uid ) {
	$cover         = NULL;
	$size          = 750;
    $PeepSoUser    = PeepSoUser::get_instance( $uid );
	$cover_hash    = get_user_meta( $uid, 'peepso_cover_hash', TRUE);

	if ( $cover_hash ) {
        $cover_hash = $cover_hash . '-';
    }
	$filename = $cover_hash . 'cover.jpg';
    if(file_exists($PeepSoUser->get_image_dir() . $filename)) {
        $cover = $PeepSoUser->get_image_url() . $filename;

        if (is_int($size) && $size > 0) {
            $filename_scaled = $cover_hash . 'cover-' . $size . '.jpg';
            if (!file_exists($PeepSoUser->get_image_dir() . $filename_scaled)) {
                $si = new PeepSoSimpleImage();
                $si->png_to_jpeg($PeepSoUser->get_image_dir() . $filename);
                $si->load($PeepSoUser->get_image_dir() . $filename);
                $si->resizeToWidth($size);
                $si->save($PeepSoUser->get_image_dir() . $filename_scaled, IMAGETYPE_JPEG);
            }

            $cover = $PeepSoUser->get_image_url() . $filename_scaled;
        }
    }
	if ( empty( $cover ) ) {
		$cover = wbtm_render_peepso_member_cover_image();
	}
    $res['cover'] = $cover;
    return $res;
}

/**
 * Reign Dokan :: Product Edit Page Add Class
 */
function wb_reign_manage_body_class( $classes ) {
	if ( function_exists( 'is_product' ) ) {
		if ( is_product() && get_query_var( 'edit' ) ) {
			$classes[] = 'rda-product-edit-screen';
		}
	}
	return $classes;
}

add_action( 'body_class', 'wb_reign_manage_body_class', 10, 1 );