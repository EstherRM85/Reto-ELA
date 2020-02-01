<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Reign
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) { ?>
		<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'reign' ), the_title_attribute( 'echo=0' ) ) ); ?>" class="entry-media rg-post-thumbnail">
			<?php the_post_thumbnail( 'reign-large-thumb' ); ?>
		</a>
	<?php } ?>

	<div class="rg-post-content">
		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<div class="entry-meta"><?php reign_entry_list_footer(); ?></div>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php
			if ( is_singular() ) {
				/* translators: %s: Name of current post */
				the_content( sprintf(
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'reign' ), array( 'span' => array( 'class' => array() ) ) ), the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );
			} else {
				the_excerpt();
			}
			?>

			<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'reign' ),
				'after'	 => '</div>',
			) );
			?>

			<?php if ( !is_singular() ) { ?>
				<p class="no-margin"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'reign' ), the_title_attribute( 'echo=0' ) ) ); ?>" class="read-more button"><?php _e( 'Read More', 'reign' ); ?></a></p>
				<?php } ?>

		</div><!-- .entry-content -->
	</div>

</article><!-- #post-## -->