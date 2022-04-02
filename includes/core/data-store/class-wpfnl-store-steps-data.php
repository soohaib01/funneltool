<?php

namespace WPFunnels\Data_Store;

use WPFunnels\Metas\Wpfnl_Step_Meta_keys;
use WPFunnels\Wpfnl;
use WPFunnels\Wpfnl_functions;

class Wpfnl_Steps_Store_Data extends Wpfnl_Abstract_Store_data implements Wpfnl_Data_Store
{
    protected $id;

    protected $internal_keys = [];

    protected $funnel_id;

    protected $type;

    protected $step_title;

    protected $meta_values;


    public function create()
    {
        // TODO: Implement create() method.
    }


    /**
     * create individual steps
     *
     * @param $funnel_id
     * @param string $title
     * @param string $type
     * @param string $post_content
     * @param bool $clone
     * @return int|\WP_Error
     */
    public function create_step($funnel_id, $title = 'Landing', $type = 'landing', $post_content = '', $clone = false)
    {

        $step_id = wp_insert_post(
            apply_filters(
                'wpfunnels/wpfunnels_new_step_params',
                [
                    'post_type' => WPFNL_STEPS_POST_TYPE,
                    'post_title' => wp_strip_all_tags($title),
                    'post_content' => $post_content,
                    'post_status' => 'publish',
                ]
            ),
            true
        );
        if ($type == 'checkout' && !$clone) {
            $title = 'funnel-'.$title;
            wp_update_post(
                array (
                    'ID'        => $step_id,
                    'post_name' => sanitize_title($title)
                )
            );
        }
        if ($step_id && !is_wp_error($step_id)) {
            $this->funnel_id = $funnel_id;
            $this->type = $type;
            $this->set_id($step_id);
            $this->set_default_props();

        }

        do_action('wpfunnels_after_step_creation');
        update_post_meta($step_id, '_wp_page_template', 'wpfunnels_default');
        return $step_id;
    }


    public function set_default_props()
    {
        $this->set_keys();
        foreach ($this->internal_keys as $meta_key => $value) {
            $this->update_meta($this->id, $meta_key, $value);
        }
    }


    /**
     * @param \WP_Post $step
     */
    public function set_data(\WP_Post $step)
    {
        // TODO: Implement set_data() method.
        $this->set_id($step->ID);
        $this->step_title = $step->post_title;
        $this->funnel_id = $this->get_meta($this->id, '_funnel_id');
        $this->type = $this->get_meta($this->id, '_step_type');
        $meta_keys = Wpfnl_Step_Meta_keys::get_meta_keys($this->type);
        foreach ($meta_keys as $meta_key => $value) {
            $this->internal_keys[$meta_key] = $this->get_meta($this->id, $meta_key);
        }
    }


    /**
     * read the step and its meta data
     * from DB
     *
     * @param $id
     * @since 1.0.0
     */
    public function read($id)
    {
        // TODO: Implement read() method.
        $step = get_post($id);
        if ($step) {
            $this->set_data($step);
        }
    }


    /**
     * delete step from DB
     *
     * @param $id
     * @return bool|void
     */
    public function delete($id)
    {
//        $associate_funnel_id = get_post_meta($id, '_funnel_id', true);
//        $funnel = Wpfnl::$instance->funnel_store;
//        $funnel->read($associate_funnel_id);
//        $steps_order = $funnel->get_order();
//        if (count($steps_order)) {
//            $targeted_step_key = Wpfnl_functions::array_search_by_value($id, 'id', $steps_order);
//            wp_delete_post($steps_order[$targeted_step_key]['id']);
//            $funnel->reinitialize_steps_order();
//        }
		wp_delete_post($id);
        return true;
    }


    /**
     * set step basic keys as meta on
     * DB for further use
     *
     * @since 1.0.0
     */
    public function set_keys()
    {
        $this->internal_keys['_step_type'] = $this->type;
        $this->internal_keys['_funnel_id'] = $this->funnel_id;
    }


    /**
     * get title of the step
     *
     * @return string
     * @since 1.0.0
     */
    public function get_title()
    {
        $title = $this->step_title;
        if ($title == '') {
            $title = $this->get_type();
        }
        return $title;
    }

    public function get_funnel_id()
    {
        return $this->funnel_id;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function get_next_step($order, $current)
    {
        $current_key = array_search($current, array_column($order, 'id'));
        if (isset($order[$current_key + 1])) {
            $next_key = $order[$current_key + 1];
            $next_id = $next_key['id'];
            return $next_id;
        } else {
            return false;
        }
    }


    /**
     * get meta value by key
     *
     * @param $key
     * @return mixed
     */
    public function get_internal_metas_by_key($key)
    {
        if (isset($this->internal_keys[$key])) {
            return $this->internal_keys[$key];
        }
        return null;
    }


    /**
     * import post metas
     *
     * @param $step_id
     * @param array $post_metas
     * @since 1.0.0
     */
    public function import_metas($step_id, $post_metas = [])
    {
        foreach ($post_metas as $meta_key => $meta_value) {
            $meta_value = isset($meta_value[0]) ? $meta_value[0] : '';
            if ($meta_value) {
                if (is_serialized($meta_value, true)) {
                    $raw_data = maybe_unserialize(stripslashes($meta_value));
                } elseif (is_array($meta_value)) {
                    $raw_data = json_decode(stripslashes($meta_value), true);
                } else {
                    $raw_data = $meta_value;
                }

                if ( '_elementor_data' === $meta_key ) {
                    if (is_array($raw_data)) {
                        $raw_data = wp_slash(wp_json_encode($raw_data));
                    } else {
                        $raw_data = wp_slash($raw_data);
                    }
                }

                if ($meta_key != 'order-bump-settings' && $meta_key != '_wpfnl_checkout_products' && $meta_key != 'order-bump') {
                  $this->update_meta($step_id, $meta_key, $raw_data);
                }
            }
        }
        $this->update_meta($step_id, '_is_imported', 'yes');
    }
}
