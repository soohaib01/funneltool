<?php


namespace WPFunnels\Modules\Frontend\Checkout;

use WPFunnels\Data_Store\Wpfnl_Steps_Store_Data;
use WPFunnels\Frontend\Module\Wpfnl_Frontend_Module;
use WPFunnels\Meta\Wpfnl_Default_Meta;
use WPFunnels\Wpfnl;
use WPFunnels\Wpfnl_functions;
use WPFunnels\Modules\Frontend\Checkout\Variable\Wpfnl_Variable_Product;
use WPFunnels\Modules\Frontend\Checkout\Single\Wpfnl_Single_Product;
use function cli\err;

class Module extends Wpfnl_Frontend_Module
{
	public $funnel_id;

	public $step_id;

	// const CHECKOUT = "";

	public function __construct()
	{

		/* set checkout flag */
		add_filter('woocommerce_is_checkout', [$this, 'checkout_flag'], 9999);
		add_action('woocommerce_checkout_update_order_meta', [$this, 'save_checkout_fields'], 10, 2);

		/* initialize cart data */
		add_action('wp', [$this, 'initialize_cart_data']);

		add_action('wp', [$this, 'init_wc_actions']);
		/* register checkout shortcode */
		add_shortcode('wpfunnels_checkout', array($this, 'render_checkout_shortcode'));
		add_action('wp_ajax_wpfnl_next_button_ajax', [$this, 'wpfnl_next_button_ajax']);
		add_action('wp_ajax_nopriv_wpfnl_next_button_ajax', [$this, 'wpfnl_next_button_ajax']);

		add_action( 'wp_ajax_wpfnl_order_bump_ajax', [$this, 'wpfnl_order_bump_ajax']);
		add_action( 'wp_ajax_nopriv_wpfnl_order_bump_ajax', [$this, 'wpfnl_order_bump_ajax']);

		add_action( 'wp_ajax_wpfnl_update_variation', [$this, 'wpfnl_update_variation']);
		add_action( 'wp_ajax_nopriv_wpfnl_update_variation', [$this, 'wpfnl_update_variation']);

		add_action( 'woocommerce_before_calculate_totals', [$this, 'custom_price_to_cart_item'], 9999);

		add_action( 'wp_ajax_nopriv_wpfnl_checkout_cart', [$this, 'wpfnl_checkout_cart']);
		add_filter('woocommerce_cart_product_out_of_stock_message', [$this,'wpfnl_out_of_stock_message_in_checkout'], 10, 2);

		add_action( 'wpfunnels/before_checkout_form', array($this, 'before_checkout_form_actions') );

		add_action( 'woocommerce_after_order_notes', array( $this, 'wpfnl_set_checkout_id' ), 999 );
		add_action( 'woocommerce_login_form_end', array( $this, 'wpfnl_set_checkout_id' ), 999 );
		add_filter( 'woocommerce_login_redirect', [$this,'wpfnl_redirect_after_login'], 10, 2 );

	}



	/**
	 * set checkout flag to true if this is checkout step type
	 *
	 * @param $is_checkout
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function checkout_flag($is_checkout)
	{
		if (Wpfnl_functions::check_if_this_is_step_type('checkout')) {
			$is_checkout = true;
		}
		return $is_checkout;
	}


	/**
	 * init WooCommerce related actions
	 */
	public function init_wc_actions() {
		add_action( 'woocommerce_after_order_notes', [$this, 'checkout_shortcode_metas']);
		$this->display_coupon_field();
		$this->enable_checkout_hook();
	}


	/**
	 * Enable checkout hook
	 */
	private function enable_checkout_hook(){

		if ( Wpfnl_functions::check_if_this_is_step_type('checkout') ) {

			if( is_plugin_active( 'woocommerce-german-market/WooCommerce-German-Market.php' )){
				add_action( 'woocommerce_checkout_order_review','woocommerce_checkout_payment' );
			}
		}
	}


