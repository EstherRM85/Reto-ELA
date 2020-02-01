<?php
/**
 * BuddyPress - Members Home
 *
 * @since   1.0.0
 * @version 3.0.0
 */
?>
<?php
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
					<h2 class="widget-title"><span class="custom-name"><?php echo esc_html( the_title() ); ?></span><span class="rg-toggle ico-plus fa fa-plus-circle"></span></h2>
				</div>
				<?php bp_get_template_part( 'members/single/parts/item-nav' ); ?>
			</div>
			<?php
		}
	}
	?>

	<div id="item-body" class="item-body">

		<div class="wb-grid">

			<?php do_action( 'reign_bp_nouveau_before_content' ); ?>

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

					<?php bp_nouveau_member_template_part(); ?>
				</div>
			</div>

			<?php echo get_sidebar( 'buddypress' ); ?>

		</div>

	</div><!-- #item-body -->
</div><!-- // .bp-wrap -->

<?php bp_nouveau_member_hook( 'after', 'home_content' ); ?>