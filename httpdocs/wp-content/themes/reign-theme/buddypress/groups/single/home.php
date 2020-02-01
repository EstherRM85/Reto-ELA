<?php
/**
 * BuddyPress - Groups Home
 *
 * @package    BuddyPress
 * @subpackage bp-legacy
 */
?>
<?php
global $wbtm_reign_settings;
$member_header_position = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] : 'inside';
?>
<div id="buddypress">

	<?php if ( $member_header_position == 'top' ) : ?>
		<div id="item-header" role="complementary">
			<?php
			if ( bp_has_groups() ) :
				while ( bp_groups() ) :
					bp_the_group();
					/**
					 * If the cover image feature is enabled, use a specific header
					 */
					if ( bp_group_use_cover_image_header() ) :
						bp_get_template_part( 'groups/single/cover-image-header' );
					else :
						bp_get_template_part( 'groups/single/group-header' );
					endif;
				endwhile;
			endif;
			?>
		</div><!-- #item-header -->
	<?php endif; ?>

	<div class="wb-grid">

		<?php
		if ( bp_has_groups() ) :
			while ( bp_groups() ) :
				bp_the_group();
				?>

				<?php echo get_sidebar( 'group-left' ); ?>

				<div class="bp-content-area single-group-content-area">
					<?php
					/**
					 * Fires before the display of the group home content.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_before_group_home_content' );
					?>

					<?php if ( $member_header_position == 'inside' ) : ?>
						<div id="item-header" role="complementary">
							<?php
							/**
							 * If the cover image feature is enabled, use a specific header
							 */
							if ( bp_group_use_cover_image_header() ) :
								bp_get_template_part( 'groups/single/cover-image-header' );
							else :
								bp_get_template_part( 'groups/single/group-header' );
							endif;
							?>
						</div><!-- #item-header -->
					<?php endif; ?>

					<div id="item-body">

						<div class="wb-grid">

							<div class="wb-grid-cell item-body-wrap">

								<div class="inner-item-body-wrap">

									<?php
									/**
									 * Fires before the display of the group home body.
									 *
									 * @since 1.2.0
									 */
									do_action( 'bp_before_group_body' );

									/**
									 * Does this next bit look familiar? If not, go check out WordPress's
									 * /wp-includes/template-loader.php file.
									 *
									 * @todo A real template hierarchy? Gasp!
									 */
									// Looking at home location.
									if ( bp_is_group_home() ) :

										if ( bp_group_is_visible() ) {

											// Load appropriate front template.
											bp_groups_front_template_part();
										} else {

											/**
											 * Fires before the display of the group status message.
											 *
											 * @since 1.1.0
											 */
											do_action( 'bp_before_group_status_message' );
											?>

											<div id="message" class="info">
												<p><?php bp_group_status_message(); ?></p>
											</div>

											<?php
											/**
											 * Fires after the display of the group status message.
											 *
											 * @since 1.1.0
											 */
											do_action( 'bp_after_group_status_message' );
										}

									// Not looking at home.
									else :

										// Group Admin.
										if ( bp_is_group_admin_page() ) :
											bp_get_template_part( 'groups/single/admin' );

										// Group Activity.
										elseif ( bp_is_group_activity() ) :
											bp_get_template_part( 'groups/single/activity' );

										// Group Members.
										elseif ( bp_is_group_members() ) :
											bp_groups_members_template_part();

										// Group Invitations.
										elseif ( bp_is_group_invites() ) :
											bp_get_template_part( 'groups/single/send-invites' );

										// Old group forums.
										// elseif ( bp_is_group_forum() ) :
										// 	bp_get_template_part( 'groups/single/forum' );

										// Membership request.
										elseif ( bp_is_group_membership_request() ) :
											bp_get_template_part( 'groups/single/request-membership' );

										// Anything else (plugins mostly).
										else :
											bp_get_template_part( 'groups/single/plugins' );

										endif;

									endif;

									/**
									 * Fires after the display of the group home body.
									 *
									 * @since 1.2.0
									 */
									do_action( 'bp_after_group_body' );
									?>

								</div>

							</div>

							<?php echo get_sidebar( 'buddypress' ); ?>

						</div>

					</div><!-- #item-body -->

					<?php
					/**
					 * Fires after the display of the group home content.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_after_group_home_content' );
					?>

					<?php
				endwhile;
			endif;
			?>

		</div>

	</div>

</div><!-- #buddypress -->
