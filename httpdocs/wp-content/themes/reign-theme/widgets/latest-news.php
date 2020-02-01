<?php
/**
 * Widget API: REIGN_WP_Widget_Text class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 4.4.0
 */

/**
 * Core class used to implement a Text widget.
 *
 * @since 2.8.0
 *
 * @see WP_Widget
 */
class REIGN_News_Widget extends WP_Widget {

	/**
	 * Sets up a new Text widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops	 = array(
			'classname'						 => 'news_widget',
			'description'					 => __( 'Widget to show latest blog posts.', 'reign' ),
			'customize_selective_refresh'	 => true,
		);
		$control_ops = array();
		parent::__construct( 'mytext', __( 'REIGN News Widget', 'reign' ), $widget_ops, $control_ops );
	}

	/**
	 * Outputs the content for the current Text widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Text widget instance.
	 */
	public function widget( $args, $instance ) {

		/**
		 * This filter is documented in wp-includes/widgets/class-wp-widget-pages.php
		 */
		$title			 = apply_filters( 'widget_title', empty( $instance[ 'title' ] ) ? '' : $instance[ 'title' ], $instance, $this->id_base );
		$posts_per_page	 = empty( $instance[ 'posts_per_page' ] ) ? '5' : $instance[ 'posts_per_page' ];

		$widget_text = !empty( $instance[ 'text' ] ) ? $instance[ 'text' ] : '';

		/**
		 * Filters the content of the Text widget.
		 *
		 * @since 2.3.0
		 * @since 4.4.0 Added the `$this` parameter.
		 *
		 * @param string         $widget_text The widget content.
		 * @param array          $instance    Array of settings for the current widget.
		 * @param WP_Widget_Text $this        Current Text widget instance.
		 */
		$text = apply_filters( 'widget_text', $widget_text, $instance, $this );

		echo $args[ 'before_widget' ];
		if ( !empty( $title ) ) {
			echo $args[ 'before_title' ] . esc_html( $title ) . $args[ 'after_title' ];
		}
		?>
		<div class="widget news_widget_inner">
			<?php $loop = new WP_Query( array( 'posts_per_page' => $posts_per_page ) ); ?>
			<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

				<div class="rg-news-wrapper">
					<div class="rg-news-thumb">
						<?php esc_html( the_post_thumbnail( 'reign-featured-large' ) ); ?>
					</div>
					<div class="rg-image-content">
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<span class="entry-date"><?php echo get_the_date(); ?></span>
					</div>
				</div>
				<?php
			endwhile;
			wp_reset_query();
			?>
		</div>
		<?php
		echo $args[ 'after_widget' ];
		?>

		<?php
	}

	/**
	 * Handles updating settings for the current Text widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance			 = $old_instance;
		$instance[ 'title' ] = sanitize_text_field( $new_instance[ 'title' ] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance[ 'text' ] = isset( $new_instance[ 'text' ] ) ? $new_instance[ 'text' ] : '';
		} else {
			$instance[ 'text' ] = isset( $new_instance[ 'text' ] ) ? wp_kses_post( $new_instance[ 'text' ] ) : '';
			// $instance[ 'text' ] = wp_kses_post( $new_instance[ 'text' ] );
		}
		$instance[ 'posts_per_page' ] = isset( $new_instance[ 'posts_per_page' ] ) ? $new_instance[ 'posts_per_page' ] : '5';
		return $instance;
	}

	/**
	 * Outputs the Text widget settings form.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance		 = wp_parse_args( (array) $instance, array( 'title' => 'Latest Posts', 'posts_per_page' => '5' ) );
		$posts_per_page	 = isset( $instance[ 'posts_per_page' ] ) ? $instance[ 'posts_per_page' ] : 5;
		$title			 = sanitize_text_field( $instance[ 'title' ] );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'reign' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'reign' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" type="number" value="<?php echo esc_attr( $posts_per_page ); ?>" />
		</p>
		<?php
	}

}

add_action( 'widgets_init', function() {
	register_widget( 'REIGN_News_Widget' );
} );
