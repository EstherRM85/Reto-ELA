<?php
/**
 * Reign Nav Menu Widget
 *
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Creates our custom Elementor widget
 *
 * Class Widget_Reign_WP_Menu
 *
 * @package Elementor
 */
class Widget_Reign_WP_Menu extends Widget_Base {

	/**
	 * Get Widgets name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'reign_wp_menu';
	}

	/**
	 * Get widgets title
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'WordPress Menu', 'reign' );
	}

	/**
	 * Get the current icon for display on frontend.
	 *
	 * @return string
	 */
	public function get_icon() {
		//return 'reign-elementor-widget';
		return 'eicon-bullet-list';
	}

	/**
	 * Get available categories for this widget. Which is our own category for page builder options.
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'reign-elementor' ];
	}

	/**
	 * We always show this item in the panel.
	 *
	 * @return bool
	 */
	public function show_in_panel() {
		return true;
	}

	/**
	 * This registers our controls for the widget. Currently there are none but we may add options down the track.
	 */
	protected function _register_controls() {

		$this->start_controls_section(
		'section_reign_wp_menu', [
			'label' => __( 'WordPress Menu', 'reign' ),
		]
		);

		$this->add_control(
		'desc', [
			'label'	 => sprintf( __( 'Choose the WordPress menu to output below. To change menu items please go to the <a href="%s" target="_blank">WordPress Menu Editor</a> page.', 'reign' ), admin_url( 'nav-menus.php' ) ),
			'type'	 => Controls_Manager::RAW_HTML,
		]
		);

		$menu_select = array(
			'' => esc_html__( ' - choose - ', 'reign' ),
		);

		// we also show a list of users menues.
		$menus = wp_get_nav_menus();
		foreach ( $menus as $menu ) {
			$menu_select[ $menu->term_id ] = $menu->name;
		}


		$this->add_control(
		'menu_location', [
			'label'		 => esc_html__( 'Choose Menu', 'reign' ),
			'type'		 => Controls_Manager::SELECT,
			'default'	 => '',
			'options'	 => $menu_select,
		]
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_reign_menu_style', [
			'label'	 => __( 'Menu Style', 'reign' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		]
		);

		$this->add_responsive_control(
		'menu_align', [
			'label'			 => __( 'Alignment', 'reign' ),
			'type'			 => Controls_Manager::CHOOSE,
			'options'		 => [
				'left'	 => [
					'title'	 => __( 'Left', 'reign' ),
					'icon'	 => 'fa fa-align-left',
				],
				'center' => [
					'title'	 => __( 'Center', 'reign' ),
					'icon'	 => 'fa fa-align-center',
				],
				'right'	 => [
					'title'	 => __( 'Right', 'reign' ),
					'icon'	 => 'fa fa-align-right',
				],
			],
			'prefix_class'	 => 'elementor-align-',
			'selectors'		 => [
				'{{WRAPPER}} .main-navigation' => 'text-align: {{VALUE}};',
			],
		]
		);

		$this->add_control(
		'menu_background', [
			'label'		 => __( 'Background', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#f8f8f8',
			'selectors'	 => [
				'{{WRAPPER}} .reign-main-navigation, {{WRAPPER}} .reign-main-navigation .reign-inside-navigation ul ul' => 'background-color: {{VALUE}};',
			],
		]
		);
		$this->add_control(
		'menu_background_hover', [
			'label'		 => __( 'Background (hover)', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#eaeaea',
			'selectors'	 => [
				'{{WRAPPER}} .reign-main-navigation .reign-inside-navigation ul li:hover a' => 'background-color: {{VALUE}};',
			],
		]
		);

		$this->add_control(
		'font_color', [
			'label'		 => __( 'Font Color', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#000',
			'selectors'	 => [
				'{{WRAPPER}} .reign-main-navigation .reign-menu-toggle, {{WRAPPER}} .reign-main-navigation .reign-inside-navigation ul li a' => 'color: {{VALUE}};',
			],
		]
		);

		$this->add_control(
		'font_color_hover', [
			'label'		 => __( 'Font Color (Hover)', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#000',
			'selectors'	 => [
				'{{WRAPPER}} .reign-main-navigation .reign-inside-navigation ul li a:hover' => 'color: {{VALUE}};',
			],
		]
		);
		$this->add_group_control(
		Group_Control_Typography::get_type(), [
			'name'	 => 'typography',
			'scheme' => Scheme_Typography::TYPOGRAPHY_3,
		]
		);

		$this->end_controls_section();

		$this->start_controls_section(
		'section_icon', [
			'label' => __( 'Search Box', 'elementor' ),
		]
		);

		$this->add_control(
		'searchbox_enabled', [
			'label'			 => __( 'Enable Search Box', 'elementor' ),
			'type'			 => \Elementor\Controls_Manager::SWITCHER,
			'default'		 => '',
			'label_on'		 => __( 'Yes', 'elementor' ),
			'label_off'		 => __( 'No', 'elementor' ),
			'return_value'	 => 'yes',
			'separator'		 => 'before',
		]
		);

		$this->end_controls_section();


		do_action( 'reign_wp_menu_elementor_controls', $this );
	}

	/**
	 * Render our custom menu onto the page.
	 */
	protected function render() {
		$settings = $this->get_settings();

		if ( ! empty( $settings[ 'menu_location' ] ) ) {

			ob_start();
			?>
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<span id="nav-icon1" class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span></span>
					<span></span>
					<span></span>
				</span>
				<?php
				if ( is_numeric( $settings[ 'menu_location' ] ) ) {
					$nav_menu = wp_get_nav_menu_object( $settings[ 'menu_location' ] );
					if ( $nav_menu ) {
						wp_nav_menu( array(
							'menu'			 => $nav_menu,
							'fallback_cb'	 => '',
							'container'		 => false,
							'menu_class'	 => 'primary-menu',
							'menu_id'		 => 'primary-menu',
						) );
					} else {
						echo "Menu Configuration Issue";
					}
				} else {
					wp_nav_menu(
					array(
						'theme_location'	 => $settings[ 'menu_location' ],
						'container'			 => 'div',
						'container_class'	 => 'main-nav',
						'container_id'		 => 'primary-menu',
						'menu_class'		 => '',
						'items_wrap'		 => '<ul id="%1$s" class="%2$s ' . '">%3$s</ul>'
					)
					);
				}

				if ( 'yes' == $settings[ 'searchbox_enabled' ] ) {
					?>

					<div class="search-wrap">
						<span class="rg-search-icon rg-header-icon"></span>
						<div class="search-content">
							<?php get_search_form(); ?>
						</div>
					</div>

				<?php } ?>
			</nav><!-- #site-navigation -->
			<?php
			echo apply_filters( 'reign_menu_output', ob_get_clean(), $settings[ 'menu_location' ], $settings );
		} else {
			$this->content_template();
		}
	}

	/**
	 * This is outputted while rending the page.
	 */
	protected function content_template() {
		?>
		<div class="reign-wp-menu-content-area">
			WordPress Menu Will Appear Here
		</div>
		<?php
	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Widget_Reign_WP_Menu() );


