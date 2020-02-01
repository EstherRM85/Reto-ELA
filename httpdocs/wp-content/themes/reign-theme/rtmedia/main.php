<?php
/* * **************************************
 * Main.php
 *
 * The main template file, that loads the header, footer and sidebar
 * apart from loading the appropriate rtMedia template
 * *************************************** */

// by default it is not an ajax request
global $rt_ajax_request;
$rt_ajax_request = false;

//todo sanitize and fix $_SERVER variable usage
// check if it is an ajax request

$_rt_ajax_request = rtm_get_server_var( 'HTTP_X_REQUESTED_WITH', 'FILTER_SANITIZE_STRING' );
if ( 'xmlhttprequest' === strtolower( $_rt_ajax_request ) ) {
	$rt_ajax_request = true;
}
?>

<?php
global $wbtm_reign_settings;
$member_header_position	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] : 'inside';
$member_header_position	 = apply_filters( 'wbtm_rth_manage_member_header_position', $member_header_position );
?>

<div id="buddypress">
	<?php
	if ( bp_is_group() ) {

		if ( bp_has_groups() ) :
			while ( bp_groups() ) :
				bp_the_group();
				if ( $member_header_position == 'top' ) :
					?>
					<div id="item-header" role="complementary">
						<?php
						/**
						 * If the cover image feature is enabled, use a specific header
						 */
						if ( function_exists( 'bp_group_use_cover_image_header' ) && bp_group_use_cover_image_header() ) :
							bp_get_template_part( 'groups/single/cover-image-header' );
						else :
							bp_get_template_part( 'groups/single/group-header' );
						endif;
						?>
					</div><!--#item-header-->
				<?php endif; ?>
			<?php endwhile; ?>
			<?php
		endif;
	}elseif ( bp_displayed_user_id() ) {
		if ( $member_header_position == 'top' ) :
			?>
			<div id="item-header" role="complementary">
				<?php
				/**
				 * If the cover image feature is enabled, use a specific header
				 */
				if ( function_exists( 'bp_displayed_user_use_cover_image_header' ) && bp_displayed_user_use_cover_image_header() ) :
					bp_get_template_part( 'members/single/cover-image-header' );
				else :
					bp_get_template_part( 'members/single/member-header' );
				endif;
				?>
			</div><!--#item-header-->
			<?php
		endif;
	}
	?>

	<?php
	//if it's not an ajax request, load headers
	if ( !$rt_ajax_request ) {
		// if this is a BuddyPress page, set template type to
		// buddypress to load appropriate headers
		if ( class_exists( 'BuddyPress' ) && !bp_is_blog_page() && apply_filters( 'rtm_main_template_buddypress_enable', true ) ) {
			$template_type = 'buddypress';
		} else {
			$template_type = '';
		}

		if ( 'buddypress' === $template_type ) {
			//load buddypress markup
			if ( bp_displayed_user_id() ) {
				?>

				<?php do_action( 'bp_before_member_home_content' ); ?>
				<div class="wb-grid">

					<aside id="left" class="widget-area sm-wb-grid-1-4 md-wb-grid-1-5" role="complementary">
						<div class="widget-area-inner">
							<div class="widget widget-member-nav rg-custom-mbl-menu">
								<span class="custom-icon ico-plus fa fa-plus-circle"></span>
								<span class="custom-icon ico-minus fa fa-minus-circle"></span>
								<h2 class="widget-title"><span><?php the_title(); ?></span></h2>

								<div id="item-nav">
									<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
										<ul>
											<?php bp_get_displayed_user_nav(); ?>
											<?php do_action( 'bp_member_options_nav' ); ?>
										</ul>
									</div>
								</div><!--#item-nav-->
							</div>
						</div>
					</aside>
					<div class="wb-grid-cell">
						<?php if ( $member_header_position == 'inside' ) : ?>
							<div id="item-header" role="complementary">
								<?php
								/**
								 * If the cover image feature is enabled, use a specific header
								 */
								if ( function_exists( 'bp_displayed_user_use_cover_image_header' ) && bp_displayed_user_use_cover_image_header() ) :
									bp_get_template_part( 'members/single/cover-image-header' );
								else :
									bp_get_template_part( 'members/single/member-header' );
								endif;
								?>
							</div><!--#item-header-->
						<?php endif; ?>

						<?php echo '<div id="item-body" role="main">'; ?>

						<?php do_action( 'bp_before_member_body' ); ?>
						<?php do_action( 'bp_before_member_media' ); ?>

						<div class="item-list-tabs no-ajax" id="subnav">
							<ul>
								<?php rtmedia_sub_nav(); ?>
								<?php do_action( 'rtmedia_sub_nav' ); ?>
							</ul>
						</div><!-- .item-list-tabs -->
					</div>

					<?php
				} else if ( bp_is_group() ) {
					//not a member profile, but a group
					?>

					<?php
					if ( bp_has_groups() ) : while ( bp_groups() ) :
							bp_the_group();
							?>

							<?php
							/**
							 * Fires before the display of the group home content.
							 *
							 * @since 1.2.0
							 */
							do_action( 'bp_before_group_home_content' );
							?>
							<div class="wb-grid">
								<aside id="left" class="widget-area group-widget-area sm-wb-grid-1-4 md-wb-grid-1-5" role="complementary">
									<div class="widget-area-inner">
										<div class="widget-groups-nav-inner-wrap">
											<?php if ( !bp_disable_group_avatar_uploads() ) : ?>
												<div id="item-header-avatar">
													<a href="<?php echo esc_url( bp_get_group_permalink() ); ?>">
														<?php bp_group_avatar(); ?>
													</a>
												</div><!-- #item-header-avatar -->
											<?php endif; ?>
											<div class="widget widget-member-nav rg-custom-mbl-menu">
												<span class="custom-icon ico-plus fa fa-plus-circle"></span>
												<span class="custom-icon ico-minus fa fa-minus-circle"></span>
												<h2 class="widget-title"><span><?php the_title(); ?></span></h2>
												<div id="item-nav">
													<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
														<ul>
															<?php bp_get_options_nav(); ?>
															<?php do_action( 'bp_group_options_nav' ); ?>
														</ul>
													</div>
												</div><!-- #item-nav -->
											</div>
										</div>
									</div>
								</aside>
								<div class="wb-grid-cell">
									<?php if ( $member_header_position == 'inside' ) : ?>
										<div id="item-header" role="complementary">
											<?php
											/**
											 * If the cover image feature is enabled, use a specific header
											 */
											if ( function_exists( 'bp_group_use_cover_image_header' ) && bp_group_use_cover_image_header() ) :
												bp_get_template_part( 'groups/single/cover-image-header' );
											else :
												bp_get_template_part( 'groups/single/group-header' );
											endif;
											?>
										</div><!--#item-header-->
									<?php endif; ?>
									<?php echo '<div id="item-body" role="main">'; ?>

									<?php do_action( 'bp_before_group_body' ); ?>
									<?php do_action( 'bp_before_group_media' ); ?>

									<div class="item-list-tabs no-ajax" id="subnav">
										<ul>
											<?php rtmedia_sub_nav(); ?>
											<?php do_action( 'rtmedia_sub_nav' ); ?>
										</ul>
									</div><!-- .item-list-tabs -->
								</div>
								<?php
							endwhile;
						endif; // group/profile if/else
					}
				} else { ////if BuddyPress
					?>

					<?php echo '<div id="item-body" role="main">'; ?>

					<?php
				}
			} // if ajax

			rtmedia_load_template();

			if ( !$rt_ajax_request ) {
				if ( function_exists( 'bp_displayed_user_id' ) && 'buddypress' === $template_type && ( bp_displayed_user_id() || bp_is_group() ) ) {
					if ( bp_is_group() ) {
						do_action( 'bp_after_group_media' );
						do_action( 'bp_after_group_body' );
					}
					if ( bp_displayed_user_id() ) {
						do_action( 'bp_after_member_media' );
						do_action( 'bp_after_member_body' );
					}
				}
				?>

				<?php echo '</div><!--#item-body-->'; ?>

				<?php
				if ( function_exists( 'bp_displayed_user_id' ) && 'buddypress' === $template_type && ( bp_displayed_user_id() || bp_is_group() ) ) {
					if ( bp_is_group() ) {
						do_action( 'bp_after_group_home_content' );
					}
					if ( bp_displayed_user_id() ) {
						do_action( 'bp_after_member_home_content' );
					}
				}
			}
			?>
		</div><!-- wb-grid -->
	</div><!-- wb-grid -->
</div>