<?php

namespace WPFunnels\Classes\OrderBumpActions;

use WPFunnels\Meta\Wpfnl_Default_Meta;
use WPFunnels\Traits\SingletonTrait;
use WPFunnels\Wpfnl_functions;

class Wpfnl_Order_Bump_Action {

    use SingletonTrait;

    protected $ob_settings;

    public function __construct() {

		add_action( 'woocommerce_checkout_after_order_review', array( $this, 'add_order_bump_hidden_fields' ), 99 );
		add_action( 'wpfunnels/elementor_render_order_bump', array( $this, 'load_elementor_actions' ), 10, 2 );
        add_action( 'wpfunnels/gb_render_order_bump_ajax', array( $this, 'load_gb_actions' ), 10, 2 );
        add_action( 'wpfunnels/before_checkout_form', array( $this, 'load_actions' ), 10, 2 );
//        add_filter( 'wpfunnels/order_bump_settings', array( $this, 'add_extra_ob_data' ), 10, 4 );
    }


    public function add_order_bump_hidden_fields() {
		$checkout_meta 			= new Wpfnl_Default_Meta();
		$is_order_bump_enabled 	= $checkout_meta->get_checkout_meta_value(get_the_ID(), 'order-bump');
		if ( 'yes' === $is_order_bump_enabled ) {
			echo '<input type="hidden" name="_wpfunnels_order_bump_product" value="">';
		}
		
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
	 * load elementor action for order bump preview
	 *
	 * @param $checkout_id
	 * @param $settings
	 */
	public function load_elementor_actions( $checkout_id, $settings ) {
		$is_order_bump_enabled  = $settings['isEnabled'];
		$funnel_id				= get_post_meta( $checkout_id, '_funnel_id', true );
		if ( 'yes' !== $is_order_bump_enabled ) {
			return;
		}
		$this->ob_settings = $settings;
		$this->ob_settings = apply_filters( 'wpfunnels/order_bump_settings', $this->ob_settings, $funnel_id, $checkout_id );
		$this->trigger_ob_actions();
	}


	/**
	 * load action hooks for order bump render
	 *
	 * @param $checkout_id
	 * @param $ob_settings
	 *
	 * @since 2.0.4
	 */
    public function load_gb_actions( $checkout_id, $ob_settings ) {
		$is_order_bump_enabled  = $ob_settings['isEnabled'];
		$funnel_id				= get_post_meta( $checkout_id, '_funnel_id', true );
		if ( 'yes' !== $is_order_bump_enabled ) {
            return;
        }

		$this->ob_settings = $ob_settings;
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
	 * add order bump extra meta data
	 * to main settings data
	 *
	 * @param $ob_settings
	 * @param $funnel_id
	 * @param $checkout_id
	 * @return array
	 */
	public function add_extra_ob_data( $ob_settings, $funnel_id, $checkout_id ) {
		$ob_settings['step_id']			= $checkout_id;
		$checkout_meta					= new Wpfnl_Default_Meta();
		$main_product_ids 				= $checkout_meta->get_main_product_ids( $funnel_id, $checkout_id );
		$ob_settings['main_products'] 	= $main_product_ids;
		return $ob_settings;
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


    /**
     * render order bump markup
     *
     * @since 2.0.3
     */
    public function render_order_bump() {

		global $post;
        $checkout_id = 0;
        $output = '';
        if ( $post ) {
            $checkout_id = $post->ID;
        } elseif ( is_admin() && isset( $_POST['post_id'] ) ) {
            $checkout_id = intval( $_POST['post_id'] );
        }


		if( Wpfnl_functions::check_if_this_is_step_type_by_id( $checkout_id, 'checkout' ) ) {
            if ( ! empty( $_POST['order_bump_data'] ) ) {
                $settings = $_POST['order_bump_data'];
            } else {
                $settings = $this->ob_settings;
            }

			if (isset($settings['product']) && $settings['product'] != '') {
                if ( !empty($settings['selectedStyle']) ) {
                    ob_start();
                    if ( $settings['position'] == 'popup' ) {
                        if(Wpfnl_functions::is_elementor_active()) {
                            if (\Elementor\Plugin::$instance->editor->is_edit_mode() || is_admin()) {
                                echo '<h5 style="margin-bottom: 15px;"><strong>' . __('To see the pop-up offer in action, please preview or view the page.', 'wpfnl') . '</strong></h5>';
                            } else {
								$order_bump_settings = $this->ob_settings;
                                require_once WPFNL_DIR . 'public/modules/checkout/templates-style/order-bump-template-' . $settings['selectedStyle'] . '.php';
                            }
                        } else {
							echo '<h5 style="margin-bottom: 15px;"><strong>' . __('To see the pop-up offer in action, please preview or view the page.', 'wpfnl') . '</strong></h5>';
						}
                    } else {
						$order_bump_settings = $this->ob_settings;
                        require_once WPFNL_DIR . 'public/modules/checkout/templates-style/order-bump-template-' . $settings['selectedStyle'] . '.php';
                    }
                    $output .= ob_get_clean();
                }
            }
			else {
				wc_clear_notices();
				wc_add_notice(__('No product is added to this order bump. Please select one.', 'wpfnl'), 'error');
			}
        }

        echo $output;
    }
}
