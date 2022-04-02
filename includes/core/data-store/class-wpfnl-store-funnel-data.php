<?php
namespace WPFunnels\Data_Store;

use WPFunnels\Wpfnl_functions;

class Wpfnl_Funnel_Store_Data extends Wpfnl_Abstract_Store_data implements Wpfnl_Data_Store
{
    protected $id = 0;

    protected $funnel_name;

    protected $published_date;

    protected $data;

    protected $steps_order = [];

    protected $nr_steps = 0;

    protected $status;

    protected $step_ids = [];

    protected $first_step_id = 0;

    protected $first_step_type;

    protected $current_step_id;

    /**
     * create funnel. This will create funnel without steps
     *
     * @param string $funnel_name
     * @return int|\WP_Error
     *
     * @since 1.0.0
     */
    public function create( $funnel_name = '' )
    {
        // TODO: Implement create() method.
        $funnel_id = wp_insert_post(
            apply_filters(
                'wpfunnels/wpfunnels_new_funnel_params',
                [
                    'post_type'     => WPFNL_FUNNELS_POST_TYPE,
                    'post_status'   => 'publish',
                    'post_title'    => $funnel_name ? wp_strip_all_tags( $funnel_name ) : 'New Funnel',
                ]
            ),
            true
        );
        if ($funnel_id && ! is_wp_error($funnel_id)) {
            $this->set_id($funnel_id);
        }

        do_action('wpfunnels_after_funnel_creation');
        return $funnel_id;
    }


    /**
     * Clone funnel with all the
     * steps and meta data
     * derived from https://rudrastyh.com/wordpress/duplicate-post.html
     * @since 1.0.0
     */
    public function clone_funnel()
    {
        global $wpdb;
        $funnel_id = wp_insert_post(
            apply_filters(
                'wpfunnels/wpfunnels_new_funnel_params',
                [
                    'post_title'    => wp_strip_all_tags($this->get_funnel_name()).' - Copy',
                    'post_type'     => WPFNL_FUNNELS_POST_TYPE,
                    'post_status'   => 'publish',
                ]
            ),
            true
        );

        $parent_id 						= $this->get_id();
        $funnel_data 					= $this->get_meta($this->get_id(), 'funnel_data');
        $funnel_identifier 				= $this->get_meta($this->get_id(), 'funnel_identifier');
        $funnel_identifier_to_string	= preg_replace('/\: *([0-9]+\.?[0-9e+\-]*)/', ':"\\1"', $funnel_identifier);
        $funnel_identifier_json_to_data = json_decode($funnel_identifier_to_string, true);
        $exclude_meta 					= array( '_is_imported', '_steps_order', '_steps', 'funnel_data' );
        $compoare_step_ids = array();
        foreach ($funnel_identifier_json_to_data as $funnel_identifier_key => $funnel_identifier_value) {
          	$identifier_data = get_post_meta( $parent_id, $funnel_identifier_value, true );
          	if ($identifier_data) {
				$exclude_meta[] = $funnel_identifier_value;
            	$this->update_meta($funnel_id, $funnel_identifier_value, $identifier_data);
          	}
        }

		$this->duplicate_all_meta( $this->get_id(), $funnel_id, $exclude_meta );
        $this->update_meta($funnel_id, 'funnel_data', $funnel_data);
        $this->update_meta($funnel_id, 'funnel_identifier', $funnel_identifier);


        if ($funnel_id && ! is_wp_error($funnel_id)) {
            $step_order = $this->get_order();
            $this->set_step_ids();
            $_new_step_ids = [];
            foreach ($step_order as $order) {
                if (Wpfnl_functions::check_if_module_exists($order['id'])) {
                    if ($this->check_if_step_in_funnel($order['id'])) {
                        $sql_query_sel = [];
                        $_step_id = $order['id'];
                        $title = get_the_title($_step_id);
                        $page_template = get_post_meta($_step_id, '_wp_page_template', true);
                        $post_content = get_post_field('post_content', $_step_id);

                        $step = new Wpfnl_Steps_Store_Data();
                        $step_id = $step->create_step($funnel_id, $title, $order['step_type'], $post_content, true);

                        $compoare_step_ids[$_step_id] = $step_id;
                        $builder = Wpfnl_functions::get_builder_type();

                        $_new_step_ids[] = [
                            'id'        => $step_id,
                            'step_type' => $order['step_type'],
                            'name'      => $order['name'],
                        ];
                        $step->update_meta($step_id, '_funnel_id', $funnel_id);
                        $this->duplicate_all_meta( $_step_id, $step_id, array('_funnel_id') );

                        /**
                         * save the new step information on funnel data and funnel identifier.
                         * This is required to show steps in funnel canvas
                         */

                        $this->update_step_id_in_funnel_data_and_identifier($_step_id, $step_id, $funnel_id);

                        delete_post_meta($step_id, '_wp_page_template');
                        $step->update_meta($step_id, '_wp_page_template', $page_template);
                        do_action('wpfunnels_after_step_import', $step_id, $builder);
                    }
                }
            }
            $_new_step_ids = array_values(array_filter($_new_step_ids));
            $this->update_meta($funnel_id, '_steps_order', $_new_step_ids);

        }
        //duplicate funnel automation event settings
        $this->duplicate_automation_event_settings( $parent_id , $funnel_id , $compoare_step_ids );

        return $funnel_id;
    }


