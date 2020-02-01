<?php
namespace WbcomElementorAddons\Modules\MembersGrid;

use WbcomElementorAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'MembersGrid',
		];
	}

	public function get_name() {
		return 'wbcom-members-grid';
	}
}