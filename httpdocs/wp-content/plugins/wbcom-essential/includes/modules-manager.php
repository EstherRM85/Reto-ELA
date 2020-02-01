<?php
namespace WbcomElementorAddons;

use WbcomElementorAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Manager {
	/**
	 * @var Module_Base[]
	 */
	private $modules = [];

	public function __construct() {
		// $modules = [
		// 	'query-control',
		// 	'posts',
		// 	'slides',
		// 	'forms',
		// 	'nav-menu',
		// 	'animated-headline',
		// 	'pricing',
		// 	'flip-box',
		// 	'carousel',
		// 	'countdown',
		// 	'woocommerce',
		// 	'share-buttons',
		// 	'custom-css',
		// 	'global-widget',
		// 	'blockquote',
		// 	'social',
		// 	'library',
		// ];

		$modules = array();
		if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$modules = [
				'query-control',
				'posts',
				// 'nav-menu',
				// 'pricing',
				// 'share-buttons',
				// 'custom-css'
			];
		}

		$new_modules = [
			'nav-menu',
			'forms',
			'branding',
			'notification-area',
			//'members-carousel'
		];

		if( class_exists('BuddyPress') ){
			if( bp_is_active('members') ) {
				array_push( $new_modules, 'members-grid' );
			}
			if( bp_is_active('groups') ) {
				array_push( $new_modules, 'groups-grid' );
			}
		}

		// if ( class_exists( 'WooCommerce' ) ) {
		// 	array_push( $new_modules, 'woo-cart' );
		// }

		$modules = array_merge( $modules, $new_modules );

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );

			$class_name = str_replace( ' ', '', ucwords( $class_name ) );

			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';

			/** @var Module_Base $class_name */
			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
	}

	/**
	 * @param string $module_name
	 *
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}
}