<?php
namespace WPFunnels\Admin\Module;

abstract class Wpfnl_Admin_Module
{
    protected static $_instances = [];

    abstract public function get_name();

    abstract public function init_ajax();

    abstract public function get_view();

    public function get_validation_data()
    {
        return [
            'logged_in' => true,
            'user_can' => 'manage_options',
        ];
    }

    public static function instance()
    {
        $class_name = static::class_name();

        if (empty(static::$_instances[ $class_name ])) {
            static::$_instances[ $class_name ] = new static();
        }

        return static::$_instances[ $class_name ];
    }

    public static function class_name()
    {
        return get_called_class();
    }

    final protected function get_assets_url($file_name, $file_extension, $relative_url = null, $add_min_suffix = 'default')
    {
        static $is_test_mode = null;

        if (null === $is_test_mode) {
            $is_test_mode = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG || defined('ELEMENTOR_TESTS') && ELEMENTOR_TESTS;
        }

        if (! $relative_url) {
            $relative_url = $this->get_assets_relative_url() . $file_extension . '/';
        }

        $url = $this->get_assets_base_url() . $relative_url . $file_name;

        if ('default' === $add_min_suffix) {
            $add_min_suffix = ! $is_test_mode;
        }

        if ($add_min_suffix) {
            $url .= '.min';
        }

        return $url . '.' . $file_extension;
    }


    final protected function get_js_assets_url($file_name, $relative_url = null, $add_min_suffix = 'default')
    {
        return $this->get_assets_url($file_name, 'js', $relative_url, $add_min_suffix);
    }

    final protected function get_css_assets_url($file_name, $relative_url = null, $add_min_suffix = 'default', $add_direction_suffix = false)
    {
        static $direction_suffix = null;

        if (! $direction_suffix) {
            $direction_suffix = is_rtl() ? '-rtl' : '';
        }

        if ($add_direction_suffix) {
            $file_name .= $direction_suffix;
        }

        return $this->get_assets_url($file_name, 'css', $relative_url, $add_min_suffix);
    }

    /**
     * Get assets base url
     *
     * @since 2.6.0
     * @access protected
     *
     * @return string
     */
    protected function get_assets_base_url()
    {
        return ELEMENTOR_URL;
    }

    /**
     * Get assets relative url
     *
     * @since 2.3.0
     * @access protected
     *
     * @return string
     */
    protected function get_assets_relative_url()
    {
        return 'assets/';
    }
}
