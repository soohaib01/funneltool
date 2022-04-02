<?php
namespace WPFunnels\Frontend\Module;

use WPFunnels\Wpfnl_functions;

abstract class Wpfnl_Frontend_Module
{
    protected static $_instances = [];

    public static function instance() {

        $class_name = static::class_name();

        if ( empty( static::$_instances[ $class_name ] ) ) {
            static::$_instances[ $class_name ] = new static();
        }

        return static::$_instances[ $class_name ];
    }

    public static function class_name() {
        return get_called_class();
    }
}
