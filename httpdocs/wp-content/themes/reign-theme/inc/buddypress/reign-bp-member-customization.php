<?php
/**
 * *******************************************************
 * *******************************************************
 * ******* MEMBER DIRECTORY CUSTOMIZATION :: START ********
 * *******************************************************
 * *******************************************************
 */
if ( function_exists( 'bp_get_theme_package_id' ) ) {
	$theme_package_id = bp_get_theme_package_id();
} else {
	$theme_package_id = 'legacy';
}
// if ( TRUE || 'legacy' === $theme_package_id ) {

/**
 * showing member cover image on member directory page
 *
 * @since 1.0.7
 */
if ( !function_exists( 'wbtm_render_member_cover_image' ) ) {
	add_action( 'wbtm_before_member_avatar_member_directory', 'wbtm_render_member_cover_image', 10 );

	function wbtm_render_member_cover_image() {
		global $wbtm_reign_settings;
		$member_directory_type = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] : 'wbtm-member-directory-type-2';
		if ( $member_directory_type == 'wbtm-member-directory-type-2' || $member_directory_type == 'wbtm-member-directory-type-3' ) {
			$args			 = array(
				'object_dir' => 'members',
				'item_id'	 => $user_id	 = bp_get_member_user_id(),
				'type'		 => 'cover-image',
			);
			$cover_img_url	 = bp_attachments_get_attachment( 'url', $args );
			if ( empty( $cover_img_url ) ) {
				$cover_img_url = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_xprofile_cover_image_url' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_xprofile_cover_image_url' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-cover.jpg';
				if ( empty( $cover_img_url ) ) {
					$cover_img_url = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-cover.jpg';
				}
			}
			echo '<div class="wbtm-mem-cover-img"><img src="' . $cover_img_url . '" /></div>';
		}
	}

}


/**
 * showing member statistics on member directory page
 *
 * @since 1.0.7
 */
if ( !function_exists( 'wbtm_render_bp_directory_members_item' ) ) {
	if ( function_exists( 'bp_get_theme_package_id' ) ) {
		$theme_package_id = bp_get_theme_package_id();
	} else {
		$theme_package_id = 'legacy';
	}

	if ( 'legacy' === $theme_package_id ) {
		add_action( 'bp_friend_requests_item', 'wbtm_render_bp_directory_members_item', 50 );
		add_action( 'bp_directory_members_item', 'wbtm_render_bp_directory_members_item', 50 );
	} else {
		add_action( 'wbtm_bp_nouveau_directory_members_item', 'wbtm_render_bp_directory_members_item', 50 );
	}

	function wbtm_render_bp_directory_members_item() {
		global $wbtm_reign_settings;
		$member_directory_type = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] : 'wbtm-member-directory-type-1';
		if ( $member_directory_type == 'wbtm-member-directory-type-1' ) {
			return;
		}

		$info_array	 = array();
		$user_id	 = bp_get_member_user_id();

		if ( bp_is_active( 'friends' ) ) {
			$friends_count			 = friends_get_total_friend_count( $user_id );
			$friends_count			 = friends_get_total_friend_count( $user_id );
			$url_to_use				 = esc_url( bp_core_get_user_domain( $user_id ) . bp_get_friends_slug() );
			$info_array[ 'friends' ] = array(
				'tooltip_text'	 => sprintf( '%s Friends', $friends_count, 'reign' ),
				'url'			 => $url_to_use,
				'icon_class'	 => 'fa fa-user',
				'color'			 => '#EC7063'
			);
		}

		if ( class_exists( 'BP_Follow_Component' ) ) {
			$followers					 = bp_follow_get_followers( array( 'user_id' => $user_id ) );
			$url_to_use					 = esc_url( bp_core_get_user_domain( $user_id ) . 'followers' );
			$info_array[ 'followers' ]	 = array(
				'tooltip_text'	 => sprintf( '%s Followers', count( $followers ), 'reign' ),
				'url'			 => $url_to_use,
				'icon_class'	 => 'fa fa-users',
				'color'			 => '#5DADE2'
			);
			$following					 = bp_follow_get_following( array( 'user_id' => $user_id ) );
			$url_to_use					 = esc_url( bp_core_get_user_domain( $user_id ) . 'following' );
			$info_array[ 'following' ]	 = array(
				'tooltip_text'	 => sprintf( '%s Following', count( $following ), 'reign' ),
				'url'			 => $url_to_use,
				'icon_class'	 => 'fa fa-weixin',
				'color'			 => '#F5B041'
			);
		}

		if ( class_exists( 'BadgeOS' ) && class_exists( 'BadgeOS_Community' ) ) {
			$user_points = get_user_meta( $user_id, $meta_key	 = '_badgeos_points', true );
			$url_to_use	 = esc_url( bp_core_get_user_domain( $user_id ) . 'achievements' );
			if ( empty( $user_points ) ) {
				$user_points = 0;
			}
			$info_array[ 'badgeos_points' ] = array(
				'tooltip_text'	 => sprintf( '%s Points', $user_points, 'reign' ),
				'url'			 => $url_to_use,
				'icon_class'	 => 'fa fa-trophy',
				'color'			 => '#99A3A4'
			);
		}

		if ( class_exists( 'myCRED_Core' ) ) {
			global $mycred, $mycred_modules;
			$myCRED_BuddyPress_Module_Obj	 = $mycred_modules[ 'type' ][ 'mycred_default' ][ 'buddypress' ];
			$users_balance					 = $mycred->get_users_balance( $user_id );
			$users_balance					 = $mycred->format_creds( $users_balance );
			$url_to_use						 = esc_url( bp_core_get_user_domain( $user_id ) . 'mycred-history' );
			$info_array[ 'mycred_points' ]	 = array(
				'tooltip_text'	 => sprintf( '%s Points', $users_balance, 'reign' ),
				'url'			 => $url_to_use,
				'icon_class'	 => 'fa fa-tag',
				'color'			 => '#DC7633'
			);
		}

		echo '<div class="wbtm-member-directory-meta">';
		foreach ( $info_array as $key => $info ) {
			?>
			<div class="rtm-tooltip" style="background: <?php echo $info[ 'color' ]; ?>">
				<a href="<?php echo $info[ 'url' ]; ?>"><i class="<?php echo esc_attr($info[ 'icon_class' ]); ?>"></i></a>
				<span class="rtm-tooltiptext">
					<?php echo $info[ 'tooltip_text' ]; ?>
				</span>
			</div>
			<?php
		}
		echo '</div>';
	}

}

