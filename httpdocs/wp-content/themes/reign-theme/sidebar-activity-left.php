<aside id="left" class="widget-area activity-widget-area sm-wb-grid-1-4 md-wb-grid-1-5" role="complementary">

	<?php do_action( 'wbcom_begin_activity_left_sidebar' ); ?>

	<div class="widget-area-inner">
		<div class="widget widget-member-nav rg-custom-mbl-menu">
			<span class="custom-icon ico-plus fa fa-plus-circle"></span>
			<span class="custom-icon ico-minus fa fa-minus-circle"></span>
			<h2 class="widget-title"><span><?php the_title(); ?></span></h2>
			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav" aria-label="<?php esc_attr_e( 'Member primary navigation', 'buddypress' ); ?>" role="navigation">
					<ul>

						<?php bp_get_displayed_user_nav(); ?>

						<?php
						/**
						 * Fires after the display of member options navigation.
						 *
						 * @since 1.2.4
						 */
						do_action( 'bp_member_options_nav' );
						?>

					</ul>
				</div>
			</div><!-- #item-nav -->
		</div>

		<?php if ( bp_is_user_activity() || !bp_current_component() ) : ?>
			<div class="widget member-activity-filter rg-custom-mbl-menu">
				<h2 class="widget-title">
					<span><?php _e( 'Show:', 'buddypress' ); ?></span>
					<span class="custom-icon ico-plus fa fa-plus-circle"></span>
					<span class="custom-icon ico-minus fa fa-minus-circle"></span>
				</h2>

				<div class="item-list-tabs no-ajax" id="subnav" aria-label="<?php esc_attr_e( 'Member secondary navigation', 'buddypress' ); ?>" role="navigation">
					<ul>

						<?php //bp_get_options_nav(); ?>

						<li id="activity-filter-select" class="last">
							<label for="activity-filter-by"><?php //_e( 'Show:', 'buddypress' );            ?></label>
							<select id="activity-filter-by" class="rg-select-filter">
								<option value="-1"><?php _e( 'Everything', 'buddypress' ); ?></option>

								<?php bp_activity_show_filters(); ?>

								<?php
								/**
								 * Fires inside the select input for member activity filter options.
								 *
								 * @since 1.2.0
								 */
								do_action( 'bp_member_activity_filter_options' );
								?>

							</select>
							<ul class="rg-filters-wrap"></ul>
						</li>
					</ul>
				</div><!-- .item-list-tabs -->
			</div>
		<?php endif; ?>
	</div>

	<?php do_action( 'wbcom_end_activity_left_sidebar' ); ?>

</aside>
