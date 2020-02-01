<?php
/**
 * BuddyPress - Groups Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter().
 *
 * @package    BuddyPress
 * @subpackage bp-legacy
 */
?>

<?php
/**
 * Fires before the display of groups from the groups loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_groups_loop' );
?>

<?php if ( bp_get_current_group_directory_type() ) : ?>
	<p class="current-group-type"><?php bp_current_group_directory_type_message(); ?></p>
<?php endif; ?>

<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>

	<div id="pag-top" class="pagination">
		<div class="pag-count" id="group-dir-count-top">

			<?php bp_groups_pagination_count(); ?>

		</div>
		<div class="pagination-links" id="group-dir-pag-top">

			<?php bp_groups_pagination_links(); ?>

		</div>
	</div>

	<?php
	/**
	 * Fires before the listing of the groups list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_groups_list' );

	global $wbtm_reign_settings;
	$group_directory_type	 = isset( $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_directory_type' ] ) ? $wbtm_reign_settings[ 'reign_buddyextender' ][ 'group_directory_type' ] : 'wbtm-group-directory-type-2';
	$addition_class			 = '';
	if ( $group_directory_type != 'wbtm-group-directory-type-1' ) {
		$addition_class = 'lg-wb-grid-1-3';
	}
	?>
	<?php
	if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
		$img_class = 'img-card';
	}
	?>

	<ul id="groups-list" class="item-list wb-grid rg-group-list <?php echo esc_attr($group_directory_type); ?>" aria-live="assertive" aria-atomic="true" aria-relevant="all">

		<?php
		while ( bp_groups() ) :
			bp_the_group();
			?>

			<li <?php bp_group_class( array( "wb-grid-cell sm-wb-grid-1-1 md-wb-grid-1-2 '. $addition_class .'" ) ); ?>>
				<div class="bp-group-inner-wrap">

					<?php
					/**
					 * Fires inside the listing of an individual group listing item.
					 * Added by Reign Theme
					 * @since 1.0.7
					 */
					do_action( 'wbtm_before_group_avatar_group_directory' );
					?>
					<?php if ( !bp_disable_group_avatar_uploads() ) : ?>
						<?php
						if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
							echo '<figure class="img-dynamic aspect-ratio avatar">';
						}
						?>
						<a class="item-avatar-group <?php echo esc_attr($img_class); ?>" href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( '' ); ?></a>
						<?php
						if ( $group_directory_type == 'wbtm-group-directory-type-4' ) {
							echo '</figure>';
						}
						?>
					<?php endif; ?>

					<div class="group-content-wrap">

						<div class="item">
							<div class="item-title"><?php bp_group_link(); ?></div>
							<!-- <div class="item-meta"><span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ); ?>"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span></div> -->

									<!-- <div class="item-desc"><?php bp_group_description_excerpt(); ?></div> -->

							<?php
							/**
							 * Fires inside the listing of an individual group listing item.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_directory_groups_item' );
							?>

						</div>

						<?php do_action( 'wbtm_bp_directory_groups_data' ); ?>

						<div class="group-admins-wrap">
							<?php reign_bp_group_list_admins(); ?>
						</div>
						<?php
						if ( $group_directory_type == 'wbtm-group-directory-type-3' ) {
							echo '<div class="action-wrap"><i class="fa fa-plus-circle"></i>';
						}
						?>
						<div class="action">

							<?php
							/**
							 * Fires inside the action section of an individual group listing item.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_directory_groups_actions' );
							?>


						</div>
						<?php
						if ( $group_directory_type == 'wbtm-group-directory-type-3' ) {
							echo '</div>';
						}
						?>
					</div>
				</div>
			</li>
		<?php endwhile; ?>
	</ul>

	<?php
	/**
	 * Fires after the listing of the groups list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_directory_groups_list' );
	?>

	<div id="pag-bottom" class="pagination">
		<div class="pag-count" id="group-dir-count-bottom">

			<?php bp_groups_pagination_count(); ?>

		</div>
		<div class="pagination-links" id="group-dir-pag-bottom">

			<?php bp_groups_pagination_links(); ?>

		</div>
	</div>

<?php else : ?>

	<div id="message" class="info">
		<p><?php _e( 'There were no groups found.', 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php
/**
 * Fires after the display of groups from the groups loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_groups_loop' );
