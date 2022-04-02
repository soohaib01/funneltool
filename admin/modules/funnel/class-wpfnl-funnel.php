<?php

namespace WPFunnels\Modules\Admin\Funnel;

use WPFunnels\Admin\Module\Wpfnl_Admin_Module;
use WPFunnels\Traits\SingletonTrait;
use WPFunnels\Wpfnl;
use WPFunnels\Wpfnl_functions;
use WPFunnelsPro\Wpfnl_Pro;
use WC_Countries;
class Module extends Wpfnl_Admin_Module
{

    use SingletonTrait;

    private $id;

    protected $funnel;

    protected $step_module = null;

    protected $step_type;


    public function init($id)
    {
        $this->id = $id;
        $this->funnel = Wpfnl::$instance->funnel_store;
        $this->funnel->set_id($id);
    }



    public function init_ajax()
    {
		wp_ajax_helper()->handle('save-steps-order')
			->with_callback([ $this, 'save_steps_order' ])
			->with_validation($this->get_validation_data());

        wp_ajax_helper()->handle('funnel-name-change')
            ->with_callback([ $this, 'funnel_name_change' ])
            ->with_validation($this->get_validation_data());

        wp_ajax_helper()->handle('clone-funnel')
            ->with_callback([ $this, 'clone_funnel' ])
            ->with_validation($this->get_validation_data());

        wp_ajax_helper()->handle('delete-funnel')
            ->with_callback([ $this, 'delete_funnel' ])
            ->with_validation($this->get_validation_data());

		wp_ajax_helper()->handle('update-funnel-status')
			->with_callback([ $this, 'update_funnel_status' ])
			->with_validation($this->get_validation_data());

        wp_ajax_helper()->handle('funnel-drag-order')
            ->with_callback([ $this, 'funnel_drag_order' ])
            ->with_validation($this->get_validation_data());

        wp_ajax_helper()->handle('bulk-delete')
            ->with_callback([ $this, 'delete_marked_funnels' ])
            ->with_validation($this->get_validation_data());

    }


    /**
     * return funnel object
     *
     * @return Wpfnl_Funnel_Store_Data
     * @since 1.0.0
     */
    public function get_funnel()
    {
        return $this->funnel;
    }


    /**
     * show funnel window if the following conditions met
     *      a. if funnel exits
     *          show steps if -
     *              a. step_id exits in url
     *              b. step exits
     *              c. this funnel contains the step
     *  otherwise show 404 page
     *
     *
     * @throws \Exception
     * @since 1.0.0
     */
    public function get_view()
    {
        // TODO: Implement get_view() method.
        if (Wpfnl_functions::check_if_module_exists($this->funnel->get_id())) {
            $step_id = filter_input(INPUT_GET, 'step_id', FILTER_SANITIZE_STRING);
            $this->funnel->read($this->id);
            $funnel = $this->get_funnel();

            if (
                $step_id
                && Wpfnl_functions::check_if_module_exists($step_id)
                && $this->funnel->check_if_step_in_funnel($step_id)
            ) {
                $this->step_type = 'landing';
            } else {
                $step_id = $funnel->get_first_step_id();
                $this->step_type = $funnel->get_first_step_type();
            }
            $is_pro_activated = Wpfnl_functions::is_wpfnl_pro_activated();
            $is_pro_module = Wpfnl_functions::is_pro_module($this->step_type);

            $is_module_registered = Wpfnl_functions::is_module_registered($this->step_type, 'steps', true, $is_pro_module);

            if ($this->step_type) {
                if ($is_pro_activated && $is_module_registered && $is_pro_module) {
                    $this->step_module = Wpfnl_Pro::$instance->module_manager->get_admin_modules($this->step_type);
                    $this->step_module->init($step_id);
                } elseif ($is_module_registered) {
                    $this->step_module = Wpfnl::$instance->module_manager->get_admin_modules($this->step_type);
                    $this->step_module->init($step_id);
                }
            }
            require_once WPFNL_DIR . '/admin/modules/funnel/views/view.php';
        } else {
            require_once WPFNL_DIR . '/admin/partials/404.php';
        }
    }


	/**
	 * save steps order
	 *
	 * @param $payload
	 * @return array
	 *
	 * @since 2.0.5
	 */
    public function save_steps_order( $payload ) {
		$funnel_id 		= isset( $payload['funnelID'] ) ? $payload['funnelID'] : 0;
		$input_node  	= $payload['inputNode'];
		$output_node  	= $payload['outputNode'];
		if( $funnel_id ) {
			$funnel_data = get_post_meta( $funnel_id, 'funnel_data', true );

		}
		return array(
			'success' => true
		);
	}

