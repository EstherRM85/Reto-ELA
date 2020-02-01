<?php
namespace Elementor;
// namespace WbcomElementorAddons\Modules\Social\Classes;

use Elementor\Controls_Manager;
use Elementor\Settings;
use Elementor\Utils;
use Elementor\Widget_Base;
// use WbcomElementorAddons\Plugin;

use Elementor\Core\Settings\Manager as SettingsManager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WBCOM_Additional_Elementor_Global_Settings {

	protected static $available_color_pallets = array(
			'joker' => [
				'title' => 'Joker',
				'items' => [
					'COLOR_1' => '#202020',
					'COLOR_2' => '#b7b4b4',
					'COLOR_3' => '#707070',
					'COLOR_4' => '#f6121c',
				],
			],
			'ocean' => [
				'title' => 'Ocean',
				'items' => [
					'COLOR_1' => '#1569ae',
					'COLOR_2' => '#b6c9db',
					'COLOR_3' => '#545454',
					'COLOR_4' => '#fdd247',
				],
			],
			'royal' => [
				'title' => 'Royal',
				'items' => [
					'COLOR_1' => '#d5ba7f',
					'COLOR_2' => '#902729',
					'COLOR_3' => '#ae4848',
					'COLOR_4' => '#302a8c',
				],
			],
			'violet' => [
				'title' => 'Violet',
				'items' => [
					'COLOR_1' => '#747476',
					'COLOR_2' => '#ebca41',
					'COLOR_3' => '#6f1683',
					'COLOR_4' => '#a43cbd',
				],
			],
			'sweet' => [
				'title' => 'Sweet',
				'items' => [
					'COLOR_1' => '#6ccdd9',
					'COLOR_2' => '#763572',
					'COLOR_3' => '#919ca7',
					'COLOR_4' => '#f12184',
				],
			],
			'urban' => [
				'title' => 'Urban',
				'items' => [
					'COLOR_1' => '#db6159',
					'COLOR_2' => '#3b3b3b',
					'COLOR_3' => '#7a7979',
					'COLOR_4' => '#2abf64',
				],
			],
			'earth' => [
				'title' => 'Earth',
				'items' => [
					'COLOR_1' => '#882021',
					'COLOR_2' => '#c48e4c',
					'COLOR_3' => '#825e24',
					'COLOR_4' => '#e8c12f',
				],
			],
			'river' => [
				'title' => 'River',
				'items' => [
					'COLOR_1' => '#8dcfc8',
					'COLOR_2' => '#565656',
					'COLOR_3' => '#50656e',
					'COLOR_4' => '#dc5049',
				],
			],
			'pastel' => [
				'title' => 'Pastel',
				'items' => [
					'COLOR_1' => '#f27f6f',
					'COLOR_2' => '#f4cd78',
					'COLOR_3' => '#a5b3c1',
					'COLOR_4' => '#aac9c3',
				],
			],
		);

	public function __construct() {
		if ( is_admin() ) {
			add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_fields' ] );
		}
		add_action( 'update_option', array( $this, 'sync_wbcom_setting_and_elementor' ), 10, 3 );
	}

	public function sync_wbcom_setting_and_elementor( $option_name, $old_value, $value ) {
		
		$elementor_prefix = 'elementor_';
		$wbcom_elementor_typography_options = array(
			$elementor_prefix . 'ph_font_family',
			$elementor_prefix . 'ph_font_weight',
			$elementor_prefix . 'sh_font_family',
			$elementor_prefix . 'sh_font_weight',
			$elementor_prefix . 'bt_font_family',
			$elementor_prefix . 'bt_font_weight',
			$elementor_prefix . 'at_font_family',
			$elementor_prefix . 'at_font_weight',
		);

		$wbcom_elementor_color_options = array(
			$elementor_prefix . 'cp_primary_color',
			$elementor_prefix . 'cp_secondary_color',
			$elementor_prefix . 'cp_text_color',
			$elementor_prefix . 'cp_accent_color',
		);
		
		if( isset( $_POST['action'] ) && ( $_POST['action'] == 'elementor_apply_scheme' ) ) {
			if( $option_name == 'elementor_scheme_typography' ) {
				update_option( $elementor_prefix . 'ph_font_family', $value['1']['font_family'] );
				update_option( $elementor_prefix . 'ph_font_weight', $value['1']['font_weight'] );

				update_option( $elementor_prefix . 'sh_font_family', $value['2']['font_family'] );
				update_option( $elementor_prefix . 'sh_font_weight', $value['2']['font_weight'] );

				update_option( $elementor_prefix . 'bt_font_family', $value['3']['font_family'] );
				update_option( $elementor_prefix . 'bt_font_weight', $value['3']['font_weight'] );

				update_option( $elementor_prefix . 'at_font_family', $value['4']['font_family'] );
				update_option( $elementor_prefix . 'at_font_weight', $value['4']['font_weight'] );
			}
			else if( $option_name == 'elementor_scheme_color' ) {
				$value = array_values( $value );
				$color_pallets = self::$available_color_pallets;
				foreach ( $color_pallets as $_key => $_value ) {
					$elementor_scheme_color = array_values( $_value['items'] );
					if( $value == $elementor_scheme_color ) {
						update_option( 'elementor_cp_global_colors', $_key );
						break;
					}
				}
				
				// update_option( $elementor_prefix . 'cp_primary_color', $value['1'] );
				// update_option( $elementor_prefix . 'cp_secondary_color', $value['2'] );
				// update_option( $elementor_prefix . 'cp_text_color', $value['3'] );
				// update_option( $elementor_prefix . 'cp_accent_color', $value['4'] );
			}
		}

		if( isset( $_POST['option_page'] ) && ( $_POST['option_page'] == 'elementor' ) ) {
			if( in_array( $option_name, $wbcom_elementor_typography_options ) ) {
				$elementor_scheme_typography = get_option( 'elementor_scheme_typography', array() );
				switch ( $option_name ) {
					case $elementor_prefix . 'ph_font_family':
						$elementor_scheme_typography['1']['font_family'] = $value;
						update_option( 'elementor_scheme_typography', $elementor_scheme_typography );
						break;
					
					case $elementor_prefix . 'ph_font_weight':
						$elementor_scheme_typography['1']['font_weight'] = $value;
						update_option( 'elementor_scheme_typography', $elementor_scheme_typography );
						break;

					case $elementor_prefix . 'sh_font_family':
						$elementor_scheme_typography['2']['font_family'] = $value;
						update_option( 'elementor_scheme_typography', $elementor_scheme_typography );
						break;
					
					case $elementor_prefix . 'sh_font_weight':
						$elementor_scheme_typography['2']['font_weight'] = $value;
						update_option( 'elementor_scheme_typography', $elementor_scheme_typography );
						break;

					case $elementor_prefix . 'bt_font_family':
						$elementor_scheme_typography['3']['font_family'] = $value;
						update_option( 'elementor_scheme_typography', $elementor_scheme_typography );
						break;
					
					case $elementor_prefix . 'bt_font_weight':
						$elementor_scheme_typography['3']['font_weight'] = $value;
						update_option( 'elementor_scheme_typography', $elementor_scheme_typography );
						break;

					case $elementor_prefix . 'at_font_family':
						$elementor_scheme_typography['4']['font_family'] = $value;
						update_option( 'elementor_scheme_typography', $elementor_scheme_typography );
						break;
					
					case $elementor_prefix . 'at_font_weight':
						$elementor_scheme_typography['4']['font_weight'] = $value;
						update_option( 'elementor_scheme_typography', $elementor_scheme_typography );
						break;

					default:
						break;
				}
			}
		}

		if( isset( $_POST['option_page'] ) && ( $_POST['option_page'] == 'elementor' ) ) {
			if( $option_name == 'elementor_cp_global_colors' ) {
				$color_pallets = self::$available_color_pallets;
				$elementor_scheme_color = array_values( $color_pallets[$value]['items'] );
				$colors = array();
				foreach ( $elementor_scheme_color as $key => $value ) {
					$colors[$key+1] = $value;
				}
				update_option( 'elementor_scheme_color', $colors );
			}
		}

	}

	public function register_admin_fields( Settings $settings ) {

		$fonts = Fonts::get_fonts();
		$fonts = array_keys( $fonts );
		$_fonts = array();
		foreach ( $fonts as $key => $value ) {
			$_fonts[$value] = $value;
		}
		$fonts = $_fonts;

		$color_pallets = self::$available_color_pallets;
		$_color_pallets = array();
		foreach ( $color_pallets as $key => $value ) {
			$_color_pallets[$key] = $value['title'];
		}
		$color_pallets = $_color_pallets;

		$settings->add_section( Settings::TAB_STYLE, 'wbcom_primary_headline', [
			'callback' => function() {
				echo '<hr/>';
			},
			'label'	=>	__( 'Primary Headline', 'elementor' ),
			'fields' => [
				'ph_font_family' => [
					'label' => __( 'Font Family', 'elementor' ),
					'field_args' => [
						'type' => 'select',
						'options' => $fonts,
						'class' => 'medium-text',
						'desc' => __( '', 'elementor' ),
					],
				],
				'ph_font_weight' => [
					'label' => __( 'Font Weight', 'elementor' ),
					'field_args' => [
						'type' => 'text',
						'placeholder' => '',
						'sub_desc' => '',
						'class' => 'medium-text',
						'desc' => __( '', 'elementor' ),
					],
				],
			],
		] );

		$settings->add_section( Settings::TAB_STYLE, 'wbcom_secondary_headline', [
			'callback' => function() {
				echo '<hr/>';
			},
			'label'	=>	__( 'Secondary Headline', 'elementor' ),
			'fields' => [
				'sh_font_family' => [
					'label' => __( 'Font Family', 'elementor' ),
					'field_args' => [
						'type' => 'select',
						'options' => $fonts,
						'class' => 'medium-text',
						'desc' => __( '', 'elementor' ),
					],
				],
				'sh_font_weight' => [
					'label' => __( 'Font Weight', 'elementor' ),
					'field_args' => [
						'type' => 'text',
						'placeholder' => '',
						'sub_desc' => '',
						'class' => 'medium-text',
						'desc' => __( '', 'elementor' ),
					],
				],
			],
		] );

		$settings->add_section( Settings::TAB_STYLE, 'wbcom_body_text', [
			'callback' => function() {
				echo '<hr/>';
			},
			'label'	=>	__( 'Body Text', 'elementor' ),
			'fields' => [
				'bt_font_family' => [
					'label' => __( 'Font Family', 'elementor' ),
					'field_args' => [
						'type' => 'select',
						'options' => $fonts,
						'class' => 'medium-text',
						'desc' => __( '', 'elementor' ),
					],
				],
				'bt_font_weight' => [
					'label' => __( 'Font Weight', 'elementor' ),
					'field_args' => [
						'type' => 'text',
						'placeholder' => '',
						'sub_desc' => '',
						'class' => 'medium-text',
						'desc' => __( '', 'elementor' ),
					],
				],
			],
		] );

		$settings->add_section( Settings::TAB_STYLE, 'wbcom_accent_text', [
			'callback' => function() {
				echo '<hr/>';
			},
			'label'	=>	__( 'Accent Text', 'elementor' ),
			'fields' => [
				'at_font_family' => [
					'label' => __( 'Font Family', 'elementor' ),
					'field_args' => [
						'type' => 'select',
						'options' => $fonts,
						'class' => 'medium-text',
						'desc' => __( '', 'elementor' ),
					],
				],
				'at_font_weight' => [
					'label' => __( 'Font Weight', 'elementor' ),
					'field_args' => [
						'type' => 'text',
						'placeholder' => '',
						'sub_desc' => '',
						'class' => 'medium-text',
						'desc' => __( '', 'elementor' ),
					],
				],
			],
		] );

		
		$settings->add_section( Settings::TAB_STYLE, 'wbcom_color_pallet', [
			'callback' => function() {
				echo '<hr/>';
			},
			'label'	=>	__( 'Global Colors', 'elementor' ),
			'fields' => [
				'cp_global_colors' => [
					'label' => __( 'Select Global Color Scheme', 'elementor' ),
					'field_args' => [
						'type' => 'select',
						'options' => $color_pallets,
						'class' => 'medium-text',
						'desc' => __( '', 'elementor' ),
					],
				],
				// 'cp_primary_color' => [
				// 	'label' => __( 'Primary Color', 'elementor' ),
				// 	'field_args' => [
				// 		'type' => 'text',
				// 		'class' => 'medium-text',
				// 		'desc' => __( '', 'elementor' ),
				// 	],
				// ],
				// 'cp_secondary_color' => [
				// 	'label' => __( 'Secondary Color', 'elementor' ),
				// 	'field_args' => [
				// 		'type' => 'text',
				// 		'class' => 'medium-text',
				// 		'desc' => __( '', 'elementor' ),
				// 	],
				// ],
				// 'cp_text_color' => [
				// 	'label' => __( 'Text Color', 'elementor' ),
				// 	'field_args' => [
				// 		'type' => 'text',
				// 		'class' => 'medium-text',
				// 		'desc' => __( '', 'elementor' ),
				// 	],
				// ],
				// 'cp_accent_color' => [
				// 	'label' => __( 'Accent Color', 'elementor' ),
				// 	'field_args' => [
				// 		'type' => 'text',
				// 		'class' => 'medium-text',
				// 		'desc' => __( '', 'elementor' ),
				// 	],
				// ],
			],
		] );

	}
	
}

new WBCOM_Additional_Elementor_Global_Settings();