	/**
	 * save checkout metas
	 *
	 * @param $order_id
	 * @param $posted
	 *
	 * @since 1.0.0
	 */
	public function save_checkout_fields( $order_id, $posted )
	{

		if (isset($_POST['_wpfunnels_checkout_id'])) {
			$checkout_id = sanitize_text_field($_POST['_wpfunnels_checkout_id']);
			$checkout_id = wc_clean($checkout_id);
			update_post_meta($order_id, '_wpfunnels_checkout_id', $checkout_id);
			if (isset($_POST['_wpfunnels_funnel_id'])) {
				$funnel_id = sanitize_text_field($_POST['_wpfunnels_funnel_id']);
				$funnel_id = wc_clean($funnel_id);
				update_post_meta($order_id, '_wpfunnels_funnel_id', $funnel_id);
			}
			if (isset($_POST['_wpfunnels_order_unique_identifier'])) {
				$unique_identifier = sanitize_text_field($_POST['_wpfunnels_order_unique_identifier']);
				$unique_identifier = wc_clean($unique_identifier);
				update_post_meta($order_id, '_wpfunnels_order_unique_identifier', $unique_identifier);
			}
		}

		if ( isset( $_POST['_wpfunnels_order_bump_product'] ) ) {
			$ob_product_id = wc_clean( sanitize_text_field( wp_unslash( $_POST['_wpfunnels_order_bump_product'] ) ) );
			update_post_meta( $order_id, '_wpfunnels_order_bump_product', $ob_product_id );
			$order = wc_get_order( $order_id );
			foreach ($order->get_items() as $order_item_id => $order_item) {
				if($_POST['_wpfunnels_order_bump_product'] == $order_item['product_id']){
					wc_add_order_item_meta($order_item_id, '_wpfunnels_order_bump', 'yes');
				}
			}
		}

		if (isset($funnel_id)) {
			$order = wc_get_order($order_id);
    		$user_id = $order->get_user_id();
			$orders[sanitize_text_field($_POST['_wpfunnels_checkout_id'])] = $order_id;
			WC()->session->set('wpfnl_orders_'.$user_id.'_'.$funnel_id, $orders);
			do_action( 'wpfunnels/funnel_order_placed', $order_id, $funnel_id );
		}
	}


