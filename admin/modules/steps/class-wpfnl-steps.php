<?php

namespace WPFunnels\Admin\Modules\Steps;

use WPFunnels\Admin\Module\Steps\Wpfnl_Steps_Factory;
use WPFunnels\Admin\Module\Wpfnl_Admin_Module;
use WPFunnels\Wpfnl;

class Module extends Wpfnl_Admin_Module
{
    protected $steps = [];

    protected $id;

    protected $type;

    protected $step;

    public $step_title;

    public function __construct()
    {
        $this->steps = $this->get_steps();
    }


    public function get_step($name)
    {
        $this->step = Wpfnl_Steps_Factory::build($name);
        return $this->step;
    }

    public function get_steps()
    {
        return [
            'landing',
            'thankyou',
            'checkout',
            'upsell',
            'downsell',
        ];
    }

    public function init($id)
    {
        $this->set_id($id);
        $this->step = Wpfnl::get_instance()->step_store;
        $this->step->read($this->get_id());
        $this->set_type($this->step->get_type());
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_type($type)
    {
        $this->type = $type;
    }

    public function get_type()
    {
        return $this->type;
    }

    /**
     * set internal meta fields for this step
     *
     * @since 1.0.0
     */
    public function set_internal_meta_value()
    {
        $meta_values = [];
        foreach ($this->_internal_keys as $key => $value) {
            $meta_value = $this->step->get_meta($this->get_id(), $key);
            $meta_values[$key] = $meta_value ? $meta_value : $value;
        }
        $this->_internal_keys = $meta_values;
    }



    public function get_internal_metas()
    {
        return $this->_internal_keys;
    }

    /**
     * get meta value by key
     *
     * @param $key
     * @return mixed
     * @since 1.0.0
     */
    public function get_internal_metas_by_key($key)
    {
        if (isset($this->_internal_keys[$key])) {
            return $this->_internal_keys[$key];
        }

        return '';
    }

    public function get_name()
    {
        // TODO: Implement get_name() method.
        return 'steps';
    }

    public function init_ajax()
    {
        // TODO: Implement init_ajax() method.
        wp_ajax_helper()->handle('create-step')
            ->with_callback([ $this, 'create_step' ])
            ->with_validation($this->get_validation_data());

        wp_ajax_helper()->handle('step-edit')
            ->with_callback([ $this, 'step_edit' ])
            ->with_validation($this->get_validation_data());

        wp_ajax_helper()->handle('delete-step')
            ->with_callback([ $this, 'delete_step' ])
            ->with_validation($this->get_validation_data());
    }

    public function get_view()
    {
        // TODO: Implement get_view() method.
    }


    /**
     * create funnel by ajax request
     *
     * @return array
     * @since 1.0.0
     */
    public function create_step($payload)
    {
        $funnel_id = $payload['funnel_id'];
        $step_type = $payload['step_type'];
        $step_name = isset($payload['step_name']) ? $payload['step_name']: $step_type;
        $funnel = Wpfnl::get_instance()->funnel_store;
        $step = Wpfnl::get_instance()->step_store;

        $step_id = $step->create_step( $funnel_id, $step_name, $step_type );
        $step->set_id($step_id);

        if ($step_id && ! is_wp_error($step_id)) {
            $funnel->set_id($funnel_id);
//            $funnel->set_steps_order();
//            $funnel->save_steps_order( $step_id, $step_type, $step_name);
            $step_edit_link = get_edit_post_link($step_id);
            $step_view_link = get_post_permalink($step_id);
            return [
                'success'          		=> true,
                'step_id'          		=> $step_id,
                'step_edit_link'   		=> $step_edit_link,
                'step_view_link'   		=> rtrim( $step_view_link, '/' ),
                'step_title'       		=> $step->get_title(),
                'conversion'       		=> 0,
                'visit'       			=> 0,
                'shouldShowAnalytics' 	=> false,
            ];
        } else {
            return [
                'success' => false,
                'message' => $step_id->get_error_message(),
            ];
        }
    }


    /**
     * Edit step by ajax request
     *
     * @return array
     * @since 1.0.0
     */
    public function step_edit($payload)
    {
        $step_id = $payload['step_id'];
        $input = sanitize_text_field($payload['input']);

        $step_post = [
            'ID'           => $step_id,
            'post_title'   => $input,
        ];

        wp_update_post($step_post);
        return [
            'success' => true,
            'message' => "Step title updated",
        ];
    }


    /**
     * Delete step and all its
     * data
     *
     * @param $payload
     * @return array
     */
    public function delete_step($payload)
    {
        $step_id = sanitize_text_field($payload['step_id']);
        $step_type = get_post_meta($step_id, '_step_type', true);
        if ($step_type) {
          $step = new Wpfnl::$instance->step_store;
          $step->read($step_id);
          $funnel_id = $step->get_funnel_id();
          $response = $step->delete($step_id);
          $funnel = Wpfnl::$instance->funnel_store;
          $funnel->read($funnel_id);
          $first_active_step = $funnel->get_first_step_id();
          if ($response) {
              $redirect_link = add_query_arg(
                  [
                      'page'      => WPFNL_EDIT_FUNNEL_SLUG,
                      'id'        => $funnel_id,
                      'step_id'   => $first_active_step
                  ],
                  admin_url('admin.php')
              );
              return [
                  'success' => true,
                  'message' => "Removed",
              ];
          }
        }
        return [
            'success' => false,
            'message' => "Not Removed",
        ];
    }
}
