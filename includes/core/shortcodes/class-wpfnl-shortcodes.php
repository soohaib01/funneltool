<?php
namespace WPFunnels\Shortcodes;


use WPFunnels\Data_Store\Wpfnl_Steps_Store_Data;
use WPFunnels\Traits\SingletonTrait;
use WPFunnels\Wpfnl_functions;
use WPFunnels\Shortcodes\Wpfnl_Shortcode_Order_details;
use WPFunnels\Shortcodes\Wpfnl_Shortcode_NextStepButton;
use WPFunnels\Shortcodes\Wpfnl_Shortcode_Checkout;
use WPFunnels\Shortcodes\WC_Shortcode_Optin;


/**
 * Class Wpfnl_Shortcodes
 * @package WPFunnels\Shortcodes
 */
class Wpfnl_Shortcodes {

	use SingletonTrait;


	public static function init() {
		$shortcodes = array(
			'wpfunnels_order_details'	=> __CLASS__ . '::render_order_details',
			'wpf_next_step_button'		=> __CLASS__ . '::render_next_step_button',
			'wpf_checkout'				=> __CLASS__ . '::render_checkout',
			'wpf_optin_form'			=> __CLASS__ . '::render_optin_form',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( $shortcode, $function );
		}
	}

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'thankyou_scripts' ), 21 );
	}


	/**
	 * render order details markup
	 *
	 * @return string
	 *
	 * @since 2.0.3
	 */
	public static function render_order_details( $atts ) {
		$shortcode	= new Wpfnl_Shortcode_Order_details( (array) $atts );
		return $shortcode->get_content();
	}


	/**
	 * Render next step button
	 * 
	 * @return string
	 */
	public static function render_next_step_button( $atts ){
		$shortcode	= new Wpfnl_Shortcode_NextStepButton( (array) $atts );
		return $shortcode->get_content();
	}
	
	
	/**
	 * Render checkout 
	 * 
	 * @return string
	 */
	public static function render_checkout( $atts ){
		$shortcode	= new Wpfnl_Shortcode_Checkout( (array) $atts );
		return $shortcode->get_content();
	}
	
	/**
	 * Render optin form 
	 * 
	 * @return string
	 */
	public static function render_optin_form( $atts ){
		$shortcode	= new WC_Shortcode_Optin( (array) $atts );
		return $shortcode->get_content();
	}


	public function thankyou_scripts() {
		global $post;
		if ( Wpfnl_functions::check_if_this_is_step_type('thankyou') ) {
			$style = $this->generate_style();
			wp_add_inline_style('wpfnl-public', $style);
		}
	}




	public function generate_style()
	{
		global $post;
		$output = '';
		$thankyou = new Wpfnl_Steps_Store_Data();
		$thankyou->read($post->ID);
		$order_overview     = $thankyou->get_internal_metas_by_key('_wpfnl_thankyou_order_overview');
		$order_details      = $thankyou->get_internal_metas_by_key('_wpfnl_thankyou_order_details');
		$billing_details    = $thankyou->get_internal_metas_by_key('_wpfnl_thankyou_billing_details');
		$shipping_details   = $thankyou->get_internal_metas_by_key('_wpfnl_thankyou_shipping_details');
		$style_file = WPFNL_DIR.'public/modules/thankyou/css/dynamic-css.php';
		include $style_file;
		return $output;
	}
}
