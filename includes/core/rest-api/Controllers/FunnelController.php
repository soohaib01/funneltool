<?php

namespace WPFunnels\Rest\Controllers;

use WP_Error;
use WP_REST_Request;
use WPFunnels\Wpfnl_functions;
use Wpfnl_Pro_GB_Functions;

class FunnelController extends Wpfnl_REST_Controller
{

    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'wpfunnels/v1';

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'funnel-control';

    /**
     * check if user has valid permission
     *
     * @param $request
     * @return bool|WP_Error
     * @since 1.0.0
     */
    public function update_items_permissions_check($request)
    {
        if (!Wpfnl_functions::wpfnl_rest_check_manager_permissions( 'steps', 'edit' )) {
            return new WP_Error('wpfunnels_rest_cannot_edit', __('Sorry, you cannot edit this resource.', 'wpfnl'), ['status' => rest_authorization_required_code()]);
        }
        return true;
    }

    /**
     * Makes sure the current user has access to READ the settings APIs.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|boolean
     * @since  3.0.0
     */
    public function get_items_permissions_check($request)
    {
        if (!Wpfnl_functions::wpfnl_rest_check_manager_permissions('settings')) {
            return new WP_Error('wpfunnels_rest_cannot_view', __('Sorry, you cannot list resources.', 'wpfnl'), ['status' => rest_authorization_required_code()]);
        }
        return true;
    }


