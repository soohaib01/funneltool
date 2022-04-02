<?php

namespace WPFunnels\Widgets;

use WPFunnels\Base_Manager;
use WPFunnels\Traits\SingletonTrait;
use WPFunnels\Widgets\Gutenberg\BlockTypes\Gutenberg_Editor;
use WPFunnels\Wpfnl_functions;

class Wpfnl_Widgets_Manager extends Base_Manager
{

	use SingletonTrait;

	private $widgets = [];

	public function init()
	{
		$modules_namespace_prefix 	= $this->get_namespace_prefix();
		$builders 					= $this->get_builders();

		foreach ($builders as $builder) {
			$class_name = str_replace('-', ' ', $builder);
			$class_name = str_replace(' ', '', ucwords($class_name));
			$class_name = $modules_namespace_prefix . '\\Widgets\\' . $class_name . '\Manager';
			$this->widgets[$builder] = $class_name::instance();
		}

	}


	public function get_builders()
	{
		return apply_filters('wpfunnels/builders', array(
			'elementor',
			'gutenberg',
			'diviModules',
			'oxygen',
		));
	}
}
