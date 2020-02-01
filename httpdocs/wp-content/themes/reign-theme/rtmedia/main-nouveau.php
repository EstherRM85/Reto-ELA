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
//if it's not an ajax request, load headers
if ( $rt_ajax_request ) {
	// include the right rtMedia template
	echo '<div id="buddypress">';
	rtmedia_load_template();
	echo '</div>';
	return;
}

if ( bp_is_group() ) {

	if ( bp_has_groups() ) :
		while ( bp_groups() ) :
			bp_the_group();
			?>

			<?php
			global $wbtm_reign_settings;
			$member_header_position = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] : 'inside';

			$bp_nouveau_appearance = bp_get_option( 'bp_nouveau_appearance', array() );
			if ( !isset( $bp_nouveau_appearance[ 'group_nav_display' ] ) ) {
				$bp_nouveau_appearance[ 'group_nav_display' ] = false;
			}
			?>

			<?php bp_nouveau_group_hook( 'before', 'home_content' ); ?>

			<?php if ( $member_header_position == 'top' ) : ?>
				<div id="item-header" role="complementary" data-bp-item-id="<?php bp_group_id(); ?>" data-bp-item-component="groups" class="groups-header single-headers">

					<?php bp_nouveau_group_header_template_part(); ?>

				</div><!-- #item-header -->
			<?php endif; ?>

			<div class="bp-wrap">

				<?php
				if ( $bp_nouveau_appearance[ 'group_nav_display' ] ) {
					if ( !bp_nouveau_is_object_nav_in_sidebar() ) {
						?>
						<div class="rg-nouveau-sidebar-menu">
							<div class="rg-nouveau-sidebar-head">
								<h2 class="widget-title"><span class="custom-name"><?php echo the_title(); ?></span><span class="rg-toggle ico-plus fa fa-plus-circle"></span></h2>
							</div>
							<?php bp_get_template_part( 'groups/single/parts/item-nav' ); ?>
						</div>
						<?php
					}
				}
				?>

				<div id="item-body" class="item-body">

					<div class="wb-grid">

						<div class="wb-grid-cell">

							<div class="item-body-inner-wrapper">

								<?php if ( $member_header_position == 'inside' ) : ?>
									<div id="item-header" role="complementary" data-bp-item-id="<?php bp_group_id(); ?>" data-bp-item-component="groups" class="groups-header single-headers">

										<?php bp_nouveau_group_header_template_part(); ?>

									</div><!-- #item-header -->
								<?php endif; ?>

								<?php
								if ( !$bp_nouveau_appearance[ 'group_nav_display' ] ) {
									if ( !bp_nouveau_is_object_nav_in_sidebar() ) {
										?>
										<div class="rg-nouveau-sidebar-menu">
											<div class="rg-nouveau-sidebar-head">
												<h2 class="widget-title"><span class="custom-name"><?php echo the_title(); ?></span><span class="rg-toggle ico-plus fa fa-plus-circle"></span></h2>
											</div>
											<?php bp_get_template_part( 'groups/single/parts/item-nav' ); ?>
										</div>
										<?php
									}
								}
								?>

								<nav class="rtm-bp-navs bp-navs bp-subnavs no-ajax user-subnav" id="subnav" role="navigation" aria-label="Notifications menu">
									<ul class="subnav">
										<?php rtmedia_sub_nav(); ?>
										<?php do_action( 'rtmedia_sub_nav' ); ?>
									</ul>
								</nav>

								<?php
								do_action( 'bp_before_member_media' );
								// include the right rtMedia template
								rtmedia_load_template();
								do_action( 'bp_after_member_media' );
								?>
							</div>
						</div>
						<?php echo get_sidebar( 'buddypress' ); ?>
					</div>

				</div><!-- #item-body -->

			</div><!-- // .bp-wrap -->

			<?php bp_nouveau_group_hook( 'after', 'home_content' ); ?>

		<?php endwhile; ?>

		<?php
	endif;

	return;
}

global $wbtm_reign_settings;
$member_header_position	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'member_header_position' ] : 'inside';
$member_header_position	 = apply_filters( 'wbtm_rth_manage_member_header_position', $member_header_position );

$bp_nouveau_appearance = bp_get_option( 'bp_nouveau_appearance', array() );
if ( !isset( $bp_nouveau_appearance[ 'user_nav_display' ] ) ) {
	$bp_nouveau_appearance[ 'user_nav_display' ] = false;
}
?>

<?php bp_nouveau_member_hook( 'before', 'home_content' ); ?>

<?php if ( $member_header_position == 'top' ) : ?>
	<div id="item-header" role="complementary" data-bp-item-id="<?php echo esc_attr( bp_displayed_user_id() ); ?>" data-bp-item-component="members" class="users-header single-headers">

		<?php bp_nouveau_member_header_template_part(); ?>

	</div><!-- #item-header -->
<?php endif; ?>

<div class="bp-wrap">

	<?php
	if ( $bp_nouveau_appearance[ 'user_nav_display' ] ) {
		if ( !bp_nouveau_is_object_nav_in_sidebar() ) {
			?>
			<div class="rg-nouveau-sidebar-menu">
				<div class="rg-nouveau-sidebar-head">
					<h2 class="widget-title"><span class="custom-name"><?php echo the_title(); ?></span><span class="rg-toggle ico-plus fa fa-plus-circle"></span></h2>
				</div>
				<?php bp_get_template_part( 'members/single/parts/item-nav' ); ?>
			</div>
			<?php
		}
	}
	?>

	<div id="item-body" class="item-body">

		<div class="wb-grid">
			<div class="wb-grid-cell">

				<div class="item-body-inner-wrapper">

					<?php if ( $member_header_position == 'inside' ) : ?>
						<div id="item-header" role="complementary" data-bp-item-id="<?php echo esc_attr( bp_displayed_user_id() ); ?>" data-bp-item-component="members" class="users-header single-headers">

							<?php bp_nouveau_member_header_template_part(); ?>

						</div><!-- #item-header -->
					<?php endif; ?>

					<?php
					if ( !$bp_nouveau_appearance[ 'user_nav_display' ] ) {
						if ( !bp_nouveau_is_object_nav_in_sidebar() ) {
							?>
							<div class="rg-nouveau-sidebar-menu">
								<div class="rg-nouveau-sidebar-head">
									<h2 class="widget-title"><span class="custom-name"><?php echo the_title(); ?></span><span class="rg-toggle ico-plus fa fa-plus-circle"></span></h2>
								</div>
								<?php bp_get_template_part( 'members/single/parts/item-nav' ); ?>
							</div>
							<?php
						}
					}
					?>

					<nav class="rtm-bp-navs bp-navs bp-subnavs no-ajax user-subnav" id="subnav" role="navigation" aria-label="Notifications menu">
						<ul class="subnav">
							<?php rtmedia_sub_nav(); ?>
							<?php do_action( 'rtmedia_sub_nav' ); ?>
						</ul>
					</nav>

					<?php
					do_action( 'bp_before_member_media' );
					// include the right rtMedia template
					rtmedia_load_template();
					do_action( 'bp_after_member_media' );
					?>
				</div>
			</div>
			<?php echo get_sidebar( 'buddypress' ); ?>
		</div>

	</div><!-- #item-body -->
</div><!-- // .bp-wrap -->

<?php bp_nouveau_member_hook( 'after', 'home_content' ); ?>