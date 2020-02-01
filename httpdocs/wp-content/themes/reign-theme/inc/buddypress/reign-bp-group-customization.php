<?php

/**
********************************************************
********************************************************
******** GROUP DIRECTORY CUSTOMIZATION :: START ********
********************************************************
********************************************************
*/

if ( function_exists( 'bp_get_theme_package_id' ) ) {
	$theme_package_id = bp_get_theme_package_id();
} else {
	$theme_package_id = 'legacy';
}
// if ( TRUE || 'legacy' === $theme_package_id ) {

	/**
	* showing group cover image
	*
	* @since 1.0.7
	*/
	if( !function_exists( 'wbtm_render_group_cover_image' ) ) {
		add_action( 'wbtm_before_group_avatar_group_directory', 'wbtm_render_group_cover_image' );
		function wbtm_render_group_cover_image() {
			global $wbtm_reign_settings;
			$group_directory_type	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_directory_type' ] : 'wbtm-group-directory-type-2';
			if ( $group_directory_type != 'wbtm-group-directory-type-1' ) {
				$args			 = array(
					'object_dir' => 'groups',
					'item_id'	 => $group_id	 = bp_get_group_id(),
					'type'		 => 'cover-image',
				);
				$cover_img_url	 = bp_attachments_get_attachment( 'url', $args );
				if ( empty( $cover_img_url ) ) {
					global $wbtm_reign_settings;
					$cover_img_url	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_url' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'default_group_cover_image_url' ] : REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-cover.jpg';
					if( empty( $cover_img_url ) ) {
						$cover_img_url = REIGN_INC_DIR_URI . 'reign-settings/imgs/default-grp-cover.jpg';
					}
				}
				echo '<div class="wbtm-group-cover-img"><img src="' . $cover_img_url . '" /></div>';
			}
		}
	}

	/**
	* showing group type icon
	*
	* @since 1.0.7
	*/
	if( !function_exists( 'wbtm_bp_directory_groups_item_show_grp_type' ) ) {
		add_action( 'bp_directory_groups_item', 'wbtm_bp_directory_groups_item_show_grp_type' );
		function wbtm_bp_directory_groups_item_show_grp_type() {
			global $groups_template;
			$_group_status =& $groups_template->group->status;
			$icon_html = '';
			if ( 'public' == $_group_status ) {
				$icon_html = '<i class="fa fa-globe"></i>';
			} elseif ( 'hidden' == $_group_status ) {
				$icon_html = '<i class="fa fa-user-secret"></i>';
			} elseif ( 'private' == $_group_status ) {
				$icon_html = '<i class="fa fa-lock"></i>';
			} else {
				$icon_html = '<i class="fa fa-cog"></i>';
			}
			?>
			<div class="wbtm-bp-grp-type-<?php echo $_group_status; ?>">
				<?php echo $icon_html; ?>
				<?php bp_group_type(); ?>
			</div>
			<?php
		}
	}

	/**
	* showing group type statistics
	*
	* @since 1.0.7
	*/
	if( !function_exists( 'wbtm_render_bp_directory_groups_items' ) ) {
		add_action( 'wbtm_bp_directory_groups_data', 'wbtm_render_bp_directory_groups_items' );
		function wbtm_render_bp_directory_groups_items() {
			$info_array	 = array();
			$group_id	 = bp_get_group_id();
			$url_to_use = bp_get_group_permalink();
			$info_array[ 'last_active' ] = array(
				'tooltip_text'	 => bp_get_group_last_active(),
				'url'			 => $url_to_use,
				'icon_class'	 => 'fa fa-clock-o',
				'color'			 => '#EC7063'
			);

			if ( bp_is_active( 'activity' ) ) {
		 		global $bp, $wpdb;
			 	$total_activity_in_grp = $wpdb->get_var( "SELECT COUNT(*) FROM {$bp->activity->table_name} WHERE component = 'groups' AND item_id = '$group_id' ");
			 	$url_to_use = bp_get_group_permalink();
			 	$info_array[ 'activity_post_count' ] = array(
					'tooltip_text'	 => sprintf( '%s Posts', $total_activity_in_grp, 'reign' ),
					'url'			 => $url_to_use,
					'icon_class'	 => 'fa fa-pencil',
					'color'			 => '#5DADE2'
				);
		 	}

		 	global $groups_template;
			if ( isset( $groups_template->group->total_member_count ) ) {
				$member_count = (int) $groups_template->group->total_member_count;
			} else {
				$member_count = 0;
			}
			$url_to_use	= esc_url( bp_get_group_permalink() . bp_get_members_slug() );
		 	$info_array[ 'member_count' ] = array(
				'tooltip_text'	 => sprintf( '%s Members', $member_count, 'reign' ),
				'url'			 => $url_to_use,
				'icon_class'	 => 'fa fa-users',
				'color'			 => '#F5B041'
			);

			echo '<div class="wbtm-member-directory-meta">';
			foreach ( $info_array as $key => $info ) {
				?>
				<div class="rtm-tooltip" style="background: <?php echo $info[ 'color' ]; ?>">
					<a href="<?php echo $info[ 'url' ]; ?>"><i class="<?php echo $info[ 'icon_class' ]; ?>"></i></a>
					<span class="rtm-tooltiptext">
						<?php echo $info[ 'tooltip_text' ]; ?>
					</span>
				</div>
				<?php
			}
			echo '</div>';
		}
	}

