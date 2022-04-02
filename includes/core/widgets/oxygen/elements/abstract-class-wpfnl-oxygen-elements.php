<?php

namespace WPFunnels\Widgets\Oxygen;

use OxyEl;

/**
 * Class Elements
 * @package WPFunnels\Widgets\Oxygen
 */
class Elements extends OxyEl {

	CONST TAB_SLUG = 'wpf_tab';

	CONST OTHER = 'other';

	public function button_place()
	{
		return self::TAB_SLUG . "::" . self::OTHER;
	}


	public function is_builder_mode() {
		if( empty($_GET) ) {
			return false;
		}

		if( $_GET['action'] ) {
			if( 'oxy_render_oxy-next-step-button' === $_GET['action'] ) {
				return true;
			}
		}

		return false;
	}

}
