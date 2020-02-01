<?php
/**
 *
 * This file is used for rendering and saving plugin settings logged-in user.
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$blpro_login_settings = bp_get_option( 'blpro_login_settings' );

$bp_components = array(
	'members'         => __( 'Community Members', 'buddypress-private-community-pro' ),
	'settings'        => __( 'Account Settings', 'buddypress-private-community-pro' ),
	'friends'         => __( 'Friend Connections', 'buddypress-private-community-pro' ),
	'messages'        => __( 'Private Messaging', 'buddypress-private-community-pro' ),
	'activity'        => __( 'Activity Streams', 'buddypress-private-community-pro' ),
	'profile'         => __( 'Profile', 'buddypress-private-community-pro' ),
	'notifications'   => __( 'Notifications', 'buddypress-private-community-pro' ),
	'groups'   		  => __( 'User Groups', 'buddypress-private-community-pro' ),
	'forums'   		  => __( 'Group Forums', 'buddypress-private-community-pro' ),
	'blogs'   		  => __( 'Site Tracking', 'buddypress-private-community-pro' )
);
$bp_activity = array(
	'private_message' => __( 'Private Message', 'buddypress-private-community-pro' ),
	'public_message'  => __( 'Public Message', 'buddypress-private-community-pro' ),
	'friend_req'      => __( 'Friendship Requests', 'buddypress-private-community-pro' ),
	'posting'         => __( 'Posting', 'buddypress-private-community-pro' ),
	'commenting'      => __( 'Commenting', 'buddypress-private-community-pro' )
);

global $wp_roles;
$user_roles = $wp_roles->get_names();

$users_list = get_users();

$member_types_exist = false;
$member_types = bp_get_member_types( $args = array(), $output = 'object' );
if(!empty( $member_types )){
	$member_types_exist = true;
}
$all_mt_index = array( 'all' => (object) array( 'labels' => array( 'name' => 'All' ) ) );
$member_types =  array_merge( $all_mt_index + $member_types );

global $bp;
$primary_nav = $bp->members->nav->get_primary( $args = array(), $sort = true );
$primary_nav = json_decode( json_encode( $primary_nav ) );
?>
<div class="wbcom-tab-content">
<form method="post" action="admin.php?action=update_network_options">
	<p class="description" style="margin-top:2%;font-size: 14px;" id="tagline-description"><?php esc_html_e( 'Note: These settings are only for logged-in users.', 'buddypress-private-community-pro' ); ?>
			</p>
	<?php
	settings_fields( 'blpro_logdin_settings' );
	do_settings_sections( 'blpro_logdin_settings' );
	?>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Remove admin roles from members directory', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<label class="blpro-switch">
					<input name='blpro_login_settings[remove_admin_roles]' type='checkbox' class="regular-text" value='yes' <?php (isset($blpro_login_settings['remove_admin_roles']))?checked($blpro_login_settings['remove_admin_roles'],'yes'):''; ?>/>
					<div class="blpro-slider blpro-round"></div>
				</label>
			<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: members with user role Administrator will not be listed in member directory.', 'buddypress-private-community-pro' ); ?>
			</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Remove users from member directory list', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select id="blpro-remove-users-list" name="blpro_login_settings[remove_users][]" multiple>
					<?php foreach ( $users_list as $key => $user_data ) { ?>
					<?php $selected = (!empty( $blpro_login_settings['remove_users'] ) && in_array( $user_data->ID, $blpro_login_settings['remove_users'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $user_data->ID; ?>" <?php echo $selected; ?>><?php echo $user_data->data->display_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Display profile progress bar', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<label class="blpro-switch">
					<input name='blpro_login_settings[progress_bar]' type='checkbox' class="regular-text" value='yes' <?php (isset($blpro_login_settings['progress_bar']))?checked($blpro_login_settings['progress_bar'],'yes'):''; ?>/>
					<div class="blpro-slider blpro-round"></div>
				</label>
			<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: profile progress bar will be displayed at member profile page.', 'buddypress-private-community-pro' ); ?>
			</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'User profile completeness percentage to get listed in member directory ', 'buddypress-private-community-pro' ); ?></label></th>
			<td><input name='blpro_login_settings[member_after_percent]' type='number' class="regular-text" min="1" max="100" value='<?php echo (isset($blpro_login_settings['member_after_percent']))?$blpro_login_settings['member_after_percent']:''; ?>' />
			<p class="description" id="tagline-description"><?php esc_html_e( 'User with profile completeness percentage greater than or equal to the entered value will get listed in member directory.', 'buddypress-private-community-pro' ); ?>
			</p>
			<p class="description" id="tagline-description"><?php esc_html_e( 'Note : Leave blank to list all users.', 'buddypress-private-community-pro' ); ?>
			</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Enable profile visibility settings at front end', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<label class="blpro-switch">
					<input name='blpro_login_settings[prof_visib]' type='checkbox' class="regular-text" value='yes' <?php (isset($blpro_login_settings['prof_visib']))?checked($blpro_login_settings['prof_visib'],'yes'):''; ?>/>
					<div class="blpro-slider blpro-round"></div>
				</label>
			<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: users will be able to set profile visibility at front end.', 'buddypress-private-community-pro' ); ?>
			</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Lock buddypress activities', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<label class="blpro-switch">
					<input name='blpro_login_settings[lock_bp_act]' type='checkbox' class="regular-text locked_bp_comp_login" value='yes' <?php (isset($blpro_login_settings['lock_bp_act']))?checked($blpro_login_settings['lock_bp_act'],'yes'):''; ?>/>
					<div class="blpro-slider blpro-round"></div>
				</label>
			<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: selected users or user roles will not be able to access marked buddypress components.', 'buddypress-private-community-pro' ); ?>
			</p>
			</td>
		</tr>
		<?php
			if( isset( $blpro_login_settings['lock_bp_act'] ) ){
				$styles="";
			}else{
				$styles="display:none";
			}
		?>
		<tr style="<?php echo $styles; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Select buddypress activities to lock', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select id="blpro-bp-comp-list" name="blpro_login_settings[locked_bp_act][]" multiple>
					<?php foreach ( $bp_activity as $slug => $bp_act ) { ?>
					<?php $selected = (!empty( $blpro_login_settings['locked_bp_act'] ) && in_array( $slug, $blpro_login_settings['locked_bp_act'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $slug; ?>" <?php echo $selected; ?>><?php echo $bp_act; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr style="<?php echo $styles; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Lock buddypress components according to', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<fieldset>
					<label class="blpro-label-padding">
						<input class="blpro-lock-acc" data-id="lock_acc_roles" name="blpro_login_settings[lock_acc]" data-rows="bplock-rows" value="lock_acc_roles" type="radio" <?php ( isset( $blpro_login_settings['lock_acc'] ) )? checked($blpro_login_settings['lock_acc'],'lock_acc_roles'):''; ?>>
						<span class="blpro-span-text"><?php esc_html_e( 'User roles', 'buddypress-private-community-pro' ); ?></span>
					</label>
					<label class="blpro-label-padding">
						<input class="blpro-lock-acc" data-id="lock_acc_users" name="blpro_login_settings[lock_acc]" data-rows="bplock-rows" value="lock_acc_users" type="radio" <?php ( isset( $blpro_login_settings['lock_acc'] ) )? checked($blpro_login_settings['lock_acc'],'lock_acc_users'):''; ?>>
						<span class="blpro-span-text"><?php esc_html_e( 'Users', 'buddypress-private-community-pro' ); ?></span>
					</label>
					<label class="blpro-label-padding">
						<input class="blpro-lock-acc" data-id="lock_acc_mtypes" name="blpro_login_settings[lock_acc]" data-rows="bplock-rows" value="lock_acc_mtypes" type="radio" <?php ( isset( $blpro_login_settings['lock_acc'] ) )? checked($blpro_login_settings['lock_acc'],'lock_acc_mtypes'):''; ?>>
						<span class="blpro-span-text"><?php esc_html_e( 'Member Types', 'buddypress-private-community-pro' ); ?></span>
					</label>
				</fieldset>
			</td>
		</tr>
		<?php
			if( isset( $blpro_login_settings['lock_acc'] ) && 'lock_acc_roles' == $blpro_login_settings['lock_acc'] && isset( $blpro_login_settings['lock_bp_act'] )){
				$style="";
			}else{
				$style="display:none";
			}
		?>
		<tr class="bplock-rows" id="lock_acc_roles" style="<?php echo $style; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Select user roles', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select id="blpro-user-roles-list" name="blpro_login_settings[user_roles][]" multiple>
					<?php foreach ( $user_roles as $slug => $role_name ) { ?>
					<?php $selected = (!empty( $blpro_login_settings['user_roles'] ) && in_array( $slug, $blpro_login_settings['user_roles'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $slug; ?>" <?php echo $selected; ?>><?php echo $role_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<?php
			if( isset( $blpro_login_settings['lock_acc'] ) && 'lock_acc_users' == $blpro_login_settings['lock_acc'] && isset( $blpro_login_settings['lock_bp_act'] )){
				$style="";
			}else{
				$style="display:none";
			}
		?>
		<tr class="bplock-rows" id="lock_acc_users" style="<?php echo $style; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Select users', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select id="blpro-users-list" name="blpro_login_settings[users][]" multiple>
					<?php foreach ( $users_list as $key => $user_data ) { ?>
					<?php $selected = (!empty( $blpro_login_settings['users'] ) && in_array( $user_data->ID, $blpro_login_settings['users'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $user_data->ID; ?>" <?php echo $selected; ?>><?php echo $user_data->data->display_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<?php
			if( isset( $blpro_login_settings['lock_acc'] ) && 'lock_acc_mtypes' == $blpro_login_settings['lock_acc'] && isset( $blpro_login_settings['lock_bp_act'] )){
				$style="";
			}else{
				$style="display:none";
			}
		?>
		<tr class="bplock-rows" id="lock_acc_mtypes" style="<?php echo $style; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Select member types', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select id="blpro-member-types-list" name="blpro_login_settings[member_types][]" multiple>
					<?php foreach ( $member_types as $key => $type_obj ) { ?>
					<?php $selected = (!empty( $blpro_login_settings['member_types'] ) && in_array( $key, $blpro_login_settings['member_types'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $type_obj->labels['name']; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr id="hide-bp-primary-nav">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Hide Buddypress Primary Nav', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select class="blpro_primary_nav" name='blpro_login_settings[primary_nav][]' multiple>
					<?php foreach ($primary_nav as $key => $value) {
							if( !$value->parent ){
								
								if( is_array( $blpro_login_settings) && isset( $blpro_login_settings['primary_nav'] ) && in_array( $value->slug, $blpro_login_settings['primary_nav'] ) ) {
									$selected = 'selected';
								}else{
									$selected = '';
								}
								echo "<option value='".$value->slug."' ".$selected.">".$value->name."</option>";
							}
						  } 
					?>
				</select>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Select primary nav menus which you want to hide from buddypress nav.', 'buddypress-private-community-pro' ); ?>
				</p>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</form>
</div>
