<div class="reign-fallback-header">
	<div class="container">
		<div class="wb-grid">
			<div class="header-left no-gutter wb-grid-flex wb-grid-center wb-grid-space-between">

				<div class="mobile-view-search-wrap header-right no-gutter wb-grid-flex wb-grid-center">
					<div class="search-wrap">
						<span class="rg-search-icon icon-search-interface-symbol"></span>
						<div class="rg-search-form-wrap">
							<?php get_search_form(); ?>
						</div>
					</div>
				</div>
				<!-- <div class="mobile-view-search">
				<?php //get_search_form(); ?>
				</div> -->
				<div class="site-branding">
					<div class="logo">
						<?php
						if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
							the_custom_logo();
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

				<div class="mobile-view-cart">
					<?php my_wc_cart_count(); ?>
				</div>

				<div class="mobile-view-notification">
					<?php get_template_part( 'template-parts/user-notifications' ); ?>
				</div>

				<nav id="site-navigation" class="main-navigation" role="navigation">
					<span class="menu-toggle wbcom-nav-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
						<span></span>
						<span></span>
						<span></span>
					</span>
					<?php wp_nav_menu( array( 'theme_location' => 'menu-1', 'menu_id' => 'primary-menu', 'fallback_cb' => '', 'container' => false, 'menu_class' => 'primary-menu', ) ); ?>
				</nav>
			</div>

			<div class="header-right no-gutter wb-grid-flex wb-grid-center">
				<div class="search-wrap">
					<span class="rg-search-icon icon-search-interface-symbol"></span>
					<div class="rg-search-form-wrap">
						<?php get_search_form(); ?>
					</div>
				</div>

				<?php my_wc_cart_count(); ?>

				<?php
				if ( is_user_logged_in() ) {

					get_template_part( 'template-parts/user-messages' );
					get_template_part( 'template-parts/user-notifications' );

					$current_user = wp_get_current_user();

					if ( ($current_user instanceof WP_User ) ) {
						$user_link = function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( get_current_user_id() ) : '#';
						echo '<div class="user-link-wrap">';
						echo '<a class="user-link" href="' . $user_link . '">';
						?>
						<span class="rg-user"><?php echo $current_user->display_name; ?></span>
						<?php
						echo get_avatar( $current_user->user_email, 200 );
						echo '</a>';
						wp_nav_menu( array( 'theme_location' => 'menu-2', 'menu_id' => 'user-profile-menu', 'fallback_cb' => '', 'container' => false, 'menu_class' => 'user-profile-menu', ) );
						echo '</div>';
					}
				} else {
					global $wbtm_reign_settings;
					$login_page_url = wp_login_url();
					if ( isset( $settings[ 'reign_pages' ][ 'reign_login_page' ] ) && ( $wbtm_reign_settings[ 'reign_pages' ][ 'reign_login_page' ] != '-1' ) ) {
						$login_page_id	 = $wbtm_reign_settings[ 'reign_pages' ][ 'reign_login_page' ];
						$login_page_url	 = get_permalink( $login_page_id );
					}
					$registration_page_url = wp_registration_url();
					if ( isset( $wbtm_reign_settings[ 'reign_pages' ][ 'reign_register_page' ] ) && ( $wbtm_reign_settings[ 'reign_pages' ][ 'reign_register_page' ] != '-1' ) ) {
						$registration_page_id	 = $wbtm_reign_settings[ 'reign_pages' ][ 'reign_register_page' ];
						$registration_page_url	 = get_permalink( $registration_page_id );
					}
					?>
					<div class="rg-icon-wrap">
						<a href="<?php echo $login_page_url; ?>" class="btn-login" title="Login"><span class="fa fa-sign-in"</span></a>
					</div><?php
					if ( get_option( 'users_can_register' ) ) {
						?>
						<span class="sep">|</span>
						<div class="rg-icon-wrap">
							<a href="<?php echo $registration_page_url; ?>" class="btn-register" title="Register"><span class="fa fa-address-book-o"</span></a>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>
	</div>
</div>