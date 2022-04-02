<?php

namespace WPFunnels\Rest\Controllers;

use WP_Error;
use WP_REST_Request;
use WPFunnels\Wpfnl_functions;

class StepController extends Wpfnl_REST_Controller
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
	protected $rest_base = 'steps';

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

		register_rest_route($this->namespace, '/' . $this->rest_base, array(
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
	}


	/**
	 * @param WP_REST_Request $request
	 * @return \WP_REST_Response
	 *
	 * @since 2.0.5
	 */
	public function update_step_meta( WP_REST_Request $request ) {
		$step_id = $request['step_id'];
		$funnel_id = $request['funnel_id'];
		$settings = $request->get_params();
		
		$steps = Wpfnl_functions::get_steps($settings['funnel_id']);
		foreach($steps as $key=>$step){
			if($step['id'] == $settings['step_id']){
				$steps[$key]['name'] = $settings['title'];
			}
		}
		update_post_meta( $settings['funnel_id'], '_steps_order',$steps);

		wp_update_post([
			"ID" 			=> $step_id,
			"post_title" 	=> wp_strip_all_tags( $settings['title'] ),
			"post_name" 	=> sanitize_title($settings['slug']),
		]);
		$response = array(
			'success'		=> true,
			'post_title'	=> htmlspecialchars_decode(get_the_title($step_id)),
			'permalink'		=> rtrim( get_the_permalink($step_id), '/' ),
			'slug'			=> sanitize_title($settings['slug']),
		);

		// update step name through '_steps_order' meta
		$all_steps = Wpfnl_functions::get_steps($funnel_id);
		foreach( $all_steps as $key => $step ){
			if($step['id'] == $request['step_id'] ){
				$all_steps[$key]['name'] = $settings['title'];
				break;
			}
		}
		update_post_meta( $funnel_id , '_steps_order', $all_steps );
		return $this->prepare_item_for_response( $response, $request );
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
		return rest_ensure_response( $data );
	}

}
