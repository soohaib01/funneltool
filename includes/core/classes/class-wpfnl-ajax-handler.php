<?php

/**
 * ajax handler
 */
namespace WPFunnels\Ajax_Handler;

use Elementor\Plugin;
use Elementor\Utils;
use WPFunnels\Optin\Optin_Record;
use WPFunnels\Wpfnl_functions;

/**
 * Class Ajax_Handler
 * @package WPFunnels\Widgets\Elementor
 */
class Ajax_Handler {

	public function __construct() {
		add_action( 'wp_ajax_wpfnl_optin_submission', [ $this, 'optin_form_submission' ] );
		add_action( 'wp_ajax_nopriv_wpfnl_optin_submission', [ $this, 'optin_form_submission' ] );

		add_action( 'wp_ajax_wpfnl_shortcode_optin_submission', [ $this, 'wpfnl_shortcode_optin_submission' ] );
		add_action( 'wp_ajax_nopriv_wpfnl_shortcode_optin_submission', [ $this, 'wpfnl_shortcode_optin_submission' ] );

		add_action( 'wp_ajax_wpfnl_gutenberg_optin_submission', [ $this, 'gutenberg_optin_form_submission' ] );
		add_action( 'wp_ajax_nopriv_wpfnl_gutenberg_optin_submission', [ $this, 'gutenberg_optin_form_submission' ] );
	}


	/**
	 * optin form submission
	 */
	public function optin_form_submission() {
		check_ajax_referer( 'optin_form_nonce', 'security' );

		$step_id 	= isset($_POST['step_id']) ? $_POST['step_id'] : '';
		$postData 	= isset($_POST['postData']) ? $_POST['postData'] : '';
		$funnel_id	= Wpfnl_functions::get_funnel_id_from_step($step_id);
		$post_data  = array();
		parse_str($postData, $post_data);

		// init elementor to retrieve setting for optin form widget
		$elementor 	= Plugin::instance();
		$document 	= $elementor->documents->get( $step_id );
		$form 		= null;
		if ( $document ) {
			$form = Utils::find_element_recursive( $document->get_elements_data(), $post_data['form_id'] );
		}

		if ( empty( $form ) ) {
			$results = array(
				'message'	=> 'invalid_form',
				'success'	=> false
			);
			echo json_encode($results);
			die();
		}

		$widget 			= $elementor->elements_manager->create_element_instance( $form );
		$form['settings'] 	= $widget->get_settings_for_display();
		unset($post_data['post_id']);
		unset($post_data['form_id']);

		/**
		 * record
		 */
		$record 			= new Optin_Record( $post_data, $form );
		$fields				= $record->get_fields();
		$name				= '';
		if ($fields) {
			foreach ( $fields as $key => $value ) {
				if ('email' === $key) {
					$name = strstr($value,'@',true);;
				} elseif( 'last_name' === $key ) {
					$name = $value;
				} elseif ( 'first_name' === $key ) {
					$name = $value;
				}
			}
		}

		$response 	= array(
			'success'			=> true,
			'post_action' 		=> 'notification',
			'notification_text' => '',
			'redirect_url'		=> '#'
		);

		$post_action 					= $form['settings']['post_action'];
		$response['post_action'] 		= $post_action;
		$action_type 					= '';
		$response['notification_text'] 	= isset($form['settings']['notification_text']) && $form['settings']['notification_text'] ? $form['settings']['notification_text'] : '';

		switch ($post_action) {
			case 'notification':
				$response['redirect']	= false;
				break;
			case 'redirect_to':
				$action_type 			= 'redirect_to_url';
				$response['redirect']	= true;
				if( !empty($form['settings']['redirect_url']) ){
					$response['redirect_url'] 	= add_query_arg( array(
						'optin' => true,
						'uname'  => $name,
					), $form['settings']['redirect_url']['url'] );
				} else{
					$response['redirect_url'] = '#';
				}
				break;
			default:
				$action_type 				= 'next_step';
				$next_step 					= Wpfnl_functions::get_next_step( $funnel_id, $step_id );
				$response['redirect_url'] 	= add_query_arg( array(
					'optin' => true,
					'uname'  => $name,
				), get_the_permalink($next_step['step_id']) );
				$response['redirect']		= true;
		}

		// $admin_email_text 		= isset( $form['settings']['admin_email_text'] ) ? $form['settings']['admin_email_text'] : '';
		$admin_email 	 		= isset( $form['settings']['admin_email'] ) ? $form['settings']['admin_email'] : false;
		$admin_email_subject 	= isset( $form['settings']['admin_email_subject'] ) ? $form['settings']['admin_email_subject'] : 'Opt-in form Submission';

		$user_info = $record->form_data;
		if( $admin_email && $admin_email_subject && $user_info['email']){
			$this->send_email_to_admin( $admin_email, $admin_email_subject, $user_info );
		}

		/**
		 * submit & process form data
		 */

		do_action( 'wpfunnles/after_optin_submit', $step_id, $post_action, $action_type, $record );
		echo json_encode($response, true);
		die();
	}


