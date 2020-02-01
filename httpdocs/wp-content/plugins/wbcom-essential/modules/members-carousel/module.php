<?php
namespace WbcomElementorAddons\Modules\MembersCarousel;

use WbcomElementorAddons\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'MembersCarousel',
		];
	}

	public function get_name() {
		return 'wbcom-members-carousel';
	}
}
