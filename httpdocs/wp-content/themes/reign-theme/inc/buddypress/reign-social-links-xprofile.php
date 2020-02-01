<?php
/* * * Social fields ** */

add_action( 'after_switch_theme', 'wbcom_rtm_set_default_social_fields' );
function wbcom_rtm_set_default_social_fields () {
	global $wbtm_reign_settings;
	$wbtm_social_links	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'wbtm_social_links' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'wbtm_social_links' ] : array();
	if( empty( $wbtm_social_links ) ) {
		$wbtm_social_links = array(
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
		$wbtm_reign_settings[ 'reign_buddyextender' ][ 'wbtm_social_links' ] = $wbtm_social_links;
		update_option( "reign_options", $wbtm_reign_settings );
		$wbtm_reign_settings = get_option( "reign_options", array() );
	}
}

/**
 * Save the user social fields data
 * @since Reign 1.0.0
 * */
add_action( "xprofile_updated_profile", "wbcom_user_social_fields_save", 1, 3 );

function wbcom_user_social_fields_save( $user_id, $posted_field_ids, $errors ) {
	if ( empty( $user_id ) ) { // no user ah! skip it then.
		return;
	}
	$socials = get_user_meta( $user_id, "wbtm_user_social_links", true );
	if ( !is_array( $socials ) ) {
		$socials = array();
	}
	if ( isset( $_POST[ "wbtm_user_social_links" ] ) && $_POST[ "wbtm_user_social_links" ] == "1" ) {
		foreach ( wbcom_get_user_social_array() as $field_slug => $social ) {
			$slug	 = $field_slug;
			$url	 = $_POST[ "wbcom_social_" . $slug ];
			//check if its valid URL
			if ( filter_var( $url, FILTER_VALIDATE_URL ) || empty( $url ) ) {
				$socials[ $slug ] = $url;
				update_user_meta( $user_id, $slug, $url ); //update it
			}
		}
		update_user_meta( $user_id, "wbtm_user_social_links", $socials ); //update it
	}
}

add_action( "bp_after_profile_field_content", "wbcom_user_social_fields" );

function wbcom_user_social_fields( $user_id = false ) {
	if ( !$user_id ) {
		$user_id = bp_displayed_user_id();
	}
	/* field will only shown on base.
	 * so if in case we are on somewhere else then skip it !
	 *
	 * It's safe enough to assume that 'base' profile group will always be there and its id will be 1,
	 * since there's no apparent way of deleting this field group.
	 */
	if ( !function_exists( 'bp_get_the_profile_group_id' ) || ( function_exists( 'bp_get_the_profile_group_id' ) && bp_get_the_profile_group_id() != 1 ) ) {
		return;
	}
	$socials = (array) get_user_meta( $user_id, "wbtm_user_social_links", true );
	$socials = apply_filters( "wbcom_get_user_social_array", $socials, $user_id );
	if ( 'edit' == bp_current_action() ) {
		?>
		<div class="editfield field_name required-field visibility-public field_type_textbox">
			<fieldset>
				<legend><?php _e( 'Social', 'reign' ); ?></legend>
				<div class="wbcom-user-social">
					<input type="hidden" name="wbtm_user_social_links" value="1">
					<?php foreach ( wbcom_get_user_social_array() as $field_slug => $social ) { ?>
						<div class="bp-profile-field editfield field_type_textbox field_<?php echo $field_slug; ?>">
							<label for="wbcom_social_<?php echo $field_slug; ?>"><?php echo $social[ 'name' ]; ?></label>
							<input id="wbcom_social_<?php echo $field_slug; ?>" name="wbcom_social_<?php echo $field_slug; ?>" type="text" value="<?php echo esc_attr( @$socials[ $field_slug ] ); ?>" />
						</div>
					<?php } ?>
				</div>
			</fieldset>
		</div>
		<?php
	} else {
		$val = wbcom_get_user_social_array();
		if ( wbcom_array_not_all_empty( $socials, $val ) and ! empty( $val ) ) {
			?>
			<div class="bp-widget social">
				<h2><?php _e( 'Social', 'reign' ); ?></h2>
				<table class="profile-fields">
					<tbody>
						<?php foreach ( wbcom_get_user_social_array() as $field_slug => $social ) : ?>
							<?php
							if ( !isset( $socials[ $field_slug ] ) ) {
								continue;
							}
							$field_value = $socials[ $field_slug ];
							if ( empty( $field_value ) ) {
								continue;
							}
							$field_value = make_clickable( $field_value );
							?>
							<tr class="field_type_textbox field_<?php echo $field_slug; ?>">
								<td class="label"><?php echo $social[ 'name' ]; ?></td>
								<td class="data"><?php echo $field_value; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php
		}
	}
}

function wbcom_get_user_social_array() {
	global $wbtm_reign_settings;
	$wbtm_social_links	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'wbtm_social_links' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'wbtm_social_links' ] : array();
	return $wbtm_social_links;
}

function wbcom_array_not_all_empty( $xprofile_links, $backend_keys ) {
	foreach ($backend_keys as $key => $value) {
		if( isset( $xprofile_links[$key] ) && !empty( $xprofile_links[$key] ) ) {
			return true;
		}
	}
	return false;
}