	/**
	 * wpfnl shortcode optin submission
	 */
	public function wpfnl_shortcode_optin_submission(){
		check_ajax_referer( 'optin_form_nonce', 'security' );
		$response 	= array(
			'success'			=> true,
			'post_action' 		=> 'notification',
			'notification_text' => '',
			'redirect_url'		=> '#'
		);
		$postData 	= isset($_POST['postData']) ? $_POST['postData'] : '';
		parse_str($postData, $post_data);

		$step_id    = isset($post_data['post_id']) ? $post_data['post_id'] : '';
		$funnel_id = '';
		if( $step_id ){
			$funnel_id	= Wpfnl_functions::get_funnel_id_from_step($step_id);
		}

		$post_action 					= isset($post_data['post_action']) && $post_data['post_action'] ? $post_data['post_action'] : 'notification';
		$response['post_action'] 		= $post_action;
		$action_type 					= '';
		$response['notification_text'] 	= isset($post_data['notification_text']) && $post_data['notification_text'] ? $post_data['notification_text'] : '';
		$user_info = array();
		if ($post_data) {
			foreach ( $post_data as $key => $value ) {
				if ('email' === $key) {
					$name = strstr($value,'@',true);
					$user_info['email'] = $value;
				} elseif( 'last_name' === $key ) {
					$name = $value;
					$user_info['last_name'] = $value;
				} elseif ( 'first_name' === $key ) {
					$name = $value;
					$user_info['first_name'] = $value;
				}elseif ( 'phone' === $key ) {
					$name = $value;
					$user_info['phone'] = $value;
				}
			}
		}
		switch ($post_action) {
			case 'notification':
				$response['redirect']	= false;
				break;
			case 'redirect_to':
				$action_type 			= 'redirect_to_url';
				$response['redirect']	= true;
				if( !empty($post_data['redirect_url']) ){
					if( $post_data['redirect_url'] !== 'next_step' ){
						$action_type 			= 'redirect_to_url';
						$response['redirect_url'] 	= add_query_arg( array(
							'optin' => true,
							'uname'  => $name,
						), $post_data['redirect_url'] );

					}elseif( $post_data['redirect_url'] == 'next_step' ){
						$action_type 				= 'next_step';

						$next_step 					= $funnel_id ? Wpfnl_functions::get_next_step( $funnel_id, $step_id ) : '';
						if( $next_step ){
							$response['redirect_url'] 	= add_query_arg( array(
								'optin' => true,
								'uname'  => $name,
							), get_the_permalink($next_step['step_id']) );
							$response['redirect_url'] = get_the_permalink($next_step['step_id']);
						}else{
							$response['redirect_url'] = '#';
						}
					}

				} else{
					$response['redirect_url'] = '#';
				}
				break;
			default:
				$action_type 				= 'next_step';
				$next_step 					= $funnel_id ? Wpfnl_functions::get_next_step( $funnel_id, $step_id ) : '';
				if( $next_step ){
					$response['redirect_url'] 	= add_query_arg( array(
						'optin' => true,
						'uname'  => $name,
					), get_the_permalink($next_step['step_id']) );
				}else{
					$response['redirect_url'] = '#';
				}
				$response['redirect']		= true;
		}

		$admin_email 	 		= isset( $post_data['admin_email'] ) && $post_data['admin_email'] ? $post_data['admin_email'] : false;
		$admin_email_subject 	= isset( $post_data['admin_email_subject'] ) && $post_data['admin_email_subject'] ? $post_data['admin_email_subject'] : 'Opt-in form Submission';

		if( $admin_email && $admin_email_subject && isset($user_info['email']) && $user_info['email']){
			$this->send_email_to_admin( $admin_email, $admin_email_subject, $user_info );
		}
		$record = (object)[];
		$record->form_data = $user_info;
		do_action( 'wpfunnles/after_optin_submit', $step_id, $post_action, $action_type, $record );
		echo json_encode($response, true);
		die();
	}


