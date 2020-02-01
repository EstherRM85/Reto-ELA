<?php
namespace WbcomElementorAddons\Modules\Forms;

use WbcomElementorAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {
	
	public function get_name() {
		return 'wbcom-forms';
	}

	public function get_widgets() {
		return [
			'Login',
			'Registration'
		];
	}
}
