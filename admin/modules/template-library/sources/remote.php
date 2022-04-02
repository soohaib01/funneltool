<?php

namespace WPFunnels\TemplateLibrary;

use WPFunnels\API;
use WPFunnels\Rest\Controllers\TemplateLibraryController;
use WPFunnels\Wpfnl;
use WPFunnels\Wpfnl_functions;
use function cli\err;

class Wpfnl_Source_Remote extends Wpfnl_Source_Base
{
    public function get_source()
    {
        // TODO: Implement get_source() method.
        return 'remote';
    }

    public function get_funnels($arg = [])
    {
        // TODO: Implement get_funnels() method.
        return API::get_funnels_data($arg);
    }

    public function get_funnel($template_id)
    {
        // TODO: Implement get_funnel() method.
    }

    public function get_data(array $args)
    {
        // TODO: Implement get_data() method.
    }

    public function import_funnel($args = [])
    {
        // TODO: Implement import_funnel() method.
        $funnel = Wpfnl::$instance->funnel_store;
        $funnel_id = $funnel->create();
        $funnel->update_meta($funnel_id, '_is_imported', 'yes');
        $funnel_title = $args['name'];
        $params = array(
            'ID' => $funnel_id,
            'post_title' => $funnel_title,
            'post_name' => sanitize_title($funnel_title),
        );
        wp_update_post($params);
        return [
            'success' => true,
            'funnelID' => $funnel_id,
        ];
    }

    public function import_step( $args = [] )
    {
        // TODO: Implement import_step() method.
        if (empty($args['funnelID'])) {
            return [
                'success' => true,
                'message' => __('No funnel id found', 'wpfnl'),
            ];
        }
        do_action('wpfunnel_step_import_start');
        $response = TemplateLibraryController::get_step($args['step']['id']);

        $title = $response['title'];
        $post_content = $response['content'];
        $post_metas = $response['post_meta'];

        $builder = Wpfnl_functions::get_builder_type();

        $step = Wpfnl::$instance->step_store;
        $step_id = $step->create_step($args['funnelID'], $title, $args['step']['step_type'], $post_content);
        $step->import_metas($step_id, $post_metas);


        // re-signing the shortcode signature keys if builder type is oxygen
        if( 'oxygen' === Wpfnl_functions::get_builder_type() ) {
        	$ct_shortcodes 	= get_post_meta( $step_id, 'ct_builder_shortcodes', true );
			$ct_shortcodes 	= parse_shortcodes($ct_shortcodes, false, false);
			$shortcodes = parse_components_tree($ct_shortcodes['content']);
			update_post_meta($step_id, 'ct_builder_shortcodes', $shortcodes);
		}

		if ( 'divi-builder' === Wpfnl_functions::get_builder_type() ) {
			if ( isset( $response['data']['divi_content'] ) && ! empty( $response['data']['divi_content'] ) ) {
				update_post_meta( $step_id, 'divi_content', $response['data']['divi_content'] );
				wp_update_post(
					array(
						'ID' 			=> $step_id,
						'post_content' 	=> $response['data']['divi_content']
					)
				);
			}
		}

        if ( 'gutenberg' === Wpfnl_functions::get_builder_type() ) {
			if ( isset( $response['data']['rawData'] ) && ! empty( $response['data']['rawData'] ) ) {
				wp_update_post(
					array(
						'ID' => $step_id,
						'post_content' => $response['data']['rawData']
                	)
				);
			}
        }

        $funnel = Wpfnl::$instance->funnel_store;
        $funnel->set_id($args['funnelID']);
        $funnel->set_steps_order();
        $funnel->save_steps_order( $step_id, $args['step']['step_type'], $title );


		if( isset($args['importType']) && $args['importType'] === 'templates' ) {
			$this->update_step_id_in_funnel_data_and_identifier( $args['step']['id'], $step_id, $args );
		}

        do_action('wpfunnels_step_import_complete');
        do_action('wpfunnels_after_step_import', $step_id, $builder);


        return [
            'success' 		=> true,
            'stepID' 		=> $step_id,
			'stepEditLink'	=> get_edit_post_link($step_id),
			'stepViewLink'	=> get_permalink($step_id)
        ];
    }


    /**
     * @param $remote_step
     * @param $new_step
     * @param $args
     */
    public function update_step_id_in_funnel_data_and_identifier($remote_step, $new_step, $args)
    {
        $funnel_id 			= $args['funnelID'];
        $funnel_identifier 	= array();
		$funnel_data 		= array();
		$funnel_json 		= get_post_meta($funnel_id, 'funnel_data', true);
        if ($funnel_json) {
            if(is_array($funnel_json)) {
                $funnel_data = $funnel_json;
            } else {
                $funnel_data = json_decode($funnel_json,1);
            }

            $node_data = $funnel_data['drawflow']['Home']['data'];
            foreach ($node_data as $node_key => $node_value) {
				if ( isset($node_value['data']['step_id']) && $node_value['data']['step_id'] == $remote_step ) {
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
		else {
			$funnel_data = $args['funnelData'];
			$funnel_data = json_decode(wp_unslash($funnel_data), true);
			$node_data = $funnel_data['drawflow']['Home']['data'];
			foreach ($node_data as $node_key => $node_value) {
				if ( isset($node_value['data']['step_id']) &&  $node_value['data']['step_id'] == $remote_step ) {
					$funnel_data['drawflow']['Home']['data'][$node_key]['data']['step_id'] = $new_step;
					$post_edit_link = base64_encode(get_edit_post_link($new_step));
					$post_view_link = base64_encode(get_post_permalink($new_step));
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
        if ( $funnel_data ) {
            update_post_meta($funnel_id, 'funnel_data', $funnel_data);

            /** save steps data */
			$steps = $this->get_steps( $funnel_data );
			update_post_meta( $funnel_id, '_steps', $steps );
        }

        if ($funnel_identifier) {
            $funnel_identifier_json = json_encode($funnel_identifier, JSON_UNESCAPED_SLASHES);
            update_post_meta($funnel_id, 'funnel_identifier', $funnel_identifier_json);
        }
    }


	/**
	 * get steps
	 *
	 * @param $funnel_flow_data
	 * @return array
	 *
	 * @since 2.0.5
	 */
	private function get_steps( $funnel_flow_data ) {
		$drawflow		= $funnel_flow_data['drawflow'];
		$steps 			= array();
		if( isset( $drawflow['Home']['data'] ) ) {
			$drawflow_data = $drawflow['Home']['data'];
			foreach ( $drawflow_data as $key => $data ) {
				$step_data 	= $data['data'];
				$step_type 	= $step_data['step_type'];
				if('conditional' !== $step_type) {
					$step_id 	= $step_data['step_id'];
					$step_name	= sanitize_text_field(get_the_title($step_data['step_id']));
					$steps[]	= array(
						'id'		=> $step_id,
						'step_type'	=> $step_type,
						'name'		=> $step_name,
					);
				}

			}
		}
		return $steps;
	}
}
