<?php
/**
 * BuddyPress - Groups Header
 *
 * @package    BuddyPress
 * @subpackage bp-legacy
 */

/**
 * Fires before the display of a group's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_group_header' );
?>

<div id="item-header-content">
	<span class="highlight"><?php bp_group_type(); ?></span>
	<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ); ?>"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span>

	<?php
	/**
	 * Fires before the display of the group's header meta.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_group_header_meta' );
	?>

	<div id="item-meta">

	<?php bp_group_description(); ?>

	<?php bp_group_type_list(); ?>

		<div id="item-buttons">

	<?php
	/**
	 * Fires in the group header actions section.
	 *
	 * @since 1.2.6
	 */
	do_action( 'bp_group_header_actions' );
	?>

		</div><!-- #item-buttons -->

	<?php
	/**
	 * Fires after the group header actions section.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_group_header_meta' );
	?>

	</div>
</div><!-- #item-header-content -->

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
	/**
	 * This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php
	 */
	do_action( 'template_notices' );
	?>

</div>
