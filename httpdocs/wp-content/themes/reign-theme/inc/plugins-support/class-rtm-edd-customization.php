<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'RTM_PMPRO_Customization' ) ) :

/**
 * @class RTM_PMPRO_Customization
 */
class RTM_PMPRO_Customization {
	
	/**
	 * The single instance of the class.
	 *
	 * @var RTM_PMPRO_Customization
	 */
	protected static $_instance = null;
	
	/**
	 * Main RTM_PMPRO_Customization Instance.
	 *
	 * Ensures only one instance of RTM_PMPRO_Customization is loaded or can be loaded.
	 *
	 * @return RTM_PMPRO_Customization - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * RTM_PMPRO_Customization Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {

		add_filter( 'reign_set_sidebar_id', array( $this, 'set_edd_downloads_archive_sidebar' ) );

		add_action( 'edd_product_details_widget_before_purchase_button' , array( $this, 'rtm_show_price_on_product_details_widget' ), 10, 2 );


		add_action( 'edd_product_details_widget_before_categories_and_tags', array( $this, 'render_categories_and_tags' ), 10, 2 );

		// add_filter( 'wbtm_reign_pages_vertical_tabs', array( $this, 'add_vertical_tab' ), 10, 1 );
		// add_action( 'render_theme_options_for_rtm_edd', array( $this, 'render_theme_options_for_rtm_edd' ) );

		add_action( 'customize_register', array( $this, 'add_panels_and_sections' ) );
		add_filter( 'kirki/fields', array( $this, 'add_fields' ) );

		add_action( 'wp_head', array( $this, 'custom_edd_css' ) );


		if ( class_exists( 'EDD_Cross_Sell_And_Upsell' ) ) {
			add_filter( 'the_content', array( $this, 'rtm_edd_csau_single_download_upsells' ), 99, 1 );
			add_action( 'edd_after_checkout_cart', array( $this, 'rtm_edd_csau_display_on_checkout_page' ), 9 );
		}

		
		add_filter( 'edd_downloads_list_wrapper_class', array( $this, 'alter_edd_downloads_list_wrapper_class' ), 10, 2 );


		add_filter( 'reign_customizer_supported_post_types', array( $this, 'add_post_type' ), 10, 1 );
	}

	public function add_post_type( $post_types ) {
		$post_types[] = array(
			'slug' => 'download',
			'name' => __( 'Download', 'reign' ),
		);
		return $post_types;
	}

	public function add_panels_and_sections( $wp_customize ) {
		$wp_customize->add_section(
			'reign_edd_support',
			array(
				'title'       => __( 'Easy Digital Download', 'reign' ),
				'priority'    => 10,
				'panel'       => 'reign_plugin_support_panel',
				'description' => '',
			)
		);
	}

	public function add_fields( $fields ) {

		$fields[] = array(
			'type'        => 'slider',
			'settings'    => 'reign_edd_downloads_per_row',
			'label'       => esc_attr__( 'Downloads Per Row', 'reign' ),
			'description'       => esc_attr__( 'This setting helps you manage number of download items to show per row on download archive page.', 'reign' ),
			'section'     => 'reign_edd_support',
			'default'     => 4,
			'priority'    => 10,
			'choices'     => array(
				'min'  => '1',
				'max'  => '5',
				'step' => '1',
			),
		);

		return $fields;

	}

	public function alter_edd_downloads_list_wrapper_class( $wrapper_classes, $atts ) {
		$wrapper_classes .= ' rtm_edd_list';
		return $wrapper_classes;
	}

	public function custom_edd_css() {
		// global $wbtm_reign_settings;
		// $rtm_edd_per_row = isset( $wbtm_reign_settings[ 'reign_pages' ][ 'rtm_edd_per_row' ] ) ? $wbtm_reign_settings[ 'reign_pages' ][ 'rtm_edd_per_row' ] : '4';
		// $rtm_edd_per_row = intval( $rtm_edd_per_row );
		// $width = floor( ( 100 / $rtm_edd_per_row ) );

		$rtm_edd_per_row = get_theme_mod( 'reign_edd_downloads_per_row', '4' );
		$rtm_edd_per_row = intval( $rtm_edd_per_row );
		$width = floor( ( 100 / $rtm_edd_per_row ) );
		?>
		<style type="text/css">
			.rtm-download-item-article {
				width: <?php echo $width; ?>% !important;
			}
		</style>
		<?php
	}


	public function add_vertical_tab( $vertical_tabs ) {
		$vertical_tabs['rtm_edd'] = __( 'Easy Digital Download', 'reign' );
		return $vertical_tabs;
	}

	public function render_theme_options_for_rtm_edd() {
		global $wbtm_reign_settings;
		?>
		<table class="form-table">
			<tr>
				<th>
					<label>
						<?php esc_html_e( 'Number of downloads per row', 'reign' ); ?>
					</label>
					<div class="rtm-tooltip">?
						<span class="rtm-tooltiptext">
						<?php esc_html_e( 'This setting helps you manage number of download items to show per row on download archive page.', 'reign' ); ?>
						</span>
					</div>
				</th>
				<td>
					<?php
					$rtm_edd_per_row = isset( $wbtm_reign_settings[ 'reign_pages' ][ 'rtm_edd_per_row' ] ) ? $wbtm_reign_settings[ 'reign_pages' ][ 'rtm_edd_per_row' ] : '4';
					echo '<input type="number" min="1" name="reign_pages[rtm_edd_per_row]" value="' . $rtm_edd_per_row . '" />';
					?>
				</td>
			</tr>
		</table>
		<?php
	}

	public function render_categories_and_tags( $instance, $download_id ) {
		$categories      = $instance['categories'] ? $instance['categories'] : '';
		$tags            = $instance['tags'] ? $instance['tags'] : '';

		$category_list  = false;
		$category_label = '';
		if ( $categories ) {
			$category_terms = get_the_terms( $download_id, 'download_category' );
			if ( $category_terms && ! is_wp_error( $category_terms ) ) {
				$category_list     = get_the_term_list( $download_id, 'download_category', '', ', ' );
				$category_count    = count( $category_terms );
				$category_labels   = edd_get_taxonomy_labels( 'download_category' );
				$category_label    = $category_count > 1 ? $category_labels['name'] : $category_labels['singular_name'];
			}
		}
		$tag_list  = false;
		$tag_label = '';
		if ( $tags ) {
			$tag_terms = get_the_terms( $download_id, 'download_tag' );
			if ( $tag_terms && ! is_wp_error( $tag_terms ) ) {
				$tag_list     = get_the_term_list( $download_id, 'download_tag', '', ', ' );
				$tag_count    = count( $tag_terms );
				$tag_taxonomy = edd_get_taxonomy_labels( 'download_tag' );
				$tag_label    = $tag_count > 1 ? $tag_taxonomy['name'] : $tag_taxonomy['singular_name'];
			}
		}

		echo '<div class="rtm-edd-pro-meta">';
		if( $category_list ) {
			?>
			<div class="rtm-edd-cat-list edd_meta">
				<label><?php esc_html_e( 'Category', 'reign' ); ?></label>
				<div>
					<?php echo $category_list; ?>
				</div>
			</div>
			<?php
		}

		if( $tag_list ) {
			?>
			<div class="rtm-edd-tag-list edd_meta">
				<label><?php esc_html_e( 'Tag', 'reign' ); ?></label>
				<div>
					<?php echo $tag_list; ?>
				</div>
			</div>
			<?php
		}
		echo '</div>';
	}

	
	public function rtm_show_price_on_product_details_widget( $instance , $download_id ) {
		$this->rtm_get_edd_download_price_html();
	}

	public function rtm_get_edd_download_price_html() {
		?>
		<?php $item_props = edd_add_schema_microdata() ? ' itemprop="offers" itemscope itemtype="http://schema.org/Offer"' : ''; ?>

		<?php
		/**
		 * Free download.
		 */
		if ( edd_is_free_download( get_the_ID() ) ) :
		?>
		<div<?php echo $item_props; ?>>
			<div itemprop="price">
				<span class="edd_price" id="edd_price_<?php echo get_the_id(); ?>">
					<?php esc_html_e( 'Free', 'reign' ); ?>
				</span>
			</div>
		</div>
		<?php
		/**
		 * Variable priced download
		 */
		elseif ( edd_has_variable_prices( get_the_ID() ) ) :
			$variable_prices = edd_get_variable_prices( get_the_ID() );
			$variable_prices = array_map( function( $price ) {
					return $price['amount'];
				},
				$variable_prices
			);
			$variable_prices = array_unique( $variable_prices );
			sort( $variable_prices );
			if( count( $variable_prices ) > 1 ) {
				$min_price = edd_currency_filter( edd_format_amount( $variable_prices[0] ) );
				$max_price = edd_currency_filter( edd_format_amount( $variable_prices[count($variable_prices)-1] ) );
				?>
				<div<?php echo $item_props; ?>>
					<div itemprop="price">
						<span class="edd_price"> 
							<?php
							/* translators: Variable price start */
							// esc_html_e( 'From', 'themedd' );
							?>
							<?php echo $min_price . ' - ' . $max_price; ?>
						</span>
					</div>
				</div>
				<?php
			}
			else {
				?>
				<div<?php echo $item_props; ?>>
					<div itemprop="price"> 
						<?php edd_price( get_the_ID() ); ?>
					</div>
				</div>
			<?php
			}
		/**
		 * Normal priced download.
		 */
		elseif ( ! edd_has_variable_prices( get_the_ID() ) ) :
			?>
			<div<?php echo $item_props; ?>>
				<div itemprop="price">
					<?php edd_price( get_the_ID() ); ?>
				</div>
			</div>
		<?php endif; ?>
		<?php
	}

	public function set_edd_downloads_archive_sidebar( $sidebar_id ) {
		if ( is_post_type_archive( 'download' ) ) {
			$sidebar_id = 'edd-download-archive-sidebar';
		}
		elseif ( is_singular( 'download' ) ) {
			$sidebar_id = 'edd-single-download-sidebar';
		}
		return $sidebar_id;
	}


	public function rtm_edd_csau_single_download_upsells( $content ) {
	    remove_filter( 'the_content', 'edd_csau_single_download_upsells', 100 );
	    // upsells
	    if( is_singular( 'download' ) && is_main_query() ) {
	        $new_content = $this->rtm_edd_csau_html();
	        return $content . $new_content;
	    }
	    return $content;
	}


	public function rtm_edd_csau_display_on_checkout_page() {
	    remove_action( 'edd_after_checkout_cart', 'edd_csau_display_on_checkout_page' );
	    echo $this->rtm_edd_csau_html();
	}

	/**
	 * Display Cross-sell/Upsell products
	 *
	 * @since 1.0
	*/
	public function rtm_edd_csau_html( $columns = '3' ) {
	    global $post, $edd_options;
	    // upsell products for the single download page
	    if ( is_singular( 'download' ) ) {
	        $products = edd_csau_get_products( get_the_ID(), 'upsell' );
	    }
	    // cross-sell products at checkout
	    elseif ( edd_is_checkout() ) {
	        // get contents on the cart
	        $cart_items = edd_get_cart_contents();
	        // return if there's nothing in the cart
	        if ( ! $cart_items ) {
	            return;
	        }
	        $cart = array();
	        // create new products array with the cart items cross sell products
	        if ( $cart_items ) {
	            foreach ( $cart_items as $cart_item ) {
	                $download_id = $cart_item[ 'id' ];
	                // create $cart array with IDs
	                $cart[] = (int) $cart_item[ 'id' ];
	                // create $product_list array with cross sell products
	                $product_list[] = get_post_meta( $download_id, '_edd_csau_cross_sell_products', false );
	            }
	        }
	        $products = $product_list;
	        // clean the array
	        $products = array_filter( $products );
	        // return if no cross sell products after clean
	        if ( ! $products ) {
	            return;
	        }
	        // merge into single level array
	        $products = call_user_func_array( 'array_merge', $products );
	        // remove duplicate IDs
	        $products = array_unique( $products );
	        foreach( $products as $key => $product ) {
	            if( edd_item_in_cart( $product ) ) {
	                unset( $products[ $key ] );
	            }
	        }
	    } else {
	        return;
	    }
	    if ( $products ) { ?>
	        <?php
	        if ( edd_is_checkout() ) {
	            $posts_per_page = isset( $edd_options[ 'edd_csau_cross_sell_number' ] ) && !empty( $edd_options[ 'edd_csau_cross_sell_number' ] ) ? $edd_options[ 'edd_csau_cross_sell_number' ] : '3';
	        }
	        elseif( is_singular( 'download' ) ) {
	            $posts_per_page = isset( $edd_options[ 'edd_csau_upsell_number' ] ) && !empty( $edd_options[ 'edd_csau_upsell_number' ] ) ? $edd_options[ 'edd_csau_upsell_number' ] : '3';
	        }
	        $query = array(
	            'post_type'         => 'download',
	            'posts_per_page'    => $posts_per_page,
	            'orderby'           => 'date',
	            'order'             => 'DESC',
	            'post__in'          => $products,
	        );
	        $query = apply_filters( 'edd_csau_query', $query );
	        $downloads = new WP_Query( $query );
	        if ( $downloads->have_posts() ) :
	            // upsell heading
	            if ( is_singular( 'download' ) ) {
	                $upsell_heading = get_post_meta( get_the_ID(), '_edd_csau_upsell_heading', true );
	                // show singular heading
	                if( $upsell_heading ) {
	                    $heading = esc_attr( $upsell_heading );
	                }
	                // show default in settings
	                elseif( isset( $edd_options[ 'edd_csau_upsell_heading' ] ) ) {
	                    $heading = esc_attr( $edd_options[ 'edd_csau_upsell_heading' ] );
	                }
	                else {
	                    $heading = __( 'You may also like', 'reign' );
	                }
	            }
	            // cross-sell heading
	            elseif ( edd_is_checkout() ) {
	                $ids = edd_csau_get_cart_trigger_ids();
	                if ( count( $ids ) == 1 && get_post_meta( $ids[0], '_edd_csau_cross_sell_heading', true ) ) {
	                    $heading = esc_attr( get_post_meta( $ids[0], '_edd_csau_cross_sell_heading', true ) );
	                }
	                // show default in settings
	                elseif( isset( $edd_options[ 'edd_csau_cross_sell_heading' ] ) ) {
	                    $heading = esc_attr( $edd_options[ 'edd_csau_cross_sell_heading' ] );
	                }
	                else {
	                    $heading = __( 'You may also like', 'reign' );
	                }
	            } // end is_checkout
	            $i = 1;
	            global $wp_query;
	            //$download_count = $downloads->found_posts > 3 ? 3 : $downloads->found_posts;
	            $classes = array();
	            $classes = apply_filters( 'edd_csau_classes', $classes );
	            // default classes
	            $classes[] = 'edd-csau-products';
	            // columns
	            if( $columns )
	                $classes[] = 'col-' . $columns;
	            // filter array and remove empty values
	            $classes = array_filter( $classes );
	            $classes = !empty( $classes ) ? implode( ' ', $classes ) : '';
	            $class_list = !empty( $classes ) ? 'class="' . $classes  . '"' : '';
	            ob_start();
	            ?>
	            <div <?php echo $class_list; ?>>
	                <?php if ( $heading ) : ?>
	                    <h2><?php echo esc_html( $heading ); ?></h2>
	                <?php endif; ?>
	                <?php echo '<div class="rtm_edd_list">'; ?>
	                <?php while ( $downloads->have_posts() ) : $downloads->the_post(); ?>
	                    <?php //get_template_part( 'template-parts/content', 'download' ); ?>
	                    <?php edd_get_template_part( 'shortcode', 'download' ); ?>
	                <?php endwhile; ?>
	                <?php echo '</div>'; ?>
	                <?php wp_reset_postdata(); ?>
	            </div>
	            <?php
	            $html = ob_get_clean();
	            return apply_filters( 'rtm_edd_csau_html', $html, $downloads, $heading, $columns, $class_list );
	        endif;
	        ?>
	    <?php }
	    ?>
	<?php }
		
}

endif;

/**
 * Main instance of RTM_PMPRO_Customization.
 * @return RTM_PMPRO_Customization
 */
RTM_PMPRO_Customization::instance();