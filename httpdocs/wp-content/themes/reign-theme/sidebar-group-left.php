<aside id="left" class="widget-area group-widget-area sm-wb-grid-1-4 md-wb-grid-1-5" role="complementary">
	<?php do_action( 'wbcom_begin_group_left_sidebar' ); ?>

	<div class="widget-area-inner">
		<div class="widget-groups-nav-inner-wrap">
			<?php //if ( !bp_disable_group_avatar_uploads() ) : ?>
			<!-- <div id="item-header-avatar">
				<a href="<?php //echo esc_url( bp_get_group_permalink() );                   ?>">

			<?php //bp_group_avatar(); ?>

				</a>
			</div> --><!-- #item-header-avatar -->
			<?php //endif; ?>

			<div class="widget widget-groups-nav rg-custom-mbl-menu">
				<span class="custom-icon ico-plus fa fa-plus-circle"></span>
				<span class="custom-icon ico-minus fa fa-minus-circle"></span>
				<h2 class="widget-title"><span><?php the_title(); ?></span></h2>
				<div id="item-nav">
					<div class="item-list-tabs no-ajax" id="object-nav" aria-label="<?php esc_attr_e( 'Group primary navigation', 'buddypress' ); ?>" role="navigation">
						<ul>

							<?php bp_get_options_nav(); ?>

							<?php
							/**
							 * Fires after the display of group options navigation.
							 *
							 * @since 1.2.0
							 */
							do_action( 'bp_group_options_nav' );
							?>

						</ul>
					</div>
				</div><!-- #item-nav -->
			</div>
		</div>

		<div class="widget">
			<div id="item-actions">

				<?php if ( bp_group_is_visible() ) : ?>

					<h2 class="widget-title"><span><?php _e( 'Group Admins', 'buddypress' ); ?></span></h2>

					<?php
					bp_group_list_admins();

					/**
					 * Fires after the display of the group's administrators.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_after_group_menu_admins' );

					if ( bp_group_has_moderators() ) :

						/**
						 * Fires before the display of the group's moderators, if there are any.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_before_group_menu_mods' );
						?>

						<h2 class="widget-title group-mods-title"><span><?php _e( 'Group Mods', 'buddypress' ); ?></span></h2>

						<?php
						bp_group_list_mods();

						/**
						 * Fires after the display of the group's moderators, if there are any.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_after_group_menu_mods' );

					endif;

				endif;
				?>

			</div><!-- #item-actions -->
		</div>

		<?php if ( bp_is_group_activity() ) : ?>
			<div class="widget group-activity-filter rg-custom-mbl-menu">
				<h2 class="widget-title">
					<span><?php _e( 'Show:', 'buddypress' ); ?></span>
					<span class="custom-icon ico-plus fa fa-plus-circle"></span>
					<span class="custom-icon ico-minus fa fa-minus-circle"></span>
				</h2>

				<div class="item-list-tabs no-ajax" id="subnav" aria-label="<?php esc_attr_e( 'Group secondary navigation', 'buddypress' ); ?>" role="navigation">
					<ul>

						<?php
						/**
						 * Fires inside the syndication options list, after the RSS option.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_group_activity_syndication_options' );
						?>

						<li id="activity-filter-select" class="last">

							<select id="activity-filter-by" class="rg-select-filter">
								<option value="-1"><?php _e( 'Everything', 'buddypress' ); ?></option>

								<?php bp_activity_show_filters( 'group' ); ?>

								<?php
								/**
								 * Fires inside the select input for group activity filter options.
								 *
								 * @since 1.2.0
								 */
								do_action( 'bp_group_activity_filter_options' );
								?>
							</select>
							<ul class="rg-filters-wrap"></ul>
						</li>
					</ul>
				</div><!-- .item-list-tabs -->
			</div>
		<?php endif; ?>
	</div>

	<?php do_action( 'wbcom_end_group_left_sidebar' ); ?>
</aside>
