<?php

namespace WPFunnels\Rest\Controllers;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WPFunnels\Wpfnl_functions;
use function cli\err;
use \WC_Subscriptions_Product;
class ProductsController extends Wpfnl_REST_Controller
{

	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wpfunnels/v1';

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'products';

	/**
	 * check if user has valid permission
	 *
	 * @param $request
	 * @return bool|WP_Error
	 * @since 1.0.0
	 */
	public function update_items_permissions_check($request)
	{
		if (!Wpfnl_functions::wpfnl_rest_check_manager_permissions('steps', 'edit')) {
			return new WP_Error('wpfunnels_rest_cannot_edit', __('Sorry, you cannot edit this resource.', 'wpfnl'), array('status' => rest_authorization_required_code()));
		}
		return true;
	}

	/**
	 * Makes sure the current user has access to READ the settings APIs.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|boolean
	 * @since  3.0.0
	 */
	public function get_items_permissions_check($request)
	{
		if (!Wpfnl_functions::wpfnl_rest_check_manager_permissions('settings')) {
			return new WP_Error('wpfunnels_rest_cannot_edit', __('Sorry, you cannot list resources.', 'wpfnl'), array('status' => rest_authorization_required_code()));
		}
		return true;
	}


	/**
	 * register rest routes
	 *
	 * @since 1.0.0
	 */
	public function register_routes()
	{
		register_rest_route(
			$this->namespace, '/' . $this->rest_base . '/get_products'. '/(?P<step_id>\d+)' , array(
				array(
					'methods'               => WP_REST_Server::READABLE,
					'callback'              => array( $this, 'get_products' ),
					'permission_callback'   => array( $this, 'get_items_permissions_check' ),
				),
			)
		);

		register_rest_route($this->namespace, '/getProducts/', [
			[
				'methods' => \WP_REST_Server::READABLE,
				'callback' => [
					$this,
					'get_wc_products'
				],
				'permission_callback' => [
					$this,
					'get_items_permissions_check'
				],
			],
		]);


		register_rest_route($this->namespace, '/calculateDiscountPrice/', [
			[
				'methods' => \WP_REST_Server::READABLE,
				'callback' => array( $this, 'calculate_discount_price'),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
			],
		]);
	}


	/**
	 * Calculate Discount Price
	 *
	 * @param string $data
	 * @return array|WP_Error
	 */
	public function calculate_discount_price($request)
	{
		$data 			= $request->get_params();
		$_product 		= wc_get_product($data['product']);
		$regular_price 	= $_product->get_regular_price();
		if( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ){
			$signUpFee = \WC_Subscriptions_Product::get_sign_up_fee( $_product );
			$regular_price = $signUpFee + $regular_price;
		}
		$sale_price 	= $_product->get_sale_price();
		if( $data['discount_type'] === 'original' ) {
			$calculable_price 	= $regular_price;
			$discount_price 	= $sale_price ? $sale_price : $regular_price;
		}

		else {
			if ($data['applyto'] == 'sale') {
				if ($sale_price != "") {
					$calculable_price = $sale_price;
				} else {
					$calculable_price = $regular_price;
				}
			}
			else {
				$calculable_price = $regular_price;
			}

//			if ($data['applyto'] == 'sale') {
//				if($calculable_price > $sale_price) {
//					$response = array(
//						'success' 		=> false,
//						'discountPrice'	=> '0',
//						'discountPriceHtml' => '',
//
//					);
//					return rest_ensure_response( $response );
//				}
//			} elseif ($data['applyto'] == 'regular') {
//				$response = array(
//					'success' 		=> false,
//					'discountPrice'	=> '',
//					'discountPriceHtml' => '',
//				);
//				return rest_ensure_response( $response );
//			} else {
//				$discount_price = $this->calculate_custom_price($data['discount_type'], $data['discount_value'], $calculable_price, $data['applyto']);
//			}

			$discount_price = $this->calculate_custom_price($data['discount_type'], $data['discount_value'], $calculable_price, $data['applyto']);
		}


		$calculable_price 	= preg_replace('/[^\d.]/', '', $calculable_price);
		$discount_price 	= preg_replace('/[^\d.]/', '', $discount_price);

		$response = array(
			'success'           => true,
			'discountPrice'     => wc_price($data['quantity'] * $discount_price ),
			'discountPriceHtml' => wc_format_sale_price( $data['quantity'] * $calculable_price, $data['quantity'] * $discount_price ),
			'htmlPrice' 		=> $sale_price ? wc_format_sale_price( $data['quantity'] * $regular_price, $data['quantity'] * $sale_price ) : wc_price( $data['quantity'] * $regular_price ),
		);
		return rest_ensure_response( $response );
	}


	/**
	 * Get all Products.
	 *
	 * @param string $request Data.
	 * @return array|WP_Error
	 */
	public function get_wc_products($request)
	{
		$data = [];
		$default = [
			'value' => null,
			'label' => 'Select a Product'
		];
		$data[] = $default;
		if (in_array('woocommerce/woocommerce.php', WPFNL_ACTIVE_PLUGINS)) {
			$all_ids = get_posts([
				'post_type' => 'product',
				'numberposts' => -1,
				'post_status' => 'publish',
				'fields' => 'ids',
			]);
			foreach ($all_ids as $id) {
				$product = wc_get_product($id);
				$type = $product->get_type();
				if ($type == 'variable') {
					$variations = $product->get_available_variations();
					foreach ($variations as $variation) {
						$product = wc_get_product($variation['variation_id']);
						$value = $variation['variation_id'];
						$label = $product->get_name();
						$result = [
							'value' => $value,
							'label' => $label,
						];
						$data[] = $result;
					}
				} else {
					$value = $id;
					$label = $product->get_name();
					$result = [
						'value' => $value,
						'label' => $label,
					];
					$data[] = $result;
				}
			}
		}
		return $data;
	}