	/**
	 * Automatically add product to checkout
	 *
	 * @param array $product_param
	 * @throws \Exception
	 *
	 * @since 1.0.0
	 */
	public function initialize_cart_data( $product_param = [] ) {
		if (is_admin()) {
			return;
		}
		if (isset($_GET['removed_item'])) {
			return;
		}

		global $post;
		if ( Wpfnl_functions::check_if_this_is_step_type('checkout') ) {
			if(wp_doing_ajax()) {
				return;
			} else {
				$checkout_id = $post->ID;
				$funnel_id = get_post_meta($checkout_id, '_funnel_id', true);
				$product_array = get_post_meta($checkout_id, '_wpfnl_checkout_products', true);
				$product_array = apply_filters('wpfunnels/funnel_products', $product_array, $funnel_id, $checkout_id);

				/** remove all cart data */
				\WC()->cart->empty_cart();

				if (!is_array($product_array) || empty($product_array[0]['id'])) {
					$product_array = $product_param;
					wc_clear_notices();
					wc_add_notice(__('No product is added to the funnel. Please add product from checkout step settings.', 'wpfnl'), 'error');
				}

				$coupon = get_post_meta($checkout_id, '_wpfnl_checkout_discount', true);
				if (is_array($coupon) && !empty($coupon)) {
					$coupon = reset($coupon);
				}
				if ($product_array) {
					foreach ($product_array as $product) {

						if (isset($product["id"]) && isset($product["quantity"])) {

							$product_id = $product["id"];
							$quantity = $product["quantity"];
							$found = false;
							//check if product already in cart
							if (sizeof(WC()->cart->get_cart()) > 0) {

								foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
									$_product = $values['data'];
									if ($_product->get_id() == $product_id) {
										$found = true;
									}
								}
								// if product not found, add it
								if (!$found) {

									if( isset($product['variation_id']) ){
										// Store product obj in $_product variable
										$_product = wc_get_product($product['variation_id']);
									}else{
										// Store product obj in $_product variable
										$_product = wc_get_product($product_id);
									}

									//check if product type is variable or not from $_product object
									if ( !($_product->get_type() == 'variable') ) {
										// check if product has variation id or not from $product array
										if( isset($product['variation_id']) && isset($product['variations']) ){
											$ob_cart_item_data = [
												'custom_price' 	=> get_post_meta($product['variation_id'], '_price', true) ? get_post_meta($product['variation_id'], '_price', true) : get_post_meta($product['variation_id'], '_regular_price', true)
											];
											WC()->cart->add_to_cart( $product_id, $quantity, $product['variation_id'], $product['variations'], $ob_cart_item_data);
										}else{
											$__product = wc_get_product( $product_id );
											$is_perfect_variation = false;
											if( $__product->get_type() == 'variation' ){
												$is_perfect_variation = Wpfnl_functions::is_perfect_variations( $product_id );
												if( $is_perfect_variation['status'] ){
													WC()->cart->add_to_cart($product_id, $quantity);
												}else{
													$this->add_variaiton_product_in_cart( $checkout_id, $product_id, $quantity );
												}
											}else{
												WC()->cart->add_to_cart($product_id, $quantity);
											}
										}

									}else {
										$variations = $_product->get_available_variations();
										$this->add_default_variation($product_id, $_product, $variations, $quantity);
									}
								}
							} else {
								if( isset($product['variation_id']) ){
									// Store product obj in $_product variable
									$_product = wc_get_product($product['variation_id']);
								}else{
									// Store product obj in $_product variable
									$_product = wc_get_product($product_id);
								}
								//check if product type is variable or not from $_product object
								if (!($_product->get_type() == 'variable')) {
									// check if product has variation id or not from $product array
									if( isset($product['variation_id']) && isset($product['variations']) ){
										$ob_cart_item_data = [
											'custom_price' 	=> get_post_meta($product['variation_id'], '_price', true) ? get_post_meta($product['variation_id'], '_price', true) : get_post_meta($product['variation_id'], '_regular_price', true)
										];
										WC()->cart->add_to_cart( $product_id, $quantity, $product['variation_id'], $product['variations'], $ob_cart_item_data);
									}else{
										$__product = wc_get_product( $product_id );
										$is_perfect_variation = false;
										if( $__product->get_type() == 'variation' ){
											$is_perfect_variation = Wpfnl_functions::is_perfect_variations( $product_id );
											if( $is_perfect_variation['status'] ){
												WC()->cart->add_to_cart($product_id, $quantity);
											}else{
												$this->add_variaiton_product_in_cart( $checkout_id, $product_id, $quantity );
											}
										}else{
											WC()->cart->add_to_cart($product_id, $quantity);
										}
									}

								}else {
									$variations = $_product->get_available_variations();
									$this->add_default_variation($product_id, $_product, $variations, $quantity);
								}
							}
						}
					}
				}

				WC()->session->set('order_bump_accepted', 'no');

				if (!empty($coupon)) {
					WC()->cart->add_discount($coupon);
				}
			}
		}
	}


	private function add_variaiton_product_in_cart( $checkout_id, $product_id, $quantity){

			$product = wc_get_product( $product_id );
			if( $product ){
				$selected_attr = array();
				$attributes = $product->get_attributes();
				foreach($attributes as $attribute_key=>$attribute_value){

					if( $attribute_value ){
						$selected_attr[] = $attribute_key;
						$default_attr['attribute_'.$attribute_key] = $attribute_value;
					}
				}

				if( $product->get_parent_id() ){
					$_product = wc_get_product( $product->get_parent_id() );
					$attributes = $_product->get_attributes();
					foreach ( $attributes as $key => $value) {
						if( !in_array($key,$selected_attr) ){
							$attr_value = $_product->get_attribute( $key );
							$attr_value = strtolower($attr_value);
							if (strpos($attr_value, '|')) {
								$attr_array = explode("|",$attr_value);
							}else{
								$attr_array = explode(",",$attr_value);
							}
							$default_attr['attribute_'.$key] = $attr_array[0];

						}
					}
					$cart_item_data = [
						'custom_price' 	=> get_post_meta($product_id, '_price', true) ? get_post_meta($product_id, '_price', true) : get_post_meta($product_id, '_regular_price', true)
					];
					WC()->cart->add_to_cart( $product->get_parent_id(), $quantity, $product_id, $default_attr, $cart_item_data);
				}
			}
	}


	/**
	 * Add default variation to cart
	 *
	 * @param $product
	 * @param $variations
	 * @param $quantity
	 * @throws \Exception
	 */
	private function add_default_variation($product_id,$product,$variations,$quantity){
		$i = 0;
		$formatted_variation = [];
		$is_default_variation = false;
		foreach ($variations as $variation) {

			if($variation['is_in_stock'] ){

				if($product->get_default_attributes()){
					$attributes = $product->get_attributes();
					$def_attributes = $product->get_default_attributes();
					foreach($attributes as $attribute_key=>$attribute_value){

						$def_attr = $product->get_default_attributes();
						if(isset($def_attr[$attribute_key])){
							$attribute_name = str_replace( 'attribute_', '', $attribute_key );
							$default_value = $product->get_variation_default_attribute($attribute_name);
							$formatted_variation['attribute_'.$attribute_name] = $default_value;
							$is_default_variation = true;
							// $default_attr[] = $default_value;

						}
						else{
							$is_default_variation = true;
							$attribute_name = str_replace( 'attribute_', '', $attribute_key );
							$attr_value = $product->get_attribute( $attribute_name );
							$attr_value = strtolower($attr_value);
							if (strpos($attr_value, '|')) {
								$attr_array = explode("|",$attr_value);
							}else{
								$attr_array = explode(",",$attr_value);
							}
							$formatted_variation['attribute_'.$attribute_name] = $attr_array[0];
							// $default_attr[] = $attr_array[0];
						}

					}
				}else{
					if($i==0){
						$attributes = $product->get_attributes();
						foreach ( $attributes as $key => $value) {
							$attr_value = $product->get_attribute( $key );
							$attr_value = strtolower($attr_value);
							if (strpos($attr_value, '|')) {
								$attr_array = explode("|",$attr_value);
							}else{
								$attr_array = explode(",",$attr_value);
							}
							$is_custom_default_variation = true;
							$formatted_variation['attribute_'.$key] = $attr_array[0];
						}
					}


					// WC()->cart->add_to_cart($variation['variation_id'], $quantity);
				}

				if( $is_default_variation || $is_custom_default_variation){
					$variation_id = (new \WC_Product_Data_Store_CPT())->find_matching_product_variation(
						new \WC_Product($product_id),
						$formatted_variation
					);
					
					if( !$variation_id ){
						$_product = wc_get_product($product_id);
						$variations = $_product->get_available_variations();
						$variations_id = wp_list_pluck( $variations, 'variation_id' );
						$variation_id = $variations_id[0];
						$formatted_variation = wc_get_product_variation_attributes($variation_id); 

					}
	
					$ob_cart_item_data = [
						'custom_price' 	=> get_post_meta($variation_id, '_price', true) ? get_post_meta($variation_id, '_price', true) : get_post_meta($variation_id, '_regular_price', true)
					];
					WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $formatted_variation, $ob_cart_item_data);
					break; // Stop the main loop
				}
				$i++;
			}
		}
	}


	/**
	 * render content for wpfunnels_checkout shortcode
	 *
	 * @param $atts
	 * @return string
	 *
	 * @since 2.0.3
	 */
	public function render_checkout_shortcode($atts)
	{
		$atts = shortcode_atts(
			array(
				'id' => 0,
			),
			$atts
		);
		$checkout_id = intval($atts['id']);
		if (is_admin()) {
			if (0 === $checkout_id && isset($_POST['id'])) {
				$checkout_id = intval($_POST['id']);
			}
		}

		if (empty($checkout_id)) {
			global $post;
			if(is_object($post)) {
				$checkout_id = intval($post->ID);
			}
		}
		if(!$checkout_id) {
			return '';
		}
		$checkout_layout = Wpfnl::get_instance()->meta->get_checkout_meta_value($checkout_id, 'wpfnl_checkout_layout', 'two-column');

		$output = '';
		ob_start();
		do_action('wpfunnels/before_gb_checkout_form', $checkout_id);
		$template_file = WPFNL_DIR . "public/modules/checkout/templates/checkout-template.php";
		include $template_file;
		do_action('wpfunnels/after_checkout_form', $checkout_id);
		$output .= ob_get_clean();
		return $output;

	}


	public function custom_price_to_cart_item($cart_object)
	{
		if (wp_doing_ajax() && !WC()->session->__isset('reload_checkout')) {
			foreach ($cart_object->cart_contents as $key => $value) {
				if (isset($value['custom_price'])) {
					$custom_price = floatval($value['custom_price']);
					$value['data']->set_price($custom_price);
				}
			}
		}
	}

	public function render_order_bump_popup()
	{
		$step_id = get_the_ID();
		$order_bump = get_post_meta($step_id, 'order-bump', true);
		$order_bump_settings = get_post_meta($step_id, 'order-bump-settings', true);
		require_once WPFNL_DIR . 'public/modules/checkout/templates-style/order-bump-template-style3.php';
	}



	/**
	 * insert helper hidden ids
	 *
	 * @since 1.0.0
	 */
	public function checkout_shortcode_metas()
	{
		if (Wpfnl_functions::check_if_this_is_step_type('checkout')) {
			global $post;
			$this->step_id = $post->ID;
			$step = new Wpfnl_Steps_Store_Data();
			$step->read($this->step_id);
			$this->funnel_id = $step->get_funnel_id();
			echo '<input type="hidden" class="input-hidden _wpfunnels_funnel_id" name="_wpfunnels_funnel_id" value="' . intval($this->funnel_id) . '">';
			echo '<input type="hidden" class="input-hidden _wpfunnels_checkout_id" name="_wpfunnels_checkout_id" value="' . intval($post->ID) . '">';
			echo '<input type="hidden" class="input-hidden _wpfunnels_order_unique_identifier" name="_wpfunnels_order_unique_identifier" value="' . uniqid() . '">';
		}
	}


	/**
	 * Next step button ajax
	 *
	 * @since 1.0.0
	 */
	public function wpfnl_next_button_ajax()
	{
		$step_id 	= sanitize_text_field($_POST['step_id']);
		$funnel_id 	= get_post_meta($step_id, '_funnel_id', true);
		$next_step 	= Wpfnl_functions::get_next_step($funnel_id, $step_id);
		if ( $next_step ) {

			$custom_url = Wpfnl_functions::custom_url_for_thankyou_page( $next_step['step_id'] );
            if( $custom_url ){
                $response =  $custom_url;
            }else{
				$next_step_id = $next_step['step_id'];
				$redirect_url = get_post_permalink($next_step_id);
				if ($redirect_url) {
					$response = $redirect_url;
				} else {
					$response = 'error';
				}
			}

			do_action( 'wpfunnels/trigger_cta', $step_id, $funnel_id );
			wp_send_json($response);
		}
		return false;
	}

	public function add_product_to_checkout()
	{
	}

	public function load_data()
	{
	}

	/**
	 * Calculate Discount.
	 * @since 1.1.5
	 */
	public function calculate_custom_price($discount_type, $discount_value, $product_price)
	{
		$custom_price = $product_price;

		if (!empty($discount_type)) {
			if ('discount-percentage' === $discount_type) {
				if ( $discount_value > 0 && $discount_value <= 100) {
					$custom_price = $product_price - (($product_price * $discount_value) / 100);
				}else{
					$custom_price = $product_price;
				}
			} elseif ('discount-price' === $discount_type) {
				if ($discount_value > 0 && $product_price >= $discount_value ) {
					$custom_price = $product_price - $discount_value;
				}else{
					$custom_price = $product_price;
				}
			}
		}

		return number_format($custom_price, 2);
	}

	/**
	 * Apply Coupon to product.
	 * @since 1.1.5
	 */
	public function apply_discount_coupon($discount_type, $discount_coupon)
	{
		$coupon_applied = false;

		if ('coupon' === $discount_type && !empty($discount_coupon)) {
			$discount_coupon = strtolower($discount_coupon);
			WC()->cart->add_discount($discount_coupon);
			$coupon_applied = true;
		}

		return $coupon_applied;
	}

	public function wpfnl_update_variation(){

		$variations 	= $_POST['variations'];
		$product_id 	= $_POST['variations'][0]['product_id'];
		$variation_id 	= $_POST['variations'][0]['variation_id'];
		$quantity 		= $_POST['variations'][0]['quantity'];

		$_product = wc_get_product($variation_id);
		$attribute = $_product->get_attributes();
		foreach ( $attribute as $key => $value) {
			if( $value ){
				$formatted_variation['attribute_'.$key] = $value;
			}else{
				foreach( $variations as $variation ){
					if( $variation['attr'] == $key ){
						$formatted_variation['attribute_'.$key] = $variation['value'];
					}
				}
			}
		}

		$variation_id = (new \WC_Product_Data_Store_CPT())->find_matching_product_variation(
			new \WC_Product($product_id),
			$formatted_variation
		);

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			if ($cart_item['product_id'] == $product_id) {
				WC()->cart->remove_cart_item($cart_item_key);
			}
		}
		$cart_item_data = [
			'custom_price' 	=> get_post_meta($variation_id, '_price', true) ? get_post_meta($variation_id, '_price', true) : get_post_meta($variation_id, '_regular_price', true)
		];

		WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $formatted_variation, $cart_item_data);
	}


	/**
	 * Order Bump Ajax Initialized
	 * When you click on add order bump button it will add the product to cart and click again to recover cart
	 *
	 * @since 1.1.
	 */
	public function wpfnl_order_bump_ajax()
	{
		$step_id 		= filter_input(INPUT_POST, 'step_id', FILTER_VALIDATE_INT);
		$product_id 	= filter_input(INPUT_POST, 'product', FILTER_VALIDATE_INT);
		$checker 		= filter_input(INPUT_POST, 'checker', FILTER_SANITIZE_STRING);
		$quantity 		= filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_STRING);
		$funnel_id		= Wpfnl_functions::get_funnel_id_from_step($step_id);
		//=== Custom price configured when you add sale price or apply coupon or add discount value ===//
		$order_bump_settings 	= get_post_meta($step_id, 'order-bump-settings', true);
		$_product 				= wc_get_product($product_id);
		$discount_type			= $order_bump_settings['discountOption'];

		$discount_apply_to  	= $_product->is_on_sale() ? 'sale' :  'regular' ;
		$product_price 			= $this->get_product_price( $_product, $discount_apply_to );

		// fetch main products
		$checkout_meta 		= new Wpfnl_Default_Meta();
		$main_products		= $checkout_meta->get_main_products( $funnel_id, $step_id );
		if( 'discount-percentage' === $discount_type || 'discount-price' === $discount_type ) {
			$discount_apply_to 	= isset($order_bump_settings['discountapply']) ? $order_bump_settings['discountapply'] : 'regular';
			$discount_value 	= isset($order_bump_settings['discountValue']) ? $order_bump_settings['discountValue'] : 0;
			$product_price		= $this->get_product_price( $_product, $discount_apply_to );
			$product_price 		= $this->calculate_custom_price( $discount_type, $discount_value, $product_price );
		}

		$ob_cart_item_data = [
			'custom_price' 		=> preg_replace('/[^\d.]/', '', $product_price ),
			'wpfnl_order_bump' 	=> true,
		];
		$data = array();
		$should_replace_first_product = isset($order_bump_settings['isReplace']) ? $order_bump_settings['isReplace'] : 'no';

		add_filter( 'woocommerce_checkout_cart_item_quantity', array($this, 'wpfnl_checkout_cart_item_quantity'), 10, 3 );
		if ($checker == "true") {
			WC()->session->set('order_bump_accepted', 'yes');
			// if order bump is checked we will empty the cart in the first place. The we have to check if isReplace is yes or no.
			// if replace first product is enabled, then we will only add the order bump product
			// else we will add all the checkout product along with the order bump product
			if ( $should_replace_first_product == 'yes' ) {
				WC()->cart->empty_cart(true);
				WC()->cart->add_to_cart($product_id, $quantity, 0, [], $ob_cart_item_data);
			} else {
				WC()->cart->add_to_cart( $product_id, $quantity, 0, [], $ob_cart_item_data);
			}
			$data = [
				'status' 		=> 'success',
				'message' 		=> __('Successfully added', 'wpfnl'),
				'order_bump'	=> true
			];


			do_action( 'wpfunnels/order_bump_accepted', $step_id, $product_id );

		}
		elseif ($checker == "false") {
			WC()->session->set('order_bump_accepted', 'no');
			// $this->add_checkout_products($step_id);
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				if ($cart_item['product_id'] == $product_id) {
					//remove single product
					WC()->cart->remove_cart_item($cart_item_key);

					// Check main product and ob product is same or not
					foreach ( $main_products as $main_product ) {
						$in_cart = false;

						if( $cart_item['product_id'] == $main_product['id']){
							$product_cart_id = WC()->cart->generate_cart_id( $main_product['id'] );
							$in_cart = WC()->cart->find_product_in_cart( $product_cart_id );
							if( !$in_cart ){
								WC()->cart->add_to_cart( $main_product['id'], $main_product['quantity'], 0, []);
							}
						}
					}
				}
			    else if ( $cart_item['variation_id'] == $product_id) {

					WC()->cart->remove_cart_item( $cart_item_key );
					// Check main product and ob product is same or not
					foreach ( $main_products as $main_product ) {
						$in_cart = false;
						if( $main_product['id'] == $product_id ){
							$product = wc_get_product($product_id);
							$product_cart_id = WC()->cart->generate_cart_id( $product_id );
							$in_cart = WC()->cart->find_product_in_cart( $product_cart_id );

							if( !$in_cart ){
								WC()->cart->add_to_cart($product->get_parent_id(),$main_product['quantity'], $main_product['id'], []);
							}
						}
					}
				}

			}
			if ( $should_replace_first_product == 'yes' ) {
				WC()->cart->empty_cart(true);
				foreach ( $main_products as $main_product ) {
					WC()->cart->add_to_cart( $main_product['id'], $main_product['quantity'], 0, []);
				}
			}
			$data = [
				'status' => 'success',
				'message' => __('Successfully removed', 'wpfnl'),
			];
			do_action( 'wpfunnels/order_bump_rejected', $step_id, $product_id );


		}

		if ($order_bump_settings['discountOption'] == 'coupon') {
			$this->apply_discount_coupon($order_bump_settings['discountOption'], $order_bump_settings['couponName']);
		}
		// do_shortcode('[woocommerce_checkout]');
		wp_send_json( Wpfnl_functions::get_wc_fragments($data) );
	}


	/**
	 * get calculable price
	 *
	 * @param \WC_Product $product
	 * @param $discount_apply
	 * @return string
	 *
	 * @since 2.0.5
	 */
	private function get_product_price( \WC_Product $product, $discount_apply = 'regular' ) {
		$price = $product->get_regular_price();
//		if( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ){
//			$signUpFee 	= \WC_Subscriptions_Product::get_sign_up_fee( $product );
//			$price 		= $price + $signUpFee;
//		}
		return $discount_apply === 'sale' && $product->get_sale_price() ? $product->get_sale_price() : $price;
	}


	/**
	 * add checkout products to cart
	 *
	 * @param $checkout_id
	 * @throws \Exception
	 */
	private function add_checkout_products($checkout_id)
	{
		$checkout_products = get_post_meta( $checkout_id, '_wpfnl_checkout_products', true );
		foreach ($checkout_products as $product) {
			$_product_id = $product['id'];
			$_qty = $product['quantity'];
			WC()->cart->add_to_cart($_product_id, $_qty);
		}
	}

	/**
	 * add last updated checkout products to cart
	 *
	 * @param $updated_cart
	 * @throws \Exception
	 */
	private function update_checkout_with_last_updated_cart($updated_cart){
		foreach ($updated_cart as $cart) {
			WC()->cart->add_to_cart( $cart['product_id'], $cart['quantity'], $cart['variation_id'], $cart['variation'], $cart['line_total']);
		}
	}


	public function wpfnl_checkout_cart()
	{
		$values = [];
		parse_str(sanitize_text_field($_POST['post_data']), $values);
		$cart = $values['cart'];
		foreach ($cart as $cart_key => $cart_value) {
			WC()->cart->set_quantity($cart_key, $cart_value['qty'], false);
			WC()->cart->calculate_totals();
			woocommerce_cart_totals();
		}
		wp_die();
	}


	/**
	 * Order Bump Templates
	 *
	 * @param $settings
	 * @since 1.0.0
	 */
	public function render_order_bump_template($settings)
	{
		if (!empty($settings['selectedStyle'])) {
			require_once WPFNL_DIR . 'public/modules/checkout/templates-style/order-bump-template-' . $settings['selectedStyle'] . '.php';
		}
	}


	/**
	 * display coupon field or not
	 *
	 * @since 2.0.5
	 */
	public function display_coupon_field() {
		if ( Wpfnl_functions::check_if_this_is_step_type('checkout') ) {
			global $post;
			$checkout_id = $post->ID;
			$show_coupon = Wpfnl::get_instance()->meta->get_checkout_meta_value( $checkout_id, '_wpfnl_checkout_coupon' );
			if( 'no' === $show_coupon ) {
				remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form' );
			}
		}
	}

	/**
	 * Out of Stock message in checkout page
	 * @param $message
	 * @param $product_data
	 * @return string
	 */

	public function wpfnl_out_of_stock_message_in_checkout( $message, $product_data ){
		$message = __( 'Sorry, the following product(s) that you are willing to purchase is out of stock at the moment.', 'woocommerce' );
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_data->get_id() ), array( 100, 100) );
		$message .= '<div class="wpfnl-checkout-stock-message"><img src="'.$image[0].'"><h4>'.$product_data->get_title().' </h4></div>';
		return $message;
	}


	/**
	 * Remove Astra theme checkout shipping hook
	 */
	public function remove_astra_theme_checkout_shipping_hook(){
		return true;
	}


	/**
	 * Update quantity from checkout page
	 *
	 * @param $quantity, $cart_item, $cart_item_key
	 */
	public function wpfnl_checkout_cart_item_quantity( $quantity, $cart_item, $cart_item_key ) {

		$step_id = 0;
		$isQuantity = 'no';

		if( wp_doing_ajax() ) {
			$step_id        = isset($_POST['step_id']) ? $_POST['step_id'] : 0;
		} else {
			$step_id = get_the_ID();
		}


		$isQuantity = get_post_meta($step_id, '_wpfnl_quantity_support',true);
		$order_bump_product = get_post_meta($step_id,'order-bump-settings',true);

		if($isQuantity === 'yes'){
			if(isset($order_bump_product['product']) && isset($order_bump_product['isEnabled'])){

				if( ($order_bump_product['product'] == $cart_item["product_id"]) && $order_bump_product['isEnabled'] == 'yes' ){
					return $quantity;
				}
				$variations = json_encode($cart_item['variation']);
				$product_id = $cart_item["product_id"];
				$quantity = $cart_item["quantity"];
				$variation_id = $cart_item["variation_id"];
				$quantity = "× <input type='number' min='1' value='".$quantity."' class='wpfnl-quantity-setect' data-product-id='".$product_id."' data-variation='".$variations."' data-variation-id='".$variation_id."'/>";
			}
		}
		return $quantity;

	}


	public function before_checkout_form_actions( $checkout_id ) {
		remove_all_actions( 'woocommerce_checkout_billing' );
		remove_all_actions( 'woocommerce_checkout_shipping' );

		add_action( 'woocommerce_checkout_billing', array( WC()->checkout, 'checkout_form_billing' ) );
		add_action( 'woocommerce_checkout_shipping', array( WC()->checkout, 'checkout_form_shipping' ) );
	}


	/**
	 * Set checkout ID hidden field in checkout page.
	 *
	 * @param array $checkout
	 * @return void
	 */
	public function wpfnl_set_checkout_id( $checkout ) {
		global $post;
		$checkout_id = 0;
		if ( Wpfnl_functions::is_funnel_checkout_page() ) {
			if( isset($post->ID) ){
				$checkout_id = $post->ID;
			}else{
				$checkout_id = $_POST['step_id'];
			}

		}
		if( $checkout_id ){
			echo '<input type="hidden" class="input-hidden _wpfunnels_checkout_id" name="_wpfunnels_checkout_id" value="' . intval( $checkout_id ) . '">';
		}
	}


	/**
	 * Redirect funnel checkout after login in checkout page
	 *
	 * @param $redirect_url
	 * @param $user
	 * @return false|string
	 */
	public function wpfnl_redirect_after_login( $redirect_url, $user ) {

		if (isset($_POST['_wpfunnels_checkout_id'])) {
			$funnel_checkout_id = $_POST['_wpfunnels_checkout_id'];
			$redirect_url = get_the_permalink($funnel_checkout_id);
		}
		return $redirect_url;

	}


	/**
     * Get Custom  Woocommerce template
     * @param $template
     * @param $template_name
     * @param $template_path
     * @return mixed|string
     */

    public static function wpfunnels_woocommerce_locate_template($template, $template_name, $template_path)
    {
        global $woocommerce;
        $_template 		= $template;
        $plugin_path 	= WPFNL_DIR . '/woocommerce/templates/';

        if (file_exists($plugin_path . $template_name)) {
            $template = $plugin_path . $template_name;
        }

        if ( ! $template ) {
            $template = $_template;
        }

        return $template;
    }
}
