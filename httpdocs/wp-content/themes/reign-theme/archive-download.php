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
<?php do_action( 'wbcom_before_content_section' ); ?>
<div class="content-wrapper">
	<div class="rtm_edd_list">
		<?php
		if ( have_posts() ) : ?>

			
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

<?php do_action( 'wbcom_after_content_section' ); ?>

<?php
get_footer();