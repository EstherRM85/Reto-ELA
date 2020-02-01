<?php
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


if ( !class_exists( 'Reign_Buddy_Extender_Options' ) ) :

	/**
	 * @class Reign_Buddy_Extender_Options
	 */
	class Reign_Buddy_Extender_Options {

		/**
		 * The single instance of the class.
		 *
		 * @var Reign_Buddy_Extender_Options
		 */
		protected static $_instance	 = null;
		protected static $_slug		 = 'buddy_extender';

		/**
		 * Main Reign_Buddy_Extender_Options Instance.
		 *
		 * Ensures only one instance of Reign_Buddy_Extender_Options is loaded or can be loaded.
		 *
		 * @return Reign_Buddy_Extender_Options - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Reign_Buddy_Extender_Options Constructor.
		 */
		public function __construct() {
			$this->init_hooks();
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {
			add_filter( 'alter_reign_admin_tabs', array( $this, 'alter_reign_admin_tabs' ), 10, 1 );
			add_action( 'render_theme_options_page_for_' . self::$_slug, array( $this, 'render_theme_options' ) );

			add_action( 'render_theme_options_for_avatar_settings', array( $this, 'render_theme_options_for_avatar_settings' ) );
			add_action( 'render_theme_options_for_advanced_settings', array( $this, 'render_theme_options_for_advanced_settings' ) );

			add_action( 'render_theme_options_for_group_cover_image', array( $this, 'render_theme_options_for_group_cover_image' ) );
			add_action( 'render_theme_options_for_xprofile_cover_image', array( $this, 'render_theme_options_for_xprofile_cover_image' ) );

			add_action( 'render_theme_options_for_xprofile_social_links', array( $this, 'render_theme_options_for_xprofile_social_links' ) );

			add_action( 'render_theme_options_for_bp_layout_mgmt', array( $this, 'render_theme_options_for_bp_layout_mgmt' ) );

			add_action( 'wp_loaded', array( $this, 'save_reign_theme_settings' ) );
		}

		public function alter_reign_admin_tabs( $tabs ) {
			$tabs[ self::$_slug ] = __( 'BuddyPress Settings', 'reign' );
			return $tabs;
		}

		public function render_theme_options() {
			$vertical_tabs	 = array(
				'avatar_settings'		 => __( 'Avatar Settings', 'reign' ),
				'advanced_settings'		 => __( 'Advanced Settings', 'reign' ),
				'group_cover_image'		 => __( 'Default Group Cover Image', 'reign' ),
				'xprofile_cover_image'	 => __( 'Default Profile Cover Image', 'reign' ),
				'xprofile_social_links'	 => __( 'Social Media Links', 'reign' ),
				'bp_layout_mgmt'		 => __( 'BP Layout Management', 'reign' )
			);
			$vertical_tabs	 = apply_filters( 'wbtm_' . self::$_slug . '_vertical_tabs', $vertical_tabs );
			include 'vertical-tabs-skeleton.php';
		}

		public function render_theme_options_for_bp_layout_mgmt() {
			global $wbtm_reign_settings;
			$member_header_position	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] : 'inside';
			$member_header_type		 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_type' ] : 'wbtm-cover-header-type-1';
			$group_header_type		 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_header_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_header_type' ] : 'wbtm-cover-header-type-1';
			$member_directory_type	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_directory_type' ] : 'wbtm-member-directory-type-2';
			$group_directory_type	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_directory_type' ] : 'wbtm-group-directory-type-2';
			?>
			<table class="form-table">
				<tr>
					<th>
						<label>
							<?php esc_html_e( 'Select Header Position', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Select Header Position', 'reign' ); ?>
							</span>
						</div>
					</th>
					<td>
						<?php
						$member_header_positions = array(
							'inside' => array(
								'name'		 => 'Inside',
								'img_url'	 => '',
							),
							'top'	 => array(
								'name'		 => 'Top',
								'img_url'	 => '',
							)
						);
						echo '<select name="reign_buddyextender[member_header_position]">';
						foreach ( $member_header_positions as $slug => $position ) {
							echo '<option value="' . $slug . '" ' . selected( $member_header_position, $slug ) . '>' . $position[ 'name' ] . '</option>';
						}
						echo '</select>';
						?>
					</td>
				</tr>
				<tr>
					<th>
						<label>
							<?php esc_html_e( 'Select Member Header Layout', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Select Member Header Layout', 'reign' ); ?>
							</span>
						</div>
					</th>
					<td>
						<?php
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
							echo '<input type="radio" name="reign_buddyextender[member_header_type]" value="' . $slug . '" id="member-' . $slug . '" ' . checked( $member_header_type, $slug, false ) . ' />';
							echo '<label for="member-' . $slug . '"><img src="' . $header[ 'img_url' ] . '" /><span>' . $header[ 'name' ] . '</span></label>';
							echo '</li>';
						}
						echo '</ul>';
						echo '</div>';
						?>
					</td>
				</tr>
				<tr>
					<th>
						<label>
							<?php esc_html_e( 'Select Group Header Layout', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Select Group Header Layout', 'reign' ); ?>
							</span>
						</div>
					</th>
					<td>
						<?php
						$group_header_types	 = array(
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
						$group_header_types	 = apply_filters( 'reign_group_header_types_layout_options', $group_header_types );

						echo '<div class="wbtm-radio-img-selector-sec">';
						echo '<ul>';
						foreach ( $group_header_types as $slug => $header ) {
							echo '<li>';
							echo '<input type="radio" name="reign_buddyextender[group_header_type]" value="' . $slug . '" id="group-' . $slug . '" ' . checked( $group_header_type, $slug, false ) . ' />';
							echo '<label for="group-' . $slug . '"><img src="' . $header[ 'img_url' ] . '" /><span>' . $header[ 'name' ] . '</span></label>';
							echo '</li>';
						}
						echo '</ul>';
						echo '</div>';
						?>
					</td>
				</tr>
				<tr>
					<th>
						<label>
							<?php esc_html_e( 'Select Member Directory Layout', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Select Member Directory Layout', 'reign' ); ?>
							</span>
						</div>
					</th>
					<td>
						<?php
						$member_directory_types = array(
							'wbtm-member-directory-type-1'	 => array(
								'name'		 => 'Layout #1',
								'img_url'	 => REIGN_INC_DIR_URI . 'reign-settings/imgs/member-layout-1.jpg',
							),
							'wbtm-member-directory-type-2'	 => array(
								'name'		 => 'Layout #2',
								'img_url'	 => REIGN_INC_DIR_URI . 'reign-settings/imgs/member-layout-2.jpg',
							),
							'wbtm-member-directory-type-3'	 => array(
								'name'		 => 'Layout #3',
								'img_url'	 => REIGN_INC_DIR_URI . 'reign-settings/imgs/member-layout-3.jpg',
							),
							'wbtm-member-directory-type-4'	 => array(
								'name'		 => 'Layout #4',
								'img_url'	 => REIGN_INC_DIR_URI . 'reign-settings/imgs/member-layout-4.jpg',
							),
						);

						echo '<div class="wbtm-radio-img-selector-sec">';
						echo '<ul>';
						foreach ( $member_directory_types as $slug => $directory ) {
							echo '<li>';
							echo '<input type="radio" name="reign_buddyextender[member_directory_type]" value="' . $slug . '" id="member-dir-' . $slug . '" ' . checked( $member_directory_type, $slug, false ) . ' />';
							echo '<label for="member-dir-' . $slug . '"><img src="' . $directory[ 'img_url' ] . '" /><span>' . $directory[ 'name' ] . '</span></label>';
							echo '</li>';
						}
						echo '</ul>';
						echo '</div>';
						?>
					</td>
				</tr>
				<tr>
					<th>
						<label>
							<?php esc_html_e( 'Select Group Directory Layout', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Select Group Directory Layout', 'reign' ); ?>
							</span>
						</div>
					</th>
					<td>
						<?php
						$group_directory_types = array(
							'wbtm-group-directory-type-1'	 => array(
								'name'		 => 'Layout #1',
								'img_url'	 => REIGN_INC_DIR_URI . 'reign-settings/imgs/group-layout-1.jpg',
							),
							'wbtm-group-directory-type-2'	 => array(
								'name'		 => 'Layout #2',
								'img_url'	 => REIGN_INC_DIR_URI . 'reign-settings/imgs/group-layout-2.jpg',
							),
							'wbtm-group-directory-type-3'	 => array(
								'name'		 => 'Layout #3',
								'img_url'	 => REIGN_INC_DIR_URI . 'reign-settings/imgs/group-layout-3.jpg',
							),
							'wbtm-group-directory-type-4'	 => array(
								'name'		 => 'Layout #4',
								'img_url'	 => REIGN_INC_DIR_URI . 'reign-settings/imgs/group-layout-4.jpg',
							),
						);

						echo '<div class="wbtm-radio-img-selector-sec">';
						echo '<ul>';
						foreach ( $group_directory_types as $slug => $directory ) {
							echo '<li>';
							echo '<input type="radio" name="reign_buddyextender[group_directory_type]" value="' . $slug . '" id="group-dir-' . $slug . '" ' . checked( $group_directory_type, $slug, false ) . ' />';
							echo '<label for="group-dir-' . $slug . '"><img src="' . $directory[ 'img_url' ] . '" /><span>' . $directory[ 'name' ] . '</span></label>';
							echo '</li>';
						}
						echo '</ul>';
						echo '</div>';
						?>
					</td>
				</tr>
			</table>
			<?php
		}

		public function render_theme_options_for_avatar_settings() {
			global $wbtm_reign_settings;
			?>
			<table class="form-table">
				<tr>
					<td>
						<label for="avatar_thumb_size_select">
							<?php esc_html_e( 'Avatar Thumb Size', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Changes user and group avatar to selected dimensions in activity, members and group lists.', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<?php
						$sizes						 = $this->reign_bpextender_get_avatar_sizes( 'avatar_thumb_size_select' );
						$avatar_thumb_size_select	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_thumb_size_select' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_thumb_size_select' ] : '';
						echo '<select name="reign_buddyextender[avatar_thumb_size_select]">';
						foreach ( $sizes as $k => $v ) {
							echo '<option value="' . $k . '" ' . selected( $avatar_thumb_size_select, $k, false ) . ' >' . $v . '</option>';
						}
						echo '</select>';
						?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="avatar_full_size_select">
							<?php esc_html_e( 'Avatar Full Size', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Changes user and group avatar to dimensions in user and group header.', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<?php
						$sizes					 = $this->reign_bpextender_get_avatar_sizes( 'avatar_full_size_select' );
						$avatar_full_size_select = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_full_size_select' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_full_size_select' ] : '';
						echo '<select name="reign_buddyextender[avatar_full_size_select]">';
						foreach ( $sizes as $k => $v ) {
							echo '<option value="' . $k . '" ' . selected( $avatar_full_size_select, $k, false ) . ' >' . $v . '</option>';
						}
						echo '</select>';
						?>
					</td>
				</tr>
				<tr>
					<th>
						<label for="avatar_max_size_select">
							<?php esc_html_e( 'Avatar Max Size.', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Changes maximum image size a user can uplaod for avatars.', 'reign' ); ?>
							</span>
						</div>
					</th>
					<td>
						<?php
						$sizes					 = $this->reign_bpextender_get_avatar_sizes( 'avatar_max_size_select' );
						$avatar_max_size_select	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_max_size_select' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_max_size_select' ] : '';
						echo '<select name="reign_buddyextender[avatar_max_size_select]">';
						foreach ( $sizes as $k => $v ) {
							echo '<option value="' . $k . '" ' . selected( $avatar_max_size_select, $k, false ) . ' >' . $v . '</option>';
						}
						echo '</select>';
						?>
					</td>
				</tr>
				<tr>
					<th>
						<label for="avatar_default_image">
							<?php esc_html_e( 'Default User Avatar', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Upload an image that displays before a user has added a custom image.', 'reign' ); ?>
							</span>
						</div>
					</th>
					<td>
						<?php
						$img_id	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_default_image_id' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_default_image_id' ] : '';
						$img_src = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_default_image' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'avatar_default_image' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-avatar.png';
						if ( empty( $img_src ) ) {
							$img_src = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-avatar.png';
						}

						$image_inline_style	 = 'width:150px;height:150px;object-fit-cover;';
						$remove_inline_style = '';
						if ( empty( $img_src ) ) {
							$image_inline_style	 .= 'display:none;';
							$remove_inline_style .= 'display:none;';
						}

						echo '<p>';
						echo '<input type="hidden" class="reign-upload-file" name="reign_buddyextender[avatar_default_image]" id="avatar_default_image" value="' . $img_src . '" size="45" data-previewsize="[350,350]">';
						echo '<input type="hidden" class="reign-upload-file-id" name="reign_buddyextender[avatar_default_image_id]" id="avatar_default_image_id" value="' . $img_id . '">';
						echo '<img class="reign_default_cover_image reign_default_avatar_image" src="' . $img_src . '" style="' . $image_inline_style . '" />';
						echo '<a href="#" class="reign-remove-file-button" rel="avatar_default_image" style="' . $remove_inline_style . '" >' . __( 'Remove Image', 'reign' ) . '</a>';
						echo '<input id="reign-upload-button" type="button" class="button reign-upload-button" value="' . __( 'Upload Image', 'reign' ) . '" />';
						echo '</p>';
						?>
					</td>
				</tr>
				<tr>
					<th>
						<label for="group_default_image">
							<?php esc_html_e( 'Default Group Avatar', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Upload an image that displays before a custom image is added for a group.', 'reign' ); ?>
							</span>
						</div>
					</th>
					<td>
						<?php
						$img_id	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_default_image_id' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_default_image_id' ] : '';
						$img_src = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_default_image' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_default_image' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-avatar.png';
						if ( empty( $img_src ) ) {
							$img_src = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-avatar.png';
						}

						$image_inline_style	 = 'width:150px;height:150px;object-fit-cover;';
						$remove_inline_style = '';
						if ( empty( $img_src ) ) {
							$image_inline_style	 .= 'display:none;';
							$remove_inline_style .= 'display:none;';
						}

						echo '<p>';
						echo '<input type="hidden" class="reign-upload-file" name="reign_buddyextender[group_default_image]" id="group_default_image" value="' . $img_src . '" size="45" data-previewsize="[350,350]">';
						echo '<input type="hidden" class="reign-upload-file-id" name="reign_buddyextender[group_default_image_id]" id="group_default_image_id" value="' . $img_id . '">';
						echo '<img class="reign_default_cover_image reign_default_avatar_image" src="' . $img_src . '" style="' . $image_inline_style . '" />';
						echo '<a href="#" class="reign-remove-file-button" rel="group_default_image" style="' . $remove_inline_style . '" >' . __( 'Remove Image', 'reign' ) . '</a>';
						echo '<input id="reign-upload-button" type="button" class="button reign-upload-button" value="' . __( 'Upload Image', 'reign' ) . '" />';
						echo '</p>';
						?>
					</td>
				</tr>
			</table>
			<?php
		}

		public function render_theme_options_for_advanced_settings() {
			global $wbtm_reign_settings;
			?>
			<table class="form-table">
				<tr>
					<td>
						<label for="members_per_page">
							<?php esc_html_e( 'Members per page', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Here you can manage number of members to show per page.', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<input type="number" name="reign_buddyextender[members_per_page]" value="<?php echo isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'members_per_page' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'members_per_page' ] : '20'; ?>" >
					</td>
				</tr>
				<tr>
					<td>
						<label for="groups_per_page">
							<?php esc_html_e( 'Groups per page', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Here you can manage number of groups to show per page.', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<input type="number" name="reign_buddyextender[groups_per_page]" value="<?php echo isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'groups_per_page' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'groups_per_page' ] : '20'; ?>" >
					</td>
				</tr>
				<?php if ( class_exists( 'BP_XProfile_Group' )):?>
				<tr>
					<td>
						<label for="selected_xprofile_field">
							<?php esc_html_e( 'Select xProfile Field', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'You can select a xprofile field to show on member cover image header.', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<?php
						$selected_xprofile_field = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'selected_xprofile_field' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'selected_xprofile_field' ] : '';
						$profile_groups			 = BP_XProfile_Group::get( array( 'fetch_fields' => true ) );
						if ( !empty( $profile_groups ) ) {
							echo '<select name="reign_buddyextender[selected_xprofile_field]">';
							echo '<option value="-1">' . __( 'Disable xProfile Data', 'reign' ) . '</option>';
							foreach ( $profile_groups as $profile_group ) {
								if ( !empty( $profile_group->fields ) ) {
									foreach ( $profile_group->fields as $field ) {
										echo '<option value="' . $field->id . '" ' . selected( $selected_xprofile_field, $current = $field->id, $echo	 = true ) . '>' . $field->name . '</option>';
									}
								}
							}
							echo '</select>';
						}
						?>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<td>
						<label for="activity_popup_checkbox">
							<?php esc_html_e( 'What\'s new activity lightbox', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Disable What\'s new activity lightbox.', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<input type="checkbox" name="reign_buddyextender[activity_popup_checkbox]" value="on" <?php isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'activity_popup_checkbox' ] ) ? checked( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'activity_popup_checkbox' ], 'on' ) : ''; ?>>
						<span class="description"><?php esc_html_e( 'Disable What\'s new activity lightbox.', 'reign' ); ?></span>
					</td>
				</tr>

				<tr>
					<td>
						<label for="root_profiles_checkbox">
							<?php esc_html_e( 'Root Profiles', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Remove members slug from profile url.', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<input type="checkbox" name="reign_buddyextender[root_profiles_checkbox]" value="on" <?php isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'root_profiles_checkbox' ] ) ? checked( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'root_profiles_checkbox' ], 'on' ) : ''; ?>>
						<span class="description"><?php esc_html_e( 'Remove members slug from profile url.', 'reign' ); ?></span>
					</td>
				</tr>

				<tr>
					<td>
						<label for="group_auto_join_checkbox">
							<?php esc_html_e( 'Auto Group Join', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Disable auto join when posting in a group.', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<input type="checkbox" name="reign_buddyextender[group_auto_join_checkbox]" value="on" <?php isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_auto_join_checkbox' ] ) ? checked( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_auto_join_checkbox' ], 'on' ) : ''; ?>>
						<span class="description"><?php esc_html_e( 'Disable auto join when posting in a group.', 'reign' ); ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="ldap_username_checkbox">
							<?php esc_html_e( 'LDAP Usernames', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Enable support for LDAP usernames that include dots.', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<input type="checkbox" name="reign_buddyextender[ldap_username_checkbox]" value="on" <?php isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'ldap_username_checkbox' ] ) ? checked( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'ldap_username_checkbox' ], 'on' ) : ''; ?>>
						<span class="description"><?php esc_html_e( 'Enable support for LDAP usernames that include dots.', 'reign' ); ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="wysiwyg_editor_checkbox">
							<?php esc_html_e( 'WYSIWYG Textarea', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Removes text editor from textarea profile field.', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<input type="checkbox" name="reign_buddyextender[wysiwyg_editor_checkbox]" value="on" <?php isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'wysiwyg_editor_checkbox' ] ) ? checked( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'wysiwyg_editor_checkbox' ], 'on' ) : ''; ?>>
						<span class="description"><?php esc_html_e( 'Removes text editor from textarea profile field.', 'reign' ); ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="all_autocomplete_checkbox">
							<?php esc_html_e( 'All Members Auto Complete', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Auto-complete all members instead of just friends in messages.', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<input type="checkbox" name="reign_buddyextender[all_autocomplete_checkbox]" value="on" <?php isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'all_autocomplete_checkbox' ] ) ? checked( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'all_autocomplete_checkbox' ], 'on' ) : ''; ?>>
						<span class="description"><?php esc_html_e( 'Auto-complete all members instead of just friends in messages.', 'reign' ); ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="profile_autolink_checkbox">
							<?php esc_html_e( 'Profile Fields Auto Link', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Disable autolinking in profile fields.', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<input type="checkbox" name="reign_buddyextender[profile_autolink_checkbox]" value="on" <?php isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'profile_autolink_checkbox' ] ) ? checked( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'profile_autolink_checkbox' ], 'on' ) : ''; ?>>
						<span class="description"><?php esc_html_e( 'Disable autolinking in profile fields.', 'reign' ); ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="user_mentions_checkbox">
							<?php esc_html_e( 'User @ Mentions', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Disable User @ mentions.', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<input type="checkbox" name="reign_buddyextender[user_mentions_checkbox]" value="on" <?php isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'user_mentions_checkbox' ] ) ? checked( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'user_mentions_checkbox' ], 'on' ) : ''; ?>>
						<span class="description"><?php esc_html_e( 'Disable User @ mentions.', 'reign' ); ?></span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="depricated_code_checkbox">
							<?php esc_html_e( 'Ignore Deprecated Code', 'reign' ); ?>
						</label>
						<div class="rtm-tooltip">?
							<span class="rtm-tooltiptext">
								<?php esc_html_e( 'Do not load depricated code', 'reign' ); ?>
							</span>
						</div>
					</td>
					<td>
						<input type="checkbox" name="reign_buddyextender[depricated_code_checkbox]" value="on" <?php isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'depricated_code_checkbox' ] ) ? checked( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'depricated_code_checkbox' ], 'on' ) : ''; ?>>
						<span class="description"><?php esc_html_e( 'Do not load depricated code', 'reign' ); ?></span>
					</td>
				</tr>
			</table>
			<?php
		}

		public function render_theme_options_for_group_cover_image() {
			global $wbtm_reign_settings;
			$default_group_cover_image_url = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_url' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_url' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-cover.jpg';

			if ( empty( $default_group_cover_image_url ) ) {
				$default_group_cover_image_url = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-cover.jpg';
			}

			$default_group_cover_image_size	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_size' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_size' ] : '';
			$cover_image_available_sizes	 = $this->get_cover_image_available_sizes();

			$image_inline_style	 = 'width:150px;height:100px;object-fit-cover;';
			$remove_inline_style = '';
			if ( empty( $default_group_cover_image_url ) ) {
				$image_inline_style	 .= 'display:none;';
				$remove_inline_style .= 'display:none;';
			}

			echo '<table class="form-table">';
			echo '<tr>';
			echo '<td>';
			echo '<label>' . __( 'Select Image', 'reign' ) . '</label>';
			?>
			<div class="rtm-tooltip">?
				<span class="rtm-tooltiptext">
					<?php esc_html_e( 'Select image to set as Default Group Cover Image here.', 'reign' ); ?>
				</span>
			</div>
			<?php
			echo '</td>';
			echo '<td>';
			echo '<input class="reign_default_cover_image_url" type="hidden" name="reign_buddyextender[default_group_cover_image_url]" value="' . esc_url($default_group_cover_image_url) . '" />';
			echo '<img class="reign_default_cover_image" src="' . esc_url($default_group_cover_image_url) . '" style="' . $image_inline_style . '" />';
			echo '<a href="#" class="reign-remove-file-button" rel="avatar_default_image" style="' . $remove_inline_style . '" >' . __( 'Remove Image', 'reign' ) . '</a>';
			echo '<input id="reign-upload-button" type="button" class="button reign-upload-button" value="' . __( 'Upload Image', 'reign' ) . '" />';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td>';
			echo '<label>' . __( 'Select Image Size', 'reign' ) . '</label>';
			?>
			<div class="rtm-tooltip">?
				<span class="rtm-tooltiptext">
					<?php esc_html_e( 'Select image size for Default Group Cover Image here.', 'reign' ); ?>
				</span>
			</div>
			<?php
			echo '</td>';
			echo '<td>';
			if ( !empty( $cover_image_available_sizes ) && is_array( $cover_image_available_sizes ) ) {
				echo '<select name="reign_buddyextender[default_group_cover_image_size]">';
				foreach ( $cover_image_available_sizes as $key => $value ) {
					echo '<option value="' . $key . '"' . selected( $default_group_cover_image_size, $key, false ) . '>' . $value . '</option>';
				}
				echo '</select>';
			}
			echo '</td>';
			echo '</tr>';
			echo '</table>';
		}

		public function render_theme_options_for_xprofile_cover_image() {
			global $wbtm_reign_settings;
			$default_xprofile_cover_image_url = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_xprofile_cover_image_url' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_xprofile_cover_image_url' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-cover.jpg';
			if ( empty( $default_xprofile_cover_image_url ) ) {
				$default_xprofile_cover_image_url = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-mem-cover.jpg';
			}
			$default_xprofile_cover_image_size	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_xprofile_cover_image_size' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_xprofile_cover_image_size' ] : '';
			$cover_image_available_sizes		 = $this->get_cover_image_available_sizes();

			$image_inline_style	 = 'width:150px;height:100px;object-fit-cover;';
			$remove_inline_style = '';
			if ( empty( $default_xprofile_cover_image_url ) ) {
				$image_inline_style	 .= 'display:none;';
				$remove_inline_style .= 'display:none;';
			}

			echo '<table class="form-table">';
			echo '<tr>';
			echo '<td>';
			echo '<label>' . __( 'Select Image', 'reign' ) . '</label>';
			?>
			<div class="rtm-tooltip">?
				<span class="rtm-tooltiptext">
					<?php esc_html_e( 'Select image to set as Default Profile Cover Image here.', 'reign' ); ?>
				</span>
			</div>
			<?php
			echo '</td>';
			echo '<td>';
			echo '<input class="reign_default_cover_image_url" type="hidden" name="reign_buddyextender[default_xprofile_cover_image_url]" value="' . esc_url($default_xprofile_cover_image_url) . '" />';
			echo '<img class="reign_default_cover_image" src="' . $default_xprofile_cover_image_url . '" style="' . $image_inline_style . '" />';
			echo '<a href="#" class="reign-remove-file-button" rel="avatar_default_image" style="' . $remove_inline_style . '" >' . __( 'Remove Image', 'reign' ) . '</a>';
			echo '<input id="reign-upload-button" type="button" class="button reign-upload-button" value="' . __( 'Upload Image', 'reign' ) . '" />';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td>';
			echo '<label>' . __( 'Select Image Size', 'reign' ) . '</label>';
			?>
			<div class="rtm-tooltip">?
				<span class="rtm-tooltiptext">
					<?php esc_html_e( 'Select image size to set as Default Profile Cover Image here.', 'reign' ); ?>
				</span>
			</div>
			<?php
			echo '</td>';
			echo '<td>';
			if ( !empty( $cover_image_available_sizes ) && is_array( $cover_image_available_sizes ) ) {
				echo '<select name="reign_buddyextender[default_xprofile_cover_image_size]">';
				foreach ( $cover_image_available_sizes as $key => $value ) {
					echo '<option value="' . $key . '"' . selected( $default_xprofile_cover_image_size, $key, false ) . '>' . $value . '</option>';
				}
				echo '</select>';
			}
			echo '</td>';
			echo '</tr>';
			echo '</table>';
		}

		public function render_theme_options_for_xprofile_social_links() {
			global $wbtm_reign_settings;
			$wbtm_social_links	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'wbtm_social_links' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'wbtm_social_links' ] : array();
			$unique_key			 = time();
			if ( !empty( $wbtm_social_links ) && is_array( $wbtm_social_links ) ) {
				echo '<div class="wb-xprofile-social-links-wrapper-outer">';
				echo '<div class="wb-xprofile-social-links-wrapper">';
				foreach ( $wbtm_social_links as $unique_key => $social_link ) {
					$display_none = '';
					$image_link  = '';
					if ( empty( $social_link[ 'img_url' ] ) ) {
						$display_none = 'display: none;';											
					} else {
						$image_link  = $social_link[ 'img_url' ];
					}
					?>
					<div class="wbtm_social_links_container">
						<div class="wbtm_social_link_section">
							<h3 class="wbtm_social_link_toggle_head">
								<?php echo $social_link[ 'name' ]; ?>
							</h3>
							<div class="wbtm_social_link_info_box">
								<div class="img_section">
									<input class="reign_default_cover_image_url" type="hidden" name="reign_buddyextender[wbtm_social_links][<?php echo $unique_key; ?>][img_url]" value="<?php echo $image_link; ?>" required="required" />
									<img class="reign_default_cover_image" src="<?php echo $image_link; ?>" style="<?php echo $display_none; ?>" />
									<input id="reign-upload-button" type="button" class="button reign-upload-button" value="<?php esc_html_e( 'Upload Icon', 'reign' ); ?>" />
									<a href="#" class="reign-remove-file-button" rel="avatar_default_image" style="<?php echo $display_none; ?>" >
										<?php esc_html_e( 'Remove Icon', 'reign' ); ?>
									</a>
								</div>
								<div class="name_section">
									<input type="text" class="wbtm-social-link-inp" name="reign_buddyextender[wbtm_social_links][<?php echo $unique_key; ?>][name]" placeholder="<?php esc_html_e( 'New Site', 'reign' ); ?>" value="<?php echo $social_link[ 'name' ]; ?>" required="required" />
								</div>
								<div class="del_section">
									<button><?php esc_html_e( 'Delete', 'reign' ); ?></button>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				echo '</div>';
				echo '<div class="wbtm_social_links_add_more">';
				echo '<button>' . __( 'Add New Site', 'reign' ) . '</button>';
				echo '</div>';
				echo '</div>';
			} else {
				?>
				<div class="wb-xprofile-social-links-wrapper-outer">
					<div class="wb-xprofile-social-links-wrapper">
						<div class="wbtm_social_links_container">
							<div class="wbtm_social_link_section">
								<h3 class="wbtm_social_link_toggle_head">
									<?php esc_html_e( 'New Site', 'reign' ); ?>
								</h3>
								<div class="wbtm_social_link_info_box">
									<div class="img_section">
										<input class="reign_default_cover_image_url" type="hidden" name="reign_buddyextender[wbtm_social_links][<?php echo $unique_key; ?>][img_url]" value="" />
										<img class="reign_default_cover_image" src="" style="display: none;" />
										<input id="reign-upload-button" type="button" class="button reign-upload-button" value="<?php esc_html_e( 'Upload Image', 'reign' ); ?>" />
										<a href="#" class="reign-remove-file-button" rel="avatar_default_image" style="display: none;" >
											<?php esc_html_e( 'Remove Image', 'reign' ); ?>
										</a>
									</div>
									<div class="name_section">
										<input type="text" name="reign_buddyextender[wbtm_social_links][<?php echo $unique_key; ?>][name]" placeholder="<?php esc_html_e( 'New Site', 'reign' ); ?>" />
									</div>
									<div class="del_section">
										<button><?php esc_html_e( 'Delete', 'reign' ); ?></button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="wbtm_social_links_add_more">
						<button><?php esc_html_e( 'Add New Site', 'reign' ); ?></button>
					</div>
				</div>
				<?php
			}
		}

		public function save_reign_theme_settings() {
			if ( isset( $_POST[ "reign-settings-submit" ] ) && $_POST[ "reign-settings-submit" ] == 'Y' ) {
				check_admin_referer( "reign-options" );
				global $wbtm_reign_settings;
				if ( isset( $_POST[ 'reign_buddyextender' ] ) ) {
					$wbtm_reign_settings[ 'reign_buddyextender' ] = $_POST[ 'reign_buddyextender' ];
				}
				update_option( "reign_options", $wbtm_reign_settings );
				$wbtm_reign_settings = get_option( "reign_options", array() );
			}
		}

		public function get_cover_image_available_sizes() {
			$cover_image_available_sizes = array(
				'1024x300'	 => __( '1024 X 300', 'reign' ),
				'1024x500'	 => __( '1024 X 500', 'reign' ),
				'1024x600'	 => __( '1024 X 600', 'reign' ),
				'1024x800'	 => __( '1024 X 800', 'reign' )
			);
			return $cover_image_available_sizes = apply_filters( 'alter_reign_cover_image_available_sizes', $cover_image_available_sizes );
		}

		public function reign_bpextender_get_avatar_sizes( $field ) {
			switch ( $field ) {
				case 'avatar_thumb_size_select' :

					$sizes = array(
						'25'	 => __( '25 x 25 px', 'reign' ),
						'50'	 => __( '50 x 50 px', 'reign' ),
						'75'	 => __( '75 x 75 px', 'reign' ),
						'100'	 => __( '100 x 100 px', 'reign' ),
						'125'	 => __( '125 x 125 px', 'reign' ),
						'150'	 => __( '150 x 150 px', 'reign' ),
						'175'	 => __( '175 x 175 px', 'reign' ),
						'200'	 => __( '200 x 200 px', 'reign' ),
						'225'	 => __( '225 x 225 px', 'reign' ),
						'250'	 => __( '250 x 250 px', 'reign' ),
						'275'	 => __( '275 x 275 px', 'reign' ),
						'300'	 => __( '300 x 300 px', 'reign' ),
					);

					return apply_filters( 'get_avatar_thumb_sizes', $sizes );

					break;
				case 'avatar_full_size_select' :

					$sizes = array(
						'100'	 => __( '100 x 100 px', 'reign' ),
						'125'	 => __( '125 x 125 px', 'reign' ),
						'150'	 => __( '150 x 150 px', 'reign' ),
						'175'	 => __( '175 x 175 px', 'reign' ),
						'200'	 => __( '200 x 200 px', 'reign' ),
						'225'	 => __( '225 x 225 px', 'reign' ),
						'250'	 => __( '250 x 250 px', 'reign' ),
						'275'	 => __( '275 x 275 px', 'reign' ),
						'300'	 => __( '300 x 300 px', 'reign' ),
						'325'	 => __( '325 x 325 px', 'reign' ),
						'350'	 => __( '350 x 350 px', 'reign' ),
						'375'	 => __( '375 x 375 px', 'reign' ),
					);

					return apply_filters( 'get_avatar_full_sizes', $sizes );

					break;
				case 'avatar_max_size_select' :

					$sizes = array(
						'320'	 => __( '320 px', 'reign' ),
						'640'	 => __( '640 px', 'reign' ),
						'960'	 => __( '960 px', 'reign' ),
						'1280'	 => __( '1280 px', 'reign' ),
					);

					return apply_filters( 'get_max_full_sizes', $sizes );

					break;
			}
		}

	}

	endif;

/**
 * Main instance of Reign_Buddy_Extender_Options.
 * @return Reign_Buddy_Extender_Options
 */
if ( class_exists( 'BuddyPress' ) ) {
	Reign_Buddy_Extender_Options::instance();
}
