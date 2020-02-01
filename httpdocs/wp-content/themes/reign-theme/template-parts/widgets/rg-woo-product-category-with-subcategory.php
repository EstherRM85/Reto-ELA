<?php
$cat_args = apply_filters( 'widget_rg_woo_product_category_with_subcategory_args', array(
	'orderby'	 => 'name',
	'hide_empty' => true,
	'parent' => 0,
) );

if( !empty( $atts['count'] ) ) {
	$cat_args[ 'number' ] = $atts['count'];
}

$selected_categories = $atts['selected_categories'];
if( !empty( $selected_categories ) ) {
	$selected_categories = trim( $selected_categories );
	$selected_categories = explode( ',', $selected_categories );
	if( is_array( $selected_categories ) ) {
		$cat_args[ 'include' ] = $selected_categories;
	}
}

$categories = get_terms( 'product_cat', $cat_args );

$ul_wrapper_class	 = ( $atts[ 'enable_slider' ] ) ? 'rg-woo-category-slider-wrap' : '';
$li_wb_grid_classes	 = 'wb-grid-cell sm-wb-grid-1-2 md-wb-grid-1-' . $atts[ 'per_row' ];

$data_slick = '{"slidesToShow": ' . $atts['per_row'] . ', "slidesToScroll": 1}';

?>
<ul class="wb-grid woocommerce rg-woo-category-wrap rg-woo-category-subcategory-wrap <?php echo esc_attr( $ul_wrapper_class ); ?>" data-slick='{"slidesToShow": <?php echo esc_attr( $atts['per_row'] ); ?>, "slidesToScroll": 1}'>
<?php
foreach ( $categories as $category ) {
	
	if( 'uncategorized' === $category->slug ) { continue; }
	
	$subcat_args = array(
		'orderby'	 => 'name',
		'hide_empty' => true,
		'parent' => $category->term_id,
	);
	if( !empty( $atts['subcat_count'] ) ) {
		$subcat_args[ 'number' ] = $atts['subcat_count'];
	}
	$subcats = get_terms( 'product_cat', $subcat_args );
	if( empty( $subcats ) ) { continue; }

	$thumbnail_id	 = get_term_meta( $category->term_id, 'thumbnail_id', true );
	$cat_image		 = wp_get_attachment_url( $thumbnail_id );
	if ( !$cat_image ) {
		$cat_image = wc_placeholder_img_src();
	}
	$style = "background-image: url( '" . $cat_image . "');";

	ob_start();
	?>
	<div class="rg-woo-category-name">
		<a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
			<h3 class="category-name"><?php echo esc_html( $category->name ); ?></h3>
		</a>
	</div>
	<?php
	$rg_woo_category_name = ob_get_clean();

	echo '<li class="rg-woo-category-item-wrap ' . esc_attr( $atts[ 'layout' ] ) . ' ' . esc_attr( $li_wb_grid_classes ) . '">';
		echo '<a class="rg-woo-category-data" style="' . esc_attr( $style ) . '" href="' . esc_url( get_term_link( $category ) ) . '">';
		echo '</a>';
		echo '<div class="rg-woo-sub-category-data">';
			echo $rg_woo_category_name;
			echo '<ul class="rg-woo-sub-category-data">';
			foreach ($subcats as $key => $subcat) {
				echo '<li><a href="' . esc_url( get_term_link( $subcat ) ) . '">';
				echo esc_html( $subcat->name );
				echo '</a></li>';
			}
			echo '</ul>';
		echo '</div>';
	echo '</li>';

}
echo '</ul>';