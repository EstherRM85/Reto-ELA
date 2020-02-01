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

	<?php do_action( 'rtm_post_begins' ); ?>
		<?php if ( ! is_front_page() ) { 
				$post_type = get_post_type();

				$wbcom_metabox_data = get_post_meta( get_the_ID(), 'reign_wbcom_metabox_data', true );

				$page_option	= isset( $wbcom_metabox_data['layout']['display_page_title'] ) ? $wbcom_metabox_data['layout']['display_page_title'] : '';

				$hide_title = get_theme_mod('reign_' . $post_type . '_single_pagetitle_enable', true);

				$hide = true;

				if( $page_option == 'on' ){
					$hide = false;
				}elseif( $hide_title ){
					$hide = true;
				}
				if( !$hide){ ?>
					<header class="entry-header">
			            <?php the_title(sprintf('<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h1>'); ?>
			        </header><!-- .entry-header -->
			    <?php
				}
			  } ?>    

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