    /**
     * register rest routes
     *
     * @since 1.0.0
     */
    public function register_routes()
    {
        register_rest_route($this->namespace, '/' . $this->rest_base . '/saveFunnel/', [
            [
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => [
                    $this,
                    'save_funnel_data'
                ],
                 'permission_callback' => [
                     $this,
                     'update_items_permissions_check'
                 ] ,
            ],
        ]);

        register_rest_route($this->namespace, '/' . $this->rest_base . '/getThankyouData/', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [
                    $this,
                    'get_thankyou_data'
                ],
                 'permission_callback' => [
                     $this,
                     'update_items_permissions_check'
                 ] ,
            ],
        ]);

        register_rest_route($this->namespace, '/' . $this->rest_base . '/saveConditionalNode/', [
            [
                'methods' => \WP_REST_Server::EDITABLE,
                'callback' => [
                    $this,
                    'save_conditional_node'
                ],
                 'permission_callback' => [
                     $this,
                     'update_items_permissions_check'
                 ] ,
            ],
        ]);
        register_rest_route($this->namespace, '/' . $this->rest_base . '/getConditionalNode/', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [
                    $this,
                    'get_conditional_node'
                ],
                 'permission_callback' => [
                     $this,
                     'get_items_permissions_check'
                 ] ,
            ],
        ]);
        register_rest_route($this->namespace, '/' . $this->rest_base . '/getFunnel/', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [
                    $this,
                    'get_funnel_data'
                ],
                'permission_callback' => [
                    $this,
                    'update_items_permissions_check'
                ],
            ],
        ]);

        register_rest_route($this->namespace, '/' . $this->rest_base . '/getFunnelIdentifier/', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [
                    $this,
                    'get_funnel_identifier'
                ],
                 'permission_callback' => [
                     $this,
                     'update_items_permissions_check'
                 ] ,
            ],
        ]);

        register_rest_route($this->namespace, '/' . $this->rest_base . '/getStepType/', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [
                    $this,
                    'get_step_type'
                ],
                 'permission_callback' => [
                     $this,
                     'update_items_permissions_check'
                 ] ,
            ],
        ]);

        register_rest_route($this->namespace, '/' . $this->rest_base . '/getFunnelTitle/', array(
			array(
				'methods' => \WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_funnel_title'),
				'permission_callback' => array( $this, 'update_items_permissions_check' ),
			),
		));
        register_rest_route($this->namespace, '/' . $this->rest_base . '/getFunnelLink/', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [
                    $this,
                    'get_funnel_link'
                ],
                 'permission_callback' => [
                     $this,
                     'update_items_permissions_check'
                 ],
            ],
        ]);


        register_rest_route($this->namespace, '/' . $this->rest_base . '/exportFunnel/', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [
                    $this,
                    'export_funnel'
                ],
                 'permission_callback' => [
                     $this,
                     'update_items_permissions_check'
                 ] ,
            ],
        ]);

        register_rest_route($this->namespace, '/' . $this->rest_base . '/getallfunnels/', array(
			array(
				'methods' => \WP_REST_Server::READABLE,
				'callback' => [
					$this,
					'get_all_funnels'
				],
				'permission_callback' => [
					$this,
					'update_items_permissions_check'
				] ,
			),
		));

		register_rest_route($this->namespace, '/' . $this->rest_base . '/steps/', array(
			'args' => array(
				'funnel_id' => array(
					'description' => __('Funnel ID.', 'wpfnl'),
					'type' => 'string',
				),
				'step_id' => array(
					'description' => __('Step ID.', 'wpfnl'),
					'type' => 'string',
				)
			),
			array(
				'methods' => \WP_REST_Server::EDITABLE,
				'callback' => [
					$this,
					'update_step_meta'
				],
				'permission_callback' => [
					$this,
					'update_items_permissions_check'
				] ,
			)
		));


        register_rest_route($this->namespace, '/' . $this->rest_base . '/get_gbf_data/(?P<funnel_id>\d+)', [
            [
                'methods' => \WP_REST_Server::READABLE,
                'callback' => [
                    $this,
                    'get_GBF_data'
                ],
                'permission_callback' => [
                    $this,
                    'update_items_permissions_check'
                ] ,
            ],
        ]);

        
    }

    /**
     * Check if funnel data exists or not
     **/
    public function get_all_funnels()
    {
        $args = array(
            'post_type' => 'wpfunnel_steps',
            'numberposts' => -1
        );
        $funnels = get_posts($args);

        if ($funnels) {
            if (count($funnels) > 0) {
                return false;
            }
        }

        return true;
    }


    /**
     * Save thankyou data
     */
    public function get_thankyou_data($request)
    {
        $step_id = $request['step_id'];
        $data = array();
        $data['_wpfnl_thankyou_order_overview'] 	= get_post_meta( $step_id, '_wpfnl_thankyou_order_overview', true )   ? get_post_meta( $step_id, '_wpfnl_thankyou_order_overview', true ) : 'on';
        $data['_wpfnl_thankyou_order_details'] 		= get_post_meta( $step_id, '_wpfnl_thankyou_order_details', true )    ? get_post_meta( $step_id, '_wpfnl_thankyou_order_details', true ) : 'on';
        $data['_wpfnl_thankyou_billing_details'] 	= get_post_meta( $step_id, '_wpfnl_thankyou_billing_details', true )  ? get_post_meta( $step_id, '_wpfnl_thankyou_billing_details', true ): 'on';
        $data['_wpfnl_thankyou_shipping_details'] 	= get_post_meta( $step_id, '_wpfnl_thankyou_shipping_details', true ) ? get_post_meta( $step_id, '_wpfnl_thankyou_shipping_details', true ) : 'on';
        $data['_wpfnl_thankyou_is_custom_redirect'] = get_post_meta( $step_id, '_wpfnl_thankyou_is_custom_redirect', true ) ? get_post_meta( $step_id, '_wpfnl_thankyou_is_custom_redirect', true ) : 'off';
        $data['_wpfnl_thankyou_is_direct_redirect'] = get_post_meta( $step_id, '_wpfnl_thankyou_is_direct_redirect', true ) ? get_post_meta( $step_id, '_wpfnl_thankyou_is_direct_redirect', true ) : 'off';
        $data['_wpfnl_thankyou_set_time']           = get_post_meta( $step_id, '_wpfnl_thankyou_set_time', true ) ? get_post_meta( $step_id, '_wpfnl_thankyou_set_time', true ) : '';
        $data['_wpfnl_thankyou_custom_redirect_url']= get_post_meta( $step_id, '_wpfnl_thankyou_custom_redirect_url', true ) ? get_post_meta( $step_id, '_wpfnl_thankyou_custom_redirect_url', true ) : '';
        return $data;
    }

    /**
     * Get conditional node
     *
     * @param WP_REST_Request $request request.
     * @return array|WP_Error
     */
    public function get_conditional_node($request)
    {
        $funnel_id = $request['funnel_id'];
        $node_identifier = $request['node_identifier'];

        $data = get_post_meta($funnel_id, $node_identifier, true);

        if ($data) {
            return array(
                'status' => 'success',
                'data' => $data,
            );
        } else {
            $response = array(
                'status' => 'error',
                'data' => '',
            );
        }

        return $this->prepare_item_for_response($response, $request);
    }

    /**
     * Save conditional node
     *
     * @param string $request request.
     * @return array|WP_Error
     */
    public function save_conditional_node($request)
    {
        $funnel_id = $request['funnel_id'];
        $condition_data = $request['condition_data'];
        $node_identifier = $request['node_identifier'];
        update_post_meta($funnel_id, $node_identifier, $condition_data);
		$response = array(
			'status' => true,
		);
		return $this->prepare_item_for_response( $response, $request );
    }

    /**
     * Get step_type.
     *
     * @param string $request request.
     * @return array|WP_Error
     */
    public function get_step_type($request)
    {
        $step_type = '';
        $step_id = $request['step_id'];
        $step_type = get_post_meta($step_id, '_step_type', true);
        return $step_type;
    }

    /**
     * Get funnel identifier.
     *
     * @param string $request request.
     * @return array|WP_Error
     */
    public function get_funnel_identifier($request)
    {
        $funnel_id  = $request['funnel_id'];
        $identifier = get_post_meta($funnel_id, 'funnel_identifier', true);
        if ($identifier) {
            return array(
                'status' => 'success',
                'data' => $identifier,
            );
        }
        return array(
            'status' => 'error',
            'data' => '',
        );
    }


	/**
	 * Get funnel title.
	 *
	 * @param $request
	 * @return \WP_REST_Response
	 */
    public function get_funnel_title($request)
    {
        $funnel_id 	= $request['funnel_id'];
        $title 		= html_entity_decode(get_the_title($funnel_id));
        $response = array(
        	'success'	=> true,
        	'title'		=> $title,
		);
 		return $this->prepare_item_for_response($response, $request);
    }


	/**
	 * get the funnel view link
	 *
	 * @param $request
	 * @return \WP_REST_Response
	 */
    public function get_funnel_link( $request )
    {
        $funnel_id = $request['funnel_id'];
        $steps = get_post_meta( $funnel_id, '_steps_order', true );
		$response['success'] = false;
        if ($steps) {
            if ( isset($steps[0]) && $steps[0]['id'] ) {
				$response['link'] = get_post_permalink($steps[0]['id']);
                $utm_params       = $this->get_utm_params();
                if($utm_params != '') {
                    $response['link'] = $response['link'].$utm_params;
                }
				$response['success'] = true;
            }
        }
		return $this->prepare_item_for_response($response, $request);
    }


    /**
     * Get funnel data.
     *
     * @param $request
     * @return \WP_REST_Response
     */
    public function get_funnel_data($request)
    {
        $funnel_id 			= $request['funnel_id'];
        $funnel_data 		= get_post_meta( $funnel_id, 'funnel_data', true );
        $funnel_identifier 	= get_post_meta( $funnel_id, 'funnel_identifier', true );
        $_steps_order 		= get_post_meta( $funnel_id, '_steps_order', true ); //get step order
        $status				= get_post_status( $funnel_id );
        $steps_order 		= array();
        $response 			= array();

        if ($_steps_order) {
            foreach ($_steps_order as $step) {
                $steps_order[] = $step;
            }
        }

        $funnel_data = $this->get_formatted_funnel_data( $funnel_data );

        for ($i = 0; $i < count($steps_order); $i++) {
        	$_temp_step 			= $steps_order[$i];
        	$_temp_step['visit'] 	= 0;
        	$_temp_step['conversion'] 	= 0;
			$_temp_step['name'] 	= get_the_title($steps_order[$i]['id']);
            $_step_type 			= get_post_meta( $steps_order[$i]['id'], '_step_type', true );
            $should_assign_product 	= false;
            if(in_array($_step_type, array( 'checkout', 'upsell', 'downsell' ))) {
				$meta_key = '_wpfnl_checkout_products';
            	switch ($_step_type) {
					case 'upsell':
						$meta_key = '_wpfnl_upsell_products';
						break;
					case 'downsell':
						$meta_key = '_wpfnl_downsell_products';
						break;

				}

				if( !get_post_meta( $steps_order[$i]['id'], $meta_key, true ) ) {
					$should_assign_product = true;
				}

            }

			$_temp_step['should_assign_product'] = $should_assign_product;
            $steps_order[$i] = apply_filters( 'wpfunnels/step_data', $_temp_step, $steps_order[$i]['id'] );
        }

        if ($funnel_data) {
            $response = array(
                'status' 			=> 'success',
                'funnel_data' 		=> $funnel_data,
                'funnel_identifier' => $funnel_identifier,
                'steps_order' 		=> $steps_order,
				'funnel_status'		=> $status
            );

        } else {
            $response = array(
                'status' => 'error',
            );
        }

        return $this->prepare_item_for_response($response, $request);
    }


	/**
	 * get formatted funnel data
	 *
	 * @param $drawflow
	 * @return mixed
	 *
	 * @since 2.0.5
	 */
    private function get_formatted_funnel_data( $drawflow ) {
		if( isset( $drawflow['drawflow']['Home']['data'] ) ) {
			$drawflow_data = $drawflow['drawflow']['Home']['data'];
			foreach ( $drawflow_data as $key => $data ) {
				$step_data 		= $data['data'];
				$step_type		= $step_data['step_type'];
				if('conditional' !== $step_type) {
					$step_id 		= $step_data['step_id'];
					if( 'conditional' !== $step_type ) {
						$edit_post_link = get_edit_post_link( $step_id );
						$view_link		= get_the_permalink( $step_id );
                        $utm_params     = $this->get_utm_params();
                        if($utm_params != '') {
                            $view_link = $view_link.$utm_params;
                        }
						$title			= get_the_title( $step_id );
						$drawflow['drawflow']['Home']['data'][$key]['data'] = array(
							'step_edit_link'	=> base64_encode( $edit_post_link ),
							'step_id'			=> $step_id,
							'step_type'			=> $step_data['step_type'],
							'step_view_link'	=> base64_encode( rtrim( $view_link, '/' ) ),
							'step_name'			=> $title,
						);
					}
				}
			}
		}
		return $drawflow;
	}

    /**
     * Save funnel data.
     *
     * @param string $request request.
     * @return array|WP_Error
     */
    public function save_funnel_data($request)
    {
        $funnel_id              	= $request['funnel_id'];
        $funnel_json            	= $request['funnel_data'];
        $funnel_identifier      	= $request['funnel_identifier'];
        $should_update_steps_order	= $request['should_update_steps_order'];
        $should_update_steps		= $request['should_update_steps'];
        $funnel_data            	= array();
        $_steps                 	= array();
		$response                 	= array(
			'success'	=> true,
			'link'		=> home_url()
		);
        if ($funnel_json) {
            $funnel_data = $funnel_json;
            $steps = $funnel_data['drawflow']['Home']['data'];
            foreach ($steps as $key => $step) {
                if ($step) {
                    $node_data 			= $step['data'];
                    if (isset($node_data["step_name"])) unset($node_data["step_name"]);
                    $step['data'] = $node_data;
                    $_steps[$key] = $step;
                }
            }
        }
        $funnel_data['drawflow']['Home']['data'] = $_steps;
        update_post_meta($funnel_id, 'funnel_data', $funnel_data);
        update_post_meta($funnel_id, 'funnel_identifier', $funnel_identifier);


        if( $should_update_steps ) {
			$steps = $this->get_steps( $funnel_data );
			update_post_meta( $funnel_id, '_steps', $steps );
		}

		if( $should_update_steps_order ) {
			$_steps_order 	= $this->get_steps_order( $funnel_data );
			$steps_order 	= array();
			foreach ($_steps_order as $_step){
				if(count($_step)) {
					$steps_order[] = $_step;
				}
			}

			if(count($steps_order)) {
				update_post_meta( $funnel_id, '_steps_order', $steps_order );
				if(isset($steps_order[0]['id'])) {
					$response['link'] = esc_url( get_post_permalink( $steps_order[0]['id'] ));
				}
			} else {
				delete_post_meta( $funnel_id, '_steps_order' );
			}
		}

		$steps = get_post_meta( $funnel_id, '_steps_order', true );
		if($steps && count($steps)) {
			$response['link'] = get_post_permalink($steps[0]['id']);
		}
		$response['success'] = true;
        return rest_ensure_response($response);
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
				if( 'conditional' !== $step_data['step_type'] ) {
					$step_id 	= $step_data['step_id'];
					$step_type 	= $step_data['step_type'];
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


	/**
	 * get steps order
	 *
	 * @param $funnel_flow_data
	 * @return array
	 *
	 * @since 2.0.5
	 */
    private function get_steps_order( $funnel_flow_data ) {
		$drawflow		= $funnel_flow_data['drawflow'];
		$nodes			= array();
		$step_order		= array();
		$first_node_id	= '';
		$start_node 	= array();


		if( isset( $drawflow['Home']['data'] ) ) {
			$drawflow_data = $drawflow['Home']['data'];

			/**
			 * if has only one step, that only step will be the first step, no conditions should be checked.
			 * just return the step order
			 */
			if( 1 === count( $drawflow_data ) ) {
				$node_id 	= array_keys($drawflow_data)[0];
				$data 		= $drawflow_data[$node_id];
				$step_data 	= $data['data'];
				$step_id 	= $step_data['step_id'];
				$step_type 	= $step_data['step_type'];
				$step_order[] 	= array(
					'id'		=> $step_id,
					'step_type'	=> $step_type,
					'name'		=> sanitize_text_field( get_the_title( $step_id ) ),
				);
				return $step_order;

			}

			/**
			 * first we will find the first node (the node which has only output connection but no input connection will be considered as first node) and the list of nodes array which has the
			 * step information includes output connection and input connection and it will be stored on $nodes
			 */
			foreach ( $drawflow_data as $key => $data ) {
				$step_data 	= $data['data'];
				$step_type 	= $step_data['step_type'];
				$step_id 	= $step_type !== 'conditional' ? $step_data['step_id'] : 0;
				if(
					(isset( $data['outputs']['output_1']['connections'] ) && count( $data['outputs']['output_1']['connections'] ) ) ||
					(isset( $data['inputs']['input_1']['connections'] ) && count($data['inputs']['input_1']['connections']) )
				) {

					if('conditional' === $step_type) {
						continue;
					}

					/**
					 * A starting node is a node which has only output connection but not any input connection.
					 * if the step is landing, then there should not be any input connection for this step. so we will only consider the output connection for landing only.
					 * for other step types (checkout, offer, thankyou), we will check if the step has any output connection and no input connection.
					 */
					if( 'landing' === $step_type ) {
						if (
							isset($data['outputs']['output_1']['connections']) && count($data['outputs']['output_1']['connections']) &&
							(isset( $data['inputs'] ) && count($data['inputs']) == 0 )
						) {
							$start_node 	= array(
								'id' 		=> $step_id,
								'step_type' => $step_type,
								'name' 		=> sanitize_text_field(get_the_title($step_id)),
							);
						}
					} else {
						if (
							isset($data['outputs']['output_1']['connections']) && count($data['outputs']['output_1']['connections']) &&
							(isset($data['inputs']['input_1']['connections']) && count($data['inputs']['input_1']['connections']) === 0)
						) {
							$start_node 	= array(
								'id' 		=> $step_id,
								'step_type' => $step_type,
								'name' 		=> sanitize_text_field(get_the_title($step_id)),
							);
						} else {
							$step_order[] = array(
								'id' 		=> $step_id,
								'step_type' => $step_type,
								'name' 		=> sanitize_text_field(get_the_title($step_id)),
							);
						}
					}
				}
			}
			$step_order = $this->array_insert($step_order, $start_node, 0);
		}

		return $step_order;
	}


	/**
	 * array insert element on position
	 *
	 * @param $original
	 * @param $inserted
	 * @param int $position
	 * @return mixed
	 */
	private function array_insert(&$original, $inserted, $position) {
		array_splice($original, $position, 0, array($inserted));
		return $original;
	}


	/**
	 * Export funnel data
	 *
	 * @param $request
	 * @return false|string
	 *
	 * @since 1.0.0
	 */
    public function export_funnel($request)
    {
        $funnel_id = $request['funnel_id'];

        //===main array of data which will be downloaded as json file===//
        $data = array();

        $funnel_title = get_the_title($funnel_id);
        $funnel_meta = get_post_meta($funnel_id);

        //=== Added title and meta of current funnel post===//
        $data['title'] = $funnel_title;
        $data['meta'] = $funnel_meta;

        //=== Find list of steps and ther data===//
        //== Getting steps from identifier meta==//
        if (isset($data['meta']['funnel_identifier'])) {
            $identifier_meta = $data['meta']['funnel_identifier'];
            $all_steps_array = array();
            foreach ($identifier_meta as $identifier_meta_key => $identifier_meta_value) {
                $node_step_pair = json_decode($identifier_meta_value, true);
                foreach ($node_step_pair as $node_step_pair_key => $node_step_pair_value) {
                    $current_steps_array = array();
                    $step_id                            = $node_step_pair_value;
                    $step_title                         = get_the_title($step_id);
                    $step_meta                          = get_post_meta($step_id);
                    $content_post                       = get_post($step_id);
                    $content                            = json_encode($content_post->post_content);
                    $current_steps_array['step_id']     = $step_id;
                    $current_steps_array['title']       = $step_title;
                    $current_steps_array['meta']        = $step_meta;
                    $current_steps_array['content']     = $content;
                    $all_steps_array[]                  = $current_steps_array;
                }
            }
            $data['steps'] = $all_steps_array;
        }

        return json_encode($data);
    }
    /**
     * Get UTM Params URL
     */

    public function get_utm_params() {
        $utm_params = '';
        $utm_settings = $this->get_utm_settings();
        if($utm_settings['utm_enable'] == 'on') {
            $utm_params  = '?utm_source='.$utm_settings['utm_source'].'&utm_medium='.$utm_settings['utm_medium'].'&utm_campaign='.$utm_settings['utm_campaign'];
            $utm_params .= ((!empty($utm_settings['utm_content'])) ? '&utm_content='.$utm_settings['utm_content'] : '');
            $utm_params   = strtolower($utm_params);
        }
        return $utm_params;
    }

       /**
     * Get GTM Settings
     * @return array
     */

    public function get_utm_settings() {
		$default_settings = array(
			'utm_enable'	=> 'off',
			'utm_source' 	=> '',
			'utm_medium' 	=> '',
			'utm_campaign' 	=> '',
			'utm_content' 	=> '',
		);
        $utm_settings = get_option('_wpfunnels_utm_params', $default_settings);
        return wp_parse_args($utm_settings, $default_settings);
    }
    /**
     * Prepare a single setting object for response.
     *
     * @param object $item Setting object.
     * @param WP_REST_Request $request Request object.
     * @return \WP_REST_Response $response Response data.
     * @since  1.0.0
     */
    public function prepare_item_for_response($item, $request)
    {
        $data = $this->add_additional_fields_to_object($item, $request);
        return rest_ensure_response($data);
    }

    /**
     * Get GBF data
     * @param $request
     * @return WP_Error|\WP_REST_Response
     */
    public function get_GBF_data( $request ){

        $funnel_id = $request['funnel_id'];
        $steps = Wpfnl_functions::get_steps( $funnel_id );

        if( !is_plugin_active( 'wpfunnels-pro-gbf/wpfnl-pro-gb.php' ) ){
            $response = array(
                'success'   => false,
                'data'      => 'Global Funnel is not activated'
            );
            return rest_ensure_response($response);
        }
        $start_condition = get_post_meta( $funnel_id, 'global_funnel_start_condition', true );
        $step_ids = array();
        foreach( $steps as $step ){
            if( $step['step_type'] == 'checkout' ){
                if( !empty( $start_condition ) ){
                    array_push($step_ids,$step['id']);
                }
            }elseif( $step['step_type'] == 'upsell' ){
                $upsell_rules   = get_post_meta( $step['id'], 'global_funnel_upsell_rules', true );
                if( !empty( $upsell_rules ) ){
                    array_push($step_ids,$step['id']);
                }
            }elseif( $step['step_type'] == 'downsell' ){
                $downsell_rules   = get_post_meta( $step['id'], 'global_funnel_downsell_rules', true );
                if( !empty( $downsell_rules ) ){
                    array_push($step_ids,$step['id']);
                }
            }
        }

        $response = array(
            'success'   => true,
            'data'      => $step_ids
        );
        return rest_ensure_response($response);
    }

}
