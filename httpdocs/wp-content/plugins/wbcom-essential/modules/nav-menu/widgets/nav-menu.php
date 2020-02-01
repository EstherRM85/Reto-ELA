<?php
namespace WbcomElementorAddons\Modules\NavMenu\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Nav_Menu extends Widget_Base {

	/**
	 * Get Widgets name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'wbcom-nav-menu';
	}

	/**
	 * Get widgets title
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Nav Menu', 'wbcom-essential' );
	}

	/**
	 * Get the current icon for display on frontend.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
	}

	/**
	 * Get available categories for this widget. Which is our own category for page builder options.
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'wbcom-elements' ];
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
			'0' => esc_html__( ' - choose - ', 'reign' ),
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

		// $this->add_control(
		// 	'menu_toggle_color', [
		// 		'label'		 => __( 'Menu Toggle Color', 'reign' ),
		// 		'type'		 => \Elementor\Controls_Manager::COLOR,
		// 		'default'	 => '#ffffff',
		// 		'selectors'	 => [
		// 			'{{WRAPPER}} #site-navigation.main-navigation #nav-icon1 span' => 'background-color: {{VALUE}};',
		// 		],
		// 	]
		// );

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
				'{{WRAPPER}} .main-navigation ul' => 'text-align: {{VALUE}};',
			],
		]
		);

		$this->add_control(
		'menu_background', [
			'label'		 => __( 'Background', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#f8f8f8',
			'selectors'	 => [
				'{{WRAPPER}} .main-navigation ul li' => 'background-color: {{VALUE}};',
			],
		]
		);
		$this->add_control(
		'menu_background_hover', [
			'label'		 => __( 'Background (hover)', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#eaeaea',
			'selectors'	 => [
				'{{WRAPPER}} .main-navigation ul li a:hover' => 'background-color: {{VALUE}};',
			],
		]
		);
		$this->add_control(
		'menu_background_active', [
			'label'		 => __( 'Background (active)', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#eaeaea',
			'selectors'	 => [
				'{{WRAPPER}} .main-navigation ul li.current-menu-item a' => 'background-color: {{VALUE}};',
			],
		]
		);

		$this->add_control(
		'font_color', [
			'label'		 => __( 'Font Color', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#000',
			'selectors'	 => [
				'{{WRAPPER}} .main-navigation .reign-menu-toggle, {{WRAPPER}} .main-navigation ul li a' => 'color: {{VALUE}};',
			],
		]
		);

		$this->add_control(
		'font_color_hover', [
			'label'		 => __( 'Font Color (Hover)', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#000',
			'selectors'	 => [
				'{{WRAPPER}} .main-navigation ul li a:hover' => 'color: {{VALUE}};',
			],
		]
		);

		$this->add_control(
		'font_color_active', [
			'label'		 => __( 'Font Color (Active)', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#399dff',
			'selectors'	 => [
				'{{WRAPPER}} .main-navigation ul li.current-menu-item a' => 'color: {{VALUE}};',
			],
		]
		);

		// $this->add_group_control(
		// Group_Control_Typography::get_type(), [
		// 	'name'	 => 'typography',
		// 	'scheme' => Scheme_Typography::TYPOGRAPHY_3,
		// ]
		// );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .main-navigation ul.primary-menu > li a',
			]
		);

		/* added for advanced menu color options :: start */
		// $this->add_control(
		// 	'enable_border_top',
		// 	[
		// 		'label' => __( 'Enable Top Border','wbcom-essential' ),
		// 		'type' => Controls_Manager::SWITCHER,
		// 		'default' => '',
		// 		'label_off' => __( 'Off', 'wbcom-essential' ),
		// 		'label_on' => __( 'On', 'wbcom-essential' ),
		// 		'description' => __( 'Note: This will apply to active menu.', 'wbcom-essential' )
		// 	]
		// );

