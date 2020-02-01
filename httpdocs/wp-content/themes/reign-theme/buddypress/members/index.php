<?php
/**
 * BuddyPress - Members
 *
 * @package    BuddyPress
 * @subpackage bp-legacy
 */

/**
 * Fires at the top of the members directory template file.
 *
 * @since 1.5.0
 */
do_action('bp_before_directory_members_page');
?>

<div id="buddypress">

    <?php
    /**
     * Fires before the display of the members.
     *
     * @since 1.1.0
     */
    do_action('bp_before_directory_members');
    ?>

    <?php
    /**
     * Fires before the display of the members content.
     *
     * @since 1.1.0
     */
    do_action('bp_before_directory_members_content');
    ?>

    <?php /* Backward compatibility for inline search form. Use template part instead. */ ?>
    <?php if (has_filter('bp_directory_members_search_form')) : ?>
    <div id="members-dir-search" class="dir-search" role="search">
            <?php bp_directory_members_search_form(); ?>
    </div><!-- #members-dir-search -->

    <?php else : ?>
    <?php bp_get_template_part('common/search/dir-search-form'); ?>

    <?php endif; ?>

    <?php
    /**
     * Fires before the display of the members list tabs.
     *
     * @since 1.8.0
     */
    do_action('bp_before_directory_members_tabs');
    ?>

<form action="" method="post" id="members-directory-form" class="dir-form">

    <div class="wb-grid wb-grid-reverse">
    <div class="bp-content-area members-content-area">
        <header class="entry-header page-header">
        <?php
        /** the_title() creates conflict with BP Profile Search plugin. **/
        $component = bp_current_component();
        if ('members' === $component) {
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

        <div id="members-dir-list" class="members dir-list">
            <?php bp_get_template_part('members/members-loop'); ?>
        </div><!-- #members-dir-list -->

    <?php
    /**
     * Fires and displays the members content.
     *
     * @since 1.1.0
     */
    do_action('bp_directory_members_content');
    ?>

    <?php wp_nonce_field('directory_members', '_wpnonce-member-filter'); ?>

    <?php
    /**
     * Fires after the display of the members content.
     *
     * @since 1.1.0
     */
    do_action('bp_after_directory_members_content');
    ?>
    </div>

    <?php echo get_sidebar('buddypress'); ?>
    </div>
</form><!-- #members-directory-form -->

    <?php
    /**
     * Fires after the display of the members.
     *
     * @since 1.1.0
     */
    do_action('bp_after_directory_members');
    ?>

</div><!-- #buddypress -->

<?php
/**
 * Fires at the bottom of the members directory template file.
 *
 * @since 1.5.0
 */
do_action('bp_after_directory_members_page');
