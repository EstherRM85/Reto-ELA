<?php

if ( ! function_exists( 'reign_xprofile_cover_image' ) ) {

	function reign_xprofile_cover_image( $settings = array() ) {
		global $wbtm_reign_settings;
		$default_xprofile_cover_image_size = isset( $wbtm_reign_settings['reign_buddyextender']['default_xprofile_cover_image_size'] ) ? $wbtm_reign_settings['reign_buddyextender']['default_xprofile_cover_image_size'] : '';

		/* changing default user avatar */
		$avatar_default_image = isset( $settings[ 'reign_buddyextender' ][ 'avatar_default_image_id' ] ) ? $settings[ 'reign_buddyextender' ][ 'avatar_default_image_id' ] : '';
		if( !empty( $avatar_default_image ) ) {
			$settings['default_cover'] = $avatar_default_image;
		}
		/* changing default user avatar */
		
		if ( empty( $default_xprofile_cover_image_size ) ) {
			$settings['width']  = 1750;
			$settings['height'] = 450;
		}
		return $settings;
	}

	// add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'reign_xprofile_cover_image', 10, 1 );
}

/* Get User online */
if ( ! function_exists( 'reign_is_user_online' ) ) {

	/**
	 * Check if a Buddypress member is online or not
	 *
	 * @global object $wpdb
	 * @param  integer $user_id
	 * @param  integer $time
	 * @return boolean
	 */
	function reign_is_user_online( $user_id, $time = 5 ) {
		global $wpdb;
		$sql        = $wpdb->prepare(
			"SELECT u.user_login FROM $wpdb->users u JOIN $wpdb->usermeta um ON um.user_id = u.ID
			WHERE u.ID = %d
			AND um.meta_key = 'last_activity'
			AND DATE_ADD( um.meta_value, INTERVAL %d MINUTE ) >= UTC_TIMESTAMP()", $user_id, $time
		);
		$user_login = $wpdb->get_var( $sql );
		if ( isset( $user_login ) && $user_login != '' ) {
			return true;
		} else {
			return false;
		}
	}
}


if ( ! function_exists( 'reign_get_online_status' ) ) {

	function reign_get_online_status( $user_id ) {
		$output = '';
		if ( reign_is_user_online( $user_id ) ) {
			$output .= '<span class="reign-status online"></span>';
		} else {
			$output .= '<span class="reign-status offline"></span>';
		}
		return $output;
	}
}


if ( ! function_exists( 'reign_get_group_member_count' ) ) {

	function reign_get_group_member_count() {
		global $groups_template;
		if ( isset( $groups_template->group->total_member_count ) ) {
			echo '<span class="group-count">' . (int) $groups_template->group->total_member_count . '</span>';
		}
	}
}

if ( ! function_exists( 'reign_bp_get_group_type' ) ) {

	function reign_bp_get_group_type( $group = false ) {
		global $groups_template;

		if ( empty( $group ) ) {
			$group = & $groups_template->group;
		}

		if ( 'public' == $group->status ) {
			$type = __( 'Public', 'buddypress' );
		} elseif ( 'hidden' == $group->status ) {
			$type = __( 'Hidden', 'buddypress' );
		} elseif ( 'private' == $group->status ) {
			$type = __( 'Private', 'buddypress' );
		} else {
			$type = ucwords( $group->status ) . ' ' . __( 'Group', 'buddypress' );
		}

		return '<span class="group-type ' . $group->status . '">' . $type . '</span>';
	}
}

/**
 * Output markup listing group admins.
 *
 * @since 1.0.0
 *
 * @param object|bool $group Optional. Group object.
 *                           Default: current group in loop.
 */
function reign_bp_group_list_admins( $group = false ) {
	global $groups_template;

	if ( empty( $group ) ) {
		$group = & $groups_template->group;
	}

	if ( ! empty( $group->admins ) ) {
		$i = 0;
		?>
	<ul id="group-admins">
		<?php foreach ( (array) $group->admins as $admin ) { ?>
				<li class="group-admin-item">
					<a href="<?php echo esc_url(bp_core_get_user_domain( $admin->user_id, $admin->user_nicename, $admin->user_login )); ?>">
	<?php
	echo bp_core_fetch_avatar(
		array(
			'item_id' => $admin->user_id,
			'email'   => $admin->user_email,
			'alt'     => sprintf( __( 'Profile picture of %s', 'buddypress' ), bp_core_get_user_displayname( $admin->user_id ) ),
		)
	);
?>
					</a>
					<div class="group-admin-meta">
						<span class="group-by"><?php _e( 'Created by', 'reign' ); ?></span>
						<span class="admin-name"><a href="<?php echo bp_core_get_user_domain( $admin->user_id, $admin->user_nicename, $admin->user_login ); ?>"><?php echo bp_core_get_user_displayname( $admin->user_id ); ?></a></span>
					</div>
				</li>
				<?php
				$i++;
				if ( $i == 1 ) {
					break;
				}
}
	?>
	</ul>
	<?php } else { ?>
		<span class="activity"><?php _e( 'No Admins', 'buddypress' ); ?></span>
														<?php
}
}

if ( ! function_exists( 'reign_group_index_widgets' ) ) {

	function reign_group_index_widgets() {
		get_template_part( 'template-parts/groups-widgets' );
	}

	add_action( 'wbcom_begin_group_index_sidebar', 'reign_group_index_widgets' );
}

if ( ! function_exists( 'reign_member_index_widgets' ) ) {

	function reign_member_index_widgets() {
		get_template_part( 'template-parts/members-widgets' );
	}

	add_action( 'wbcom_begin_member_index_sidebar', 'reign_member_index_widgets' );
}
