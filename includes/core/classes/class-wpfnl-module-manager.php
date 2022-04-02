<?php

namespace WPFunnels\Modules;

use WPFunnels\Base_Manager;

class Wpfnl_Modules_Manager extends Base_Manager {

    private $admin_modules = [];

    private $frontend_modules = [];

    public function __construct() {
        $modules_namespace_prefix = $this->get_namespace_prefix();
        $modules = $this->get_modules_names();
        $admin_modules = $modules['admin'];
        $frontend_modules = $modules['frontend'];

        foreach ( $admin_modules as $key => $module_name ) {
            if( $key === 'steps') {
                $class_name = str_replace('-', ' ', $key);
                $class_name = str_replace(' ', '', ucwords($class_name));
                $class_name = $modules_namespace_prefix . '\\Admin\\Modules\\' . $class_name . '\Module';
                $this->admin_modules[$key] = $class_name::instance();
                $this->admin_modules[$key]->init_ajax();
                foreach ($module_name as $step) {
                    $class_name = str_replace('-', ' ', $step);
                    $class_name = str_replace(' ', '', ucwords($class_name));
                    $class_name = $modules_namespace_prefix . '\\Admin\\Modules\\Steps\\' . $class_name . '\Module';
                    $this->admin_modules[$step] = $class_name::instance();
                    $this->admin_modules[$step]->init_ajax();
                }
            }else {
                $class_name = str_replace('-', ' ', $module_name);
                $class_name = str_replace(' ', '', ucwords($class_name));
                $class_name = $modules_namespace_prefix . '\\Modules\\Admin\\' . $class_name . '\Module';
                $this->admin_modules[$module_name] = $class_name::instance();
                $this->admin_modules[$module_name]->init_ajax();
            }
        }

        foreach ( $frontend_modules as $module_name ) {
            $class_name = str_replace( '-', ' ', $module_name );
            $class_name = str_replace( ' ', '', ucwords( $class_name ) );
            $class_name = $modules_namespace_prefix . '\\Modules\\Frontend\\' . $class_name . '\Module';
            $this->frontend_modules[ $module_name ] = $class_name::instance();
        }
    }

    public function get_modules_names() {
        return [
            'admin' => array(
                'funnels',
                'funnel',
                'settings',
                'steps' => array(
                    'landing',
                    'thankyou',
                    'checkout',
                ),
                'create-funnel',
                'product',
                'discount',
				'category',
            ),
            'frontend' => array(
                'checkout',
                'thankyou'
            ),
        ];
    }

    public function get_admin_modules( $module_name ) {
        if ( $module_name ) {
            if ( isset( $this->admin_modules[ $module_name ] ) ) {
                return $this->admin_modules[ $module_name ];
            }

            return null;
        }

        return $this->admin_modules;
    }

    public function get_frontend_modules( $module_name ) {
        if ( $module_name ) {
            if ( isset( $this->frontend_modules[ $module_name ] ) ) {
                return $this->frontend_modules[ $module_name ];
            }
            return null;
        }
        return $this->frontend_modules;
    }

}
