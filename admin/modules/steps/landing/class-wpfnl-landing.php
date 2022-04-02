<?php

namespace WPFunnels\Admin\Modules\Steps\Landing;

use WPFunnels\Admin\Modules\Steps\Module as Steps;

class Module extends Steps
{
    protected $_internal_keys = [

    ];

    /**
     * get view of the landing page settings module
     *
     * @since 1.0.0
     */
    public function get_view()
    {
        // TODO: Implement get_view() method.
        $show_settings = filter_input(INPUT_GET, 'show_settings', FILTER_SANITIZE_STRING);
        if ($show_settings == 1) {
            require_once WPFNL_DIR . '/admin/modules/steps/landing/views/settings.php';
        } else {
            require_once WPFNL_DIR . '/admin/modules/steps/landing/views/view.php';
        }
    }

    public function get_name()
    {
        // TODO: Implement get_name() method.
        return 'landing';
    }

    public function init_ajax()
    {
        // TODO: Implement init_ajax() method.
    }
}
