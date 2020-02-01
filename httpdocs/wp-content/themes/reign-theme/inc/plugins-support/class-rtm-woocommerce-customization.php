<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'RTM_WooCommerce_Customization' ) ) :

/**
 * @class RTM_WooCommerce_Customization
 */
class RTM_WooCommerce_Customization {
	
	/**
	 * The single instance of the class.
	 *
	 * @var RTM_WooCommerce_Customization
	 */
	protected static $_instance = null;
	
	/**
	 * Main RTM_WooCommerce_Customization Instance.
	 *
	 * Ensures only one instance of RTM_WooCommerce_Customization is loaded or can be loaded.
	 *
	 * @return RTM_WooCommerce_Customization - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * RTM_WooCommerce_Customization Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {

		/**
		* Removing woocommerce breadcrumb.
		*/
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

		/**
		* Removes the "shop" title on the main shop page.
		*/
		add_filter( 'woocommerce_show_page_title', array( $this, 'remove_shop_page_title' ), 10 );

		/*
		 * Reign WooCommerce Shortcode to render product categories.
		 */
		add_shortcode( 'rg_woo_product_categories', array( $this, 'render_rg_woo_product_categories' ) );

		/*
		 * Reign WooCommerce Shortcode to render product categories & subcategory together.
		 */
		add_shortcode( 'rg_woo_product_category_with_subcategory', array( $this, 'render_rg_woo_product_category_with_subcategory' ) );

		/**
		* Modify page title for product-category and product-tag.
		*/
		add_filter( 'reign_page_header_section_title', array( $this, 'manage_page_header_section_title' ), 10, 1 );

	}

	public function manage_page_header_section_title( $title ) {
		if ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) {
			$term = get_term_by( 'slug', get_query_var('term'), get_query_var('taxonomy') );
			if( isset( $term->name ) ) {
				$title = $term->name;
			}
		}
		return $title;
	}

	public function render_rg_woo_product_categories( $atts = array() ) {
			
		$atts = wp_parse_args( (array) $atts, array(
            'title' => __( 'Product Categories', 'reign' ),
            'per_row' => 3,
            'count' => 6,
            'show_parent_categories_only'    => false,
            'show_count'    => true,
            'enable_slider' => false,
            'layout'   => 'layout-type-1',
            'selected_categories'   => array(),
        ) );
        
        ob_start();

       include( REIGN_THEME_DIR . '/template-parts/widgets/rg-woo-product-category.php' );

        $content = ob_get_clean();
		return $content;

	}

	public function render_rg_woo_product_category_with_subcategory( $atts = array() ) {

		$atts = wp_parse_args( (array) $atts, array(
            'title' => __( 'Product Categories', 'reign' ),
            'per_row' => 3,
            'count' => 6,
            'subcat_count' => 4,
            'enable_slider' => false,
            'layout'   => 'layout-type-1',
            'selected_categories'   => array(),
        ) );
        
        ob_start();

        include( REIGN_THEME_DIR . '/template-parts/widgets/rg-woo-product-category-with-subcategory.php' );

        $content = ob_get_clean();
		return $content;

	}

	public function remove_shop_page_title( $show_title ) {
		$show_title = false;
	    return $show_title;
	}

}

endif;

/**
 * Main instance of RTM_WooCommerce_Customization.
 * @return RTM_WooCommerce_Customization
 */
RTM_WooCommerce_Customization::instance();