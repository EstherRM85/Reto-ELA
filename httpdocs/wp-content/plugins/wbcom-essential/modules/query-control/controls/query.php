<?php
namespace WbcomElementorAddons\Modules\QueryControl\Controls;

use Elementor\Control_Select2;
use WbcomElementorAddons\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Query extends Control_Select2 {

	public function get_type() {
		return Module::QUERY_CONTROL_ID;
	}
}
