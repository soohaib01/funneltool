<?php
/**
 * Order details shortcode class
 */
namespace WPFunnels\Shortcodes;

use WPFunnels\Meta\Wpfnl_Default_Meta;
use WPFunnels\Wpfnl_functions;

/**
 * Class WC_Shortcode_Optin
 * @package WPFunnels\Shortcodes
 */
class Wpfnl_Shortcode_Checkout {

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
				'type' 			=> 'two-column',
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

		if( Wpfnl_functions::check_if_this_is_step_type('checkout') ) {

            //===Coupon Enabler===//
            $coupon_enabler = get_post_meta(get_the_ID(), '_wpfnl_checkout_coupon', true);
            if ( $coupon_enabler != 'yes' ) {
                remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
            }

            $checkout_layout = '';
            if( $this->attributes['type'] == 'one-column' ){
                $checkout_layout = 'wpfnl-col-1';
            }elseif( $this->attributes['type'] == 'two-column' ){
                $checkout_layout = 'wpfnl-col-2';
            }else{
                if( Wpfnl_functions::is_wpfnl_pro_activated() && 'multistep' === $this->attributes['type'] ){
                    $checkout_layout = 'wpfnl-multistep';
                }elseif( !Wpfnl_functions::is_wpfnl_pro_activated() && 'multistep' === $this->attributes['type'] ) {
                    $checkout_layout = 'wpfnl-col-2';
                }
            }
			query_posts('post_type="checkout"');
			$order_bump = get_post_meta(get_the_ID(), 'order-bump', true);
			if($order_bump == 'yes'){
				do_action( 'wpfunnels/before_checkout_form', get_the_ID() );
			}
            $html = '';
            $html .= '<div class="wpfnl-checkout '.$checkout_layout.'">';
            $html .=  do_shortcode('[woocommerce_checkout]');
            $html .= '</div>';

			$this->render_order_bump();
			
            return $html;
		}
		return false;
	}


	/**
	 * Render order bump
	 * 
	 */
	public function render_order_bump(){
		add_action( 'wpfunnels/before_checkout_form', array( $this, 'load_actions' ), 10, 2 );
	}

	public function load_actions( $checkout_id, $settings = array() ) {
		
		$checkout_meta 			= new Wpfnl_Default_Meta();
		$is_order_bump_enabled 	= $checkout_meta->get_checkout_meta_value($checkout_id, 'order-bump');
		$funnel_id				= get_post_meta( $checkout_id, '_funnel_id', true );
		if ( 'yes' !== $is_order_bump_enabled ) {
			return;
		}
		$this->ob_settings = $checkout_meta->get_checkout_meta_value($checkout_id, 'order-bump-settings', wpfnl()->meta->get_default_order_bump_meta());
		$this->ob_settings = apply_filters( 'wpfunnels/order_bump_settings', $this->ob_settings, $funnel_id, $checkout_id );
		$this->trigger_ob_actions();
	}


	/**
	 * trigger WC action for order bump
	 */
	private function trigger_ob_actions() {

		$position = $this->get_order_bump_attribute( $this->ob_settings, 'position' );

		if( !$position ) {
			return;
		}

		switch ($position) {
			case 'before-checkout':
				add_action('woocommerce_before_checkout_form', [$this, 'render_order_bump'], 10);
				break;
			case 'after-order':
				add_action('woocommerce_checkout_order_review', [$this, 'render_order_bump'], 8);
				break;
			case 'after-customer-details':
				add_action('woocommerce_after_order_notes', [$this, 'render_order_bump'], 8);
				break;
			case 'before-payment':
				add_action('woocommerce_review_order_before_payment', [$this, 'render_order_bump'], 8);
				break;
			case 'after-payment':
				add_action('woocommerce_review_order_after_payment', [$this, 'render_order_bump'], 8);
				break;
			case 'popup':
				$this->ob_settings['selectedStyle'] = 'popup';
				$this->render_popup_in_elementor_editor();
				break;
		}
	}


	/**
     * get order bump attribute from order bump settings data
     *
     * @param $order_bump_data
     * @param $key
     * @return bool|mixed
     */
    private function get_order_bump_attribute( $order_bump_data, $key ) {
        if( !isset($order_bump_data[$key]) ) {
            return false;
        }
        return $order_bump_data[$key];
    }


	/**
     * render popup style for order bump in elementor
     * builder preview
     *
     * @since 2.0.3
     */
    public function render_popup_in_elementor_editor() {
        if(Wpfnl_functions::is_elementor_active()) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode() || is_admin()) {
                add_action( 'woocommerce_before_checkout_form', [ $this, 'render_order_bump' ], 10 );
            } else {
				add_action( 'woocommerce_before_checkout_form', [ $this, 'render_order_bump' ], 10 );
			}
        } else {
			add_action( 'woocommerce_before_checkout_form', [ $this, 'render_order_bump' ], 10 );
		}
    }

}
