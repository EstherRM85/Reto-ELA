<?php
namespace WbcomElementorAddons\Modules\GroupsGrid;

use WbcomElementorAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'GroupsGrid',
		];
	}

	public function get_name() {
		return 'wbcom-groups-grid';
	}
}