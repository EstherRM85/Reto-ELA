<?php
/**
 * Widget API: Reign_Woo_Widget_Product_Categories class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */
/**
 * Core class used to implement a Categories widget.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class Reign_Woo_Widget_Product_Categories extends WP_Widget {
	/**
	 * Sets up a new Categories widget instance.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
        $widget_ops = array(
            'classname' => 'widget_reign_woo_product_categories',
            'description' => esc_html__( 'WooCommerce product categories list.', 'reign' ),
            'customize_selective_refresh' => true,
        );
        parent::__construct( 'reign_woo_product_categories', esc_html__( 'Reign - Product Categories', 'reign' ), $widget_ops );
    }
	
	/**
     * Outputs the HTML for this widget.
     *
     * @param array  $args
     * @param array  $instance [An array of settings for this widget instance]
     *
     * @return void Echoes it's output
     */
    function widget( $args, $instance ) {

        extract( $args, EXTR_SKIP );

        $instance = wp_parse_args( (array) $instance, array(
            'title'                       => esc_html__( 'Product Categories', 'reign' ),
            'per_row'                     => 3,
            'count'                       => 6,
            'show_parent_categories_only' => false,
            'show_count'                  => true,
            'enable_slider'               => false,
            'layout'                      => 'layout-type-1',
            'selected_categories'         => '',
        ) );

        $title = apply_filters( 'widget_title', $instance['title'] );
        $limit = absint( $instance['count'] ) ? absint( $instance['count'] ) : 10;

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $atts = $instance;
        include( REIGN_THEME_DIR . '/template-parts/widgets/rg-woo-product-category.php' );
        
        echo $after_widget;
    }

	/**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  $new_instance [An array of new settings as submitted by the admin]
     * @param array  $old_instance [An array of the previous settings]
     *
     * @return array
     */
    function update( $new_instance, $old_instance ) {
        // update logic goes here
        $new_instance['show_parent_categories_only'] = !empty($new_instance['show_parent_categories_only']) ? 1 : 0;
        $new_instance['show_count']                  = !empty($new_instance['show_count']) ? 1 : 0;
        $new_instance['enable_slider']               = !empty($new_instance['enable_slider']) ? 1 : 0;
        $updated_instance                            = $new_instance;
        return $updated_instance;
    }


	/**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  $instance [An array of the current settings for this widget]
     *
     * @return void Echoes it's output
     */
    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array(
            'title'                       => esc_html__( 'Product Categories', 'reign' ),
            'per_row'                     => 3,
            'count'                       => 6,
            'show_parent_categories_only' => false,
            'show_count'                  => true,
            'enable_slider'               => false,
            'layout'                      => 'layout-type-1',
            'selected_categories'         => '',
        ) );

        $title                       = $instance['title'];
        $count                       = $instance['count'];
        $per_row                     = $instance['per_row'];
        $show_parent_categories_only = $instance['show_parent_categories_only'];
        $show_count                  = $instance['show_count'];
        $enable_slider               = $instance['enable_slider'];
        $layout                      = $instance['layout'];
        $selected_categories         = $instance['selected_categories'];        
        $categories                  = get_terms( 'product_cat', array( 'orderby' => 'name' ) );
        ?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'reign' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'per_row' ) ); ?>"><?php esc_html_e( 'No of category per row:', 'reign' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'per_row' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'per_row' ) ); ?>" type="number" value="<?php echo esc_attr( $per_row ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'No of Category:', 'reign' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" value="<?php echo esc_attr( $count ); ?>" />
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_parent_categories_only') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_parent_categories_only') ); ?>"<?php checked( $show_parent_categories_only ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id('show_parent_categories_only') ); ?>"><?php esc_html_e( 'Show parent categories only', 'reign' ); ?></label><br />
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_count') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_count') ); ?>"<?php checked( $show_count ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id('show_count') ); ?>"><?php esc_html_e( 'Show product counts', 'reign' ); ?></label><br />
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('enable_slider') ); ?>" name="<?php echo esc_attr( $this->get_field_name('enable_slider') ); ?>"<?php checked( $enable_slider ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id('enable_slider') ); ?>"><?php esc_html_e( 'Enable slider', 'reign' ); ?></label><br />
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_html_e( 'Layout:', 'reign' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>">
                <option value="layout-type-1" <?php selected( $layout, 'layout-type-1' ) ; ?>><?php esc_html_e( 'Layout - 1', 'reign' ); ?></option>
                <option value="layout-type-2" <?php selected( $layout, 'layout-type-2' ) ; ?>><?php esc_html_e( 'Layout - 2', 'reign' ); ?></option>
                <option value="layout-type-3" <?php selected( $layout, 'layout-type-3' ) ; ?>><?php esc_html_e( 'Layout - 3', 'reign' ); ?></option>
                <option value="layout-type-4" <?php selected( $layout, 'layout-type-4' ) ; ?>><?php esc_html_e( 'Layout - 4', 'reign' ); ?></option>
                <option value="layout-type-5" <?php selected( $layout, 'layout-type-5' ) ; ?>><?php esc_html_e( 'Layout - 5', 'reign' ); ?></option>
                <option value="layout-type-6" <?php selected( $layout, 'layout-type-6' ) ; ?>><?php esc_html_e( 'Layout - 6', 'reign' ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'selected_categories' ) ); ?>"><?php esc_html_e( 'Selected Categories: ( "," separated ids )', 'reign' ); ?></label>

            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'selected_categories' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'selected_categories' ) ); ?>" type="text" value="<?php echo esc_attr( $selected_categories ); ?>" />
            
            <?php if( FALSE ) : ?>
                <select class="widefat reign-widget-select2" id="<?php echo esc_attr( $this->get_field_id( 'selected_categories' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'selected_categories' ) ); ?>[]" multiple>
                    <?php
                    foreach ( $categories as $key => $category ) {
                        $selected = in_array( $category->term_id, $selected_categories ) ? 'selected' : '';
                        ?>
                        <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $category->name ); ?></option>
                        <?php
                    }
                    ?>
                </select>
            <?php endif; ?>
        </p>
        <?php
    }

}
add_action( 'widgets_init', function() {
    register_widget( 'Reign_Woo_Widget_Product_Categories' );
} );