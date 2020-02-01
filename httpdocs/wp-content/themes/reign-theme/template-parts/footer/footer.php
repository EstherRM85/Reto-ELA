<footer itemscope="itemscope" itemtype="http://schema.org/WPFooter">
	<?php
	if ( is_active_sidebar( 'footer-widget-area' ) ) {
		?>
		<div class="footer-wrap">
			<div class="container">
				<aside id="footer-area" class="widget-area footer-widget-area" role="complementary">
					<div class="widget-area-inner">
						<div class="wb-grid">
							<?php dynamic_sidebar( 'footer-widget-area' ); ?>
						</div>
					</div>
				</aside>
			</div>
		</div>
		<?php
	}
	?>
	<?php
	$reign_footer_bottom = get_theme_mod( 'reign_footer_bottom', true );
	if ( $reign_footer_bottom ) {
		$reign_footer_copyright_text = get_theme_mod( 'reign_footer_copyright_text', '&copy; '. date( 'Y' ) .' - Reign | Theme by <a href="' . esc_url( 'https://wbcomdesigns.com/' ) . '" target="_blank">Wbcom Designs</a>' );
		?>
		<div id="reign-copyright-text">
			<div class="container">
				<?php echo $reign_footer_copyright_text; ?>
			</div>	
		</div>
		<?php
	}
	?>
</footer>