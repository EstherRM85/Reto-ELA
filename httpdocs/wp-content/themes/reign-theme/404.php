<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Reign
 */
get_header();
?>

	
<div <?php body_class( 'content-wrapper' ); ?>>
	<section class="error-404 not-found">
		<header class="page-header">
			
			<h2>404</h2>

				<h3><?php esc_html_e( 'page not found', 'reign' ); ?></h3>
				<p>The page you were looking  for doesn't exist.</p>
				<span><a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>">Go Back</a></span>
		


		</header><!-- .page-header -->
	</section><!-- .error-404 -->
</div>



<?php
get_footer();
