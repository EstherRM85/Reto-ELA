<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Reign
 */
get_header();
?>

<?php do_action( 'wbcom_before_content_section' ); ?>

<div class="content-wrapper">
	<?php if ( have_posts() ) : ?>

		<!-- <header class="page-header">
			<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'reign' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		</header> --><!-- .page-header -->

		<?php
		/* Start the Loop */
		while ( have_posts() ) : the_post();

			/**
			 * Run the loop for the search to output the results.
			 * If you want to overload this in a child theme then include a file
			 * called content-search.php and that will be used instead.
			 */
			get_template_part( 'template-parts/content', 'search' );

		endwhile;

		the_posts_navigation();

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>

</div>

<?php do_action( 'wbcom_after_content_section' ); ?>

<?php
get_footer();
