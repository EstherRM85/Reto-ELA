<?php
/**
 *
 * This file is used for rendering and saving plugin settings for non logged-in user.
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$blpro_nl_settings = bp_get_option( 'blpro_nl_settings' );

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

$args    = array( 'public' => true, '_builtin' => false, 'exclude_from_search' => false );
$cpts    = get_post_types( $args, 'objects' );

$pages = get_pages();
foreach( $pages as $page ) {
	$wp_pages[$page->ID] = $page->post_title;
}

global $bp;
$primary_nav = $bp->members->nav->get_primary( $args = array(), $sort = true );
$primary_nav = json_decode( json_encode( $primary_nav ) );
?>
<div class="wbcom-tab-content">
<form method="post" action="admin.php?action=update_network_options">
	<p class="description" style="margin-top:2%;font-size: 14px;" id="tagline-description"><?php esc_html_e( 'Note: These settings are only for non logged-in users.', 'buddypress-private-community-pro' ); ?>
			</p>
	<?php
	settings_fields( 'blpro_nonlogdin_settings' );
	do_settings_sections( 'blpro_nonlogdin_settings' );
	?>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Lock buddypress components', 'buddypress-private-community-pro' ); ?></label></th>
			
			<td>
				<label class="blpro-switch">
					<input name='blpro_nl_settings[lock_bp_components]' type='checkbox' class="regular-text blpro-disp-resp-tr" data-id="locked_bp_components" value='yes' <?php (isset($blpro_nl_settings['lock_bp_components']))?checked($blpro_nl_settings['lock_bp_components'],'yes'):''; ?>/>
					<div class="blpro-slider blpro-round"></div>
				</label>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: non logged-in users will not be able to access buddypress components.', 'buddypress-private-community-pro' ); ?>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Hide Buddypress Primary Nav', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select class="blpro_primary_nav" name='blpro_nl_settings[primary_nav][]' multiple>
					<?php foreach ($primary_nav as $key => $value) {
							if( !$value->parent ){
								
								if( is_array( $blpro_nl_settings) && isset( $blpro_nl_settings['primary_nav'] ) && in_array( $value->slug, $blpro_nl_settings['primary_nav'] ) ) {
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
		<?php 
			if( isset( $blpro_nl_settings['lock_bp_components'] ) ){
				$style="";
			}else{
				$style="display:none";
			} 
		?>
		<tr id="locked_bp_components" style="<?php echo $style; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Select components to lock', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select id="blpro-bp-components-list" name="blpro_nl_settings[locked_bp_components][]" multiple>
					<?php foreach ( $bp_components as $slug => $bp_component ) { ?>
					<?php $selected = (!empty( $blpro_nl_settings['locked_bp_components'] ) && in_array( $slug, $blpro_nl_settings['locked_bp_components'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $slug; ?>" <?php echo $selected; ?>><?php echo $bp_component; ?></option>
					<?php } ?>
				</select>
			<p class="description" id="tagline-description"><?php esc_html_e( 'Select the buddypress components that you wish to restrict access for logged out users.', 'buddypress-private-community-pro' ); ?>
			</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Lock custom post types', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<label class="blpro-switch">
					<input name='blpro_nl_settings[lock_cpt]' type='checkbox' class="regular-text blpro-disp-resp-tr" data-id="locked_cpts" value='yes' <?php (isset($blpro_nl_settings['lock_cpt']))?checked($blpro_nl_settings['lock_cpt'],'yes'):''; ?>/>
					<div class="blpro-slider blpro-round"></div>
				</label>
			<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: non logged-in users will not be able to access custom post types pages.', 'buddypress-private-community-pro' ); ?>
			</p>
			</td>
		</tr>
		<?php 
			if( isset( $blpro_nl_settings['lock_cpt'] ) ){
				$style="";
			}else{
				$style="display:none";
			} 
		?>
		<tr id="locked_cpts" style="<?php echo $style; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Select cpt to lock', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select id="blpro-cpt-list" name="blpro_nl_settings[locked_cpts][]" multiple>
					<?php foreach ( $cpts as $slug => $cpt ) { ?>
					<?php $selected = (!empty( $blpro_nl_settings['locked_cpts'] ) && in_array( $slug, $blpro_nl_settings['locked_cpts'] ) ) ? 'selected' : ''; ?>
					<option value="<?php echo $slug; ?>" <?php echo $selected; ?>><?php echo $cpt->label; ?></option>
					<?php } ?>
				</select>
			<p class="description" id="tagline-description"><?php esc_html_e( 'Select the buddypress components that you wish to restrict access for logged out users.', 'buddypress-private-community-pro' ); ?>
			</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Lock pages', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<label class="blpro-switch">
					<input name='blpro_nl_settings[lock_wp_pages]' type='checkbox' class="regular-text blpro-disp-resp-tr" data-id="locked_pages" value='yes' <?php (isset($blpro_nl_settings['lock_wp_pages']))?checked($blpro_nl_settings['lock_wp_pages'],'yes'):''; ?>/>
					<div class="blpro-slider blpro-round"></div>
				</label>
			<p class="description" id="tagline-description"><?php esc_html_e( 'Enabled: non logged-in users will not be able to access wordpress pages.', 'buddypress-private-community-pro' ); ?>
			</p>
			</td>
		</tr>
		<?php 
			if( isset( $blpro_nl_settings['lock_wp_pages'] ) ){
				$style="";
			}else{
				$style="display:none";
			} 
		?>
		<tr id="locked_pages" style="<?php echo $style; ?>">
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Select wp pages to lock', 'buddypress-private-community-pro' ); ?></label></th>
			<td>
				<select id="blpro-page-list" name="blpro_nl_settings[locked_pages][]" multiple>
					<?php if ( !empty( $wp_pages ) ) {?>
					<?php foreach ( $wp_pages as $pgid => $wp_page ) {?>
					<?php $selected = ( !empty( $blpro_nl_settings['locked_pages'] ) && in_array( $pgid, $blpro_nl_settings['locked_pages'] ) ) ? 'selected': '';?>
					<option value="<?php echo $pgid;?>" <?php echo $selected;?>><?php echo $wp_page; ?></option>
					<?php }?>
					<?php }?>
				</select>
			<p class="description" id="tagline-description"><?php esc_html_e( 'Select the wordpress pages that you wish to get locked for loggedout users.', 'buddypress-private-community-pro' ); ?>
			</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="bplock-display-content"><?php _e( 'Custom restriction message', 'buddypress-private-community-pro' );?></label></th>
			<td>
				<?php $options = array( 'textarea_rows' => 5, 'textarea_name' => 'blpro_nl_settings[locked_content]' );?>
				<?php
				if ( !isset( $blpro_nl_settings['locked_content'] ) ) {
					$locked_content = apply_filters( 'blpro_default_locked_message', __( 'Hey Member! Thanks for checking this page out -- however, it’s restricted to logged members only. If you’d like to access it, please login to the website.', 'buddypress-private-community-pro' ) );
				}else{
					$locked_content = stripcslashes($blpro_nl_settings['locked_content']);
				}
				wp_editor( $locked_content, 'blpro-locked-content', $options );?>
				<p class="description"><?php _e( 'Above message will be displayed on the protected pages.', 'buddypress-private-community-pro' );?></p>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</form>
</div>