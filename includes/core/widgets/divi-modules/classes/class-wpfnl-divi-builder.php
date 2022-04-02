<?php
/**
 * Divi page builder compatibility class
 */

namespace WPFunnels\Widgets\DiviModules;

use WPFunnels\Traits\SingletonTrait;

/**
 * Class Wpfnl_Divi_Editor
 * @package WPFunnels\Widgets\DiviModules
 *
 * @since 2.2.6
 */
class Wpfnl_Divi_Editor {

	use SingletonTrait;

	public function __construct() {
		$this->divi_compatibility();
	}


	private function divi_compatibility() {
		add_filter( 'wpfunnels/page_container_atts', array( $this, 'add_id_to_wpf_page_container' ) );
		add_filter( 'et_builder_plugin_compat_path_wpfunnels', array( $this, 'register_et_builder_compatibility_class' ), 10, 2 );
	}


	/**
	 * add id to wpf page container which is needed. Because divi apply styles
	 * to modules based on this id
	 *
	 * @param $atts
	 * @return array
	 *
	 * @since 2.2.6
	 */
	public function add_id_to_wpf_page_container($atts) {
		$atts['id'] = 'page-container';
		return $atts;
	}


	/**
	 *
	 * register ET builder compatibility class
	 *
	 * @param $path
	 * @param $plugin_name
	 * @return string
	 *
	 * @since 2.3.4
	 */
	public function register_et_builder_compatibility_class( $path, $plugin_name ) {
		if( 'wpfunnels' === $plugin_name ) {
			return WPFNL_DIR . 'includes/core/widgets/divi-modules/classes/class-wpfnl-divi-theme-compatibility.php';
		}
		return $path;
	}
}
