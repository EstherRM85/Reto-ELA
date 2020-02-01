<?php
/**
 * BuddyPress - Groups Home
 *
 * @since 3.0.0
 * @version 3.0.0
 */
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
							<h2 class="widget-title"><span class="custom-name"><?php echo esc_html( the_title() ); ?></span><span class="rg-toggle ico-plus fa fa-plus-circle"></span></h2>
						</div>
						<?php bp_get_template_part( 'groups/single/parts/item-nav' ); ?>
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

							<?php bp_nouveau_group_template_part(); ?>

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