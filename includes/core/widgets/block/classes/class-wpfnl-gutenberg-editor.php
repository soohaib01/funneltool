<?php

namespace WPFunnels\Widgets\Gutenberg;

use WPFunnels\Meta\Wpfnl_Default_Meta;
use WPFunnels\Traits\SingletonTrait;
use WPFunnels\Wpfnl;
use WPFunnels\Wpfnl_functions;

class Wpfnl_Gutenberg_Editor {

	use SingletonTrait;

	public function __construct() {
		$this->gb_compatibility();
	}


	private function gb_compatibility() {
		add_action( 'admin_init', array( $this, 'editor_compatibility' ) );
		add_action( 'wpfunnels/before_gb_checkout_form_ajax', array( $this, 'load_gb_cf_ajax_action' ), 10, 2 );
	}


	/**
	 * editor compatibility action for gutenberg
	 *
	 * @since 2.0.3
	 */
	public function editor_compatibility() {

		if( Wpfnl_functions::is_step_edit_page() && Wpfnl_functions::is_wc_active() ) {
			if(is_admin()) {
				add_filter( 'wpfunnels/show_dummy_order_details', '__return_true' );
			}
			$this->before_checkout_actions();
			$frontend = Wpfnl::get_instance()->frontend;
			add_filter('woocommerce_locate_template', array( $frontend, 'wpfunnels_woocommerce_locate_template' ), 20, 3);

			$step_id = isset($_POST['post_id']) ? intval( $_POST['post_id'] ) : 0;

			$show_coupon = Wpfnl::get_instance()->meta->get_checkout_meta_value( $step_id, '_wpfnl_checkout_coupon' );
			if( 'no' === $show_coupon ) {
				remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form' );
			}
		}
	}

	/**
	 * before checkout shortcode actions
	 *
	 * @since 2.0.3
	 */
	private function before_checkout_actions() {
		wc()->frontend_includes();
		/* For preview */
		add_filter('woocommerce_checkout_redirect_empty_cart', '__return_false');
	}


	/**
	 * this will be only triggered on checkout form block. and only in the
	 * editor page on ajax call
	 *
	 * @param $checkout_id
	 * @param $post_data
	 */
	public function load_gb_cf_ajax_action( $checkout_id, $post_data ) {
		$default_settings	= Wpfnl_Default_Meta::get_default_order_bump_meta();
		$ob_data 			= isset( $post_data['order_bump_data'] ) ? $post_data['order_bump_data'] : $default_settings;
		$ob_settings 		= $this->get_order_bump_settings_for_preview( $checkout_id, $ob_data );
		do_action('wpfunnels/gb_render_order_bump_ajax', $checkout_id, $ob_data );
	}


	/**
	 * get order bump settings for preview
	 *
	 * @param $post_id
	 * @param $post_data
	 * @return mixed
	 *
	 * @since 2.0.4
	 */
	private function get_order_bump_settings_for_preview( $post_id, $post_data ) {
		$order_bump_settings 	= get_post_meta( $post_id, 'order-bump-settings', true );
		if( $order_bump_settings ) {
			return $this->replace_ob_settings_with_block_data( $order_bump_settings, $post_data );
		}
		return $post_data;
	}


	/**
	 * replace ob settings with widget data
	 *
	 * @param $order_bump_settings
	 * @param $post_data
	 * @return mixed
	 *
	 * @since 2.0.4
	 */
	private function replace_ob_settings_with_block_data( $order_bump_settings, $post_data ) {
		if( empty($post_data) ) {
			return $order_bump_settings;
		}
		$order_bump_settings['checkBoxLabel'] 			= isset( $post_data['checkBoxLabel'] ) ? $post_data['checkBoxLabel'] : $post_data['checkBoxLabel'];
		$order_bump_settings['highLightText'] 			= isset( $post_data['highLightText'] ) ? $post_data['highLightText'] : $post_data['highLightText'];
		$order_bump_settings['productDescriptionText'] 	= isset( $post_data['productDescriptionText'] ) ? $post_data['productDescriptionText'] : $post_data['productDescriptionText'];
		$order_bump_settings['position'] 				= isset( $post_data['position'] ) ? $post_data['position'] : $post_data['position'];
		$order_bump_settings['selectedStyle'] 			= isset( $post_data['selectedStyle'] ) ? $post_data['selectedStyle'] : $post_data['selectedStyle'];
		$order_bump_settings['productImage'] 			= isset( $post_data['productImage'] ) ? $post_data['productImage'] : $post_data['productImage'];
		$order_bump_settings['isEnabled'] 				= isset( $post_data['isEnabled'] ) ? $post_data['isEnabled'] : $post_data['isEnabled'];
		return $order_bump_settings;

	}
}
