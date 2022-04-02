<?php

namespace WPFunnels;

class Wpfnl_functions
{
	public static $installed_plugins;

	/**
	 * get all the steps of the funnel
	 *
	 * @param $funnel_id
	 * @return array|mixed
	 */
	public static function get_steps( $funnel_id ) {
		$steps = get_post_meta( $funnel_id, '_steps_order', true );
		if ( ! is_array( $steps ) ) {
			$steps = array();
		}
		return $steps;
	}

	/**
	 * check if the associate order is from funnel or not
	 *
	 * @param \WC_Order $order
	 * @return bool
	 * @since 2.0.3
	 */
	public static function check_if_funnel_order(\WC_Order $order) {
		$is_funnel_order = false;
		$funnel_id = self::get_funnel_id_from_order($order->get_id());

		if($funnel_id) {
			$is_funnel_order = true;
		}
		return $is_funnel_order;
	}


	/**
	 * get accociate funnel id from order id
	 *
	 * @param $order_id
	 * @return int
	 *
	 * @since 2.0.3
	 */
	public static function get_funnel_id_from_order($order_id) {

		$funnel_id = get_post_meta( $order_id, '_wpfunnels_funnel_id', true );
		if( !$funnel_id ){
			$funnel_id = get_post_meta( $order_id, '_wpfunnels_parent_funnel_id', true );
		}
		return intval( $funnel_id );
	}


	public static function is_funnel_admin_page()
	{
		if (isset($_GET['page'])) {
			$page = sanitize_text_field($_GET['page']);
			if ($page === 'wp_funnels') {
				return true;
			} elseif ($page === 'settings') {
				return true;
			} elseif ($page === 'edit_funnel') {
				return true;
			} elseif ($page === 'create_funnel') {
				return true;
			}
		}
		return false;
	}

	/**
	 * check if the given string is a date or not
	 *
	 * @param $date
	 * @return bool
	 * @since 1.0.0
	 */
	public static function validate_date($date)
	{
		return (bool)strtotime($date);
	}

	public function render_order_bump()
	{
		$step_id = get_the_ID();
		$order_bump = get_post_meta($step_id, 'order-bump', true);
		$order_bump_settings = get_post_meta($step_id, 'order-bump-settings', true);
		if (isset($order_bump_settings['product']) && $order_bump_settings['product'] != '') {
			$this->render_order_bump_template($order_bump_settings);
		}
	}

	public function render_order_bump_template($settings)
	{
		if (!empty($settings['selectedStyle'])) {
			require_once WPFNL_DIR . 'public/modules/checkout/templates-style/order-bump-template-' . $settings['selectedStyle'] . '.php';
		}
	}