// }

/**
********************************************************
********************************************************
******** GROUP DIRECTORY CUSTOMIZATION :: END ********
********************************************************
********************************************************
*/


/**
********************************************************
********************************************************
******** GROUP SINGLE PAGE CUSTOMIZATION :: START ********
********************************************************
********************************************************
*/

/**
* showing group name on member cover image
*
* @since 1.0.7
*/
if( !function_exists( 'wbtm_bp_group_header_render_name_and_rating' ) ) {
	add_action( 'bp_before_group_header_meta', 'wbtm_bp_group_header_render_name_and_rating', 5 );
	function wbtm_bp_group_header_render_name_and_rating() {
		?>
		<div class="item-title"><h2 class="user-nicename"><?php echo esc_html(bp_get_group_name()); ?></h2></div>
		<?php
	}
}

/**
* showing group statistics on member cover image
*
* @since 1.0.7
*/
if( !function_exists( 'wbtm_render_extra_group_info' ) ) {
	add_action( 'wbtm_group_extra_info_section', 'wbtm_render_extra_group_info' );
	function wbtm_render_extra_group_info() {
		$info_array	 = array();
		$group_id	 = bp_get_group_id();

		if ( bp_is_active( 'activity' ) ) {
	 		global $bp, $wpdb;
		 	$total_activity_in_grp = $wpdb->get_var( "SELECT COUNT(*) FROM {$bp->activity->table_name} WHERE component = 'groups' AND item_id = '$group_id' ");
		 	$info_array[ 'activity_post_count' ] = array(
				'value'	 => $total_activity_in_grp,
				'label'	 => __( 'Posts', 'reign' ),
			);
	 	}

	 	global $groups_template;
		if ( isset( $groups_template->group->total_member_count ) ) {
			$member_count = (int) $groups_template->group->total_member_count;
		} else {
			$member_count = 0;
		}

		$info_array[ 'member_count' ] = array(
			'value'	 => $member_count,
			'label'	 => __( 'Members', 'reign' ),
		);

	 	foreach( $info_array as $key => $info ) {
	        ?>
	        <div class="rtm-usermeta-box">
	        	<span class="rtm-usermeta-count">
	        		<?php echo $info['value']; ?>
	        	</span>
	        	<span class="rtm-usermeta-text">
	        		<?php echo $info['label']; ?>
	        	</span>
	        </div>
	        <?php
	    }
	}
}

/**
********************************************************
********************************************************
******** GROUP SINGLE PAGE CUSTOMIZATION :: START ********
********************************************************
********************************************************
*/