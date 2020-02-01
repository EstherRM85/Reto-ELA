<?php
add_action( 'body_class', function( $classes ) {
	$classes[] = 'post-type-archive-download';
	return $classes;
} );
?>
<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Reign
 */
get_header();
?>

<div class="content-wrapper">
	<?php if ( have_posts() ) : ?>

		<!-- <header class="page-header">
			<?php
			// the_archive_title( '<h1 class="page-title">', '</h1>' );
			// the_archive_description( '<div class="archive-description">', '</div>' );
			?>
		</header> --><!-- .page-header -->
		<div class="rtm_edd_list">
		<?php
				/* Start the Loop */
		while ( have_posts() ) : the_post();

			/*
			 * Include the Post-Format-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
			 */
			get_template_part( 'template-parts/content', 'download' );

				endwhile;

				the_posts_navigation();

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>
		</div>
</div>

<?php echo get_sidebar(); ?>

<?php
get_footer();