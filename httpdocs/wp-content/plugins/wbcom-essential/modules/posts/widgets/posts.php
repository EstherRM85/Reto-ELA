<?php
namespace WbcomElementorAddons\Modules\Posts\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use WbcomElementorAddons\Base\Base_Widget;
use WbcomElementorAddons\Modules\QueryControl\Controls\Group_Control_Posts;
use WbcomElementorAddons\Modules\QueryControl\Module;
use WbcomElementorAddons\Modules\Posts\Skins;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Posts
 */
class Posts extends Base_Widget {

	/**
	 * @var \WP_Query
	 */
	private $query = null;

	protected $_has_template_content = false;

	public function get_name() {
		return 'posts';
	}

	public function get_title() {
		return __( 'Posts', 'wbcom-essential' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_script_depends() {
		return [ 'imagesloaded' ];
	}

	public function on_import( $element ) {
		if ( ! get_post_type_object( $element['settings']['posts_post_type'] ) ) {
			$element['settings']['posts_post_type'] = 'post';
		}

		return $element;
	}

	public function on_export( $element ) {
		$element = Group_Control_Posts::on_export_remove_setting_from_element( $element, 'posts' );
		return $element;
	}

	protected function _register_skins() {
		$this->add_skin( new Skins\Skin_Classic( $this ) );
		$this->add_skin( new Skins\Skin_Cards( $this ) );
	}

	public function get_query() {
		return $this->query;
	}

	protected function _register_controls() {
		$this->register_query_section_controls();
		$this->register_pagination_section_controls();
	}

	public function render() {}

	private function register_query_section_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'wbcom-essential' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'wbcom-essential' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Posts::get_type(),
			[
				'name' => 'posts',
				'label' => __( 'Posts', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'advanced',
			[
				'label'   => __( 'Advanced', 'wbcom-essential' ),
				'type'    => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => __( 'Order By', 'wbcom-essential' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post_date',
				'options' => [
					'post_date'  => __( 'Date', 'wbcom-essential' ),
					'post_title' => __( 'Title', 'wbcom-essential' ),
					'menu_order' => __( 'Menu Order', 'wbcom-essential' ),
					'rand'       => __( 'Random', 'wbcom-essential' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => __( 'Order', 'wbcom-essential' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc'  => __( 'ASC', 'wbcom-essential' ),
					'desc' => __( 'DESC', 'wbcom-essential' ),
				],
			]
		);

		$this->add_control(
			'offset',
			[
				'label'   => __( 'Offset', 'wbcom-essential' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'condition' => [
					'posts_post_type!' => 'by_id',
				],
				'description' => __( 'Use this setting to skip over posts (e.g. \'2\' to skip over 2 posts).', 'wbcom-essential' ),
			]
		);

		Module::add_exclude_controls( $this );

		$this->end_controls_section();
	}

	public function register_pagination_section_controls() {
		$this->start_controls_section(
			'section_pagination',
			[
				'label' => __( 'Pagination', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label' => __( 'Pagination', 'wbcom-essential' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'None', 'wbcom-essential' ),
					'numbers' => __( 'Numbers', 'wbcom-essential' ),
					'prev_next' => __( 'Previous/Next', 'wbcom-essential' ),
					'numbers_and_prev_next' => __( 'Numbers', 'wbcom-essential' ) . ' + ' . __( 'Previous/Next', 'wbcom-essential' ),
				],
			]
		);

		$this->add_control(
			'pagination_page_limit',
			[
				'label' => __( 'Page Limit', 'wbcom-essential' ),
				'default' => '5',
				'condition' => [
					'pagination_type!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_numbers_shorten',
			[
				'label' => __( 'Shorten', 'wbcom-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'yes',
				'condition' => [
					'pagination_type' => [
						'numbers',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'pagination_prev_label',
			[
				'label' => __( 'Previous Label', 'wbcom-essential' ),
				'default' => __( '&laquo; Previous', 'wbcom-essential' ),
				'condition' => [
					'pagination_type' => [
						'prev_next',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'pagination_next_label',
			[
				'label' => __( 'Next Label', 'wbcom-essential' ),
				'default' => __( 'Next &raquo;', 'wbcom-essential' ),
				'condition' => [
					'pagination_type' => [
						'prev_next',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'pagination_align',
			[
				'label' => __( 'Alignment', 'wbcom-essential' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'wbcom-essential' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'wbcom-essential' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'wbcom-essential' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'pagination_type!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_style',
			[
				'label' => __( 'Pagination', 'wbcom-essential' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'pagination_type!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pagination_typography',
				'selector' => '{{WRAPPER}} .elementor-pagination',
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
			]
		);

		$this->add_control(
			'pagination_color_heading',
			[
				'label' => __( 'Colors', 'wbcom-essential' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'pagination_colors' );

		$this->start_controls_tab(
			'pagination_color_normal',
			[
				'label' => __( 'Normal' , 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label' => __( 'Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination .page-numbers:not(.dots)' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_color_hover',
			[
				'label' => __( 'Hover' , 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'pagination_hover_color',
			[
				'label' => __( 'Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination a.page-numbers:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_color_active',
			[
				'label' => __( 'Active' , 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'pagination_active_color',
			[
				'label' => __( 'Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-pagination .page-numbers.current' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label' => __( 'Space Between', 'wbcom-essential' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:first-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .elementor-pagination .page-numbers:not(:last-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->end_controls_section();
	}

	public function query_posts() {
		$query_args = Module::get_query_args( 'posts', $this->get_settings() );

		$query_args['posts_per_page'] = $this->get_current_skin()->get_instance_value( 'posts_per_page' );
		$query_args['paged'] = $this->get_current_page();

		$this->query = new \WP_Query( $query_args );
	}

	public function get_current_page() {
		if ( '' === $this->get_settings( 'pagination_type' ) ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	public function get_posts_nav_link( $page_limit = null ) {
		if ( ! $page_limit ) {
			$page_limit = $this->query->max_num_pages;
		}

		$return = [];

		$paged = $this->get_current_page();

		$link_template = '<a class="page-numbers %s" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s">%s</span>';

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;
			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', get_pagenum_link( $next_page ), $this->get_settings( 'pagination_prev_label' ) );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $this->get_settings( 'pagination_prev_label' ) );
		}

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', get_pagenum_link( $next_page ), $this->get_settings( 'pagination_next_label' ) );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $this->get_settings( 'pagination_next_label' ) );
		}

		return $return;
	}
}
