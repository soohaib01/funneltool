<?php

namespace WPFunnels\TemplateLibrary;

use WPFunnels\Wpfnl;

abstract class Wpfnl_Source_Base
{
    abstract public function get_source();

    abstract public function get_funnels($arg = []);

    abstract public function get_funnel($template_id);

    abstract public function get_data(array $args);

    abstract public function import_funnel($args = []);

    abstract public function import_step($args = []);


    /**
     * after funnel import
     * redirect to new funnel edit url
     *
     * @param $payload
     * @return array
     * @since 1.0.0
     */
    public function after_funnel_creation($payload)
    {
        $funnel = Wpfnl::$instance->funnel_store;
        $funnel->set_id($payload['funnelID']);
        $funnel->set_steps_order();

        // rearrange steps order
        $funnel_data = get_post_meta( $payload['funnelID'], 'funnel_data', true );
        $steps_order = $this->get_steps_order($funnel_data);
        update_post_meta( $payload['funnelID'], '_steps_order', $steps_order );
        update_post_meta( $payload['funnelID'], '_steps', $steps_order );


        $redirect_link = add_query_arg(
            [
                'page'      => WPFNL_EDIT_FUNNEL_SLUG,
                'id'        => $payload['funnelID'],
                'step_id'   => $funnel->get_first_step_id(),
            ],
            admin_url('admin.php')
        );
        return [
            'success'       => true,
            'redirectLink'  => $redirect_link,
        ];
    }

    /**
     * after funnel import
     * redirect to new funnel edit url
     *
     * @param $payload
     * @return array
     * @since 1.0.0
     */
    public function after_step_creation($payload)
    {
        $redirect_link = add_query_arg(
            [
                'page'      => WPFNL_EDIT_FUNNEL_SLUG,
                'id'        => $payload['funnelID'],
                'step_id'   => $payload['stepID'],
            ],
            admin_url('admin.php')
        );
        return [
            'success'       => true,
            'redirectLink'  => $redirect_link,
        ];
    }


	/**
	 * get steps order
	 *
	 * @param $funnel_flow_data
	 * @return array
	 *
	 * @since 2.2.6
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

}
