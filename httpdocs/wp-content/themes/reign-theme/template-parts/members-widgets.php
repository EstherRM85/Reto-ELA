<?php
if ( function_exists( 'bp_get_theme_package_id' ) ) {
	$theme_package_id = bp_get_theme_package_id();
} else {
	$theme_package_id = 'legacy';
}
if ( 'nouveau' === $theme_package_id ) {
	return;
}
?>
<div class="widget widget-members-nav rg-custom-mbl-menu">
	<h2 class="widget-title">
		<span><?php _e( 'Members', 'buddypress' ); ?></span>
		<span class="custom-icon ico-plus fa fa-plus-circle"></span>
		<span class="custom-icon ico-minus fa fa-minus-circle"></span>
	</h2>
	<div class="item-list-tabs" aria-label="<?php esc_attr_e( 'Members directory main navigation', 'buddypress' ); ?>" role="navigation">
		<ul>
			<li class="selected" id="members-all"><a href="<?php bp_members_directory_permalink(); ?>"><?php printf( __( 'All Members %s', 'buddypress' ), '<span>' . bp_get_total_member_count() . '</span>' ); ?></a></li>

			<?php if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>
				<li id="members-personal"><a href="<?php echo esc_url( bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/' ); ?>"><?php printf( __( 'My Friends %s', 'buddypress' ), '<span>' . bp_get_total_friend_count( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>
			<?php endif; ?>

			<?php do_action( 'bp_members_directory_member_types' ); ?>
		</ul>
	</div><!-- .item-list-tabs -->
</div>

<div class="widget widget-members-subnav rg-custom-mbl-menu">
	<h2 class="widget-title">
		<span><?php _e( 'Order By:', 'buddypress' ); ?></span>
		<span class="custom-icon ico-plus fa fa-plus-circle"></span>
		<span class="custom-icon ico-minus fa fa-minus-circle"></span>
	</h2>
	<div class="item-list-tabs" id="subnav" aria-label="<?php esc_attr_e( 'Members directory secondary navigation', 'buddypress' ); ?>" role="navigation">
		<ul>
			<?php do_action( 'bp_members_directory_member_sub_types' ); ?>
			<li id="members-order-select" class="last filter">
				<select id="members-order-by" class="rg-select-filter">
					<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
					<option value="newest"><?php _e( 'Newest Registered', 'buddypress' ); ?></option>

					<?php if ( bp_is_active( 'xprofile' ) ) : ?>
						<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>
					<?php endif; ?>

					<?php do_action( 'bp_members_directory_order_options' ); ?>
				</select>
				<ul class="rg-filters-wrap"></ul>
			</li>
		</ul>
	</div>
</div>