/**
 * manging action buttons for self profile on member directory page
 *
 * @since 1.0.7
 */
if ( !function_exists( 'wbtm_bp_dir_mem_manage_profile_buttons' ) ) {
	add_action( 'bp_directory_members_actions', 'wbtm_bp_dir_mem_manage_profile_buttons' );

	function wbtm_bp_dir_mem_manage_profile_buttons() {
		$user_id = bp_get_member_user_id();
		if ( $user_id != bp_loggedin_user_id() ) {
			return false;
		}
		$profile_url = bp_core_get_user_domain( $user_id ) . bp_get_profile_slug() . '/';
		$defaults	 = array(
			'block_self'		 => false,
			'must_be_logged_in'	 => true,
			'component'			 => 'members',
			'wrapper_class'		 => 'message-button',
			'link_class'		 => 'wbtm-profile-btn',
			'link_text'			 => __( 'Profile Settings', 'reign' ),
			'id'				 => 'wbtm-profile-btn-' . $user_id,
			'wrapper_id'		 => 'wbtm-profile-btn-wrap-' . $user_id,
			'link_href'			 => $profile_url,
			'link_title'		 => __( 'You can manage your profile settings here.', 'reign' ),
		);

		// Get Button Html Code.
		echo bp_get_button( $defaults );

		$account_setting_url = bp_core_get_user_domain( $user_id ) . bp_get_settings_slug() . '/';
		$defaults			 = array(
			'block_self'		 => false,
			'must_be_logged_in'	 => true,
			'component'			 => 'members',
			'wrapper_class'		 => 'message-button',
			'link_class'		 => 'wbtm-profile-btn',
			'link_text'			 => __( 'Account Settings', 'reign' ),
			'id'				 => 'wbtm-profile-btn-' . $user_id,
			'wrapper_id'		 => 'wbtm-profile-btn-wrap-' . $user_id,
			'link_href'			 => $account_setting_url,
			'link_title'		 => __( 'You can manage your account settings here.', 'reign' ),
		);

		// Get Button Html Code.
		echo bp_get_button( $defaults );
	}

}

