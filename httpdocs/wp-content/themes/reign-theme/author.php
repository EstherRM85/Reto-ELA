<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Reign
 */
get_header();
?>

<?php do_action( 'wbcom_before_content_section' ); ?>

<div class="content-wrapper">
	<?php
	if ( have_posts() ) :

		if ( is_home() && !is_front_page() ) :
			?>
			<!-- <header class="entry-header page-header">
				<h1 class="entry-title"><?php //single_post_title();  ?></h1>
			</header> --><!-- .entry-header -->

			<?php
		endif;

		// global $wbtm_reign_settings;
		// $blog_list_layout = isset( $wbtm_reign_settings[ 'reign_pages' ][ 'blog_list_layout' ] ) ? $wbtm_reign_settings[ 'reign_pages' ][ 'blog_list_layout' ] : 'default-view';
		$blog_list_layout = get_theme_mod( 'reign_blog_list_layout', 'default-view' );
		if ( $blog_list_layout == 'masonry-view' ) {
			echo '<div class="masonry">';
		}
		if ( $blog_list_layout == 'wb-grid-view' ) {
			echo '<div class="wb-grid-view-wrap">';
		}
		/* Start the Loop */
		while ( have_posts() ) : the_post();

			/*
			 * Include the Post-Format-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
			 */
			get_template_part( 'template-parts/content', get_post_format() );

		endwhile;

		if ( $blog_list_layout == 'masonry-view' ) {
			echo '</div>';
		}
		if ( $blog_list_layout == 'wb-grid-view' ) {
			echo '</div>';
		}

		the_posts_navigation();

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>

</div>

<?php do_action( 'wbcom_after_content_section' ); ?>

<?php
get_footer();
