<?php
/**
 * BuddyPress - Groups
 *
 * @package    BuddyPress
 * @subpackage bp-legacy
 */

/**
 * Fires at the top of the groups directory template file.
 *
 * @since 1.5.0
 */
do_action('bp_before_directory_groups_page');
?>

<div id="buddypress">

    <?php
    /**
     * Fires before the display of the groups.
     *
     * @since 1.1.0
     */
    do_action('bp_before_directory_groups');
    ?>

    <?php
    /**
     * Fires before the display of the groups content.
     *
     * @since 1.1.0
     */
    do_action('bp_before_directory_groups_content');
    ?>

    <?php /* Backward compatibility for inline search form. Use template part instead. */ ?>
    <?php if (has_filter('bp_directory_groups_search_form')) : ?>
        <div id="group-dir-search" class="dir-search" role="search">
    <?php bp_directory_groups_search_form(); ?>
        </div><!-- #group-dir-search -->

    <?php else : ?>
    <?php bp_get_template_part('common/search/dir-search-form'); ?>

    <?php endif; ?>

    <form action="" method="post" id="groups-directory-form" class="dir-form">
        <div class="wb-grid wb-grid-reverse">
            <div class="bp-content-area groups-content-area">
                <header class="entry-header page-header">
                <?php
                /** the_title() creates conflict with BP Profile Search plugin. **/
                $component = bp_current_component();
                if ('groups' === $component) {
                    if (is_multisite()) {
                        $bp_pages = get_site_option('bp-pages');
                    } else {
                        $bp_pages = get_option('bp-pages');
                    }
                        $page_id = $bp_pages[$component];
                }
                echo '<h1 class="entry-title">' . esc_attr__( get_the_title( $page_id ), 'reign' ) . '</h1>';
                ?>
                </header><!-- .entry-header -->

                <div id="template-notices" role="alert" aria-atomic="true">
        <?php
        /**
         * This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php
         */
        do_action('template_notices');
        ?>
                </div>

                <div id="groups-dir-list" class="groups dir-list">
        <?php bp_get_template_part('groups/groups-loop'); ?>
                </div><!-- #groups-dir-list -->

                <?php
                /**
                 * Fires and displays the group content.
                 *
                 * @since 1.1.0
                 */
                do_action('bp_directory_groups_content');
                ?>

                <?php wp_nonce_field('directory_groups', '_wpnonce-groups-filter'); ?>

                <?php
                /**
                 * Fires after the display of the groups content.
                 *
                 * @since 1.1.0
                 */
                do_action('bp_after_directory_groups_content');
                ?>
            </div>

    <?php echo get_sidebar('buddypress'); ?>
        </div>
    </form><!-- #groups-directory-form -->

    <?php
    /**
     * Fires after the display of the groups.
     *
     * @since 1.1.0
     */
    do_action('bp_after_directory_groups');
    ?>

</div><!-- #buddypress -->

<?php
/**
 * Fires at the bottom of the groups directory template file.
 *
 * @since 1.5.0
 */
do_action('bp_after_directory_groups_page');
