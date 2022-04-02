<?php
/**
 * Order details shortcode class
 */
namespace WPFunnels\Shortcodes;

use WPFunnels\Wpfnl_functions;

/**
 * Class WC_Shortcode_Optin
 * @package WPFunnels\Shortcodes
 */
class Wpfnl_Shortcode_NextStepButton {

	/**
	 * Attributes
	 *
	 * @var array
	 */
	protected $attributes = array();


	/**
	 * Wpfnl_Shortcode_Order_details constructor.
	 * @param array $attributes
	 */
	public function __construct( $attributes = array() ) {
		
		$this->attributes = $this->parse_attributes( $attributes );
		
		
	}


	/**
	 * Get shortcode attributes.
	 *
	 * @since  3.2.0
	 * @return array
	 */
	public function get_attributes() {
		return $this->attributes;
	}


	/**
	 * parse attributes
	 *
	 * @param $attributes
	 * @return array
	 */
	protected function parse_attributes( $attributes ) {
		
		$attributes = shortcode_atts(
			array(
				'btn_text' 			=> '',
				'background_color' 	=> '',
				'color' 			=> '',
				'padding' 			=> '',
			),
			$attributes
		);
		return $attributes;
	}

	/**
	 * get wrapper classes
	 *
	 * @return array
	 */
	protected function get_wrapper_classes() {
		$classes = array( 'wpfnl', 'wpfnl-order-details-wrapper' );
		return $classes;
	}


	/**
	 * content of optin form
	 *
	 * @return string
	 */
	public function get_content() {
		if( Wpfnl_functions::check_if_this_is_step_type('landing') ) {
			$products_array = get_post_meta(get_the_ID(), 'checkout_product_selector', true);
			$products = '';
			if ($products_array) {
				$products = implode(",", $products_array);
			}
			
			$html = '';
			do_action( 'wpfunnels/before_next_step_button' );
			$html .= '<div class="next-step-button-wrapper">';
			$html .=  '<a href="#" data-id="'.get_the_ID().'" data-products="'.$products.'"';
			$html .=  'id="wpfunnels_next_step_controller"';
			$html .=  'style="cursor: pointer;background-color: '.$this->attributes['background_color'].';color: '.$this->attributes['color'].'; padding: '.$this->attributes['padding'].';" class="next-step-button btn-default">';
			$html .=  isset($this->attributes['btn_text']) && $this->attributes['btn_text'] ? $this->attributes['btn_text'] : 'Get Now';
			$html .= '</a></div><span class="wpfnl-alert" id="wpfnl-next-button-loader"></span>';
			do_action( 'wpfunnels/after_next_step_button' );

			return  $html;
		}
		return false;
	}
}
