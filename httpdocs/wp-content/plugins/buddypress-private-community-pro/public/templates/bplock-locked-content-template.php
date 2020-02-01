<?php
if ( !defined( 'ABSPATH' ) )
	exit; //Exit if accessed directly
global $post;
get_header();
$pg_title = __( 'Logged in members only!', 'buddypress-private-community-pro' );
?>
<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="container">
				<article id="post-<?php echo $post->ID; ?>" class="post-<?php echo $post->ID; ?> page type-page status-publish hentry">
					<header class="entry-header blpro-locked-title-header">
						<h2 class="entry-title blpro-locked-title"><?php echo apply_filters( 'bplock_locked_template_pg_title', $pg_title ); ?></h2>
					</header><!-- .entry-header -->
					<div class="entry-content">
						<?php
						do_action( 'bplock_before_login_form' );
						echo do_shortcode( '[blpro_login_form]' );
						do_action( 'bplock_after_login_form' );
						?>
					</div><!-- .entry-content -->
				</article><!-- #post-## -->
			</div>
		</main><!-- .site-main -->
	</div>
</div>
<?php get_footer(); ?>
