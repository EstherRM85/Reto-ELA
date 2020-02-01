<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Reign
 */
$sidebar_id = wbcom_get_sidebar_id_to_show( 'secondary_sidebar' );
if( !$sidebar_id ) {
	global $post;
	if( $post ) {
		$post_type = get_post_type();
		if ( is_singular() ) {
			$sidebar_id = get_theme_mod('reign_' . $post_type . '_single_left_sidebar', '' );
		}else{
			$sidebar_id = get_theme_mod('reign_' . $post_type . '_archive_left_sidebar', '' );
		}
	}
}
if ( !$sidebar_id ) {
	$sidebar_id = 'sidebar-left';
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_shop() || is_post_type_archive( 'product' ) || is_product() || is_cart() || is_checkout() || is_product_category() ) {
			$sidebar_id = 'woocommerce-sidebar-left';
		}
	}
}

$sidebar_id = apply_filters( 'reign_set_left_sidebar_id', $sidebar_id );

if ( is_active_sidebar( $sidebar_id ) ) {
	?>
	<aside id="reign-sidebar-left" class="widget-area default" role="complementary">
		<div class="widget-area-inner">
			<?php dynamic_sidebar( $sidebar_id ); ?>
		</div>
	</aside>
	<?php
}
?>