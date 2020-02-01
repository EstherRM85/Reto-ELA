<?php
/**
 * BuddyPress Activity templates
 *
 * @since 2.3.0
 *
 * @package    BuddyPress
 * @subpackage bp-legacy
 */
/**
 * Fires before the activity directory listing.
 *
 * @since 1.5.0
 */
do_action('bp_before_directory_activity');
?>

<div id="buddypress">

<div class="wb-grid">

    <aside id="left" class="widget-area sm-wb-grid-1-4 md-wb-grid-1-5" role="complementary">
    <div class="widget-area-inner">
            <?php get_template_part('template-parts/activity-widgets'); ?>
    </div>
    </aside>

    <div class="activity-content-wrapper">
    <div class="wb-grid">
        <div class="bp-content-area activity-content-area">
        <header class="entry-header">
            <?php
            /** the_title() creates conflict with BP Profile Search plugin. **/
            $component = bp_current_component();
            if ('activity' === $component) {
                if (is_multisite()) {
                    $bp_pages = get_site_option('bp-pages');
                } else {
                    $bp_pages = get_option('bp-pages');
                }
                    $page_id = $bp_pages[$component];
            }
            echo '<h1 class="entry-title">' . esc_attr__( get_the_title( $page_id ), 'reign' ) . '</h1>';
            ?>
            <span class="feed"><a href="<?php bp_sitewide_activity_feed_link(); ?>" class="bp-tooltip" data-bp-tooltip="<?php esc_attr_e('RSS Feed', 'buddypress'); ?>" aria-label="<?php esc_attr_e('RSS Feed', 'buddypress'); ?>"><?php _e('RSS', 'buddypress'); ?></a></span>
        </header><!-- .entry-header -->

            <?php
            /**
             * Fires before the activity directory display content.
             *
             * @since 1.2.0
             */
            do_action('bp_before_directory_activity_content');
            ?>

            <?php if (is_user_logged_in()) : ?>
        <?php bp_get_template_part('activity/post-form'); ?>

            <?php endif; ?>

        <div id="template-notices" role="alert" aria-atomic="true">
        <?php
        /**
         * Fires towards the top of template pages for notice display.
         *
         * @since 1.0.0
         */
        do_action('template_notices');
        ?>

        </div>

            <?php
            /**
             * Fires before the display of the activity list.
             *
             * @since 1.5.0
             */
            do_action('bp_before_directory_activity_list');
            ?>

        <div class="activity" aria-live="polite" aria-atomic="true" aria-relevant="all">

    <?php bp_get_template_part('activity/activity-loop'); ?>

        </div><!-- .activity -->

            <?php
            /**
             * Fires after the display of the activity list.
             *
             * @since 1.5.0
             */
            do_action('bp_after_directory_activity_list');
            ?>

            <?php
            /**
             * Fires inside and displays the activity directory display content.
             */
            do_action('bp_directory_activity_content');
            ?>

            <?php
            /**
             * Fires after the activity directory display content.
             *
             * @since 1.2.0
             */
            do_action('bp_after_directory_activity_content');
            ?>

            <?php
            /**
             * Fires after the activity directory listing.
             *
             * @since 1.5.0
             */
            do_action('bp_after_directory_activity');
            ?>

        </div>

    <?php echo get_sidebar('buddypress'); ?>

    </div>

    </div>

</div>

</div>