	/**
	 * define constant if it is not set yet
	 *
	 * @param $name
	 * @param $value
	 *
	 * @since 2.0.3
	 */
	public static function maybe_define_constant( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * set do not cache constants
	 */
	public static function do_not_cache() {
		global $post;

		if ( ! apply_filters( 'wpfunnels/do_not_cache', true, $post->ID ) ) {
			return;
		}
		self::maybe_define_constant( 'DONOTCACHEPAGE', true );
		self::maybe_define_constant( 'DONOTCACHEOBJECT', true );
		self::maybe_define_constant( 'DONOTCACHEDB', true );
		nocache_headers();
	}

	/**
	 * return formatted date from the
	 * given date object
	 *
	 * @param $date
	 * @return false|string
	 * @since 1.0.0
	 */
	public static function get_formatted_date($date)
	{
		return date('Y-m-d h:i A', strtotime($date));
	}


	/**
	 * @return bool|int
	 */
	public static function get_checkout_id_from_post_data()
	{

		if (isset($_POST['_wpfunnels_checkout_id'])) {
			$checkout_id = filter_var(wp_unslash($_POST['_wpfunnels_checkout_id']), FILTER_SANITIZE_NUMBER_INT);
			return intval($checkout_id);
		}

		return false;
	}


	/**
	 * get funnel id
	 *
	 * @param $step_id
	 * @return mixed
	 */
	public static function get_funnel_id_from_step($step_id) {
		$funnel_id = get_post_meta($step_id, '_funnel_id', true);
		return intval($funnel_id);
	}

	public static function get_order_id_from_post_data() {
		if (isset($_POST['_wpfunnels_order_unique_identifier'])) {
			$identifier = wp_unslash($_POST['_wpfunnels_order_unique_identifier']);
			global $wpdb;
			$tbl = $wpdb->prefix.'postmeta';
			$prepare_guery = $wpdb->prepare( "SELECT post_id FROM $tbl where meta_key ='_wpfunnels_order_unique_identifier' and meta_value like '%s'", $identifier );
			$get_value = $wpdb->get_row( $prepare_guery );
			if($get_value) {
				return intval($get_value->post_id);
			}
		}
		return false;
	}


	/**
	 * @param $order_id
	 * @return int
	 */
	public static function get_checkout_id_from_order( $order_id ) {
		$checkout_id = get_post_meta( $order_id, '_wpfunnels_checkout_id', true );
		return intval( $checkout_id );
	}


	/**
	 * @return bool|int
	 */
	public static function get_funnel_id_from_post_data()
	{

		if (isset($_POST['_wpfunnels_funnel_id'])) {

			$funnel_id = filter_var(wp_unslash($_POST['_wpfunnels_funnel_id']), FILTER_SANITIZE_NUMBER_INT);

			return intval($funnel_id);
		}

		return false;
	}


	/**
	 * get funnel id from step page
	 *
	 * @return false|mixed
	 */
	public static function get_funnel_id() {
		global $post;
		$funnel_id = false;
		if ( $post ) {
			$funnel_id = get_post_meta( $post->ID, '_funnel_id', true );
		}
		return $funnel_id;
	}

	/**
	 * unserialize data
	 *
	 * @param $data
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function unserialize_array_data($data)
	{
		$data = serialize($data);
		if (@unserialize($data) !== false) {
			return unserialize($data);
		}
		return $data;
	}


	public static function is_wpfnl_page_template( $page_template ) {
		if ( in_array( $page_template, array( 'wpfunnels_boxed', 'wpfunnels_default', 'wpfunnels_fullwidth_with_header_footer', 'wpfunnels_fullwidth_without_header_footer', 'wpfunnels_checkout' ), true ) ) {
			return true;
		}

		return false;
	}


	/**
	 * get formatted string with phrase
	 * e.g: 1 item if singular or
	 * 2 items if plural
	 *
	 * @param $number
	 * @param string $singular
	 * @param string $plural
	 * @return string
	 * @since 1.0.0
	 */
	public static function get_formatted_data_with_phrase($number, $singular = '', $plural = 's')
	{
		if ($number == 1 || $number == 0) {
			return $singular;
		}
		return $plural;
	}


	/**
	 * get formatted string from funnel status
	 *
	 * @param $status
	 * @return string
	 * @since 1.0.0
	 */
	public static function get_formatted_status($status)
	{
		switch ($status) {
			case 'publish':
				return 'Published';
				break;
			case 'draft':
				return 'Draft';
				break;
			default:
				return $status;
				break;
		}
	}


	/**
	 * generate active class for funnel menus
	 *
	 * @param $key
	 * @return bool
	 * @since 1.0.0
	 */
	public static function define_active_class($key)
	{
		$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
		if ($page === WPFNL_MAIN_PAGE_SLUG && $key === 'overview') {
			return true;
		}

		if ($page === 'create_funnel' && $key === 'create_funnel') {
			return true;
		}

		if ($page === 'wpfnl_settings' && $key === 'settings') {
			return true;
		}
	}


	/**
	 * return key from the array if valued matched
	 *
	 * @param $value
	 * @param $search_key
	 * @param $array
	 * @return int|string
	 * @since 1.0.0
	 */
	public static function array_search_by_value($value, $search_key, $array)
	{
		foreach ($array as $key => $val) {
			if(isset($val[$search_key])){
				if ($val[$search_key] == $value) {
					return $key;
				}
			}
		}
		return '';
	}


	/**
	 * @param $id
	 * @return bool
	 * @since 1.0.0
	 */
	public static function check_if_module_exists($id)
	{
		return 'publish' == get_post_status($id) || 'draft' == get_post_status($id);

	}

	public static function check_if_funnel_exists($id)
	{
		return true;
	}


	/**
	 * check if the cpt is step or not
	 *
	 * @param $step_id
	 * @param string $type
	 * @return bool
	 */
	public static function check_if_this_is_step_type_by_id($step_id, $type = 'landing')
	{
		$post_type = get_post_type($step_id);
		if (WPFNL_STEPS_POST_TYPE === $post_type) {
			if ($type === get_post_meta($step_id, '_step_type', true)) {
				return true;
			}
		}
		return false;
	}


	/**
	 * @param $funnel_id
	 * @return bool|mixed
	 *
	 * @since 2.2.6
	 */
	public static function get_thankyou_page_id( $funnel_id ) {
		$thankyou_page_id 	= false;
		$steps 				= self::get_steps($funnel_id);
		if(is_array($steps)) {
			foreach ( $steps as $step ) {
				if ( 'thankyou' === $step['step_type'] ) {
					$thankyou_page_id = $step['id'];
					break;
				}
			}
		}
		return $thankyou_page_id;
	}


	/**
	 * Check if the current post type is step or not
	 *
	 * @return bool
	 * @since 2.0.3
	 */
	public static function is_funnel_step_page( $post_type = '' )
	{
		if( self::get_current_post_type($post_type) === WPFNL_STEPS_POST_TYPE ) {
			return true;
		}
		return false;
	}


	/**
	 * get current post type
	 *
	 * @param $post_type
	 * @return string
	 *
	 * @since 2.3.5
	 */
	public static function get_current_post_type( $post_type ) {
		global $post;
		if ( '' === $post_type && is_object( $post ) ) {
			$post_type = $post->post_type;
		}
		return $post_type;
	}


	public static function is_doing_ajax() {
		if ( wp_doing_ajax() || isset( $_GET['wc-ajax'] ) ) {
			if ( isset( $_GET['wc-ajax'] ) && isset( $_POST['_wpfunnels_checkout_id'] ) ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * check if funnel checkout page
	 *
	 * @param $funnel_id
	 * @return bool
	 */
	public static function is_funnel_checkout_page()
	{
		if(isset($_POST['post_data'])){
			parse_str($_POST['post_data'],$post_data);
			if(isset($post_data['_wpfunnels_checkout_id'])){
				return [
					'status' => true,
					'id'	 => $post_data['_wpfunnels_checkout_id']
				];
			}
		}
		return [
			'status' => false,
			'id'	 => ''
		];
	}


	/**
	 * check if funnel exists
	 *
	 * @param $funnel_id
	 * @return bool
	 */
	public static function is_funnel_exists($funnel_id)
	{
		if (!$funnel_id) return false;
		if (FALSE === get_post_status($funnel_id)) {
			return true;
		}
		return false;
	}


	/**
	 * function to check if the current page is a post edit page
	 *
	 * @return bool
	 *
	 * @since 2.0.3
	 */
	public static function is_step_edit_page(){

		$step_id = -1;
		if ( is_admin() && isset( $_REQUEST['action'] ) ) {
			if ( 'edit' === $_REQUEST['action'] && isset( $_GET['post'] ) ) {
				$step_id = isset( $_GET[ 'post' ] ) ? $_GET[ 'post' ] : - 1;
			}
			elseif ( isset( $_REQUEST['wpfunnels_gb'] ) && isset( $_POST['post_id'] ) ){ //phpcs:ignore
				$step_id = intval( $_POST['post_id'] ); //phpcs:ignore
			}
			if ( $step_id === - 1 ) {

				return false;
			}
			$get_post_type = get_post_type( $step_id );
			if ( WPFNL_STEPS_POST_TYPE === $get_post_type ) {
				return true;
			}
		}
		return false;
	}


	/**
	 * this function will check if the edited step is
	 * the accepted step type
	 *
	 * @param $type
	 * @return bool
	 *
	 * @since 2.0.3
	 */
	public static function if_edited_step_type_is( $type ){
		if(self::is_step_edit_page()) {
			$post_id = isset( $_GET[ 'post' ] ) ? $_GET[ 'post' ] : - 1;
			return self::check_if_this_is_step_type_by_id($post_id, $type);
		}
		return false;
	}


	/**
	 * check if the cpt is step or not
	 *
	 * @param string $type
	 * @return bool
	 * @since 1.0.0
	 */
	public static function check_if_this_is_step_type($type = 'landing')
	{
		$post_type = get_post_type();

		if (WPFNL_STEPS_POST_TYPE === $post_type) {
			global $post;
			if ($type === get_post_meta($post->ID, '_step_type', true)) {
				return true;
			}
		}
		return false;
	}


	/**
	 * hooks for start and end the journey
	 * of a funnel
	 *
	 */
	public static function start_journey() {
		$post_type = get_post_type();
		if ( WPFNL_STEPS_POST_TYPE === $post_type ) {
			global $post;
			$step_id 		= $post->ID;
			$funnel_id 		= self::get_funnel_id_from_step($step_id);
			$steps 			= self::get_steps($funnel_id);
			$funnel_data 	= self::get_funnel_data($funnel_id);
			// start the journey
			if( $steps && is_array($steps) ) {
				if( $steps[0]['id'] === $step_id ) {
					do_action( 'wpfunnels/funnel_journey_starts', $step_id, $funnel_id );
				}
			}

			// end the journey
			if( $funnel_data && isset($funnel_data['drawflow']['Home']['data']) ) {
				$funnel_data = $funnel_data['drawflow']['Home']['data'];
				foreach ( $funnel_data as $data ) {
					$info 		= $data['data'];
					$step_type 	= $info['step_type'];

					if('conditional' !== $step_type) {
						$_step_id 	= $info['step_id'];
						$output_con = $data['outputs'];

						if( empty($output_con) ) {
							if( $_step_id === $step_id ) {
								do_action( 'wpfunnels/funnel_journey_end', $step_id, $funnel_id );
								break;
							}
						}
					}
				}
			}
		}
	}


	/**
	 * Conditional node logic check
	 *
	 * @param $funnel_id
	 * @param $order
	 * @param $condition_identifier
	 * @param $current_page_id
	 * @param $checker
	 * @return bool
	 *
	 * @since 2.0.2
	 */
	public static function check_condition( $funnel_id, $order, $condition_identifier, $current_page_id, $checker = 'accept' )
	{

		$group_conditions = get_post_meta( $funnel_id, $condition_identifier, true );

		if ($group_conditions) {
			// Loop through group condition.
			foreach ($group_conditions as $group) {
				if (empty($group)) {
					continue;
				}

				$match_group = true;
				// Loop over rules and determine if all rules match.
				foreach ($group as $rule) {
					if (!self::match_rule( $rule, $order, $current_page_id, $checker )) {
						$match_group = false;
						break;
					}
				}

				// If this group matches, show the field group.
				if ($match_group) {
					return true;
				}else{
					return false;
				}
			}
		}
		if( $checker == 'accept' ){
			return true;
		}
		// Return default.
		return false;
	}


	/**
	 * check if rule is matched
	 *
	 * @param $rule
	 * @param $order
	 * @param $current_page_id
	 * @param $checker
	 * @return mixed
	 *
	 * @since 2.0.2
	 */
	public static function match_rule( $rule, $order, $current_page_id, $checker )
	{

		if (isset( $rule['field'] ) && $rule['field'] == 'downsell') {
			$rule['field'] = 'upsell';
		}
		$checker_function = $rule['field'] . '_condition_checker';

		return self::$checker_function( $rule, $order, $current_page_id, $checker );
	}


	public static function upsell_condition_checker($data, $order, $current_page_id, $checker)
	{

		if ($data['value'] == 'yes') {
			// need to write a function (check_if_upsell_accepted) to see if upsell is
			// added to the order.
			// If present return true,
			// else return false

			if ( $checker == 'accept' ) {
				return true;
			} else {
				return false;
			}

		} else if ($data['value'] == 'no') {
			// if check_if_upsell_accepted() == true , return false
			// else return true
			if ($checker == 'reject') {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}


	/**
	 * @param $data
	 * @param $order
	 * @param $current_page_id
	 * @return bool
	 */
	public static function orderbump_condition_checker( $data, $order, $current_page_id, $checker = 'accept' )
	{
		$order_bump_accepted = WC()->session->get('order_bump_accepted');
		// WC()->session->set('order_bump_accepted', null);

		return $data['value'] == $order_bump_accepted;
	}


	/**
	 * @param $data
	 * @param $order
	 * @param $current_page_id
	 * @return bool
	 */
	public static function carttotal_condition_checker( $data, $order, $current_page_id, $checker = 'accept' )
	{
		$cart_total = $order->get_total();

		$checker = false;
		if ($data['condition'] == 'greater') {
			if ($cart_total > $data['value']) {
				$checker = true;
			}
		} elseif ($data['condition'] == 'equal') {
			if ($cart_total == $data['value']) {
				$checker = true;
			}
		} elseif ($data['condition'] == 'less') {
			if ($cart_total < $data['value']) {
				$checker = true;
			}
		}
		return $checker;
	}


	/**
	 * get next step of the
	 * funnel
	 *
	 * @param $funnel_id
	 * @param $step_id
	 * @param bool $condition
	 * @return array|bool
	 *
	 * @since 1.0.0
	 */
	public static function get_next_step( $funnel_id, $step_id, $condition = true )
	{

		if( $funnel_id && !$step_id ) {
			return false;
		}
		$funnel_data = self::get_funnel_data($funnel_id);
		if ( $funnel_data ) {
			$node_id        = self::get_node_id( $funnel_id, $step_id );
			$node_data      = $funnel_data['drawflow']['Home']['data'];
			foreach ( $node_data as $node_key => $node_value ) {
				if ( $node_value['id'] == $node_id ) {
					if( $condition ) {
						$next_node_id 	= $node_value['outputs']['output_1']['connections'][0]['node'];
					} else {
						$next_node_id 	= $node_value['outputs']['output_2']['connections'][0]['node'];
					}
					$next_step_id 	= self::get_step_by_node( $funnel_id, $next_node_id );
					$next_step_type = self::get_node_type( $node_data, $next_node_id );
					return array(
						'step_id' 	=> $next_step_id,
						'step_type' => $next_step_type,
					);
				}
			}
		}
		return false;
	}


	/**
	 * get previous step of the
	 * funnel
	 *
	 * @param $funnel_id
	 * @param $step_id
	 * @param bool $condition
	 * @return array|bool
	 *
	 * @since 1.0.0
	 */
	public static function get_prev_step( $funnel_id, $step_id, $condition = true )
	{
		if( $funnel_id && !$step_id ) {
			return false;
		}
		$funnel_data = self::get_funnel_data($funnel_id);
		if ( $funnel_data ) {
			$node_id        = self::get_node_id( $funnel_id, $step_id );
			$node_data      = $funnel_data['drawflow']['Home']['data'];

			foreach ( $node_data as $node_key => $node_value ) {
				if ( $node_value['id'] == $node_id ) {
					if( $condition ) {
						if(!empty($node_value['inputs'])){
							$prev_node_id 	= $node_value['inputs']['input_1']['connections'][0]['node'];
						}else{
							$prev_node_id 	= '';
						}

					} else {
						if(!empty($node_value['inputs'])){
							$prev_node_id 	= $node_value['inputs']['input_2']['connections'][0]['node'];
						}else{
							$prev_node_id 	= '';
						}

					}
					$prev_step_id 	= self::get_step_by_node( $funnel_id, $prev_node_id );
					$prev_step_type = self::get_node_type( $node_data, $prev_node_id );
					if($prev_step_type == 'conditional'){
						return self::get_prev_step($funnel_id,$prev_step_id);

					}else{

						return array(
							'step_id' 	=> $prev_step_id,
							'step_type' => $prev_step_type,
						);
					}
				}
			}

		}
		return false;
	}

	/**
	 * get node type
	 */
	public static function get_node_type($node_data, $node_id)
	{
		foreach ($node_data as $node_key => $node_value) {
			if ($node_value['id'] == $node_id) {
				return $node_value['data']['step_type'];
			}
		}
	}

	/**
	 * get step by node
	 */
	public static function get_step_by_node($funnel_id, $node_id)
	{
		$identifier_json = get_post_meta($funnel_id, 'funnel_identifier', true);
		$identifier_json = preg_replace('/\: *([0-9]+\.?[0-9e+\-]*)/', ':"\\1"', $identifier_json);
		if ($identifier_json) {
			$identifier = json_decode($identifier_json, true);
			foreach ($identifier as $identifier_key => $identifier_value) {
				if ($identifier_key == $node_id) {
					return $identifier_value;
				}
			}
		}
		return false;
	}


	/**
	 * get node by step
	 *
	 * @param $funnel_id
	 * @param $step_id
	 * @return bool|int|string
	 */
	public static function get_node_id( $funnel_id, $step_id )
	{
		$identifier_json = get_post_meta( $funnel_id, 'funnel_identifier', true );
		if ($identifier_json) {
			$identifier = json_decode( $identifier_json, true );
			foreach ( $identifier as $identifier_key => $identifier_value ) {
				if ($identifier_value == $step_id) {
					return $identifier_key;
				}
			}
		}
		return false;
	}


	/**
	 * get funnel data
	 *
	 * @param $funnel_id
	 * @return mixed
	 *
	 * @since 2.0.5
	 */
	public static function get_funnel_data( $funnel_id ) {
		return get_post_meta( $funnel_id, 'funnel_data', true );
	}


	/**
	 * update settings option
	 *
	 * @param $key
	 * @param $value
	 * @param bool $network
	 * @since 1.0.0
	 */
	public static function update_admin_settings($key, $value, $network = false)
	{
		if ( $network && is_multisite() ) {
			update_site_option($key, $value);
		} else {
			update_option($key, $value);
		}
	}


	/**
	 * get admin settings option
	 * by key
	 *
	 * @param $key
	 * @param bool $default
	 * @param bool $network
	 * @return mixed|void
	 * @since 1.0.0
	 */
	public static function get_admin_settings($key, $default = false, $network = false)
	{
		if ($network && is_multisite()) {
			$value = get_site_option($key, $default);
		} else {
			$value = get_option($key, $default);
		}
		return $value;
	}


	/**
	 * get general settings data
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function get_general_settings()
	{
		$default_settings = apply_filters(
			'wpfunnels_general_settings',
			[
				'builder' => 'elementor',
				'paypal_reference' => 'off',
				'order_bump' => 'off',
				'ab_testing' => 'off',
				'funnel_type' => 'sales',
				'create_child_order' => 'off',
			]
		);
		$saved_settings = self::get_admin_settings('_wpfunnels_general_settings', $default_settings);
		return wp_parse_args($saved_settings, $default_settings);
	}


	/**
	 * get permalink settings data
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function get_permalink_settings()
	{
		$default_settings = apply_filters(
			'wpfunnels_permalink_settings',
			[
				'structure' => 'default',
				'step_base' => WPFNL_STEPS_POST_TYPE,
				'funnel_base' => WPFNL_FUNNELS_POST_TYPE,
			]
		);
		$saved_settings = self::get_admin_settings('_wpfunnels_permalink_settings');
		return wp_parse_args($saved_settings, $default_settings);
	}


	/**
	 * get offer settings data
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function get_offer_settings()
	{
		$default_settings = apply_filters( 'wpfunnels/get_offer_settings', array(
				'offer_orders' => 'main-order',
				'show_supported_payment_gateway' => 'off',
				'skip_offer_step' => 'off',
			)
		);
		$saved_settings = self::get_admin_settings('_wpfunnels_offer_settings');
		return wp_parse_args($saved_settings, $default_settings);
	}

	/**
	 * Get GTM events
	 *
	 * @return array
	 */
	public static function get_gtm_events(){
		$default_gtm_events = array(
			'add_to_cart' 		=> 'Add to cart',
			'begin_checkout' 	=> 'Begin checkout',
			'add_payment_info' 	=> 'Add Payment Info',
			'add_shipping_info' => 'Add Shipping Info',
			'purchase' 			=> 'Purchase',
			'orderbump_accept' 	=> 'Order Bump',
			'upsell' 			=> 'Upsell',
			'downsell' 			=> 'Downsell',
		);
		return $default_gtm_events;
	}

	public static function get_gtm_settings(){
		$default_enable_settings = array(
			'gtm_enable'		=> 'off',
			'gtm_container_id' 	=> '',
			'gtm_events' 		=> array(
				'add_to_cart' 		=> 'true',
				'begin_checkout' 	=> 'true',
				'add_payment_info' 	=> 'true',
				'add_shipping_info' => 'true',
				'purchase' 			=> 'true',
				'orderbump_accept' 	=> 'true',
				'upsell' 			=> 'true',
				'downsell' 			=> 'true',
			),
		);
		$gtm_settings = self::get_admin_settings('_wpfunnels_gtm');
		return wp_parse_args($gtm_settings, $default_enable_settings);
	}
	/**
	 * get facebook pixel events
	 *
	 * @return array
	 */
	public static function get_facebook_events(){
		$default_fb_events = array(
			'AddPaymentInfo' => 'Add payment info',
			'AddToCart' => 'Add to cart',
			'InitiateCheckout' => 'Initiate checkout',
			//'Lead' => 'Lead',
			'Purchase' => 'Purchase',
			'ViewContent' => 'View content',
		);
		return $default_fb_events;
	}

	public static function get_facebook_pixel_settings(){
		$default_enable_settings = array(
			'enable_fb_pixel'			 	=> 'off',
			'facebook_pixel_id' 		=> '',
			'facebook_tracking_events' 		=> array(
				'AddPaymentInfo' 	=> 'true',
				'AddToCart' 		=> 'true',
				'InitiateCheckout'  => 'true',
				'Lead' 				=> 'true',
				'Purchase' 			=> 'true',
				'ViewContent' 		=> 'true',
			),
		);
		$facebook_pixel_setting = self::get_admin_settings('_wpfunnels_facebook_pixel');
		return wp_parse_args($facebook_pixel_setting, $default_enable_settings);
	}


	/**
	 * Get advanced settings
	 *
	 */
	public static function get_advanced_settings(){
		$default_enable_settings = array(
			'show_supported_payment_gateway'	=> 'off',
		);

		$advanced_settings = self::get_admin_settings('_wpfunnels_advanced_settings');
		return wp_parse_args($advanced_settings, $default_enable_settings);
	}

	/**
	 * Get UTM Parameters
	 *
	 * @return array
	 */
	public static function get_utm_params(){
		$default_utm_params = array(
			'utm_source' 	=> 'UTM Source',
			'utm_medium' 	=> 'UTM Medium',
			'utm_campaign' 	=> 'UTM Campaign',
			'utm_content' 	=> 'UTM Content',
		);
		return $default_utm_params;
	}

	public static function get_utm_settings(){
		$default_enable_settings = array(
			'utm_enable'	=> 'off',
			'utm_source' 	=> '',
			'utm_medium' 	=> '',
			'utm_campaign' 	=> '',
			'utm_content' 	=> '',
		);
		$utm_settings = self::get_admin_settings('_wpfunnels_utm_params');
		return wp_parse_args($utm_settings, $default_enable_settings);
	}
	/**
	 * Get user roles
	 *
	 * @return array
	 * @since 2.1.7
	 */
	public static function get_user_roles(){
		global $wp_roles;
		$all_roles = $wp_roles->roles;
		return array_keys($all_roles);
	}


	/**
	 * get the saved builder type
	 *
	 * @return mixed|void
	 * @since 1.0.0
	 */
	public static function get_builder_type()
	{
		$general_settings = self::get_general_settings();
		return $general_settings['builder'];
	}


	/**
	 * check if wc is installed
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public static function is_wc_active()
	{
		if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			return true;
		}
		return false;
	}


	/**
	 * check if elementor is installed
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public static function is_elementor_active()
	{
		if (in_array('elementor/elementor.php', apply_filters('active_plugins', get_option('active_plugins')))) {
			return true;
		}
		return false;
	}


	/**
	 * check if saved builder is activated
	 * or not
	 *
	 * @param $builder
	 * @return bool
	 * @since 1.0.0
	 */
	public static function is_builder_active($builder)
	{
		switch ($builder) {
			case 'elementor':
				return self::is_elementor_active();
				break;
			default:
				return false;
				break;
		}
	}


	/**
	 * check if the global funnel addon is activated or not
	 *
	 * @return bool
	 * @since 2.0.4
	 */
	public static function is_global_funnel_activated() {
		return apply_filters('wpfunnels/is_global_funnel_activated', false);
	}


	/**
	 * check if the funnel is global funnel
	 *
	 * @param $funnel_id
	 * @return bool
	 */
	public static function is_global_funnel( $funnel_id ) {
		if(!$funnel_id) {
			return false;
		}
		return apply_filters( 'wpfunnels/is_global_funnel', false, $funnel_id );
	}


	/**
	 * Check if pro is activated/deactivated
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public static function is_wpfnl_pro_activated()
	{
		return apply_filters('wpfunnels/is_wpfnl_pro_active', false) || apply_filters('is_wpfnl_pro_active', false);
	}


	/**
	 * Check if pro is activated/deactivated
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public static function is_pro_license_activated()
	{
		return apply_filters('wpfunnels/is_pro_license_activated', false);
	}


	/**
	 * Check if the module is pro or
	 * not
	 *
	 * @param $module
	 * @return bool
	 * @since 1.0.0
	 */
	public static function is_pro_module($module)
	{
		$pro_modules = apply_filters('wpfnl_pro_modules', []);
		return in_array($module, $pro_modules);
	}


	/**
	 *  Check if the module is exists or not
	 *
	 * @param $module_name
	 * @param string $type
	 * @param bool $step
	 * @param bool $pro
	 * @return bool
	 * @since 1.0.0
	 */
	public static function is_module_registered($module_name, $type = 'admin', $step = false, $pro = false)
	{
		$class_name = str_replace('-', ' ', $module_name);
		$class_name = str_replace(' ', '', ucwords($class_name));
		if ($pro) {
			if ($type === 'steps') {
				$class_name = 'WPFunnelsPro\\Admin\\Modules\\Steps\\' . $class_name . '\Module';
			}
		} else {
			if ($type === 'admin') {
				$class_name = 'WPFunnels\\Admin\\Modules\\' . $class_name . '\Module';
			} elseif ($type === 'steps') {
				$class_name = 'WPFunnels\\Admin\\Modules\\Steps\\' . $class_name . '\Module';
			} else {
				$class_name = 'WPFunnels\\Modules\\Frontend\\' . $class_name . '\Module';
			}
		}
		return class_exists($class_name);
	}


	/**
	 * Check manager permissions on REST API.
	 *
	 * @param string $object Object.
	 * @param string $context Request context.
	 * @return bool
	 * @since 2.6.0
	 */
	public static function wpfnl_rest_check_manager_permissions($object, $context = 'read')
	{

		$objects = [
			'settings'      => 'manage_options',
			'templates'     => 'manage_options',
			'steps'         => 'manage_options',
			'products'      => 'manage_options',
		];
		return current_user_can( $objects[$object] );
	}


	/**
	 * check if the provided plugin ($path) is installed or not
	 *
	 * @param $path
	 * @return bool
	 * @since 2.0.0
	 */
	public static function is_plugin_installed( $path )
	{
		$plugins = get_plugins();
		return isset($plugins[$path]);
	}



	/**
	 * check if the provided plugin ($path) is installed or not
	 *
	 * @param $path
	 * @return bool
	 * @since 2.0.0
	 */
	public static function is_plugin_activated( $path )
	{
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if(is_plugin_active( $path)) {
			return true;
		}
		return false;
	}



	/**
	 * check plugin status by path
	 *
	 * @param $path
	 * @param $slug
	 * @return string
	 *
	 * @since 2.0.1
	 */
	public static function get_plugin_action($path, $slug)
	{
		if( 'divi-builder' === $slug ){
			$is_divi_theme_active = self::wpfnl_is_theme_active( 'Divi' );
			if( $is_divi_theme_active ){
				return 'nothing';
			}
		}

		if (null == self::$installed_plugins) {
			self::$installed_plugins = get_plugins();
		}

		if (!isset(self::$installed_plugins[$path])) {
			return 'install';
		} elseif (!is_plugin_active($path)) {
			return 'activate';
		} else {
			return 'nothing';
		}
	}

	/**
	 * Check theme is active or not
	 *
	 * @param $theme_name
	 * @return Bool
	 */
	public static function wpfnl_is_theme_active( $theme_name ){
        $theme = wp_get_theme(); // gets the current theme
        if ( $theme_name == $theme->name || $theme_name == $theme->parent_theme ) {
            return true;
        }
        return false;
    }


	/**
     * Check plugin is installed or not
     *
     * @param $plugin_slug
     * @return Bolean
     */
    public static function wpfnl_check_is_plugin_installed( $plugin ){
        $installed_plugins = get_plugins();
        return array_key_exists( $plugin, $installed_plugins ) || in_array( $plugin, $installed_plugins, true );
    }


	/**
	 * get depenedency plugins status
	 *
	 * @return mixed|void
	 *
	 * @since 2.0.1
	 */
	public static function get_dependency_plugins_status()
	{
		return apply_filters('wpfunnels/dependency_plugin_list', array(
			'elementor' => array(
				'name' 			=> 'Elementor',
				'plugin_file' 	=> 'elementor/elementor.php',
				'slug' 			=> 'elementor',
				'action' 		=> self::get_plugin_action('elementor/elementor.php', 'elementor')
			),
			'gutenberg' => array(
				'name' 			=> 'Qubely',
				'plugin_file' 	=> 'qubely/qubely.php',
				'slug' 			=> 'qubely',
				'action' 		=> self::get_plugin_action('qubely/qubely.php', 'qubely')
			),
			'divi-builder' => array(
				'name' 			=> 'Divi',
				'plugin_file' 	=> 'divi-builder/divi-builder.php',
				'slug' 			=> 'divi-builder',
				'action' 		=> self::get_plugin_action('divi-builder/divi-builder.php', 'divi-builder')
			),
			'oxygen' => array(
				'name' 			=> 'Oxygen',
				'plugin_file' 	=> 'oxygen/functions.php',
				'slug' 			=> 'oxygen',
				'action' 		=> self::get_plugin_action('oxygen/functions.php', 'oxygen')
			)
		));
	}


	/**
	 * is there any missing plugin for wpfunnels
	 *
	 * @return string
	 *
	 * @since 2.0.1
	 */
	public static function is_any_plugin_missing()
	{
		if (null == self::$installed_plugins) {
			self::$installed_plugins = get_plugins();
		}
		$builder 			= self::get_builder_type();
		$dependency_plugins = self::get_dependency_plugins_status();
		$is_missing = 'no';

		if (isset($dependency_plugins[$builder])) {

			$plugin_data = $dependency_plugins[$builder];
			if ($plugin_data['action'] === 'activate' || $plugin_data['action'] === 'install') {
				$is_missing = 'yes';
			}
		}
		return $is_missing;
	}


	/**
	 * Recursively traverses a multidimensional array in search of a specific value and returns the
	 * array containing the value, or an
	 * null on failure.
	 *
	 * @param $search_value
	 * @param $array
	 * @return array
	 * @since 2.0.0
	 */
	public static function recursive_multidimensional_ob_array_search_by_value($search_value, $array, $keys = array())
	{
		if (is_array($array) && count($array) > 0) {
			foreach ($array as $key => $value) {
				$temp_keys = $keys;

				// Adding current key to search path
				array_push($temp_keys, $key);

				// Check if this value is an array
				// with atleast one element
				if (is_array($value) && count($value) > 0) {
					$widget_type = isset($value['widgetType']) ? $value['widgetType'] : false;
					if ($widget_type) {
						if ($widget_type === $search_value) {
							$value['path'] = $temp_keys;
							return $value;
						} else {
							$res_path = self::recursive_multidimensional_ob_array_search_by_value(
								$search_value, $value['elements'], $temp_keys);
						}
						if ($res_path != null) {
							return $res_path;
						}
					} else {
						$res_path = self::recursive_multidimensional_ob_array_search_by_value(
							$search_value, $value['elements'], $temp_keys);
					}
					if ($res_path != null) {
						return $res_path;
					}
				}
			}
		}

		return null;
	}


	/**
	 * check if checkout ajax or not
	 *
	 * @return bool
	 */
	public static function is_checkout_ajax() {
		if ( wp_doing_ajax() || isset( $_GET['wc-ajax'] ) ) {
			if ( isset( $_GET['wc-ajax'] ) && //phpcs:ignore
				isset( $_POST['_wcf_checkout_id'] ) //phpcs:ignore
			) {
				return true;
			}
		}

		return false;
	}


	/**
	 * calculate discount price
	 *
	 * @param $discount_type
	 * @param $discount_value
	 * @param $product_price
	 * @return string
	 */
	public static function calculate_discount_price( $discount_type, $discount_value, $product_price ) {
		$custom_price = $product_price;
		if (!empty($discount_type)) {
			if ('discount-percentage' === $discount_type) {
				if ( $discount_value > 0 && $discount_value <= 100) {
					$custom_price = $product_price - (($product_price * $discount_value) / 100);
				}
			} elseif ('discount-price' === $discount_type) {
				if ($discount_value > 0) {
					$custom_price = $product_price - $discount_value;
				}
			}
		}

		return number_format($custom_price, 2);
	}


	/**
	 * get attributes for wpfunnels body wrapper
	 *
	 * @param string $template
	 * @return string
	 */
	public static function get_template_container_atts( $template = '' ) {
		$attributes  = apply_filters( 'wpfunnels/page_container_atts', array() );
		$atts_string = '';
		foreach ( $attributes as $key => $value ) {
			if ( ! $value ) {
				continue;
			}
			if ( true === $value ) {
				$atts_string .= esc_html( $key ) . ' ';
			} else {
				$atts_string .= sprintf( '%s="%s" ', esc_html( $key ), esc_attr( $value ) );
			}
		}
		return $atts_string;
	}


	/**
	 * @param $funnel_id
	 * @return false|string|void
	 */
	public static function get_funnel_link( $funnel_id ) {
		if(!$funnel_id) {
			return;
		}
		$steps 		= self::get_steps($funnel_id);
		if( $steps && is_array($steps) ) {
			$first_step 	= reset($steps);
			$first_step_id 	=  $first_step['id'];
			return get_the_permalink($first_step_id);
		}
		return home_url();
	}


	/**
	 * get wc fragment
	 *
	 * @param array $data
	 * @return array
	 */
	public static function get_wc_fragments( $data = array() ) {
		ob_start();
		woocommerce_order_review();
		$woocommerce_order_review = ob_get_clean();

		ob_start();
		woocommerce_checkout_payment();
		$woocommerce_checkout_payment = ob_get_clean();

		$response = array(
			'cart_total'    		=> WC()->cart->total,
			'wc_custom_fragments'  	=> 'yes',
			'fragments'     		=> apply_filters(
				'woocommerce_update_order_review_fragments',
				array(
					'.woocommerce-checkout-review-order-table' => $woocommerce_order_review,
//					'.woocommerce-checkout-payment' => $woocommerce_checkout_payment,
				)
			),
		);

		if ( ! empty( $data ) ) {
			$response['wpfunnels_data'] = $data;
		}

		return $response;
	}


	/**
	 * get supported builders
	 *
	 * @return array
	 */
	public static function get_supported_builders() {
		$builders = array(
			'elementor' 	=> 'Elementor',
			'gutenberg' 	=> 'Gutenberg',
			'divi-builder' 	=> 'Divi builder',
			'oxygen' 		=> 'Oxygen',
		);
		return apply_filters( 'wpfunnels/supported_builders', $builders );
	}

	/**
	 * Get custom url instead of deafult tahnkyou page
	 *
	 * @param mixed $step_id
	 *
	 * @return [type]
	 */
	public static function custom_url_for_thankyou_page( $step_id ){

		$isThankyou = self::check_if_this_is_step_type_by_id($step_id, 'thankyou');
		$isCustomUrl = get_post_meta( $step_id, '_wpfnl_thankyou_is_custom_redirect', true );
		$isDirect = get_post_meta( $step_id, '_wpfnl_thankyou_is_direct_redirect', true );

		if( $isThankyou && $isCustomUrl === 'on' ){
			$url = get_post_meta( $step_id, '_wpfnl_thankyou_custom_redirect_url', true );
			if( $url ){

				if( $isDirect === 'off' ){
					$redirectAfterSec = get_post_meta( $step_id, '_wpfnl_thankyou_set_time', true ) ? get_post_meta( $step_id, '_wpfnl_thankyou_set_time', true ) : 5;
					header( "refresh:".$redirectAfterSec.";url=".$url );
				}else{
					//unsell 'wpfunnels_automation_data' cookie and trigger 'wpfunnels/trigger_automation' hook
					self::unset_site_cookie( $step_id, 'wpfunnels_automation_data', 'wpfunnels/trigger_automation' );
					return $url;
				}
			}
			return false;
		}
		return false;
	}

	/**
	 * Check product has perfect variation or not
	 *
	 * @param $variation_id
	 * @return Boolean
	 */
	public static function is_perfect_variations( $variation_id ){

		$blank_attr = [];
		$product = wc_get_product( $variation_id );
		$parent_id = $product->get_parent_id();
		$_product = wc_get_product( $parent_id );
		$attrs = self::get_product_attr( $_product );
		$attributes = $product->get_attributes();
		$response = array(
			'status' => true,
			'data' => [],
		);
		foreach($attributes as $attribute_key=>$attribute_value){

			if( !$attribute_value ){
				$blank_attr[$attribute_key] = $attrs[$attribute_key];
				$response['status'] = false;
			}
		}
		$response['data'] = $blank_attr;
		return $response;
	}


	/**
	 * Get attributes of product
	 *
	 * @param Object $produt
	 */
	public static function get_product_attr( $product ){
		$attributes = $product->get_attributes();
		$attr_array = [];
		foreach($attributes as $attribute_key=>$attribute_value){

			$attribute_name = str_replace( 'attribute_', '', $attribute_key );
			$attr_value = $product->get_attribute( $attribute_name );
			$attr_value = strtolower($attr_value);

			if (strpos($attr_value, '|')) {
				$attr_array[$attribute_key] = explode("|",$attr_value);
			}else{
				$attr_array[$attribute_key] = explode(",",$attr_value);
			}
		}
		return $attr_array;

	}



	/**
	 * Remove site cookie
	 *
	 * @param $step_id, $cookie_name, $trigger_hook, $funnel_id
	 *
	 */
	public static function unset_site_cookie( $step_id, $cookie_name, $trigger_hook = '', $funnel_id = '' ){

		if( !$funnel_id ){
			$funnel_id = self::get_funnel_id_from_step( $step_id );
		}

		if( !$funnel_id ){
			return false;
		}

        /** Set Cookie Data */
        $cookie             = isset( $_COOKIE[$cookie_name] ) ? json_decode( wp_unslash( $_COOKIE[$cookie_name] ), true ) : array();
        if(!isset($cookie['funnel_id'])) {
            $cookie['funnel_id']   = $funnel_id;
        }
        $cookie['funnel_status']   = 'successful';

		if(isset( $_COOKIE[$cookie_name] )){
			if( $trigger_hook ){
				do_action( $trigger_hook, $cookie );
			}
        }
		// unsell cookie
        setcookie( $cookie_name, null, strtotime( '-1 days' ), '/', COOKIE_DOMAIN );

	}


	/**
     * Get formated product name
     *
	 * @param Object $product
     * @return String
     */
    public static function get_formated_product_name( $product, $formatted_attr = [] ){
        $_product     = wc_get_product( $product );
		if( !$formatted_attr ){
			$attr_summary = $_product->get_attribute_summary();
			$attr_array   = explode( ",", $attr_summary );
			$each_child_attr = [];
			foreach ( $attr_array as $ata ) {
				$attr              = strpbrk( $ata, ":" );
				$each_child_attr[] = $attr;
			}

		}else{
			foreach ( $formatted_attr as $attr ) {
				$each_child_attr[] = ucfirst($attr);
			}
		}

        $each_child_attr_two = [];
        foreach ( $each_child_attr as $eca ) {
            $each_child_attr_two[] = str_replace( ": ", " ", $eca );
        }

		$_title = $product->get_title() . " - ";
		$_title = $_title . implode( ', ', $each_child_attr_two );
        return $_title;
    }


	/**
	 * Get page builder of a specific funnel by step Id from postmeta
	 *
	 * @param $funnel_id
	 * @return String $builder_name
	 *
	 * @since 2.0.5
	*/
	public static function get_page_builder_by_step_id( $funnel_id ){
		$steps = self::get_steps( $funnel_id );
		$builder_name = '';
		if( isset($steps[0]) ){
			$first_step_id = $steps[0]['id'];
			// check builder is elementor or not
			$elementor_page = get_post_meta( $first_step_id, '_elementor_edit_mode', true );

			// check builder is divi or not
			$divi_page = get_post_meta( $first_step_id, '_et_pb_use_builder', true );

			//check Oxygen builder is not
			$oxygen_page = get_post_meta($first_step_id, 'ct_builder_shortcodes',true);

			if( $elementor_page ) {
				$builder_name = 'elementor';
			} elseif( 'on' === $divi_page ){
				$builder_name = 'divi-builder';
			} elseif (!empty($oxygen_page)){
				$builder_name = 'oxygen';
			} else {
				$builder_name = 'gutenberg';
			}
			if( $builder_name ){
				return $builder_name;
			}
		}
		return $builder_name;
	}


	/**
	 * Get checkout section heading settings
	 *
	 */
	public static function get_checkout_section_heading_settings( $type = '' , $step_id = '' ){

		if( self::is_pro_license_activated() ){
			if( $step_id ){
				$get_settings = get_post_meta( $step_id, '_wpfunnels_edit_field_additional_settings', true );
				if( !empty($get_settings) ){
					if( $type === 'billing' ){

						$settings = array(
							'custom_heading' => $get_settings['custom_billing_heading'],
						);
						return $settings;
					}
					if( $type === 'shipping' ){
						$settings = array(
							'custom_heading' => $get_settings['custom_shipping_heading'],
						);
						return $settings;
					}

					if( $type === 'order' ){
						$settings = array(
							'custom_heading' => $get_settings['custom_order_detail_heading'],
						);
						return $settings;
					}
				}

			}
		}
		return false;
	}

	/**
     * Check webhook addon activated or not
     *
     * @return Boolean
     */
    public static function is_webhook_activated(){
        if( is_plugin_active( 'wpfunnels-pro-webhook/wpfunnels-pro-webhook.php' )){
            return true;
        }
		return false;
    }


	/**
     * Check webhook addon license activated or not
     *
     * @return Boolean
     */
    public static function is_webhook_license_activated(){

        if( is_plugin_active( 'wpfunnels-pro/wpfnl-pro.php' )){
            return true;
        }
		return false;
    }



	/**
	 * Get gbf supported addons
	 */
	public static function get_supported_addons(){

		$addons = array(
			'global_funnel' => array(
				'features' => array(
					'trigger_options' => array(
						''         => __('Please select type..', 'wpfnl'),
						'category' => __('Product category is ..', 'wpfnl'),
						'product'  => __('Product is ..', 'wpfnl'),
					)
				)
			),
		);

		if( defined( 'WPFNL_PRO_GB_VERSION') && version_compare( WPFNL_PRO_GB_VERSION, '1.0.7', '>=')  ){
			$addons['global_funnel']['features']['trigger_options']['all_product'] = __('Any product is selected', 'wpfnl');
		}
		return apply_filters( 'wpfunnels/supported-addons', $addons );
	}


}