/**
 * manging action buttons on member directory page
 *
 * @since 1.0.7
 */
if ( !function_exists( 'wbtm_bp_dir_mem_send_private_message_button' ) ) {
	add_action( 'bp_directory_members_actions', 'wbtm_bp_dir_mem_send_private_message_button' );

	function wbtm_bp_dir_mem_send_private_message_button() {
		if ( !bp_is_active( 'messages' ) ) {
			return false;
		}

		$user_id = bp_get_member_user_id();

		if ( bp_is_my_profile() || !is_user_logged_in() ) {
			return false;
		}

		$private_msg_url = wp_nonce_url( bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . bp_core_get_username( $user_id ) );
		$defaults		 = array(
			'block_self'		 => true,
			'must_be_logged_in'	 => true,
			'component'			 => 'messages',
			'wrapper_class'		 => 'message-button',
			'link_class'		 => 'wbtm-send-message',
			'link_text'			 => __( 'Message', 'reign' ),
			'id'				 => 'private_message-' . $user_id,
			'wrapper_id'		 => 'send-private-message-' . $user_id,
			'link_href'			 => $private_msg_url,
			'link_title'		 => __( 'Send a private message to this user.', 'reign' ),
		);

		// Get Button Html Code.
		echo bp_get_button( $defaults );
	}

}

// }

/**
 * *******************************************************
 * *******************************************************
 * ******* MEMBER DIRECTORY CUSTOMIZATION :: END ********
 * *******************************************************
 * *******************************************************
 */
/**
 * *******************************************************
 * *******************************************************
 * ******* MEMBER SINGLE PAGE CUSTOMIZATION :: START ********
 * *******************************************************
 * *******************************************************
 */
/**
 * Adds specific xprofile data to member header section on single member page
 *
 * @since   1.0.2
 */
if ( !function_exists( 'rth_bp_member_header_meta_xprofile_data' ) ) {
	add_action( 'bp_before_member_header_meta', 'rth_bp_member_header_meta_xprofile_data', 10 );

	function rth_bp_member_header_meta_xprofile_data() {
		global $wbtm_reign_settings;
		$selected_xprofile_field = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'selected_xprofile_field' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'selected_xprofile_field' ] : '';
		if ( !empty( $selected_xprofile_field ) && ( $selected_xprofile_field != '-1' ) ) {
			$field_id	 = $selected_xprofile_field;
			$field_value = bp_get_profile_field_data( array(
				'field'		 => $field_id,
				'user_id'	 => bp_displayed_user_id(),
			) );
			if ( !empty( $field_value ) ) {
				$field		 = new BP_XProfile_Field( $field_id );
				$field_name	 = $field->name;
				echo '<div class="reign-xprofile-data">';
				echo '<span class="field-value">';
				echo $field_value;
				echo '</span>';
				echo '</div>';
			}
		}
	}

}

/**
 * showing social media links on member cover image
 *
 * @since 1.0.7
 */
if ( !function_exists( 'rth_bp_member_header_meta_social_links' ) ) {
	add_action( 'bp_before_member_header_meta', 'rth_bp_member_header_meta_social_links', 15 );

	function rth_bp_member_header_meta_social_links() {
		$socials		 = get_user_meta( bp_displayed_user_id(), "wbtm_user_social_links", true );
		$html_to_render	 = '<div class="wbtm-social-media-links">';
		$counter		 = 0;
		$first_time		 = true;
		foreach ( wbcom_get_user_social_array() as $field_slug => $social ) {
			$counter++;
			if ( !isset( $socials[ $field_slug ] ) || empty( $socials[ $field_slug ] ) ) {
				continue;
			}
			if ( $first_time ) {
				$html_to_render	 .= '<ul>';
				$first_time		 = false;
			}
			$html_to_render	 .= '<li>';
			$html_to_render	 .= '<a href="' . $socials[ $field_slug ] . '" title="' . $social[ 'name' ] . '" target="_blank">';
			if ( empty( $social[ 'img_url' ] ) ) {
				$html_to_render .= '<i class="fa fa-' . strtolower( trim( $social[ 'name' ] ) ) . '"></i>';
			} else {
				$html_to_render .= '<img src="' . $social[ 'img_url' ] . '" />';
			}
			$html_to_render	 .= '</a>';
			$html_to_render	 .= '</li>';
			if ( $counter == count( wbcom_get_user_social_array() ) ) {
				$html_to_render .= '</ul>';
			}
		}
		$html_to_render .= '</div>';
		echo $html_to_render;
	}

}

