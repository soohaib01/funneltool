<?php

namespace WPFunnels\Admin\Modules\Steps\Thankyou;

use WPFunnels\Metas\Wpfnl_Step_Meta_keys;
use WPFunnels\Admin\Modules\Steps\Module as Steps;
use WPFunnels\Wpfnl;

class Module extends Steps
{
    protected $validations;

    public $_internal_keys = [];

    protected $prefix = '_wpfnl_thankyou_';

    protected $type = 'thankyou';


    /**
     * init ajax hooks for
     * saving metas
     *
     * @since 1.0.0
     */
    public function init_ajax()
    {
        $this->validations = [
            'logged_in' => true,
            'user_can' => 'manage_options',
        ];
        wp_ajax_helper()->handle('update-thankyou-settings')
            ->with_callback([ $this, 'update_settings' ])
            ->with_validation($this->validations);
    }

    /**
     * get view of the thankyou
     *
     * @since 1.0.0
     */
    public function get_view()
    {
        // TODO: Implement get_view() method.
        $show_settings = filter_input(INPUT_GET, 'show_settings', FILTER_SANITIZE_STRING);
        if ($show_settings == 1) {
            $this->_internal_keys = Wpfnl_Step_Meta_keys::get_meta_keys($this->type);
            $this->set_internal_meta_value();
            require_once WPFNL_DIR . '/admin/modules/steps/thankyou/views/settings.php';
        } else {
            require_once WPFNL_DIR . '/admin/modules/steps/thankyou/views/view.php';
        }
    }


    /**
     * update settings by ajax handler
     *
     * @return array
     * @since 1.0.0
     */
    public function update_settings($payload)
    {
        
        $step_id            = sanitize_text_field($payload['step_id']);
        unset($payload['step_id']);
        $step               = Wpfnl::get_instance()->step_store;
        $step->set_id($step_id);
        $this->_internal_keys = Wpfnl_Step_Meta_keys::get_meta_keys($this->type);
        foreach ($payload as $key => $value) {
            if (array_key_exists($this->prefix.$key, $this->_internal_keys)) {
                $step->update_meta($step_id, $this->prefix.$key, $value);
            }else{
                update_post_meta( $step_id, $this->prefix.$key, $value );
            }
        }
        return [
            'success' => true
        ];
    }
}