		// $this->add_control(
		// 'color_border_top', [
		// 	'label'		 => __( 'Top Border Color', 'reign' ),
		// 	'type'		 => Controls_Manager::COLOR,
		// 	'default'	 => '#399dff',
		// 	'selectors'	 => [
		// 		'{{WRAPPER}} .main-navigation ul li' => 'border-top: 5px solid {{VALUE}};',
		// 	],
		// 	'condition' => [
		// 		'enable_border_top' => 'yes',
		// 	],
		// ]
		// );

		// $this->add_control(
		// 	'enable_border_top',
		// 	[
		// 		'type' => Controls_Manager::URL,
		// 		'show_label' => false,
		// 		'show_external' => false,
		// 		'separator' => false,
		// 		'placeholder' => 'http://your-link.com/',
		// 		'description' => __( 'Note: Because of security reasons, you can ONLY use your current domain here.', 'wbcom-essential' ),
		// 		'condition' => [
		// 			'redirect_after_login' => 'yes',
		// 		],
		// 	]
		// );
		/* added for advanced menu color options :: end */


		$this->end_controls_section();

		/* Sub Menu Section */
		$this->start_controls_section(
		'section_reign_submenu_style', [
			'label'	 => __( 'Sub Menu Style', 'reign' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		]
		);

		$this->add_responsive_control(
		'submenu_align', [
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
				'{{WRAPPER}} .main-navigation ul li.menu-item-has-children ul' => 'text-align: {{VALUE}};',
			],
		]
		);

