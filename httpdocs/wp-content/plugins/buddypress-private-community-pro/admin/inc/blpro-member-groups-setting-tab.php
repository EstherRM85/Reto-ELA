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

$blpro_groups_settings = bp_get_option( 'blpro_groups_settings' );

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
?>
<div class="wbcom-tab-content">
<form method="post" action="admin.php?action=update_network_options">
	<p class="description" style="margin-top:2%;font-size: 14px;" id="tagline-description"><?php esc_html_e( 'Note: These settings are only for logged-in users and not applicable for administrator roles.', 'buddypress-private-community-pro' ); ?>
	</p>
	<?php
	settings_fields( 'blpro_groups_settings' );
	do_settings_sections( 'blpro_groups_settings' );
	?>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Number of groups a member can create', 'buddypress-private-community-pro' ); ?></label></th>
			<td><input name='blpro_groups_settings[create_num]' min="1" type='number' class="regular-text" max="100" value='<?php echo (isset($blpro_groups_settings['create_num']))?$blpro_groups_settings['create_num']:''; ?>' />
			<p class="description" id="tagline-description"><?php esc_html_e( 'This setting restrict members to create only specified number of groups.', 'buddypress-private-community-pro' ); ?>
			</p>
			<p class="description" id="tagline-description"><?php esc_html_e( 'Note : Leave blank for no limit', 'buddypress-private-community-pro' ); ?>
			</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Restrict no of groups creation limit according to', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<fieldset>
					<label class="blpro-label-padding">
						<input class="blpro-lock-acc" data-id="create_num_acc_roles" name="blpro_groups_settings[create_num_acc]" data-rows="create_num_rows" value="create_num_acc_roles" type="radio" <?php ( isset( $blpro_groups_settings['create_num_acc'] ) )? checked($blpro_groups_settings['create_num_acc'],'create_num_acc_roles'):''; ?>>
						<span class="blpro-span-text"><?php esc_html_e( 'User roles', 'buddypress-private-community-pro' ); ?></span>
					</label>
					<label class="blpro-label-padding">
						<input class="blpro-lock-acc" data-id="create_num_acc_users" name="blpro_groups_settings[create_num_acc]" data-rows="create_num_rows" value="create_num_acc_users" type="radio" <?php ( isset( $blpro_groups_settings['create_num_acc'] ) )? checked($blpro_groups_settings['create_num_acc'],'create_num_acc_users'):''; ?>>
						<span class="blpro-span-text"><?php esc_html_e( 'Users', 'buddypress-private-community-pro' ); ?></span>
					</label>
					<label class="blpro-label-padding">
						<input class="blpro-lock-acc" data-id="create_num_acc_mtypes" name="blpro_groups_settings[create_num_acc]" data-rows="create_num_rows" value="create_num_acc_mtypes" type="radio" <?php ( isset( $blpro_groups_settings['create_num_acc'] ) )? checked($blpro_groups_settings['create_num_acc'],'create_num_acc_mtypes'):''; ?>>
						<span class="blpro-span-text"><?php esc_html_e( 'Member Types', 'buddypress-private-community-pro' ); ?></span>
					</label>
				</fieldset>
			</td>
		</tr>
		<?php
			if( isset( $blpro_groups_settings['create_num_acc'] ) && 'create_num_acc_roles' == $blpro_groups_settings['create_num_acc'] ){
				$style="";
			}else{
				$style="display:none";
			}
		?>
		<tr class="create_num_rows" id="create_num_acc_roles" style="<?php echo $style; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Select user roles', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select class="blpro-user-roles-list" name="blpro_groups_settings[create_num_user_roles][]" multiple>
					<?php foreach ( $user_roles as $slug => $role_name ) { ?>
					<?php $selected = (!empty( $blpro_groups_settings['create_num_user_roles'] ) && in_array( $slug, $blpro_groups_settings['create_num_user_roles'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $slug; ?>" <?php echo $selected; ?>><?php echo $role_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<?php
			if( isset( $blpro_groups_settings['create_num_acc'] ) && 'create_num_acc_users' == $blpro_groups_settings['create_num_acc'] ){
				$style="";
			}else{
				$style="display:none";
			}
		?>
		<tr class="create_num_rows" id="create_num_acc_users" style="<?php echo $style; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Select users', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select class="blpro-users-list" name="blpro_groups_settings[create_num_users][]" multiple>
					<?php foreach ( $users_list as $key => $user_data ) { ?>
					<?php $selected = (!empty( $blpro_groups_settings['create_num_users'] ) && in_array( $user_data->ID, $blpro_groups_settings['create_num_users'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $user_data->ID; ?>" <?php echo $selected; ?>><?php echo $user_data->data->display_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<?php
			if( isset( $blpro_groups_settings['create_num_acc'] ) && 'create_num_acc_mtypes' == $blpro_groups_settings['create_num_acc'] ){
				$style="";
			}else{
				$style="display:none";
			}
		?>
		<tr class="create_num_rows" id="create_num_acc_mtypes" style="<?php echo $style; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Select member types', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select class="blpro-member-types-list" name="blpro_groups_settings[create_num_member_types][]" multiple>
					<?php foreach ( $member_types as $key => $type_obj ) { ?>
					<?php $selected = (!empty( $blpro_groups_settings['create_num_member_types'] ) && in_array( $key, $blpro_groups_settings['create_num_member_types'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $type_obj->labels['name']; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Number of groups a member can join', 'buddypress-private-community-pro' ); ?></label></th>
			<td><input name='blpro_groups_settings[can_join]' min="1" type='number' class="regular-text" max="100" value='<?php echo (isset($blpro_groups_settings['can_join']))?$blpro_groups_settings['can_join']:''; ?>' />
			<p class="description" id="tagline-description"><?php esc_html_e( 'This setting restrict members to join only specified number of groups.', 'buddypress-private-community-pro' ); ?>
			</p>
			<p class="description" id="tagline-description"><?php esc_html_e( 'Note : Leave blank for no limit', 'buddypress-private-community-pro' ); ?>
			</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Restrict no of groups join limit according to', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<fieldset>
					<label class="blpro-label-padding">
						<input class="blpro-lock-acc" data-id="can_join_acc_roles" name="blpro_groups_settings[can_join_acc]" data-rows="can_join_rows" value="can_join_acc_roles" type="radio" <?php ( isset( $blpro_groups_settings['can_join_acc'] ) )? checked($blpro_groups_settings['can_join_acc'],'can_join_acc_roles'):''; ?>>
						<span class="blpro-span-text"><?php esc_html_e( 'User roles', 'buddypress-private-community-pro' ); ?></span>
					</label>
					<label class="blpro-label-padding">
						<input class="blpro-lock-acc" data-id="can_join_acc_users" name="blpro_groups_settings[can_join_acc]" data-rows="can_join_rows" value="can_join_acc_users" type="radio" <?php ( isset( $blpro_groups_settings['can_join_acc'] ) )? checked($blpro_groups_settings['can_join_acc'],'can_join_acc_users'):''; ?>>
						<span class="blpro-span-text"><?php esc_html_e( 'Users', 'buddypress-private-community-pro' ); ?></span>
					</label>
					<label class="blpro-label-padding">
						<input class="blpro-lock-acc" data-id="can_join_acc_mtypes" name="blpro_groups_settings[can_join_acc]" data-rows="can_join_rows" value="can_join_acc_mtypes" type="radio" <?php ( isset( $blpro_groups_settings['can_join_acc'] ) )? checked($blpro_groups_settings['can_join_acc'],'can_join_acc_mtypes'):''; ?>>
						<span class="blpro-span-text"><?php esc_html_e( 'Member Types', 'buddypress-private-community-pro' ); ?></span>
					</label>
				</fieldset>
			</td>
		</tr>
		<?php
			if( isset( $blpro_groups_settings['can_join_acc'] ) && 'can_join_acc_roles' == $blpro_groups_settings['can_join_acc'] ){
				$style="";
			}else{
				$style="display:none";
			}
		?>
		<tr class="can_join_rows" id="can_join_acc_roles" style="<?php echo $style; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Select user roles', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select class="blpro-user-roles-list" name="blpro_groups_settings[can_join_user_roles][]" multiple>
					<?php foreach ( $user_roles as $slug => $role_name ) { ?>
					<?php $selected = (!empty( $blpro_groups_settings['can_join_user_roles'] ) && in_array( $slug, $blpro_groups_settings['can_join_user_roles'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $slug; ?>" <?php echo $selected; ?>><?php echo $role_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<?php
			if( isset( $blpro_groups_settings['can_join_acc'] ) && 'can_join_acc_users' == $blpro_groups_settings['can_join_acc'] ){
				$style="";
			}else{
				$style="display:none";
			}
		?>
		<tr class="can_join_rows" id="can_join_acc_users" style="<?php echo $style; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Select users', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select class="blpro-users-list" name="blpro_groups_settings[can_join_users][]" multiple>
					<?php foreach ( $users_list as $key => $user_data ) { ?>
					<?php $selected = (!empty( $blpro_groups_settings['can_join_users'] ) && in_array( $user_data->ID, $blpro_groups_settings['can_join_users'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $user_data->ID; ?>" <?php echo $selected; ?>><?php echo $user_data->data->display_name; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<?php
			if( isset( $blpro_groups_settings['can_join_acc'] ) && 'can_join_acc_mtypes' == $blpro_groups_settings['can_join_acc'] ){
				$style="";
			}else{
				$style="display:none";
			}
		?>
		<tr class="can_join_rows" id="can_join_acc_mtypes" style="<?php echo $style; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Select member types', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select class="blpro-member-types-list" name="blpro_groups_settings[can_join_member_types][]" multiple>
					<?php foreach ( $member_types as $key => $type_obj ) { ?>
					<?php $selected = (!empty( $blpro_groups_settings['can_join_member_types'] ) && in_array( $key, $blpro_groups_settings['can_join_member_types'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $type_obj->labels['name']; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Limit number of member per group', 'buddypress-private-community-pro' ); ?></label></th>
			<td><input name='blpro_groups_settings[member_per_group]' min="1" type='number' class="regular-text" max="100" value='<?php echo (isset($blpro_groups_settings['member_per_group']))?$blpro_groups_settings['member_per_group']:''; ?>' />
			<p class="description" id="tagline-description"><?php esc_html_e( 'Enter the limit a group can contain members.', 'buddypress-private-community-pro' ); ?>
			</p>
			<p class="description" id="tagline-description"><?php esc_html_e( 'Note : Leave blank for no limit', 'buddypress-private-community-pro' ); ?>
			</p>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</form>
</div>