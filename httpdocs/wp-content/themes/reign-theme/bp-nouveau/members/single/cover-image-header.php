<?php
/**
 * BuddyPress - Users Cover Image Header
 *
 * @since 3.0.0
 * @version 3.0.0
 */
?>
<?php
global $wbtm_reign_settings;
$member_header_class = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_type' ] : 'wbtm-cover-header-type-1';
$member_header_class = apply_filters( 'wbtm_rth_manage_member_header_class', $member_header_class );
?>
<div id="cover-image-container" class="wbtm-member-cover-image-container <?php echo esc_attr($member_header_class); ?>">
	<div id="header-cover-image"></div>

	<div id="item-header-cover-image">

		<div class="wbtm-member-info-section"><!-- custom wrapper for main content :: start -->

			<div id="item-header-avatar">
				<a href="<?php bp_displayed_user_link(); ?>">

					<?php bp_displayed_user_avatar( 'type=full' ); ?>

				</a>
			</div><!-- #item-header-avatar -->

			<div id="item-header-content">

				<?php
				/**
				 * Fires before the bp_displayed_user_mentionname.
				 * Added by Reign Theme
				 * @since 1.0.7
				 */
				do_action( 'wbtm_bp_before_displayed_user_mentionname' );
				?>

				<?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
					<h2 class="user-nicename">@<?php bp_displayed_user_mentionname(); ?></h2>
				<?php endif; ?>


				<div class="wbtm-item-buttons-wrapper">
					<!-- custom section to hover and reveal actions buttons :: start -->
					<div class="wbtm-show-item-buttons"><i class="fa fa-ellipsis-v"></i></div>
					<!-- custom section to hover and reveal actions buttons :: end -->

					<div id="item-buttons">
						<?php
						bp_nouveau_member_header_buttons(
						array(
							'container'			 => 'ul',
							'button_element'	 => 'button',
							'container_classes'	 => array( 'member-header-actions' ),
						)
						);
						?>
					</div><!-- #item-buttons -->

				</div>


				<?php
				// bp_nouveau_member_header_buttons(
				// 	array(
				// 		'container'         => 'ul',
				// 		'button_element'    => 'button',
				// 		'container_classes' => array( 'member-header-actions' ),
				// 	)
				// );
				?>

				<?php bp_nouveau_member_hook( 'before', 'header_meta' ); ?>

				<?php if ( bp_nouveau_member_has_meta() ) : ?>
					<div class="item-meta">

						<?php bp_nouveau_member_meta(); ?>

					</div><!-- #item-meta -->

					<div class="wbtm-badge">
						<?php
						if ( function_exists( 'reign_profile_achievements' ) ):
							reign_profile_achievements();
						endif;
						?>
					</div>
				<?php endif; ?>

			</div><!-- #item-header-content -->

		</div><!-- custom wrapper for main content :: end -->

		<!-- custom section for extra content :: start -->
		<div class="wbtm-cover-extra-info-section">
			<?php
			/**
			 * Fires after main content to show extra information.
			 *
			 * @since 1.0.7
			 */
			do_action( 'wbtm_member_extra_info_section' );
			?>
		</div>
		<!-- custom section for extra content :: start -->

	</div><!-- #item-header-cover-image -->
</div><!-- #cover-image-container -->