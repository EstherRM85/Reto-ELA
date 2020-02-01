<?php

function wbtm_rth_manage_member_header_pos_from_frontend( $member_header_position ) {
	$user_id	 = bp_displayed_user_id();
	$header_view = get_user_meta( $user_id, "wbtm_user_header_view", true );
	if ( isset( $header_view[ 'position' ] ) ) {
		$member_header_position = $header_view[ 'position' ];
	}
	return $member_header_position;
}

add_filter( 'wbtm_rth_manage_member_header_position', 'wbtm_rth_manage_member_header_pos_from_frontend' );

function wbtm_rth_manage_member_header_type_from_frontend( $member_header_class ) {
	$user_id	 = bp_displayed_user_id();
	$header_view = get_user_meta( $user_id, "wbtm_user_header_view", true );
	if ( isset( $header_view[ 'type' ] ) ) {
		$member_header_class = $header_view[ 'type' ];
	}
	return $member_header_class;
}

add_filter( 'wbtm_rth_manage_member_header_class', 'wbtm_rth_manage_member_header_type_from_frontend' );

/**
 * Save the user social fields data
 * @since Reign 1.0.0
 * */
function wbtm_rtm_save_header_view_info( $user_id, $posted_field_ids, $errors ) {
	if ( empty( $user_id ) ) { // no user ah! skip it then.
		return;
	}
	if ( isset( $_POST[ 'wbtm_user_header_view' ][ 'identifier' ] ) && ( "1" === $_POST[ 'wbtm_user_header_view' ][ 'identifier' ] ) ) {
		update_user_meta( $user_id, "wbtm_user_header_view", $_POST[ "wbtm_user_header_view" ] ); //update it
	}
}

add_action( "xprofile_updated_profile", "wbtm_rtm_save_header_view_info", 1, 3 );

function wbtm_rtm_header_view_mgmt_section( $user_id = false ) {
	if ( !$user_id ) {
		$user_id = bp_displayed_user_id();
	}

	if ( !function_exists( 'bp_get_the_profile_group_id' ) || ( function_exists( 'bp_get_the_profile_group_id' ) && bp_get_the_profile_group_id() != 1 ) ) {
		return;
	}
	$header_view = (array) get_user_meta( $user_id, "wbtm_user_header_view", true );
	$header_view = apply_filters( "wbcom_get_user_social_array", $header_view, $user_id );
	if ( 'edit' == bp_current_action() ) {
		?>
		<div class="editfield field_name required-field visibility-public field_type_textbox">
			<fieldset>
				<legend><?php esc_html_e( 'Profile Header View', 'reign' ); ?></legend>
				<div class="wbtm-rtm-header-view">
					<input type="hidden" name="wbtm_user_header_view[identifier]" value="1">
					<div class="bp-profile-field editfield">
						<label>
									<?php esc_html_e( 'Select Member Header Position', 'reign' ); ?>
							<div class="rtm-tooltip">?
								<span class="rtm-tooltiptext">
						<?php esc_html_e( 'Select Member Header Position', 'reign' ); ?>
								</span>
							</div>
						</label>
						<?php
						global $wbtm_reign_settings;
						$header_view = get_user_meta( $user_id, "wbtm_user_header_view", true );

						if ( isset( $header_view[ 'position' ] ) ) {
							$member_header_position = $header_view[ 'position' ];
						} else {
							$member_header_position = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] : 'inside';
						}
						$member_header_positions = array(
							'inside' => array(
								'name'		 => __( 'Inside', 'reign' ),
								'img_url'	 => '',
							),
							'top'	 => array(
								'name'		 => __( 'Top', 'reign' ),
								'img_url'	 => '',
							)
						);
						echo '<select name="wbtm_user_header_view[position]">';
						foreach ( $member_header_positions as $slug => $position ) {
							echo '<option value="' . $slug . '" ' . selected( $member_header_position, $slug ) . '>' . $position[ 'name' ] . '</option>';
						}
						echo '</select>';
						?>
					</div>
					<hr/>
					<div class="bp-profile-field editfield">
						<label>
		<?php esc_html_e( 'Select Member Header Layout', 'reign' ); ?>
							<div class="rtm-tooltip">?
								<span class="rtm-tooltiptext">
		<?php esc_html_e( 'Select Member Header Layout', 'reign' ); ?>
								</span>
							</div>
						</label>
						<?php
						if ( isset( $header_view[ 'type' ] ) ) {
							$member_header_type = $header_view[ 'type' ];
						} else {
							$member_header_type = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_type' ] : 'wbtm-cover-header-type-1';
						}
						$member_header_types = array(
							'wbtm-cover-header-type-1'	 => array(
								'name'		 => __( 'Layout #1', 'reign' ),
								'img_url'	 => REIGN_INC_DIR_URI . 'reign-settings/imgs/header-design-1.jpg',
							),
							'wbtm-cover-header-type-2'	 => array(
								'name'		 => __( 'Layout #2', 'reign' ),
								'img_url'	 => REIGN_INC_DIR_URI . 'reign-settings/imgs/header-design-2.jpg',
							),
							'wbtm-cover-header-type-3'	 => array(
								'name'		 => __( 'Layout #3', 'reign' ),
								'img_url'	 => REIGN_INC_DIR_URI . 'reign-settings/imgs/header-design-3.jpg',
							),
						);
						$member_header_types = apply_filters( 'reign_member_header_types_layout_options', $member_header_types );

						echo '<div class="wbtm-radio-img-selector-sec">';
						echo '<ul>';
						foreach ( $member_header_types as $slug => $header ) {
							echo '<li>';
							echo '<input type="radio" name="wbtm_user_header_view[type]" value="' . $slug . '" id="member-' . $slug . '" ' . checked( $member_header_type, $slug, false ) . ' />';
							echo '<label for="member-' . $slug . '"><img src="' . $header[ 'img_url' ] . '" /><span>' . $header[ 'name' ] . '</span></label>';
							echo '</li>';
						}
						echo '</ul>';
						echo '</div>';
						?>
					</div>
				</div>
			</fieldset>
		</div>
		<?php
	}
}

add_action( 'bp_after_profile_field_content', 'wbtm_rtm_header_view_mgmt_section' );
