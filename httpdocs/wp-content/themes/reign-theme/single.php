<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Reign
 */
get_header();
?>

<?php do_action( 'wbcom_before_content_section' ); ?>

<div class="content-wrapper">
	<?php
	while ( have_posts() ) : the_post();
		get_template_part( 'template-parts/content', get_post_format() );

		$args = array(
			'prev_text'          => '<span class="rg-next-prev">'.__( 'Previous', 'reign' ).'	</span><span class="nav-title">%title</span>',
			'next_text'          => '<span class="rg-next-prev">'.__( 'Next', 'reign' ).'</span><span class="nav-title">%title</span>',
		);
		the_post_navigation( $args );

		do_action( 'reign_single_post_comment_section' );

	endwhile; // End of the loop.
	?>
</div>

<?php do_action( 'wbcom_after_content_section' ); ?>

<?php
get_footer();
