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
<div class="widget widget-groups-by rg-custom-mbl-menu">
	<h2 class="widget-title">
		<span><?php _e( 'Groups', 'buddypress' ); ?></span>
		<span class="custom-icon ico-plus fa fa-plus-circle"></span>
		<span class="custom-icon ico-minus fa fa-minus-circle"></span>
	</h2>
	<div class="item-list-tabs" aria-label="<?php esc_attr_e( 'Groups directory main navigation', 'buddypress' ); ?>">
		<ul>
			<li class="selected" id="groups-all"><a href="<?php bp_groups_directory_permalink(); ?>"><?php printf( __( 'All Groups %s', 'buddypress' ), '<span>' . bp_get_total_group_count() . '</span>' ); ?></a></li>

			<?php if ( is_user_logged_in() && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>
				<li id="groups-personal"><a href="<?php echo bp_loggedin_user_domain() . bp_get_groups_slug() . '/my-groups/'; ?>"><?php printf( __( 'My Groups %s', 'buddypress' ), '<span>' . bp_get_total_group_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>
			<?php endif; ?>

			<?php do_action( 'bp_groups_directory_group_filter' ); ?>

		</ul>
	</div><!-- .item-list-tabs -->
</div>

<div class="widget widget-groups-orderby rg-custom-mbl-menu">
	<h2 class="widget-title">
		<span><?php _e( 'Order By:', 'buddypress' ); ?></span>
		<span class="custom-icon ico-plus fa fa-plus-circle"></span>
		<span class="custom-icon ico-minus fa fa-minus-circle"></span>
	</h2>
	<div class="item-list-tabs" id="subnav" aria-label="<?php esc_attr_e( 'Groups directory secondary navigation', 'buddypress' ); ?>" role="navigation">
		<ul>
			<?php do_action( 'bp_groups_directory_group_types' ); ?>
			<li id="groups-order-select" class="last filter">
				<select id="groups-order-by" class="rg-select-filter">
					<option value="active"><?php _e( 'Last Active', 'buddypress' ); ?></option>
					<option value="popular"><?php _e( 'Most Members', 'buddypress' ); ?></option>
					<option value="newest"><?php _e( 'Newly Created', 'buddypress' ); ?></option>
					<option value="alphabetical"><?php _e( 'Alphabetical', 'buddypress' ); ?></option>
					<?php do_action( 'bp_groups_directory_order_options' ); ?>
				</select>
				<ul class="rg-filters-wrap"></ul>
			</li>
		</ul>
	</div>
</div>

<?php
if ( class_exists( 'Bp_Add_Group_Types' ) ) {
	$group_types			 = bp_groups_get_group_types( array(), 'objects' );
	$group_select_html		 = '';
	$group_select_li_html	 = '';
	if ( !empty( $group_types ) && is_array( $group_types ) ) {
		$group_select_html		 .= '<select class="bpgt-groups-search-group-type" style="display:none;">';
		$group_select_html		 .= '<option value="">' . __( 'All Types', 'reign' ) . '</option>';
		$group_select_li_html	 .= '<li><a href="javascript:void(0);" data-group-slug="">' . __( 'All Types', 'reign' ) . '</a></li>';
		;
		foreach ( $group_types as $group_type_slug => $group_type ) {
			$group_select_html		 .= '<option value="' . $group_type_slug . '">' . $group_type->labels[ 'name' ] . '</option>';
			$group_select_li_html	 .= '<li><a href="javascript:void(0);" data-group-slug="' . $group_type_slug . '">' . $group_type->labels[ 'name' ] . '</a></li>';
		}
		$group_select_html .= '</select>';
	}
	?>
	<div class="widget widget-groups-groupby rg-custom-mbl-menu">
		<h2 class="widget-title">
			<span><?php _e( 'Group Types', 'buddypress' ); ?></span>
			<span class="custom-icon ico-plus fa fa-plus-circle"></span>
			<span class="custom-icon ico-minus fa fa-minus-circle"></span>
		</h2>
		<?php echo $group_select_html; ?>
		<div class="item-list-tabs" id="subnav" aria-label="<?php esc_attr_e( 'Groups directory secondary navigation', 'buddypress' ); ?>" role="navigation">
			<ul class="wb-group-type-filters-wrap">
				<?php echo $group_select_li_html; ?>
			</ul>
		</div>
	</div>
	<?php
}
?>