		$this->add_control(
		'submenu_background', [
			'label'		 => __( 'Background', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#f8f8f8',
			'selectors'	 => [
				'{{WRAPPER}} .main-navigation ul li.menu-item-has-children ul li a' => 'background-color: {{VALUE}} !important;',
			],
		]
		);
		$this->add_control(
		'submenu_background_hover', [
			'label'		 => __( 'Background (hover)', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#eaeaea',
			'selectors'	 => [
				'{{WRAPPER}} .main-navigation ul li.menu-item-has-children ul li a:hover' => 'background-color: {{VALUE}} !important;',
			],
		]
		);

		$this->add_control(
		'submenu_font_color', [
			'label'		 => __( 'Font Color', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#000',
			'selectors'	 => [
				'{{WRAPPER}} .main-navigation .reign-menu-toggle, {{WRAPPER}} .main-navigation ul li.menu-item-has-children ul li a' => 'color: {{VALUE}};',
			],
		]
		);

		$this->add_control(
		'submenu_font_color_hover', [
			'label'		 => __( 'Font Color (Hover)', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#000',
			'selectors'	 => [
				'{{WRAPPER}} .main-navigation ul li.menu-item-has-children ul li a:hover' => 'color: {{VALUE}};',
			],
		]
		);

		$this->add_control(
		'submenu_font_color_active', [
			'label'		 => __( 'Font Color (Active)', 'reign' ),
			'type'		 => Controls_Manager::COLOR,
			'default'	 => '#399dff',
			'selectors'	 => [
				'{{WRAPPER}} .main-navigation ul li.menu-item-has-children ul li.current-menu-item a' => 'color: {{VALUE}};',
			],
		]
		);

		// $this->add_group_control(
		// Group_Control_Typography::get_type(), [
		// 	'name'	 => 'submenu_typography',
		// 	'scheme' => Scheme_Typography::TYPOGRAPHY_3,
		// ]
		// );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'submenu_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .main-navigation ul.primary-menu ul.sub-menu > li a',
			]
		);


		$this->end_controls_section();

		/* Height Management Section */
		$this->start_controls_section(
		'section_reign_menu_height', [
			'label'	 => __( 'Height Management', 'reign' ),
			'tab'	 => Controls_Manager::TAB_STYLE,
		]
		);

		$this->add_control(
		'menu_height', [
			'label'			 => __( 'Menu Height (px)', 'reign' ),
			'type'			 => Controls_Manager::NUMBER,
			'default'		 => 30,
		]
		);

		// $this->add_control(
		// 'submenu_height', [
		// 	'label'			 => __( 'Submenu Height', 'reign' ),
		// 	'type'			 => Controls_Manager::NUMBER,
		// 	'default'		 => 30,
		// ]
		// );

		$this->add_control(
			'submenu_width', [
				'label'			 => __( 'Submenu Width', 'reign' ),
				'type'			 => Controls_Manager::NUMBER,
				'default'		 => 170,
			]
		);

		$this->end_controls_section();

		// $this->start_controls_section(
		// 'section_icon', [
		// 	'label' => __( 'Search Box', 'elementor' ),
		// ]
		// );

		// $this->add_control(
		// 'searchbox_enabled', [
		// 	'label'			 => __( 'Enable Search Box', 'elementor' ),
		// 	'type'			 => \Elementor\Controls_Manager::SWITCHER,
		// 	'default'		 => '',
		// 	'label_on'		 => __( 'Yes', 'elementor' ),
		// 	'label_off'		 => __( 'No', 'elementor' ),
		// 	'return_value'	 => 'yes',
		// 	'separator'		 => 'before',
		// ]
		// );

		// $this->end_controls_section();


		do_action( 'reign_wp_submenu_elementor_controls', $this );
	}

	/**
	 * Render our custom menu onto the page.
	 */
	protected function render() {
		$settings = $this->get_settings();

		/* setting default menu in elementor based header */
		if ( empty( $settings[ 'menu_location' ] ) ) {
			$menus = wp_get_nav_menus();
			foreach ( $menus as $menu ) {
				$settings[ 'menu_location' ] = $menu->term_id;
				break;
			}
		}
		else if ( is_numeric( $settings[ 'menu_location' ] ) ) {
			$nav_menu = wp_get_nav_menu_object( $settings[ 'menu_location' ] );
			if ( !$nav_menu ) {
				$menus = wp_get_nav_menus();
				foreach ( $menus as $menu ) {
					$settings[ 'menu_location' ] = $menu->term_id;
					break;
				}
			}
		}
		/* setting default menu in elementor based header */

		if ( ! empty( $settings[ 'menu_location' ] ) ) {

			$menu_height = isset( $settings[ 'menu_height' ] ) ? $settings[ 'menu_height' ] : 90;
			$submenu_height = isset( $settings[ 'submenu_height' ] ) ? $settings[ 'submenu_height' ] : 90;
			$submenu_width = isset( $settings[ 'submenu_width' ] ) ? $settings[ 'submenu_width' ] : 170;

			$identifier = time() + rand( 10, 1000 );


			ob_start();
			?>
			<style type="text/css">
				#site-navigation-<?php echo $identifier; ?> .primary-menu > li > a,
				#site-navigation-<?php echo $identifier; ?> .header-right .search-wrap,
				#site-navigation-<?php echo $identifier; ?> .rg-icon-wrap,
				#site-navigation-<?php echo $identifier; ?> .elementor-branding,
				#site-navigation-<?php echo $identifier; ?> .user-link-wrap {
					height: <?php echo $menu_height; ?>px;
					line-height: <?php echo $menu_height; ?>px;
				}
				#site-navigation-<?php echo $identifier; ?> .primary-menu .children, .primary-menu .sub-menu {
					top: <?php echo $menu_height; ?>px;
				}

				@media screen and (min-width:768px) {
					#site-navigation-<?php echo $identifier; ?> ul#primary-menu li > ul.sub-menu {
						width: <?php echo $submenu_width; ?>px !important;
					}
				}

				/*.header-right .rg-search-form-wrap,
				.user-profile-menu,
				.primary-menu .children, .primary-menu .sub-menu,
				.user-profile-menu,
				.user-notifications .rg-dropdown, .user-notifications:hover .rg-dropdown {
					top: <?php //echo $submenu_height; ?>px;
				}*/
			</style>

			<nav id="site-navigation-<?php echo $identifier; ?>" class="main-navigation" role="navigation">
				<span class="menu-toggle wbcom-nav-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
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

				// if ( 'yes' == $settings[ 'searchbox_enabled' ] ) {
					?>

					<!-- <div class="search-wrap">
						<span class="rg-search-icon rg-header-icon"></span>
						<div class="search-content"> -->
							<?php //get_search_form(); ?>
						<!-- </div>
					</div> -->

				<?php //} ?>
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