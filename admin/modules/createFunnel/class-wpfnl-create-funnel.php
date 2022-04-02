<?php

namespace WPFunnels\Modules\Admin\CreateFunnel;

use WPFunnels\Admin\Module\Wpfnl_Admin_Module;
use WPFunnels\Traits\SingletonTrait;
use WPFunnels\Wpfnl;
use WPFunnels\Wpfnl_functions;

class Module extends Wpfnl_Admin_Module
{
    use SingletonTrait;

    private $builder;

    public function get_view()
    {
        // TODO: Implement get_view() method.
        $this->builder = Wpfnl_functions::get_builder_type();
        if (Wpfnl_functions::is_builder_active($this->builder)) {
            require_once WPFNL_DIR . '/admin/modules/createFunnel/views/view.php';
        } else {
            require_once WPFNL_DIR . '/admin/modules/createFunnel/views/builder-not-activated.php';
        }
    }

    public function init_ajax()
    {
        // TODO: Implement init_ajax() method.
        wp_ajax_helper()->handle('create-funnel')
            ->with_callback([ $this, 'create_funnel' ])
            ->with_validation($this->get_validation_data());
    }


    /**
     * create funnel by ajax request
     *
     * @return array
     * @since 1.0.0
     */
    public function create_funnel( $payload )
    {
        $funnel = Wpfnl::$instance->funnel_store;
        $funnel_id = $funnel->create($payload['funnelName']);
        $link = add_query_arg(
            [
                'page' => 'edit_funnel',
                'id' => $funnel_id,
            ],
            admin_url('admin.php')
        );

        return [
            'success' => true,
            'funnelID' => $funnel_id,
            'redirectUrl' => $link,
        ];
    }

    public function get_name()
    {
        // TODO: Implement get_name() method.
        return 'create-funnel';
    }
}