/**
 * managing the positin of social media links on member cover image
 * For Header Type #3 only
 * @since 1.0.7
 */
if ( !function_exists( 'rth_bp_member_header_meta_top_social_links' ) ) {
	add_action( 'wbtm_bp_before_displayed_user_mentionname', 'rth_bp_member_header_meta_top_social_links', 10 );

	function rth_bp_member_header_meta_top_social_links() {
		global $wbtm_reign_settings;
		$member_header_class = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_type' ] : 'wbtm-cover-header-type-1';
		$member_header_class = apply_filters( 'wbtm_rth_manage_member_header_class', $member_header_class );
		if ( $member_header_class == 'wbtm-cover-header-type-3' ) {
			remove_action( 'bp_before_member_header_meta', 'rth_bp_member_header_meta_social_links', 15 );
			rth_bp_member_header_meta_social_links();
		}
	}

}


/**
 * showing member statistics on member cover image
 *
 * @since 1.0.7
 */
if ( !function_exists( 'wbtm_render_extra_usermeta_info' ) ) {
	add_action( 'wbtm_member_extra_info_section', 'wbtm_render_extra_usermeta_info' );

	function wbtm_render_extra_usermeta_info() {
		$info_array	 = array();
		$user_id	 = bp_displayed_user_id();

		if ( bp_is_active( 'friends' ) ) {
			$friends_count			 = friends_get_total_friend_count( $user_id );
			$info_array[ 'friends' ] = array(
				'value'	 => $friends_count,
				'label'	 => __( 'Friends', 'reign' ),
			);
		}

		if ( class_exists( 'BP_Follow_Component' ) ) {
			$followers					 = bp_follow_get_followers();
			$info_array[ 'followers' ]	 = array(
				'value'	 => count( $followers ),
				'label'	 => __( 'Followers', 'reign' ),
			);
			$following					 = bp_follow_get_following();
			$info_array[ 'following' ]	 = array(
				'value'	 => count( $following ),
				'label'	 => __( 'Following', 'reign' ),
			);
		}

		if ( class_exists( 'BadgeOS' ) && class_exists( 'BadgeOS_Community' ) ) {
			$user_points = get_user_meta( $user_id, $meta_key	 = '_badgeos_points', true );
			if ( empty( $user_points ) ) {
				$user_points = 0;
			}
			$info_array[ 'badgeos_points' ] = array(
				'value'	 => $user_points,
				'label'	 => __( 'Points', 'reign' ),
			);
		}

		if ( class_exists( 'myCRED_Core' ) ) {
			global $mycred, $mycred_modules;
			$myCRED_BuddyPress_Module_Obj	 = $mycred_modules[ 'type' ][ 'mycred_default' ][ 'buddypress' ];
			$users_balance					 = $mycred->get_users_balance( $user_id );
			$users_balance					 = $mycred->format_creds( $users_balance );
			$info_array[ 'mycred_points' ]	 = array(
				'value'	 => $users_balance,
				'label'	 => __( 'Points', 'reign' ),
			);
		}

		foreach ( $info_array as $key => $info ) {
			?>
			<div class="rtm-usermeta-box">
				<span class="rtm-usermeta-count">
					<?php echo $info[ 'value' ]; ?>
				</span>
				<span class="rtm-usermeta-text">
					<?php echo $info[ 'label' ]; ?>
				</span>
			</div>
			<?php
		}
	}

}

/**
 * Compatibility with myCRED plugin
 * removes the default value render by myCRED plugin
 *
 * @since   1.0.7
 */
if ( !function_exists( 'wbtm_remove_mycred_bp_profile_header' ) ) {
	add_filter( 'mycred_bp_profile_header', 'wbtm_remove_mycred_bp_profile_header', 10, 3 );

	function wbtm_remove_mycred_bp_profile_header( $output, $myCRED_buddypress_bal_template, $myCRED_BuddyPress_Module_Obj ) {
		$output = '';
		return $output;
	}

}

/**
********************************************************
********************************************************
******** MEMBER SINGLE PAGE CUSTOMIZATION :: END ********
********************************************************
********************************************************
*/