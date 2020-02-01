<?php
$reign_header_default_icons_set = reign_header_default_icons_set();
$reign_header_icons_set = get_theme_mod( 'reign_header_icons_set', $reign_header_default_icons_set );
?>
<div class="reign-fallback-header header-mobile">
	<div class="container">
		<div class="wb-grid">
			<div class="header-left no-gutter wb-grid-flex wb-grid-center wb-grid-space-between">

				<?php
				if( in_array( 'user-menu', $reign_header_icons_set ) ) {
					get_template_part( 'template-parts/header-icons/user-menu', '' );
				}
				?>
				<?php //get_template_part( 'template-parts/header-icons/user-menu', '' ); ?>

				<div class="mobile-view-search-wrap header-right no-gutter wb-grid-flex wb-grid-center">

					<?php
					if( in_array( 'search', $reign_header_icons_set ) ) {
						get_template_part( 'template-parts/header-icons/search', '' );
					}
					?>

					<?php //get_template_part( 'template-parts/header-icons/search', '' ); ?>
				</div>

				<?php
				if( in_array( 'cart', $reign_header_icons_set ) ) {
					get_template_part( 'template-parts/header-icons/cart', '' );
				}
				?>
				
				<div class="site-branding">
					<div class="logo">
						<?php
						if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
							$mobile_menu_logo_enable = get_theme_mod( 'reign_header_mobile_menu_logo_enable', false );
							if( $mobile_menu_logo_enable ) {
								$reign_header_mobile_menu_logo = get_theme_mod( 'reign_header_mobile_menu_logo', '' );
								if( !empty( $reign_header_mobile_menu_logo ) ) {
									echo '<img class="reign-mobile-menu" src="'.$reign_header_mobile_menu_logo.'" />';
								}
								else {
									the_custom_logo();
								}
							}
							else {
								the_custom_logo();
							}
						} else {
							if ( is_front_page() && is_home() ) :
								?>
								<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
							<?php else : ?>
								<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
							<?php
							endif;
						}
						?>
					</div>
				</div>

				<?php
				// if( in_array( 'cart', $reign_header_icons_set ) ) {
				// 	get_template_part( 'template-parts/header-icons/cart', '' );
				// }
				?>

				<?php
				if( in_array( 'message', $reign_header_icons_set ) ) {
					get_template_part( 'template-parts/header-icons/message', '' );
				}
				?>

				<?php
				if( in_array( 'notification', $reign_header_icons_set ) ) {
					get_template_part( 'template-parts/header-icons/notification', '' );
				}
				?>

				<?php //get_template_part( 'template-parts/header-icons/cart', '' ); ?>

				<?php //get_template_part( 'template-parts/header-icons/notification', '' ); ?>


				<nav id="site-navigation" class="main-navigation" role="navigation">
					<span class="menu-toggle wbcom-nav-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<span></span>
						<span></span>
						<span></span>
					</span>
					<?php wp_nav_menu( array( 'theme_location' => 'menu-1', 'menu_id' => 'primary-menu', 'fallback_cb' => '', 'container' => false, 'menu_class' => 'primary-menu rg-responsive-menu', ) ); ?>
				</nav>

			</div>
			
		</div>
	</div>
</div>