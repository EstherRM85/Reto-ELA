<div class="widget widget-activity-nav rg-custom-mbl-menu">
	<h2 class="widget-title">
		<span><?php _e( 'Activity', 'buddypress' ); ?></span>
		<span class="custom-icon ico-plus fa fa-plus-circle"></span>
		<span class="custom-icon ico-minus fa fa-minus-circle"></span>
	</h2>
	<div class="item-list-tabs activity-type-tabs" aria-label="<?php esc_attr_e( 'Sitewide activities navigation', 'buddypress' ); ?>" role="navigation">
		<ul>
			<?php do_action( 'bp_before_activity_type_tab_all' ); ?>
			<li class="selected" id="activity-all"><a href="<?php bp_activity_directory_permalink(); ?>"><?php printf( __( 'All Members %s', 'buddypress' ), '<span>' . bp_get_total_member_count() . '</span>' ); ?></a></li>

			<?php if ( is_user_logged_in() ) : ?>
				<?php do_action( 'bp_before_activity_type_tab_friends' ); ?>

				<?php if ( bp_is_active( 'friends' ) ) : ?>
					<?php if ( bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>
						<li id="activity-friends"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_friends_slug() . '/'; ?>"><?php printf( __( 'My Friends %s', 'buddypress' ), '<span>' . bp_get_total_friend_count( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>
					<?php endif; ?>
				<?php endif; ?>

				<?php do_action( 'bp_before_activity_type_tab_groups' ); ?>

				<?php if ( bp_is_active( 'groups' ) ) : ?>
					<?php if ( bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>
						<li id="activity-groups"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_groups_slug() . '/'; ?>"><?php printf( __( 'My Groups %s', 'buddypress' ), '<span>' . bp_get_total_group_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>
					<?php endif; ?>
				<?php endif; ?>

				<?php do_action( 'bp_before_activity_type_tab_favorites' ); ?>

				<?php if ( bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ) : ?>
					<li id="activity-favorites"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/favorites/'; ?>"><?php printf( __( 'My Favorites %s', 'buddypress' ), '<span>' . bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>
				<?php endif; ?>

				<?php if ( bp_activity_do_mentions() ) : ?>
					<?php do_action( 'bp_before_activity_type_tab_mentions' ); ?>
					<li id="activity-mentions"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/'; ?>"><?php _e( 'Mentions', 'buddypress' ); ?><?php if ( bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ) : ?> <strong><span><?php printf( _nx( '%s new', '%s new', bp_get_total_mention_count_for_user( bp_loggedin_user_id() ), 'Number of new activity mentions', 'buddypress' ), bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ); ?></span></strong><?php endif; ?></a></li>
				<?php endif; ?>

			<?php endif; ?>

			<?php do_action( 'bp_activity_type_tabs' ); ?>
		</ul>
	</div><!-- .item-list-tabs -->
</div>

<div class="widget widget-activity-subnav rg-custom-mbl-menu">
	<h2 class="widget-title">
		<span><?php _e( 'Show:', 'buddypress' ); ?></span>
		<span class="custom-icon ico-plus fa fa-plus-circle"></span>
		<span class="custom-icon ico-minus fa fa-minus-circle"></span>
	</h2>

	<div class="item-list-tabs no-ajax" id="subnav" aria-label="<?php esc_attr_e( 'Activity secondary navigation', 'buddypress' ); ?>" role="navigation">
		<ul>
			<?php do_action( 'bp_activity_syndication_options' ); ?>

			<li id="activity-filter-select" class="last">
				<select id="activity-filter-by" class="rg-select-filter">
					<option value="-1"><?php _e( 'Everything', 'buddypress' ); ?></option>
					<?php bp_activity_show_filters(); ?>

					<?php do_action( 'bp_activity_filter_options' ); ?>
				</select>
				<ul class="rg-filters-wrap"></ul>
			</li>
		</ul>
	</div><!-- .item-list-tabs -->
</div>
