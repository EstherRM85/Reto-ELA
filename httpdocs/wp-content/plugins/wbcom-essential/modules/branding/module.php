<?php
namespace WbcomElementorAddons\Modules\Branding;

use WbcomElementorAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'wbcom-branding';
	}
	
	public function get_widgets() {
		return [
			'Branding',
		];
	}
}
