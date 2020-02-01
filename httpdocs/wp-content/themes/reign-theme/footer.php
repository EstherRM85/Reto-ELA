<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Reign
 */
?>
<?php do_action( 'wbcom_content_bottom' ); ?>
</div><!-- #content -->
<?php do_action( 'wbcom_after_content' ); ?>
<?php do_action( 'wbcom_before_footer' ) ?>
<?php do_action( 'wbcom_footer' ) ?>
<?php do_action( 'wbcom_after_footer' ) ?>
</div><!-- #page -->
<?php do_action( 'wbcom_after_page' ) ?>
<?php wp_footer(); ?>
<?php
$sticky_menu_enable = get_theme_mod( 'reign_header_sticky_menu_enable', true );
if( $sticky_menu_enable )  {
	?>
	<script type="text/javascript">
		window.onscroll = function() {
			reign_sticky_header();
		};
		
		var header = jQuery("#masthead");
		var sticky = header.outerHeight() + 100;
		// var sticky = header.outerHeight() + 144;
		// var sticky = header.outerHeight();
		
		function reign_sticky_header() {
			var scrollTop = jQuery(window).scrollTop();
			if( scrollTop > sticky ) {
				// setTimeout( function() {
				// 	header.addClass("sticky");
				// }, 500 );
				header.addClass("sticky");
				jQuery( 'body').addClass("rg-sticky-header");
			}
			else {
				// if (jQuery('footer').isInViewport()) {
				//     return;
			 //    }
			 //    setTimeout( function() {
				// 	header.removeClass("sticky");
				// }, 500 );
				header.removeClass("sticky");
				jQuery( 'body').removeClass("rg-sticky-header");
			}
		}

		jQuery.fn.isInViewport = function() {
		    var elementTop = jQuery(this).offset().top;
		    var elementBottom = elementTop + jQuery(this).outerHeight();
		    var viewportTop = jQuery(window).scrollTop();
		    var viewportBottom = viewportTop + jQuery(window).height();
		    return elementBottom > viewportTop && elementTop < viewportBottom;
		};

	</script>
	<?php
}
?>
</body>
</html>