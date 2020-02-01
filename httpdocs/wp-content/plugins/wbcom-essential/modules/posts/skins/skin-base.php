<?php
namespace WbcomElementorAddons\Modules\Posts\Skins;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use WbcomElementorAddons\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Skin_Base extends Elementor_Skin_Base {

	protected function _register_controls_actions() {
		add_action( 'elementor/element/posts/section_layout/before_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/posts/section_query/after_section_end', [ $this, 'register_style_sections' ] );
	}

	public function register_style_sections( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_design_controls();
	}

	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_columns_controls();
		$this->register_post_count_control();
		$this->register_thumbnail_controls();
		$this->register_title_controls();
		$this->register_excerpt_controls();
		$this->register_meta_data_controls();
		$this->register_read_more_controls();
	}

	public function register_design_controls() {
		$this->register_design_layout_controls();
		$this->register_design_image_controls();
		$this->register_design_content_controls();
	}

	protected function register_thumbnail_controls() {
		$this->add_control(
			'thumbnail',
			[
				'label' => __( 'Image Position', 'wbcom-essential' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => __( 'Top', 'wbcom-essential' ),
					'left' => __( 'Left', 'wbcom-essential' ),
					'right' => __( 'Right', 'wbcom-essential' ),
					'none' => __( 'None', 'wbcom-essential' ),
				],
				'prefix_class' => 'elementor-posts--thumbnail-',
			]
		);

		$this->add_control(
			'masonry',
			[
				'label' => __( 'Masonry', 'wbcom-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Off', 'wbcom-essential' ),
				'label_on' => __( 'On', 'wbcom-essential' ),
				'condition' => [
					$this->get_control_id( 'columns!' ) => '1',
					$this->get_control_id( 'thumbnail' ) => 'top',
				],
				'render_type' => 'ui',
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail_size',
				'label' => __( 'Thumbnail Size', 'wbcom-essential' ),
				'default' => 'medium',
				'exclude' => [ 'custom' ],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
				'prefix_class' => 'elementor-posts--thumbnail-size-',
			]
		);

		// $this->add_responsive_control(
		// 	'item_ratio',
		// 	[
		// 		'label' => __( 'Image Ratio', 'wbcom-essential' ),
		// 		'type' => Controls_Manager::SLIDER,
		// 		'default' => [
		// 			'size' => 0.66,
		// 		],
		// 		'tablet_default' => [
		// 			'size' => '',
		// 		],
		// 		'mobile_default' => [
		// 			'size' => 0.5,
		// 		],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0.1,
		// 				'max' => 2,
		// 				'step' => 0.01,
		// 			],
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .elementor-posts-container .elementor-post__thumbnail' => 'padding-bottom: calc( {{SIZE}} * 100% );',
		// 			'{{WRAPPER}}:after' => 'content: "{{SIZE}}"; position: absolute; color: transparent;',
		// 		],
		// 		'condition' => [
		// 			$this->get_control_id( 'thumbnail!' ) => 'none',
		// 			$this->get_control_id( 'masonry' ) => '',
		// 		],
		// 	]
		// );

		$this->add_responsive_control(
			'image_width',
			[
				'label' => __( 'Image Width', 'wbcom-essential' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 600,
					],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => '',
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 100,
					'unit' => '%',
				],
				'size_units' => [ '%', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__thumbnail__link' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
			]
		);
	}

	protected function register_columns_controls() {
		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'wbcom-essential' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'prefix_class' => 'elementor-grid%s-',
				'frontend_available' => true,
			]
		);
	}

	protected function register_post_count_control() {
		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Posts Per Page', 'wbcom-essential' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);
	}

	protected function register_title_controls() {
		$this->add_control(
			'show_title',
			[
				'label' => __( 'Title', 'wbcom-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wbcom-essential' ),
				'label_off' => __( 'Hide', 'wbcom-essential' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'wbcom-essential' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);

	}

	protected function register_excerpt_controls() {
		$this->add_control(
			'show_excerpt',
			[
				'label' => __( 'Excerpt', 'wbcom-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wbcom-essential' ),
				'label_off' => __( 'Hide', 'wbcom-essential' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label' => __( 'Excerpt Length', 'wbcom-essential' ),
				'type' => Controls_Manager::NUMBER,
				'default' => apply_filters( 'excerpt_length', 25 ),
				'condition' => [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);
	}

	protected function register_read_more_controls() {
		$this->add_control(
			'show_read_more',
			[
				'label' => __( 'Read More', 'wbcom-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wbcom-essential' ),
				'label_off' => __( 'Hide', 'wbcom-essential' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label' => __( 'Read More Text', 'wbcom-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Read More »', 'wbcom-essential' ),
				'placeholder' => __( 'Read More »', 'wbcom-essential' ),
				'condition' => [
					$this->get_control_id( 'show_read_more' ) => 'yes',
				],
			]
		);
	}

	protected function register_meta_data_controls() {
		$this->add_control(
			'meta_data',
			[
				'label' => __( 'Meta Data', 'wbcom-essential' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'default' => [ 'date', 'comments' ],
				'multiple' => true,
				'options' => [
					'author' => __( 'Author', 'wbcom-essential' ),
					'date' => __( 'Date', 'wbcom-essential' ),
					'time' => __( 'Time', 'wbcom-essential' ),
					'comments' => __( 'Comments', 'wbcom-essential' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_separator',
			[
				'label' => __( 'Separator Between', 'wbcom-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => '///',
				'selectors' => [
					'{{WRAPPER}} .elementor-post__meta-data span + span:before' => 'content: "{{VALUE}}"',
				],
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
		);
	}

	/**
	 * Style Tab
	 */
	protected function register_design_layout_controls() {
		$this->start_controls_section(
			'section_design_layout',
			[
				'label' => __( 'Layout', 'wbcom-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'column_gap',
			[
				'label'     => __( 'Columns Gap', 'wbcom-essential' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 30,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post'            => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .elementor-posts-container' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label'     => __( 'Rows Gap', 'wbcom-essential' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 35,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'alignment',
			[
				'label'        => __( 'Alignment', 'wbcom-essential' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'options'      => [
					'left'   => [
						'title' => __( 'Left', 'wbcom-essential' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'wbcom-essential' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'wbcom-essential' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'prefix_class' => 'elementor-posts--align-',
			]
		);

		$this->end_controls_section();
	}

	protected function register_design_image_controls() {
		$this->start_controls_section(
			'section_design_image',
			[
				'label' => __( 'Image', 'wbcom-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
			]
		);

		$this->add_control(
			'img_border_radius',
			[
				'label'      => __( 'Border Radius', 'wbcom-essential' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .elementor-post__thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label'     => __( 'Spacing', 'wbcom-essential' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-posts--thumbnail-left .elementor-post__thumbnail__link'  => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-posts--thumbnail-right .elementor-post__thumbnail__link' => 'margin-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-posts--thumbnail-top .elementor-post__thumbnail__link'   => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'default'   => [
					'size' => 20,
				],
				'condition' => [
					$this->get_control_id( 'thumbnail!' ) => 'none',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_design_content_controls() {

		$this->start_controls_section(
			'section_design_content',
			[
				'label' => __( 'Content', 'wbcom-essential' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label' => __( 'Title', 'wbcom-essential' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__title, {{WRAPPER}} .elementor-post__title a' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label'    => __( 'Typography', 'wbcom-essential' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-post__title, {{WRAPPER}} .elementor-post__title a',
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'title_spacing',
			[
				'label'     => __( 'Spacing', 'wbcom-essential' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'show_title' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_meta_style',
			[
				'label' => __( 'Meta', 'wbcom-essential' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label'     => __( 'Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__meta-data' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
		);

		$this->add_control(
			'meta_separator_color',
			[
				'label' => __( 'Separator Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__meta-data span:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'label'    => __( 'Typography', 'wbcom-essential' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .elementor-post__meta-data',
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
		);

		$this->add_control(
			'meta_spacing',
			[
				'label'     => __( 'Spacing', 'wbcom-essential' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__meta-data' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'meta_data!' ) => [],
				],
			]
		);

		$this->add_control(
			'heading_excerpt_style',
			[
				'label' => __( 'Excerpt', 'wbcom-essential' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => __( 'Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__excerpt p' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'label'    => __( 'Typography', 'wbcom-essential' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .elementor-post__excerpt p',
				'condition' => [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_spacing',
			[
				'label'     => __( 'Spacing', 'wbcom-essential' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_readmore_style',
			[
				'label' => __( 'Read More', 'wbcom-essential' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					$this->get_control_id( 'show_read_more' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label'     => __( 'Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__read-more' => 'color: {{VALUE}};',
				],
				'condition' => [
					$this->get_control_id( 'show_read_more' ) => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'read_more_typography',
				'label'    => __( 'Typography', 'wbcom-essential' ),
				'selector' => '{{WRAPPER}} .elementor-post__read-more',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'condition' => [
					$this->get_control_id( 'show_read_more' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'read_more_spacing',
			[
				'label'     => __( 'Spacing', 'wbcom-essential' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					$this->get_control_id( 'show_read_more' ) => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$this->parent->query_posts();

		$wp_query = $this->parent->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
		add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );

		$this->render_loop_header();

		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			$this->render_post();
		}

		$this->render_loop_footer();

		wp_reset_postdata();

		remove_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
		remove_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
	}

	public function filter_excerpt_length() {
		return $this->get_instance_value( 'excerpt_length' );
	}

	public function filter_excerpt_more( $more ) {
		return '';
	}

	protected function render_thumbnail() {
		$thumbnail = $this->get_instance_value( 'thumbnail' );

		if ( 'none' === $thumbnail && ! Plugin::elementor()->editor->is_edit_mode() ) {
			return;
		}

		$settings = $this->parent->get_settings();
		$setting_key = $this->get_control_id( 'thumbnail_size' );
		$settings[ $setting_key ] = [
			'id' => get_post_thumbnail_id(),
		];
		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		if ( empty( $thumbnail_html ) ) {
			return;
		}
		?>
		<a class="elementor-post__thumbnail__link" href="<?php echo get_permalink(); ?>">
			<div class="elementor-post__thumbnail"><?php echo $thumbnail_html; ?></div>
		</a>
		<?php
	}

	protected function render_title() {
		if ( ! $this->get_instance_value( 'show_title' ) ) {
			return;
		}

		$tag = $this->get_instance_value( 'title_tag' );
		?>
		<<?php echo $tag; ?> class="elementor-post__title">
			<a href="<?php echo get_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</<?php echo $tag; ?>>
		<?php
	}

	protected function render_excerpt() {
		if ( ! $this->get_instance_value( 'show_excerpt' ) ) {
			return;
		}
		?>
		<div class="elementor-post__excerpt">
			<?php the_excerpt(); ?>
		</div>
		<?php
	}

	protected function render_read_more() {
		if ( ! $this->get_instance_value( 'show_read_more' ) ) {
			return;
		}
		?>
			<a class="elementor-post__read-more" href="<?php echo get_permalink(); ?>">
				<?php echo $this->get_instance_value( 'read_more_text' ); ?>
			</a>
		<?php
	}

	protected function render_post_header() {
		?>
		<article <?php post_class( [ 'elementor-post elementor-grid-item' ] ); ?>>
		<?php
	}

	protected function render_post_footer() {
		?>
		</article>
		<?php
	}

	protected function render_text_header() {
		?>
		<div class="elementor-post__text">
		<?php
	}

	protected function render_text_footer() {
		?>
		</div>
		<?php
	}

	protected function render_loop_header() {
		$this->parent->add_render_attribute( 'container', [
			'class' => [
				'elementor-posts-container',
				'elementor-posts',
				'elementor-grid',
				'elementor-posts--skin-' . $this->get_id(),
			],
		] );
		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'container' ); ?>>
		<?php
	}

	protected function render_loop_footer() {
		?>
		</div>
		<?php

		$parent_settings = $this->parent->get_settings();
		if ( '' === $parent_settings['pagination_type'] ) {
			return;
		}

		$page_limit = $this->parent->get_query()->max_num_pages;
		if ( '' !== $parent_settings['pagination_page_limit']  ) {
			$page_limit = min( $parent_settings['pagination_page_limit'], $page_limit );
		}

		if ( 2 > $page_limit ) {
			return;
		}

		$this->parent->add_render_attribute( 'pagination', 'class', 'elementor-pagination' );

		$has_numbers = in_array( $parent_settings['pagination_type'], [ 'numbers', 'numbers_and_prev_next' ] );
		$has_prev_next = in_array( $parent_settings['pagination_type'], [ 'prev_next', 'numbers_and_prev_next' ] );

		$links = [];

		if ( $has_numbers ) {
			$links = paginate_links( [
				'type' => 'array',
				'current' => $this->parent->get_current_page(),
				'total' => $page_limit,
				'prev_next' => false,
				'show_all' => 'yes' !== $parent_settings['pagination_numbers_shorten'],
				'before_page_number' => '<span class="elementor-screen-only">' . __( 'Page', 'wbcom-essential' ) . '</span>',
			] );
		}

		if ( $has_prev_next ) {
			$prev_next = $this->parent->get_posts_nav_link( $page_limit );
			array_unshift( $links, $prev_next['prev'] );
			$links[] = $prev_next['next'];
		}

		?>
		<nav class="elementor-pagination" role="navigation" aria-label="<?php _e( 'Pagination', 'wbcom-essential' ); ?>">
			<?php echo implode( PHP_EOL, $links ); ?>
		</nav>
		<?php
	}

	protected function render_meta_data() {
		/** @var array $settings. e.g. [ 'author', 'date', ... ] */
		$settings = $this->get_instance_value( 'meta_data' );
		if ( empty( $settings ) ) {
			return;
		}
		?>
		<div class="elementor-post__meta-data">
			<?php
			if ( in_array( 'author', $settings ) ) {
				$this->render_author();
			}

			if ( in_array( 'date', $settings ) ) {
				$this->render_date();
			}

			if ( in_array( 'time', $settings ) ) {
				$this->render_time();
			}

			if ( in_array( 'comments', $settings ) ) {
				$this->render_comments();
			}
			?>
		</div>
		<?php
	}

	protected function render_author() {
		?>
		<span class="elementor-post-author">
			<?php the_author(); ?>
		</span>
		<?php
	}

	protected function render_date() {
		?>
		<span class="elementor-post-date">
			<?php echo apply_filters( 'the_date', get_the_date(), get_option( 'date_format' ), '', '' ); ?>
		</span>
		<?php
	}

	protected function render_time() {
		?>
		<span class="elementor-post-time">
			<?php the_time(); ?>
		</span>
		<?php
	}

	protected function render_comments() {
		?>
		<span class="elementor-post-avatar">
			<?php comments_number(); ?>
		</span>
		<?php
	}

	protected function render_post() {
		$this->render_post_header();
		$this->render_thumbnail();
		$this->render_text_header();
		$this->render_title();
		$this->render_meta_data();
		$this->render_excerpt();
		$this->render_read_more();
		$this->render_text_footer();
		$this->render_post_footer();
	}
}
