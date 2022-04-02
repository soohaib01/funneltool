<?php

namespace WPFunnels\Admin\Modules\Steps\Checkout;

use WPFunnels\Admin\Modules\Steps\Module as Steps;
use WPFunnels\Metas\Wpfnl_Step_Meta_keys;
use WPFunnels\Wpfnl;
use WPFunnels\Wpfnl_functions;
use \WC_Subscriptions_Product;
class Module extends Steps
{
	protected $validations;

	protected $_internal_keys = [];

	protected $type = 'checkout';

	protected $prefix = '_wpfnl_checkout_';


	public function __construct()
	{
		add_action('wp_ajax_order_bump_search_products', [$this, 'fetch_products']);
		add_action('wp_ajax_order_bump_search_coupons', [$this, 'fetch_coupons']);
		add_action('wpfunnels/after_save_order_bump_data', [$this, 'update_elementor_data'], 10, 2);
	}


	public function get_validation_data()
	{
		return $this->validations;
	}


	public function init($id)
	{
		parent::init($id);
		$this->set_internal_meta_value();
	}


	/**
	 * load assets
	 *
	 * @param $hook
	 * @since 1.0.0
	 */
	public function load_scripts($hook)
	{
		if (isset($_GET['step_id'])) {
			$step_id = filter_input(INPUT_GET, 'step_id', FILTER_VALIDATE_INT);
			if (Wpfnl_functions::check_if_this_is_step_type_by_id($step_id, 'checkout')) {
				wp_enqueue_script($this->type . '-js', WPFNL_URL . 'admin/assets/dist/js/order-bump.min.js', ['jquery', 'wp-util'], '1.0.0', true);
				wp_localize_script(
					$this->type . '-js',
					'CheckoutStep',
					[
						'ajaxurl' => esc_url_raw(admin_url('admin-ajax.php')),
						'rest_api_url' => esc_url_raw(get_rest_url()),
						'wc_currency' => get_woocommerce_currency_symbol(),
						'nonce' => wp_create_nonce('wp_rest'),
						'security' => wp_create_nonce('wpfnl-admin'),
						'image_path' => WPFNL_URL . 'admin/assets/images',
						'tooltipIcon' => WPFNL_URL . 'admin/partials/icons/question-tooltip-icon.php',
						'imageUploadIcon' => WPFNL_URL . 'admin/partials/icons/image-upload-icon.php',
						'step_id' => $step_id,
						'back' => add_query_arg(
							[
								'page' => WPFNL_EDIT_FUNNEL_SLUG,
								'id' => filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT),
								'step_id' => $step_id,
							],
							admin_url('admin.php')
						)
					]
				);
			}
		}
	}


	/**
	 * init ajax hooks for
	 * saving metas
	 *
	 * @since 1.0.0
	 */
	public function init_ajax()
	{
		$this->validations = [
			'logged_in' => true,
			'user_can' => 'manage_options',
		];
		wp_ajax_helper()->handle('wpfnl-update-checkout-product-tab')
			->with_callback([$this, 'save_products'])
			->with_validation($this->validations);

		wp_ajax_helper()->handle('wpfnl-add-product')
			->with_callback([$this, 'add_product'])
			->with_validation($this->validations);

		wp_ajax_helper()->handle('delete-product')
			->with_callback([$this, 'delete_product'])
			->with_validation($this->validations);
	}

	/**
	 * get view of the checkout settings page
	 *
	 * @since 1.0.0
	 */
	public function get_view()
	{
		// TODO: Implement get_view() method.
		$is_pro_activated = Wpfnl_functions::is_wpfnl_pro_activated();
		$show_settings = filter_input(INPUT_GET, 'show_settings', FILTER_SANITIZE_STRING);
		$this->_internal_keys = Wpfnl_Step_Meta_keys::get_meta_keys($this->type);
		$this->set_internal_meta_value();

		if ($show_settings == 1) {
			require_once WPFNL_DIR . '/admin/modules/steps/checkout/views/settings.php';
		} else {
			require_once WPFNL_DIR . '/admin/modules/steps/checkout/views/view.php';
		}
	}


	public function save_products($payload)
	{
		$step_id = $payload['step_id'];
		$products = array();
		if (isset($payload['products'])) {
			$products = $payload['products'];
		}

		$coupon = $payload['coupon'];
		$isMultipleProduct = $payload['isMultipleProduct'];
		$isQuantity = $payload['isQuantity'];

		if ($coupon == 'true') {
			$coupon = 'yes';
		} else {
			$coupon = 'no';
		}

		if ($isMultipleProduct == 'true') {
			$isMultipleProduct = 'yes';
		} else {
			$isMultipleProduct = 'no';
		}
		if ($isQuantity == 'true') {
			$isQuantity = 'yes';
		} else {
			$isQuantity = 'no';
		}



		if (!$products) {
			return [
				'success' => false,
				'message' => 'No Product Found',
			];
		}

		foreach ($products as $pr_key => $pr_value) {
			foreach ($pr_value as $key => $value) {
				if ($key == 'price' || $key == 'image' || $key == 'title') {
					unset($products[$pr_key][$key]);
				}
			}
		}

		update_post_meta($step_id, '_wpfnl_checkout_products', $products);
		update_post_meta($step_id, '_wpfnl_checkout_coupon', $coupon);
		update_post_meta($step_id, '_wpfnl_multiple_product', $isMultipleProduct);
		update_post_meta($step_id, '_wpfnl_quantity_support', $isQuantity);

		return [
			'success' => true,
			'message' => 'Saved Successfully',
		];
	}

	/**
	 * save checkout product tab
	 * data
	 *
	 * @param $payload
	 * @return array
	 */
	public function checkout_update_product_tab_options($payload)
	{

		$step_id = sanitize_text_field($payload['step_id']);
		unset($payload['step_id']);
		$step = Wpfnl::get_instance()->step_store;
		$step->set_id($step_id);
		$this->_internal_keys = Wpfnl_Step_Meta_keys::get_meta_keys($this->type);
		foreach ($payload as $key => $value) {
			if (array_key_exists($this->prefix . $key, $this->_internal_keys)) {
				switch ($key) {
					case 'products':
						$products = [];
						if (!empty($value)) {
							$saved_products = get_post_meta($step_id, '_wpfnl_checkout_products', true);
							if ($saved_products) {
								$saved_products[] = $value;
								$products = $saved_products;
							} else {
								$products[] = $value;
							}
						}
						$step->update_meta($step_id, $this->prefix . $key, $products);
						break;
					case 'discount':
						$discount[] = $value;
						$step->update_meta( $step_id, $this->prefix . $key, $discount );
						break;
					case 'coupon':
						$coupon = $value;
						$step->update_meta($step_id, $this->prefix . $key, $coupon);
						break;
					default:
						$step->update_meta($step_id, $this->prefix . $key, $value);
						break;
				}
			}
		}
		return [
			'success' => true,
		];
	}


	/**
	 * add product row by ajax
	 *
	 * @param $payload
	 * @return array
	 */
	public function add_product($payload)
	{
		if (isset($payload['id'])) {
			$pr_id = $payload['id'];
		} else {
			return [
				'success' => false,
				'message' => 'Product Can\'t be added',
				'products' => [],
			];
		}
		$step_id = $payload['step_id'];

		$saved_products = get_post_meta($step_id, '_wpfnl_checkout_products', true);

		if ($saved_products) {
			foreach ($saved_products as $saved_products_key => $saved_products_value) {
				if ($saved_products_value['id'] == $pr_id) {
					return [
						'success' => false,
						'message' => 'Product already exists',
						'products' => [],
					];
				}
			}
		}

		$product = wc_get_product($pr_id);
		$_payload = [
			'step_id' => $step_id,
			'products' => [
				'id' 				=> $pr_id,
				'quantity' 			=> $payload['quantity'],
				'discount_type' 	=> $payload['discount_type'],
				'discount_value' 	=> $payload['discount_value'],
			]
		];
		$this->checkout_update_product_tab_options($_payload);
		if (!empty($product)) {
			$title 	= $product->get_type() == 'variation' ? Wpfnl_functions::get_formated_product_name( $product ) : $product->get_name();;
			$price 	= $product->get_price();

			// for variation products
        	if( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ){
				if( 'subscription_variation' === $product->get_type() || 'subscription' === $product->get_type() ){
					$signUpFee 	= \WC_Subscriptions_Product::get_sign_up_fee( $product );
					$price 		= $price + $signUpFee;
				}
			}

			$subtext 				= "";
			$text_highlight 		= "";
			$text_highlight_enabler = "";
			$description 			= substr($product->get_description(), 0, 20);
			$pr_image 				= wp_get_attachment_image_src($product->get_image_id(), 'single-post-thumbnail');
			$qty 					= 1;
			return [
				'success' => true,
				'products' => [
					'id' 				=> $pr_id,
					'title' 			=> $title,
					'price' 			=> wc_price($price),
					'quantity' 			=> $qty,
					'image' 			=> $pr_image ? $pr_image[0] : '',
					'discount_type' 	=> $payload['discount_type'],
					'discount_value' 	=> $payload['discount_value'],
					'product_edit_link' => in_array($product->get_type(), array( 'variation', 'subscription_variation' )) ? get_edit_post_link($product->get_parent_id()) : get_edit_post_link($product->get_id()),
					'product_view_link' => in_array($product->get_type(), array( 'variation', 'subscription_variation' )) ? get_permalink($product->get_parent_id()) : get_permalink($product->get_id()),
				],
			];
		}
		return [
			'success' => false,
			'products' => [],
		];
	}


	public function delete_product($payload)
	{
		$step_id 	= $payload['step_id'];
		$index 		= $payload['index'];
		$type 		= $payload['type'];
		$meta_key 	= '_wpfnl_checkout_products';
		switch ($type) {
			case 'checkout':
				$meta_key = '_wpfnl_checkout_products';
				break;
			case 'upsell':
				$meta_key = '_wpfnl_upsell_products';
				break;
			case 'downsell':
				$meta_key = '_wpfnl_downsell_products';
				break;
		}
		$products 	= get_post_meta($step_id, $meta_key, true);
		$step 		= Wpfnl::get_instance()->step_store;
		$response 	= [];
		if ($products) {
			unset($products[$index]);
			$products = array_values($products);
			if ($products) {
				foreach ($products as $value) {
					$product 	= wc_get_product($value['id']);
					$title 		= $product->get_type() == 'variation' ? Wpfnl_functions::get_formated_product_name( $product ) : $product->get_name();
					$image 		= wp_get_attachment_image_src($product->get_image_id(), 'single-post-thumbnail');
					$price 		= $product->get_price();
					$response[] = [
						'id' => $value['id'],
						'title' => $title,
						'price' => wc_price($price),
						'quantity' => $value['quantity'],
						'image' => $image ? $image[0] : '',
						'product_edit_link' => in_array($product->get_type(), array( 'variation', 'subscription_variation' )) ? get_edit_post_link($product->get_parent_id()) : get_edit_post_link($product->get_id()),
						'product_view_link' => in_array($product->get_type(), array( 'variation', 'subscription_variation' )) ? get_permalink($product->get_parent_id()) : get_permalink($product->get_id()),
					];
				}
			}
			$step->update_meta($step_id, $meta_key, $products);
		}

		return [
			'success' => true,
			'products' => $response
		];
	}


	/**
	 * fetch product from WC data store
	 *
	 * @throws \Exception
	 * @since 1.0.0
	 */
	public function fetch_products()
	{

		check_ajax_referer('wpfnl-admin', 'security');
		if (isset($_GET['term'])) {
			$term = (string)sanitize_text_field(wp_unslash($_GET['term']));
		}
		if (empty($term)) {
			wp_die();
		}
		$products = [];
		$data_store = \WC_Data_Store::load('product');
		$ids = $data_store->search_products($term, '', false, false, 10);

		$product_objects = array_filter(array_map('wc_get_product', $ids), 'wc_products_array_filter_readable');
		foreach ($product_objects as $product_object) {
			if( ( $product_object->managing_stock() && $product_object->get_stock_quantity() > 0 ) || ( !$product_object->managing_stock() && $product_object->get_stock_status() !== 'outofstock' ) ){
				$formatted_name = $product_object->get_name();
				$product_image_id = $product_object->get_image_id();
				$product_image_src = $product_image_id ? wp_get_attachment_image_src($product_image_id, 'large')[0] : '';
				if ($product_object->get_type() == 'variable' || $product_object->get_type() == 'variable-subscription') {
					$variations = $product_object->get_available_variations();
					foreach ($variations as $variation) {
						$variation_product = wc_get_product($variation['variation_id']);
						if( ( $variation_product->managing_stock() && $variation_product->get_stock_quantity() > 0 ) || ( !$variation_product->managing_stock() && $variation_product->get_stock_status() !== 'outofstock' ) ){
							$price = $variation['display_regular_price'];
							if( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ){
								$signUpFee = \WC_Subscriptions_Product::get_sign_up_fee( $variation_product );
								$price = $signUpFee + $variation['display_regular_price'];
							}

							$products[$variation['variation_id']] = [
								'name' 	=> Wpfnl_functions::get_formated_product_name( $variation_product ),
								'price' => wc_price($variation_product->get_regular_price()),
								'sale_price' => $variation_product->get_sale_price() ? wc_price($variation_product->get_sale_price()) : wc_price($variation_product->get_regular_price()),
								'html_price' => $product_object->get_price_html(),
								'title' => $product_object->get_title(),
								'img' => array(
									'id' => $product_image_id,
									'url' => $product_image_src,
								),
								'description' => $product_object->get_short_description()
							];
						}
					}

				}else {
					$sale_price = $product_object->get_sale_price();
					if ($sale_price != "") {
						$sale_price = $product_object->get_sale_price() ? wc_price($product_object->get_sale_price()) : wc_price($product_object->get_regular_price());
					}

					$product_image_id = $product_object->get_image_id();
					$product_image_src = $product_image_id ? wp_get_attachment_image_src($product_image_id, 'large')[0] : '';
					$products[$product_object->get_id()] = [
						'name' => rawurldecode($formatted_name),
						'price' => wc_price($product_object->get_regular_price()),
						'sale_price' => $sale_price,
						'html_price' => $product_object->get_price_html(),
						'title' => $product_object->get_title(),
						'img' => array(
							'id' => $product_image_id,
							'url' => $product_image_src,
						),
						'description' => $product_object->get_short_description()
					];
				}
			}
		}
		wp_send_json($products);
	}


	/**
	 * fetch product from WC data store
	 *
	 * @throws \Exception
	 * @since 1.0.0
	 */
	public function fetch_coupons()
	{
		check_ajax_referer('wpfnl-admin', 'security');
		$term = (string)sanitize_text_field(urldecode(wp_unslash($_GET['term'])));
		$term = (string)wp_unslash($term);

		if (empty($term)) {
			wp_die();
		}

		$args = [
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'asc',
			'post_type' => 'shop_coupon',
			'post_status' => 'publish',
			's' => $term,
		];
		$coupons = get_posts($args);

		$discount_types = wc_get_coupon_types();
		$fetched_coupons = [];

		if ($coupons) {
			foreach ($coupons as $coupon) {
				$discount_type = get_post_meta($coupon->ID, 'discount_type', true);
				if (!empty($discount_types[$discount_type])) {
					$fetched_coupons[$coupon->post_title] = $coupon->post_title;
				}
			}
		}
		wp_send_json($fetched_coupons);
	}


	/**
	 * This will regenerate the elementor data.
	 * This approach is not for programmers. Logic behind this code is We know position of WPFunnels widget - $path_array e.g. $path_array = array(0). This means
	 * position of WPFunnels widget will be $elementor_data[$key]['elements'][$path_array[0]]. if $path_array = array(0, 0), the position will be
	 * $elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]. So we update the WPFunnels widget with new data ($settings) one by one manullay.
	 * This is a very bad approach. We will update the code soon.
	 *
	 *
	 * @param $key
	 * @param $elementor_data
	 * @param $settings
	 * @param $path_array
	 * @return mixed
	 *
	 * @since 2.0.0
	 */
	private function regenerate_elementor_data($key, $elementor_data, $settings, $path_array)
	{
		$path_array_count = count($path_array);
		if ($path_array_count == 1) {
			$elementor_data[$key]['elements'][$path_array[0]]['settings']['order_bump_position'] = $settings['position'];
			$elementor_data[$key]['elements'][$path_array[0]]['settings']['order_bump_layout'] = $settings['selectedStyle'];
			$elementor_data[$key]['elements'][$path_array[0]]['settings']['order_bump_image']['url'] = $settings['productImage']['url'];
			$elementor_data[$key]['elements'][$path_array[0]]['settings']['order_bump_image']['id'] = $settings['productImage']['id'];
			$elementor_data[$key]['elements'][$path_array[0]]['settings']['order_bump_checkbox_label'] = $settings['checkBoxLabel'];
			$elementor_data[$key]['elements'][$path_array[0]]['settings']['settings']['order_bump_product_detail_header'] = $settings['highLightText'];
			$elementor_data[$key]['elements'][$path_array[0]]['settings']['order_bump_product_detail'] = $settings['productDescriptionText'];
			$elementor_data[$key]['elements'][$path_array[0]]['settings']['order_bump'] = $settings['isEnabled'];
		} elseif ($path_array_count == 2) {
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['settings']['order_bump_position'] = $settings['position'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['settings']['order_bump_layout'] = $settings['selectedStyle'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['settings']['order_bump_image']['url'] = $settings['productImage']['url'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['settings']['order_bump_image']['id'] = $settings['productImage']['id'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['settings']['order_bump_checkbox_label'] = $settings['checkBoxLabel'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['settings']['order_bump_product_detail_header'] = $settings['highLightText'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['settings']['order_bump_product_detail'] = $settings['productDescriptionText'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['settings']['order_bump'] = $settings['isEnabled'];
		} elseif ($path_array_count == 3) {
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['settings']['order_bump_position'] = $settings['position'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['settings']['order_bump_layout'] = $settings['selectedStyle'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['settings']['order_bump_image']['url'] = $settings['productImage']['url'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['settings']['order_bump_image']['id'] = $settings['productImage']['id'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['settings']['order_bump_checkbox_label'] = $settings['checkBoxLabel'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['settings']['order_bump_product_detail_header'] = $settings['highLightText'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['settings']['order_bump_product_detail'] = $settings['productDescriptionText'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['settings']['order_bump'] = $settings['isEnabled'];
		} elseif ($path_array_count == 4) {
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['settings']['order_bump_position'] = $settings['position'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['settings']['order_bump_layout'] = $settings['selectedStyle'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['settings']['order_bump_image']['url'] = $settings['productImage']['url'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['settings']['order_bump_image']['id'] = $settings['productImage']['id'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['settings']['order_bump_checkbox_label'] = $settings['checkBoxLabel'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['settings']['order_bump_product_detail_header'] = $settings['highLightText'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['settings']['order_bump_product_detail'] = $settings['productDescriptionText'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['settings']['order_bump'] = $settings['isEnabled'];
		} elseif ($path_array_count == 5) {
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['elements'][$path_array[4]]['settings']['order_bump_position'] = $settings['position'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['elements'][$path_array[4]]['settings']['order_bump_layout'] = $settings['selectedStyle'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['elements'][$path_array[4]]['settings']['order_bump_image']['url'] = $settings['productImage']['url'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['elements'][$path_array[4]]['settings']['order_bump_image']['id'] = $settings['productImage']['id'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['elements'][$path_array[4]]['settings']['order_bump_checkbox_label'] = $settings['checkBoxLabel'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['elements'][$path_array[4]]['settings']['order_bump_product_detail_header'] = $settings['highLightText'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['elements'][$path_array[4]]['settings']['order_bump_product_detail'] = $settings['productDescriptionText'];
			$elementor_data[$key]['elements'][$path_array[0]]['elements'][$path_array[1]]['elements'][$path_array[2]]['elements'][$path_array[3]]['elements'][$path_array[4]]['settings']['order_bump'] = $settings['isEnabled'];
		}

		return $elementor_data;
	}


	/**
	 * This hook will trigger once the order bump data is saved from admin
	 *
	 * @param $post_id
	 * @param $settings
	 *
	 * @since 2.0.0
	 */
	public function update_elementor_data($post_id, $settings)
	{
		if (Wpfnl_functions::get_builder_type() === 'elementor') {
			$elementor_data = get_post_meta($post_id, '_elementor_data', true);
			if ($elementor_data) {
				if (is_array($elementor_data)) {
					$elementor_data = $elementor_data;
				} else {
					$elementor_data = add_magic_quotes($elementor_data);
					$elementor_data = json_decode($elementor_data, true);
				}


				$el_data = array();
				$checkout_widget = null;
				foreach ($elementor_data as $key => $inner_element) {
					$checkout_widget = Wpfnl_functions::recursive_multidimensional_ob_array_search_by_value('wpfnl-checkout', $inner_element['elements']);
					if ($checkout_widget) {
						$path_array = $checkout_widget['path'];

						if (!$path_array) continue;

						if ($path_array) {
							$path = '';
							$widget_settings = $checkout_widget['settings'];
							$widget_settings['order_bump_checkbox_label'] = 'hukka hua';
							$regenerated_elementor_data = $this->regenerate_elementor_data($key, $elementor_data, $settings, $path_array);
							update_post_meta($post_id, '_elementor_data', $regenerated_elementor_data);
						}
						break;
					}
				}
			}
		}
	}


}