    public function funnel_drag_order($payload)
    {
        $funnel_id = $payload['funnel_id'];
        $orders = $payload['order'];
        $existing_order = get_post_meta($funnel_id, '_steps_order', true);
        $step_names = apply_filters('wpfunnels_steps', [
            'landing'       => __('Landing', 'wpfnl'),
            'thankyou'      => __('Thank You', 'wpfnl'),
            'checkout'      => __('Checkout', 'wpfnl'),
            'upsell'        => __('Upsell', 'wpfnl'),
            'downsell'      => __('Downsell', 'wpfnl'),
        ]);
        $modified_order = [];
        foreach ($orders as $order) {
            $order = str_replace('setp-list-', '', $order);
            $step_type = get_post_meta($order, '_step_type', true);
            $step_array = [
                'id' => $order,
                'step_type' => $step_type,
                'name' => $step_names[$step_type],
            ];
            $modified_order[] = $step_array;
        }
        $modified_order = array_values(array_filter($modified_order));
        update_post_meta($funnel_id, '_steps_order', $modified_order);
        return [
            'success' => true,
        ];
    }


    /**
     * delete funnel and all the
     * data
     *
     * @param $payload
     * @return array
     * @since 1.0.0
     */
    public function delete_funnel($payload)
    {
        $funnel_id = sanitize_text_field($payload['funnel_id']);
        $funnel = Wpfnl::$instance->funnel_store;
        $funnel->read($funnel_id);

        if ($funnel->get_step_ids()) {
            foreach ($funnel->get_step_ids() as $step_id) {
                $step = Wpfnl::$instance->step_store;
                $step->delete($step_id);
            }
        }
        $response = $funnel->delete($funnel_id);
        if ($response) {
            $redirect_link = add_query_arg(
                [
                    'page' => WPFNL_MAIN_PAGE_SLUG,
                ],
                admin_url('admin.php')
            );
            return [
                'success' => true,
                'redirectUrl' => $redirect_link,
            ];
        }
    }


    public function update_funnel_status( $payload ) {

		if ( ! isset( $payload['funnel_id'] ) ) {
			return array(
				'message' => __( 'No funnel id found', 'wpfnl' )
			);
		}

		$funnel_id 	= sanitize_text_field($payload['funnel_id']);
		$status		= sanitize_text_field($payload['status']);
		$steps 		= get_post_meta( $funnel_id, '_steps_order', true );

		/** update all step status in a funnel */
		if( $steps ) {
			foreach ($steps as $step) {
				$step_data = array(
					'ID'			=> $step['id'],
					'post_status' 	=> $status
				);
				wp_update_post($step_data);
			}
		}

		$funnel_data = array(
			'ID'			=> $funnel_id,
			'post_status' 	=> $status
		);
		wp_update_post($funnel_data);

		return array(
			'success'	=> true,
			'funnel_id'	=> $funnel_id,
			'message'	=> __('Funnel status has been updated.', 'wpfnl'),
			'redirect_url'	=> admin_url('admin.php?page=wp_funnels')
		);
	}


    /**
     * clone funnel and all the steps
     * data
     *
     * @param $payload
     * @return array
     */
    public function clone_funnel($payload)
    {
        $funnel_id = sanitize_text_field($payload['funnel_id']);
        $funnel = Wpfnl::$instance->funnel_store;
        $funnel->read($funnel_id);
        $response = $funnel->clone_funnel();
        
        if ($response && ! is_wp_error($response)) {
            $link = add_query_arg(
                [
                    'page' => 'wp_funnels',
                    'id' => $response,
                ],
                admin_url('admin.php')
            );

            return [
                'success' => true,
                'redirectUrl' => $link,
            ];
        } else {
            return [
                'success' => false,
                'message' => $response->get_error_message(),
            ];
        }
    }


    /**
     * change funnel name
     *
     * @param $payload
     * @return array
     * @since 1.0.0
     */
    public function funnel_name_change($payload)
    {
        $funnel_id 		= sanitize_text_field($payload['funnel_id']);
        $updated_name 	= sanitize_text_field($payload['funnel_name']);
        $funnel 		= Wpfnl::$instance->funnel_store;
        $funnel->set_id($funnel_id);
        $funnel->update_funnel_name($updated_name);
		flush_rewrite_rules();
        return [
            'success' 	=> true,
            'funnelID' 	=> $funnel_id,
            'name' 		=> $updated_name,
        ];
    }

    public function get_name()
    {
        // TODO: Implement get_name() method.
        return 'funnel';
    }

    /**
     * change funnel name
     *
     * @param $payload
     * @return array
     * @since 1.0.0
     */
    public function delete_marked_funnels( $payload ) {

        if (isset($payload['ids'])) {
          $data_array = $payload['ids'];
          foreach ($data_array as $data_key => $data_value) {
            $funnel_id = sanitize_text_field($data_value);
            $funnel = Wpfnl::$instance->funnel_store;
            $funnel->read($funnel_id);

            if ($funnel->get_step_ids()) {
                foreach ($funnel->get_step_ids() as $step_id) {
                    $step = Wpfnl::$instance->step_store;
                    $step->delete($step_id);
                }
            }
            $funnel->delete($funnel_id);
          }
        }
        die();
    }
}
