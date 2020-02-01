<?php
namespace WbcomElementorAddons\Modules\Forms\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use WbcomElementorAddons\Base\Base_Widget;
use WbcomElementorAddons\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Login extends Base_Widget {

	public function get_name() {
		return 'wbcom-login';
	}

	public function get_title() {
		return __( 'Login', 'wbcom-essential' );
	}

	public function get_icon() {
		return 'eicon-lock-user';
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_fields_content',
			[
				'label' => __( 'Form Fields', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => __( 'Label', 'wbcom-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => __( 'Hide', 'wbcom-essential' ),
				'label_on' => __( 'Show', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'input_size',
			[
				'label' => __( 'Input Size', 'wbcom-essential' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => __( 'Extra Small', 'wbcom-essential' ),
					'sm' => __( 'Small', 'wbcom-essential' ),
					'md' => __( 'Medium', 'wbcom-essential' ),
					'lg' => __( 'Large', 'wbcom-essential' ),
					'xl' => __( 'Extra Large', 'wbcom-essential' ),
				],
				'default' => 'sm',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_content',
			[
				'label' => __( 'Log In Button', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'wbcom-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Log In', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => __( 'Size', 'wbcom-essential' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => __( 'Extra Small', 'wbcom-essential' ),
					'sm' => __( 'Small', 'wbcom-essential' ),
					'md' => __( 'Medium', 'wbcom-essential' ),
					'lg' => __( 'Large', 'wbcom-essential' ),
					'xl' => __( 'Extra Large', 'wbcom-essential' ),
				],
				'default' => 'sm',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'wbcom-essential' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => __( 'Left', 'wbcom-essential' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'wbcom-essential' ),
						'icon' => 'fa fa-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'wbcom-essential' ),
						'icon' => 'fa fa-align-right',
					],
					'stretch' => [
						'title' => __( 'Justified', 'wbcom-essential' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-button-align-',
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_new_pass_button_content',
			[
				'label' => __( 'Get New Password Button', 'reign' ),
			]
		);

		$this->add_control(
			'new_pass_button_text',
			[
				'label' => __( 'Text', 'reign' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Get New Password', 'reign' ),
			]
		);

		$this->add_control(
			'new_pass_button_size',
			[
				'label' => __( 'Size', 'reign' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => __( 'Extra Small', 'reign' ),
					'sm' => __( 'Small', 'reign' ),
					'md' => __( 'Medium', 'reign' ),
					'lg' => __( 'Large', 'reign' ),
					'xl' => __( 'Extra Large', 'reign' ),
				],
				'default' => 'sm',
			]
		);

		$this->add_responsive_control(
			'new_pass_button_align',
			[
				'label' => __( 'Alignment', 'reign' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => __( 'Left', 'reign' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'reign' ),
						'icon' => 'fa fa-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'reign' ),
						'icon' => 'fa fa-align-right',
					],
					'stretch' => [
						'title' => __( 'Justified', 'reign' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-button-align-',
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_login_content',
			[
				'label' => __( 'Additional Options', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'redirect_after_login',
			[
				'label' => __( 'Redirect After Login', 'wbcom-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_off' => __( 'Off', 'wbcom-essential' ),
				'label_on' => __( 'On', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'redirect_url',
			[
				'type' => Controls_Manager::URL,
				'show_label' => false,
				'show_external' => false,
				'separator' => false,
				'placeholder' => 'http://your-link.com/',
				'description' => __( 'Note: Because of security reasons, you can ONLY use your current domain here.', 'wbcom-essential' ),
				'condition' => [
					'redirect_after_login' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_lost_password',
			[
				'label' => __( 'Lost your password?', 'wbcom-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => __( 'Hide', 'wbcom-essential' ),
				'label_on' => __( 'Show', 'wbcom-essential' ),
			]
		);

		if ( get_option( 'users_can_register' ) ) {
			$this->add_control(
				'show_register',
				[
					'label' => __( 'Register', 'wbcom-essential' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => 'yes',
					'label_off' => __( 'Hide', 'wbcom-essential' ),
					'label_on' => __( 'Show', 'wbcom-essential' ),
				]
			);
		}

		$this->add_control(
			'show_remember_me',
			[
				'label' => __( 'Remember Me', 'wbcom-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => __( 'Hide', 'wbcom-essential' ),
				'label_on' => __( 'Show', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'show_logged_in_message',
			[
				'label' => __( 'Logged in Message', 'wbcom-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => __( 'Hide', 'wbcom-essential' ),
				'label_on' => __( 'Show', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'custom_labels',
			[
				'label' => __( 'Custom Label', 'wbcom-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'user_label',
			[
				'label' => __( 'Username Label', 'wbcom-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Username or Email Address', 'wbcom-essential' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'user_placeholder',
			[
				'label' => __( 'Username Placeholder', 'wbcom-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( ' Username or Email Address', 'wbcom-essential' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'password_label',
			[
				'label' => __( 'Password Label', 'wbcom-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Password', 'wbcom-essential' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'password_placeholder',
			[
				'label' => __( 'Password Placeholder', 'wbcom-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Password', 'wbcom-essential' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'register_label',
			[
				'label' => __( 'Register Label', 'wbcom-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Register', 'wbcom-essential' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'remember_me_label',
			[
				'label' => __( 'Remember Me Label', 'wbcom-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Remember Me', 'wbcom-essential' ),
				'condition' => [
					'show_labels' => 'yes',
					'custom_labels' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Form', 'wbcom-essential' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'wbcom-essential' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '10',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'links_color',
			[
				'label' => __( 'Links Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group > a' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_control(
			'links_hover_color',
			[
				'label' => __( 'Links Hover Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group > a:hover' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_labels',
			[
				'label' => __( 'Label', 'wbcom-essential' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => __( 'Spacing', 'wbcom-essential' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '0',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'body {{WRAPPER}} .elementor-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					// for the label position = above option
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Text Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-form-fields-wrapper' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .elementor-form-fields-wrapper',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => __( 'Fields', 'wbcom-essential' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => __( 'Text Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'selector' => '{{WRAPPER}} .elementor-field-group .elementor-field, {{WRAPPER}} .elementor-field-subgroup label',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label' => __( 'Background Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label' => __( 'Border Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper::before' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_width',
			[
				'label' => __( 'Border Width', 'wbcom-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'field_border_radius',
			[
				'label' => __( 'Border Radius', 'wbcom-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'wbcom-essential' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Typography', 'wbcom-essential' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'label' => __( 'Border', 'wbcom-essential' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'wbcom-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => __( 'Text Padding', 'wbcom-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'wbcom-essential' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'wbcom-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => __( 'Animation', 'wbcom-essential' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function form_fields_render_attributes() {
		$settings = $this->get_settings();

		if ( ! empty( $settings['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['button_size'] );
		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

		if ( ! empty( $settings['new_pass_button_size'] ) ) {
			$this->add_render_attribute( 'new_pass_button', 'class', 'elementor-size-' . $settings['new_pass_button_size'] );
		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'new_pass_button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'elementor-form-fields-wrapper',
					],
				],
				'field-group' => [
					'class' => [
						'elementor-field-type-text',
						'elementor-field-group',
						'elementor-column',
						'elementor-col-100',
					],
				],
				'submit-group' => [
					'class' => [
						'elementor-field-group',
						'elementor-column',
						'elementor-field-type-submit',
						'elementor-col-100',
					],
				],

				'button' => [
					'class' => [
						'elementor-button',
					],
					'name' => 'wp-submit',
				],
				'new_pass_button' => [
					'class' => [
						'elementor-button',
					],
					'name' => 'wp-submit',
				],
				'user_label' => [
					'for' => 'user',
				],
				'user_input' => [
					'type' => 'text',
					'name' => 'log',
					'id' => 'user',
					'placeholder' => $settings['user_placeholder'],
					'class' => [
						'elementor-field',
						'elementor-field-textual',
						'elementor-size-' . $settings['input_size'],
					],
				],
				'password_input' => [
					'type' => 'password',
					'name' => 'pwd',
					'id' => 'password',
					'placeholder' => $settings['password_placeholder'],
					'class' => [
						'elementor-field',
						'elementor-field-textual',
						'elementor-size-' . $settings['input_size'],
					],
				],
				//TODO: add unique ID
				'label_user' => [
					'for' => 'user',
					'class' => 'elementor-field-label',
				],

				'label_password' => [
					'for' => 'password',
					'class' => 'elementor-field-label',
				],
			]
		);

		if ( ! $settings['show_labels'] ) {
			$this->add_render_attribute( 'label', 'class', 'elementor-screen-only' );
		}

		$this->add_render_attribute( 'field-group', 'class', 'elementor-field-required' )
			->add_render_attribute( 'input', 'required', true )
			->add_render_attribute( 'input', 'aria-required', 'true' );

	}

	protected function render() {
		$settings = $this->get_settings();
		$current_url = remove_query_arg( 'fake_arg' );

		if ( 'yes' === $settings['redirect_after_login'] && ! empty( $settings['redirect_url']['url'] ) ) {
			$redirect_url  = $settings['redirect_url']['url'];
		} else {
			$redirect_url = $current_url;
		}

		if ( is_user_logged_in() && ! Plugin::elementor()->editor->is_edit_mode() ) {
			if ( 'yes' === $settings['show_logged_in_message'] ) {
				$current_user = wp_get_current_user();

				echo '<div class="elementor-login">' .
					sprintf( __( 'You are Logged in as %1$s (<a href="%2$s">Logout</a>)', 'wbcom-essential' ), $current_user->display_name, wp_logout_url( $current_url ) ) .
					'</div>';
			}

			return;
		}

		$this->form_fields_render_attributes();
		?>
		<style type="text/css">
			/* The alert message box */
			.wbcom-elementor-login-form .alert {
				padding: 20px;
				background-color: #f44336; /* Red */
				color: white;
				margin-bottom: 15px;
			}
			/* The close button */
			.wbcom-elementor-login-form .closebtn {
				margin-left: 15px;
				color: white;
				font-weight: bold;
				float: right;
				font-size: 22px;
				line-height: 20px;
				cursor: pointer;
				transition: 0.3s;
			}
			/* When moving the mouse over the close button */
			.wbcom-elementor-login-form .closebtn:hover {
				color: black;
			} 
		</style>
		<!-- Lost Your Password Form :: Start -->
		<form class="elementor-login elementor-form wbcom-elementor-login-form wbcom-forgot-password-form" method="post" action="">
			<input type="hidden" name="wbcom_action_to_to" value="forgot_password">
			<div class="alert" style="display: none;">
				<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
				<span class="error-message">This is an alert box.</span>
			</div>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<div <?php echo $this->get_render_attribute_string( 'field-group' ); ?>>
					<?php
					if ( $settings['show_labels'] ) {
						echo '<label ' . $this->get_render_attribute_string( 'user_label' ) . '>' . $settings['user_label'] . '</label>';
					}

					echo '<input size="1" ' . $this->get_render_attribute_string( 'user_input' ) . '>';

					?>
				</div>
				<div <?php echo $this->get_render_attribute_string( 'submit-group' ); ?>>
					<button type="submit" <?php echo $this->get_render_attribute_string( 'new_pass_button' ); ?>>
							<?php if ( ! empty( $settings['new_pass_button_text'] ) ) : ?>
								<span class="elementor-button-text"><?php echo $settings['new_pass_button_text']; ?></span>
							<?php endif; ?>
					</button>
				</div>
			</div>	
		</form>
		<!-- Lost Your Password Form :: End -->

		<form class="elementor-login elementor-form wbcom-elementor-login-form" method="post" action="<?php echo wp_login_url(); ?>">
			<input type="hidden" name="wbcom_action_to_to" value="login">
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_url );?>">
			<div class="alert" style="display: none;">
				<span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
				<span class="error-message">This is an alert box.</span>
			</div>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<div <?php echo $this->get_render_attribute_string( 'field-group' ); ?>>
					<?php
					if ( $settings['show_labels'] ) {
						echo '<label ' . $this->get_render_attribute_string( 'user_label' ) . '>' . $settings['user_label'] . '</label>';
					}

					echo '<input size="1" ' . $this->get_render_attribute_string( 'user_input' ) . '>';

					?>
				</div>
				<div <?php echo $this->get_render_attribute_string( 'field-group' ); ?>>
					<?php
					if ( $settings['show_labels'] ) :
						echo '<label ' . $this->get_render_attribute_string( 'password_label' ) . '>' . $settings['password_label'] . '</label>';
					endif;

					echo '<input size="1" ' . $this->get_render_attribute_string( 'password_input' ) . '>';
					?>
				</div>

				<?php do_action( 'login_form' ); ?>

				<?php if ( 'yes' === $settings['show_remember_me'] ) : ?>
					<div class="elementor-field-type-checkbox elementor-field-group elementor-column elementor-col-100 elementor-remember-me">
						<label for="elementor-login-remember-me">
							<input type="checkbox" id="elementor-login-remember-me" name="rememberme" value="forever">
							<?php echo $settings['remember_me_label']; ?>
							<?php //echo __( 'Remember Me', 'wbcom-essential' ); ?>
						</label>
					</div>
				<?php endif; ?>
				
				<div <?php echo $this->get_render_attribute_string( 'submit-group' ); ?>>
					<button type="submit" <?php echo $this->get_render_attribute_string( 'button' ); ?>>
							<?php if ( ! empty( $settings['button_text'] ) ) : ?>
								<span class="elementor-button-text"><?php echo $settings['button_text']; ?></span>
							<?php endif; ?>
					</button>
					<img id="wb-ele-ajax-loader" src="<?php echo WBCOM_ELEMENTOR_ADDONS_ASSETS_URL . 'ajax-loader.gif'; ?>">
				</div>
				<style type="text/css">
				#wb-ele-ajax-loader {
					width: 25px;
					margin-left: 10px;
					display: none;
				}
				</style>
				<?php
				$show_lost_password = 'yes' === $settings['show_lost_password'];
				$show_register = get_option( 'users_can_register' ) && 'yes' === $settings['show_register'];

				if ( $show_lost_password || $show_register ) : ?>
					<div class="elementor-field-group elementor-column elementor-col-100">
						<?php if ( $show_lost_password ) : ?>
							<a class="elementor-lost-password" href="<?php echo wp_lostpassword_url( $redirect_url ); ?>">
								<?php echo __( 'Lost your password?', 'wbcom-essential' ); ?>
							</a>
						<?php endif; ?>

						<?php if ( $show_register ) : ?>
							<?php if ( $show_lost_password ) : ?>
								<span class="elementor-login-separator"> | </span>
							<?php endif; ?>
							<a class="elementor-register" href="<?php echo wp_registration_url(); ?>">
								<?php echo $settings['register_label']; ?>
								<?php //echo __( 'Register', 'wbcom-essential' ); ?>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</form>
		<?php
	}

	protected function _content_template() {
		?>
		<div class="elementor-login elementor-form">
			<div class="elementor-form-fields-wrapper">
				<#
					fieldGroupClasses = 'elementor-field-group elementor-column elementor-col-100 elementor-field-type-text';
				#>
				<div class="{{ fieldGroupClasses }}">
					<# if ( settings.show_labels ) { #>
						<label class="elementor-field-label" for="user" >{{{ settings.user_label }}}</label>
						<# } #>
							<input size="1" type="text" id="user" placeholder="{{ settings.user_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}" />
				</div>
				<div class="{{ fieldGroupClasses }}">
					<# if ( settings.show_labels ) { #>
						<label class="elementor-field-label" for="password" >{{{ settings.password_label }}}</label>
						<# } #>
							<input size="1" type="password" id="password" placeholder="{{ settings.password_placeholder }}" class="elementor-field elementor-field-textual elementor-size-{{ settings.input_size }}" />
				</div>

				<# if ( settings.show_remember_me ) { #>
					<div class="elementor-field-type-checkbox elementor-field-group elementor-column elementor-col-100 elementor-remember-me">
						<label for="elementor-login-remember-me">
							<input type="checkbox" id="elementor-login-remember-me" name="rememberme" value="forever">
							<?php echo __( 'Remember Me', 'wbcom-essential' ); ?>
						</label>
					</div>
				<# } #>

				<div class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100">
					<button type="submit" class="elementor-button elementor-size-{{ settings.button_size }}">
						<# if ( settings.button_text ) { #>
							<span class="elementor-button-text">{{ settings.button_text }}</span>
						<# } #>
					</button>
				</div>

				<# if ( settings.show_lost_password || settings.show_register ) { #>
					<div class="elementor-field-group elementor-column elementor-col-100">
						<# if ( settings.show_lost_password ) { #>
							<a class="elementor-lost-password" href="<?php echo wp_lostpassword_url(); ?>">
								<?php echo __( 'Lost your password?', 'wbcom-essential' ); ?>
							</a>
						<# } #>

						<?php if ( get_option( 'users_can_register' ) ) { ?>
							<# if ( settings.show_register ) { #>
								<# if ( settings.show_lost_password ) { #>
									<span class="elementor-login-separator"> | </span>
								<# } #>
								<a class="elementor-register" href="<?php echo wp_registration_url(); ?>">
									<?php echo __( 'Register', 'wbcom-essential' ); ?>
								</a>
							<# } #>
						<?php } ?>
					</div>
				<# } #>
			</div>
		</div>
		<?php
	}

	public function render_plain_content() {}
}