	/**
	 * Gutenberg opt-in form handling
	 */
	public function gutenberg_optin_form_submission(){
		check_ajax_referer( 'optin_form_nonce', 'security' );
		$step_id 	= isset($_POST['step_id']) ? $_POST['step_id'] : '';
		$postData 	= isset($_POST['postData']) ? $_POST['postData'] : '';
		$funnel_id	= Wpfnl_functions::get_funnel_id_from_step($step_id);
		$post_data  = array();
		parse_str($postData, $post_data);

		$post_id = $step_id;
		$post = get_post($post_id);
		$all_blocks = parse_blocks($post->post_content);

		$admin_email = '';
		$admin_email_subject = '';
		$post_action = '';
		$block_attr = array();


		/**
		 * record
		 */
		$record 			= new Optin_Record( $post_data );
		$fields				= $record->get_fields();
		$name				= '';
		if ($fields) {
			foreach ( $fields as $key => $value ) {
				if ('email' === $key) {
					$name = strstr($value,'@',true);;
				} elseif( 'last_name' === $key ) {
					$name = $value;
				} elseif ( 'first_name' === $key ) {
					$name = $value;
				}
			}
		}

		$response 	= array(
			'success'			=> true,
			'post_action' 		=> 'notification',
			'notification_text' => '',
			'redirect_url'		=> '#'
		);
		$custom_url = '';
		$blocks = $this->search_items_by_key($all_blocks, 'blockName');
		foreach ($blocks as $block) {
			if( $block['blockName'] == 'wpfunnels/optin-form' ){
				$block_attr = $block['attrs'];

				$admin_email 	= isset( $block_attr['adminEmail'] ) ? $block_attr['adminEmail'] : '';
				$admin_email_subject 	= isset( $block_attr['emailSubject'] ) ? $block_attr['emailSubject'] : 'Opt-in form Submission';

				$post_action 	= isset( $block_attr['postAction'] ) ? $block_attr['postAction'] : 'notification';
				$custom_url 	= isset( $block_attr['redirect_url'] ) ? $block_attr['redirect_url'] : '#';
			}
		}

		$response['notification_text'] = isset($block_attr['notification']) ? $block_attr['notification'] : '';

		switch ($post_action) {
			case 'notification':
				$action_type 			= 'notification';
				$response['redirect']	= false;
				break;
			case 'redirect_to':
				$action_type 			= 'redirect_to_url';
				$response['redirect']	= true;
				$response['post_action']= $post_action;
				if( $custom_url ){
					$response['redirect_url'] 	= add_query_arg( array(
						'optin' => true,
						'uname'  => $name,
					), $custom_url );
				} else{
					$response['redirect_url'] = '#';
				}
				break;
			case 'next_step':
				$response['post_action']	= $post_action;
				$action_type 				= 'next_step';
				$next_step 					= Wpfnl_functions::get_next_step( $funnel_id, $step_id );
				$response['redirect_url'] 	= add_query_arg( array(
					'optin' => true,
					'uname'  => $name,
				), get_the_permalink($next_step['step_id']) );
				$response['redirect']		= true;
				break;
			default:
				$action_type 				= 'next_step';
				$next_step 					= Wpfnl_functions::get_next_step( $funnel_id, $step_id );
				$response['redirect_url'] 	= add_query_arg( array(
					'optin' => true,
					'uname'  => $name,
				), get_the_permalink($next_step['step_id']) );
				$response['redirect']		= true;
		}


		if( $admin_email && $admin_email_subject && $post_data['email']){
			$this->send_email_to_admin( $admin_email, $admin_email_subject, $post_data );
		}

		do_action( 'wpfunnles/after_optin_submit', $step_id, $post_action, $action_type, $record );
		echo json_encode($response, true);
		die();
	}


	private function search_items_by_key($array, $key){
		$results = array();

		if (is_array($array))
		{
			if (isset($array[$key]) && key($array)==$key)
				if( $array[$key] == 'wpfunnels/optin-form' ){
					$results[] = $array;
				}

			foreach ($array as $sub_array)
				$results = array_merge($results, $this->search_items_by_key($sub_array, $key));
		}

		return  $results;
	}


	/*
	 * Send email to admin
	 */
	private function send_email_to_admin( $email = '', $subject = '', $user_info = [] ){

		$current_date = date("d M Y");
		$current_time = date("h:i A");
		$poweredBy = __('WPFunnels','wpfnl');
		$info = '';
		if( isset($user_info['first_name']) && $user_info['first_name'] ){
			$info .= "First Name : {$user_info['first_name']}<br>";
		}
		if( isset($user_info['last_name']) && $user_info['last_name'] ){
			$info .= "Last Name : {$user_info['last_name']}<br>";
		}
		if( isset($user_info['email']) && $user_info['email'] ){
			$info .= "Email : {$user_info['email']}<br><br>";
		}
		if( isset($user_info['phone']) && $user_info['phone'] ){
			$info .= "Phone : {$user_info['phone']}<br><br>";
		}
		$info .= "----<br><br>Date : {$current_date} <br>Time : {$current_time} <br>Powered by : {$poweredBy} <br>";

		$email_body = $info;

		$headers = ['Content-Type: text/html; charset=UTF-8'];
		wp_mail( $email, $subject, $email_body, $headers);
	}
}
