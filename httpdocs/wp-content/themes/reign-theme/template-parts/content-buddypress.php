<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Reign
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( bp_is_group_create() ) { ?>
		<header class="entry-header page-header">
			<h1 class="entry-title"><?php esc_html_e( 'Create Group', 'buddypress' ); ?></h1>
		</header><!-- .entry-header -->
	<?php } ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'reign' ),
			'after'	 => '</div>',
		) );
		?>
	</div><!-- .entry-content -->

	<?php if ( FALSE && get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'reign' ), the_title( '<span class="screen-reader-text">"', '"</span>', false )
			), '<span class="edit-link">', '</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-## -->