	/**
	 * @param $parent_id
	 * @param $post_id
	 * @param array $exclude_meta
	 */
    private function duplicate_all_meta( $parent_id, $post_id, $exclude_meta = array() ) {
    	global $wpdb;
		$exclude_sql = '';
    	if( !empty($exclude_meta) ) {
			$metas 			= implode("', '",$exclude_meta );
			$exclude_sql 	= "AND meta_key NOT IN ('".$metas."')";
		}
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE (post_id=$parent_id {$exclude_sql})");
    	if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
                
				if( $meta_key == '_wp_old_slug' ) continue;
				if( $meta_key == 'funnel_automation_data' ) continue;

				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $post_id, '$meta_key', '$meta_value'";
                
                $meta_value = get_post_meta( $parent_id, $meta_key,true );
                update_post_meta($post_id, $meta_key, $meta_value);
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
            
		}
    }


    /**
     * duplicate automation event settings
     *
     * @param String $parent_id
     * @param String $post_id
     * @param Array $compoare_step_ids
     *
     */
    private function duplicate_automation_event_settings( $parent_id , $post_id, $compoare_step_ids ){

        $prev_settings = get_post_meta( $parent_id , 'funnel_automation_data', true );

        if( $prev_settings ){
            foreach( $prev_settings as $key => $settings ){
                foreach( $settings['triggers'] as $index => $trigger ){
                    if( $prev_settings[$key]['triggers'][$index][0]['type'] == 'offer' ){

                        $prev_settings[$key]['triggers'][$index][0]['event']  = str_replace($prev_settings[$key]['triggers'][$index][0]['stepID'],$compoare_step_ids[$prev_settings[$key]['triggers'][$index][0]['stepID']],$prev_settings[$key]['triggers'][$index][0]['event']);
                        $prev_settings[$key]['triggers'][$index][0]['stepID'] = $compoare_step_ids[$prev_settings[$key]['triggers'][$index][0]['stepID']];
                    }
                }
            }
            update_post_meta( $post_id, 'funnel_automation_data', $prev_settings );
        }
    }


    /**
     * update funnel data and funnel
     * window for canvas
     *
     * @param $prev_step_id
     * @param $new_step
     * @param $funnel_id
     *
     * @since 2.0.0
     */
    public function update_step_id_in_funnel_data_and_identifier($prev_step_id, $new_step, $funnel_id)
    {
        $funnel_identifier = array();
        $funnel_json = get_post_meta($funnel_id, 'funnel_data', true);
        $funnel_data = array();
        if ($funnel_json) {
            $funnel_data = $funnel_json;
            $node_data = $funnel_data['drawflow']['Home']['data'];
            foreach ($node_data as $node_key => $node_value) {
                if(isset($node_value['data']['step_id'])) {
                    if ($node_value['data']['step_id'] == $prev_step_id) {
                        $post_edit_link = base64_encode(get_edit_post_link($new_step));
                        $post_view_link = base64_encode(get_post_permalink($new_step));
                        $funnel_data['drawflow']['Home']['data'][$node_key]['data']['step_id'] = $new_step;
                        $funnel_data['drawflow']['Home']['data'][$node_key]['data']['step_edit_link'] = $post_edit_link;
                        $funnel_data['drawflow']['Home']['data'][$node_key]['data']['step_view_link'] = $post_view_link;
                        $funnel_data['drawflow']['Home']['data'][$node_key]['html'] = $node_value['data']['step_type'] . $new_step;
                        $funnel_identifier[$node_value['id']] = $new_step;
                    } else {
                        if ($node_value['data']['step_type'] != 'conditional') {
                            $funnel_identifier[$node_value['id']] = $node_value['data']['step_id'];
                        } else {
                            $funnel_identifier[$node_value['id']] = $node_value['data']['node_identifier'];
                        }
                    }
                }
            }
        }
        update_post_meta($funnel_id, 'funnel_data', $funnel_data);
        if ($funnel_identifier) {
            $funnel_identifier_json = json_encode($funnel_identifier, JSON_UNESCAPED_SLASHES);
            update_post_meta($funnel_id, 'funnel_identifier', $funnel_identifier_json);
        }
    }


    /**
     * Delete funnel with all its
     * data
     *
     * @param $id
     * @return bool|void
     */
    public function delete($id)
    {
        wp_delete_post($id);
        $this->set_step_ids();
        foreach ($this->step_ids as $step_id) {
            wp_delete_post($step_id);
        }
        return true;
    }


    public function read($id)
    {
        // TODO: Implement read() method.
        $funnel = get_post($id);
        if ($funnel) {
            $this->set_data($funnel);
        } else {
            $this->error('funnel_not_found', __('Invalid Funnel', 'wpfnl'));
        }
    }


    /**
     * Init all required data for a complete
     * funnel object
     *
     * @param \WP_Post $funnel
     * @since 1.0.0
     */
    public function set_data(\WP_Post $funnel)
    {
        $step_id = filter_input(INPUT_GET, 'step_id', FILTER_SANITIZE_STRING);
        $this->current_step_id = $step_id;
        $this->set_id($funnel->ID);
        $this->funnel_name = $funnel->post_title ? $funnel->post_title : 'No title';
        $this->status = Wpfnl_functions::get_formatted_status($funnel->post_status);
        if (Wpfnl_functions::validate_date( $funnel->post_date )) {
            $this->published_date = Wpfnl_functions::get_formatted_date($funnel->post_date);
        }
        $this->set_step_ids();
        $this->set_steps_order();
        $this->set_fisrt_step_info();
    }


    /**
     * all the steps ids within
     * the funnel
     *
     * @since 1.0.0
     */
    public function set_step_ids()
    {
        $this->step_ids = get_posts(
            [
                'numberposts'   => -1,
                'post_type'     => WPFNL_STEPS_POST_TYPE,
                'post_status'   => array('publish', 'draft'),
                'fields'        => 'ids',
                'meta_query'    => [
                    [
                        'key'   => '_funnel_id',
                        'value' => $this->id,
                    ]
                ]
            ]
        );
    }


    /**
     * set step order for funnel
     *
     * @since 1.0.0
     */
    public function set_steps_order()
    {
        $steps_order = get_post_meta($this->id, '_steps_order', true);
        if ($steps_order) {
            $_steps_order = Wpfnl_functions::unserialize_array_data($steps_order);
            // check if step exits and status is published
            $this->steps_order = array_filter($_steps_order, function ($item) {
                if (isset($item['id']) && Wpfnl_functions::check_if_module_exists($item['id'])) {
                    return true;
                }
                return false;
            });
            $this->nr_steps = count($this->steps_order);
        } else {
            $this->steps_order = [];
        }
    }


    public function get_steps_order() {
    	return $this->steps_order;
	}

    /**
     * reinit the step order of the funnel
     * if any step is deleted
     *
     * @since 1.0.0
     */
    public function reinitialize_steps_order()
    {
        if (count($this->get_order())) {
            $_steps_order = Wpfnl_functions::unserialize_array_data($this->get_order());
            $steps_order = array();
            // check if step exits and status is published
            if($_steps_order) {
                foreach ($_steps_order as $key => $step) {
                    if (Wpfnl_functions::check_if_module_exists($step['id'])) {
                        $steps_order[$key] = $step;
                    }
                }
            }

            $this->steps_order = $steps_order;
            $this->update_meta($this->get_id(), '_steps_order', $steps_order);
        }
    }


    /**
     * save store order of the funnel
     *
     * @param $step_id
     * @param $step_type
     * @param string $title
     * @since 1.0.0
     */
    public function save_steps_order($step_id, $step_type, $title='')
    {
        $this->steps_order[] = [
            'id' => $step_id,
            'step_type' => $step_type,
            'name' => get_the_title($step_id),
        ];
        $this->update_meta($this->get_id(), '_steps_order', $this->steps_order);
    }


    /**
     * get first step info
     *
     * @since 1.0.0
     */
    public function get_active_step_type($step_id)
    {

        if (count( $this->steps_order )) {
            $_first_step_index = Wpfnl_functions::array_search_by_value($step_id, 'id', $this->steps_order);
            $this->first_step_type = $this->steps_order[$_first_step_index]['step_type'];
        }
        return $this->first_step_type;
    }


    /**
     * set the first step of the
     * funnel if any exists
     *
     * @since 1.0.0
     */
    public function set_fisrt_step_info()
    {
        if (count($this->steps_order)) {
            $first_step = reset($this->steps_order);
            $this->first_step_type = $first_step['step_type'];
            $this->first_step_id = $first_step['id'];
        }
    }


    public function get_first_step_type()
    {
        return $this->first_step_type;
    }


    public function get_first_step_id()
    {
        return $this->first_step_id;
    }


    /**
     * update funnel name
     *
     * @param $name
     * @since 1.0.0
     */
    public function update_funnel_name($name)
    {
        $funnel = [
            'ID'           => $this->id,
            'post_title'   => $name,
        ];
        wp_update_post($funnel);
    }



    public function get_id()
    {
        return $this->id;
    }


    public function get_funnel_name()
    {
        return $this->funnel_name;
    }


    public function get_published_date()
    {
        return $this->published_date;
    }


    public function get_data()
    {
        return $this->data;
    }


    public function get_order()
    {
        return $this->steps_order;
    }


    public function get_total_steps()
    {
        return $this->nr_steps;
    }


    public function get_status()
    {
        return $this->status;
    }

    public function get_step_ids()
    {
        return $this->step_ids;
    }

    public function get_current_step_id()
    {
        return $this->current_step_id;
    }


    public function check_if_step_in_funnel($step_id)
    {
        return in_array($step_id, $this->step_ids);
    }
}
