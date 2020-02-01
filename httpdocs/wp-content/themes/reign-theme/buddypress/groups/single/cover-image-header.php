<?php
/**
 * BuddyPress - Groups Cover Image Header.
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */
/**
 * Fires before the display of a group's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_group_header' );
?>
<?php
global $wbtm_reign_settings;
$group_header_class = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_header_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_header_type' ] : 'wbtm-cover-header-type-1';
$group_header_class = apply_filters( 'wbtm_rth_manage_group_header_class', $group_header_class );
?>
<div id="cover-image-container" class="wbtm-group-cover-image-container <?php echo esc_attr($group_header_class); ?>">
	<a id="header-cover-image" href="<?php echo esc_url( bp_get_group_permalink() ); ?>"></a>

	<div id="item-header-cover-image">

		<div class="wbtm-member-info-section"><!-- custom wrapper for main content :: start -->
			<?php if ( !bp_disable_group_avatar_uploads() ) : ?>
				<div id="item-header-avatar">
					<a href="<?php echo esc_url( bp_get_group_permalink() ); ?>">

						<?php bp_group_avatar(); ?>

					</a>
				</div><!-- #item-header-avatar -->
			<?php endif; ?>

			<div id="item-header-content">

				<div class="wbtm-item-buttons-wrapper">
					<!-- custom section to hover and reveal actions buttons :: start -->
					<div class="wbtm-show-item-buttons"><i class="fa fa-ellipsis-v"></i></div>
					<!-- custom section to hover and reveal actions buttons :: end -->

					<div id="item-buttons">
						<?php
						/**
						 * Fires in the member header actions section.
						 *
						 * @since 1.2.6
						 */
						do_action( 'bp_group_header_actions' );
						?>
					</div><!-- #item-buttons -->
				</div>

				<?php
				/**
				 * Fires before the display of the group's header meta.
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_before_group_header_meta' );
				?>

				<div id="item-meta">

					<?php
					/**
					 * Fires after the group header actions section.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_group_header_meta' );
					?>

					<?php
					/* Added by Reign */
					global $groups_template;
					$_group_status	 = & $groups_template->group->status;
					$icon_html		 = '';
					if ( 'public' == $_group_status ) {
						$icon_html = '<i class="fa fa-globe"></i>';
					} elseif ( 'hidden' == $_group_status ) {
						$icon_html = '<i class="fa fa-user-secret"></i>';
					} elseif ( 'private' == $_group_status ) {
						$icon_html = '<i class="fa fa-lock"></i>';
					} else {
						$icon_html = '<i class="fa fa-cog"></i>';
					}
					/* Added by Reign */
					?>

					<div class="wbtm-highlight">
						<?php echo $icon_html; ?>
						<span class="highlight"></i><?php bp_group_type(); ?></span>
					</div>
					<div class="wbtm-activity">
						<i class="fa fa-clock-o"></i>
						<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ); ?>"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span>
					</div>

					<?php //bp_group_description(); ?>

					<?php bp_group_type_list(); ?>
				</div>
			</div><!-- #item-header-content -->

			<?php if ( false ) : ?>
				<div id="item-actions">

					<?php if ( bp_group_is_visible() ) : ?>

						<h2><?php _e( 'Group Admins', 'buddypress' ); ?></h2>

						<?php
						bp_group_list_admins();

						/**
						 * Fires after the display of the group's administrators.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_after_group_menu_admins' );

						if ( bp_group_has_moderators() ) :

							/**
							 * Fires before the display of the group's moderators, if there are any.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_before_group_menu_mods' );
							?>

							<h2><?php _e( 'Group Mods', 'buddypress' ); ?></h2>

							<?php
							bp_group_list_mods();

							/**
							 * Fires after the display of the group's moderators, if there are any.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_after_group_menu_mods' );

						endif;

					endif;
					?>

				</div><!-- #item-actions -->
			<?php endif; ?>
		</div><!-- custom wrapper for main content :: end -->

		<!-- custom section for extra content :: start -->
		<div class="wbtm-cover-extra-info-section">
			<?php
			/**
			 * Fires after main content to show extra information.
			 *
			 * @since 1.0.7
			 */
			do_action( 'wbtm_group_extra_info_section' );
			?>
		</div>
		<!-- custom section for extra content :: start -->

	</div><!-- #item-header-cover-image -->
</div><!-- #cover-image-container -->

<?php
/**
 * Fires after the display of a group's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_group_header' );
?>

<div id="template-notices" role="alert" aria-atomic="true">
	<?php
	/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
	do_action( 'template_notices' );
	?>

</div>