	/**
	 * Prepare a single setting object for response.
	 *
	 * @since  3.0.0
	 */
	public function get_products($request) {
		$step_id 			= $request['step_id'];
		$products 			=  get_post_meta($step_id, '_wpfnl_checkout_products', true);
		$use_of_coupon 		=  get_post_meta($step_id, '_wpfnl_checkout_coupon', true);
		$isMultipleProduct 	=  get_post_meta($step_id, '_wpfnl_multiple_product', true);
		$isQuantity 		=  get_post_meta($step_id, '_wpfnl_quantity_support', true);
		if ($use_of_coupon == 'yes') {
			$use_of_coupon = true;
		}
		else {
			$use_of_coupon = false;
		}

		if ($isMultipleProduct == 'yes') {
			$isMultipleProduct = true;
		}
		else {
			$isMultipleProduct = false;
		}

		if ($isQuantity == 'yes') {

			$isQuantity = true;
		}
		else {
			$isQuantity = false;
		}

		if($products) {
			foreach ($products as $value) {
				$product    = wc_get_product($value['id']);
				$title      = $product->get_type() == 'variation' ? Wpfnl_functions::get_formated_product_name( $product ) : $product->get_name();;
				$image      = wp_get_attachment_image_src($product->get_image_id(), 'thumbnail') ? wp_get_attachment_image_src($product->get_image_id(), 'thumbnail') : wp_get_attachment_image_src($product->get_image_id(), 'single-post-thumbnail');
				$price      = $product->get_price();

				if( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ){
                    $signUpFee = \WC_Subscriptions_Product::get_sign_up_fee( $product );
					$price = $price + $signUpFee;
                }
				$response['products'][] = array(
					'id'        		=> $value['id'],
					'title'     		=> $title,
					'price'     		=> wc_price( $price ),
					'quantity'  		=> $value['quantity'],
					'image'     		=> $image ? $image[0] : '',
					'discount_type'		=> '',
					'discount_value'  	=> '',
					'product_edit_link' => in_array($product->get_type(), array( 'variation', 'subscription_variation' )) ? get_edit_post_link($product->get_parent_id()) : get_edit_post_link($product->get_id()),
					'product_view_link' => in_array($product->get_type(), array( 'variation', 'subscription_variation' )) ? get_permalink($product->get_parent_id()) : get_permalink($product->get_id()),
				);
			}
		} else {
			$response['products'] = array();
		}
		$response['coupon'] = $use_of_coupon;
		$response['isMultipleProduct'] = $isMultipleProduct;
		$response['isQuantity'] = $isQuantity;
		$response['success'] = true;
		return $this->prepare_item_for_response( $response, $request );
	}



	public function fetch_products() {
		$products        = [];
		$data_store = \WC_Data_Store::load('product');
		$ids        = $data_store->search_products($term, '', false, false, 10);

		$product_objects = array_filter(array_map('wc_get_product', $ids), 'wc_products_array_filter_readable');
		foreach ($product_objects as $product_object) {
			$formatted_name = $product_object->get_formatted_name();
			if($product_object->get_type() == 'variable') {
				$variations = $product_object->get_available_variations();
				foreach ($variations as $variation) {
					$products[$variation['variation_id']] = [
						'name' => $formatted_name .'('. $variation['sku'].')',
						'price' => $variation['display_price'],
						'sale_price' => $variation['display_regular_price'],
					];
				}
			}
			else {
				$products[$product_object->get_id()] = [
					'name' => rawurldecode($formatted_name),
					'price' => $product_object->get_regular_price(),
					'sale_price' => $product_object->get_sale_price(),
				];
			}
		}
	}

	/**
	 * Calculate Discount Price.
	 *
	 * @param $discount_type
	 * @param $discount_value
	 * @param $product_price
	 * @param string $apply_to
	 * @return string
	 */
	public function calculate_custom_price( $discount_type, $discount_value, $product_price, $apply_to = 'regular' )
	{
		$custom_price = $product_price;
		$custom_price = preg_replace('/[^\d.]/', '', $custom_price);
		if (!empty($discount_type)) {
			if ('discount-percentage' === $discount_type) {
				if ( $discount_value > 0 && $discount_value <= 100 ) {
					$custom_price = $product_price - ( ( $product_price * $discount_value ) / 100);
				}
			} elseif ('discount-price' === $discount_type) {
				if ($discount_value <= $product_price) {
					$custom_price = $product_price - $discount_value;
				}else{
					$custom_price = $product_price;
				}
			}
		}

		return number_format((float)$custom_price, 2);
	}


	/**
	 * Prepare a single setting object for response.
	 *
	 * @since  3.0.0
	 * @param object          $item Setting object.
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response $response Response data.
	 */
	public function prepare_item_for_response( $item, $request ) {
		$data     = $this->add_additional_fields_to_object( $item, $request );
		$response = rest_ensure_response( $data );
		return $response;
	}
}
