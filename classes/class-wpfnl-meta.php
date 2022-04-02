<?php

namespace WPFunnels\Meta;

class Wpfnl_Default_Meta {


    /**
     * get checkout meta field
     *
     * @param int $post_id
     * @param string $key
     * @param bool $default
     * @return mixed|void
     *
     * @since 2.0.3
     */
    public function get_checkout_meta_value( $post_id = 0, $key = '', $default = false ) {
        $value = $this->get_meta( $post_id, $key );
        if ( ! $value ) {
            if (false !== $default) {
                $value = $default;
            }
        }
        return apply_filters( "wpfunnels/checkout_meta_{$key}", $value );
    }


    /**
     * get post meta value
     *
     * @param $post_id
     * @param $key
     * @return mixed
     *
     * @since 2.0.3
     */
    public function get_meta( $post_id, $key ) {
        return get_post_meta( $post_id, $key, true );
    }


	/**
	 * update funnel meta
	 *
	 * @param $post_id
	 * @param $key
	 * @param $value
	 */
	public function update_meta( $post_id, $key, $value ) {
		update_post_meta( $post_id, $key, $value );
	}


	/**
	 * get funnel meta value by key
	 *
	 * @param $funnel_id
	 * @param $key
	 * @param $default
	 * @return mixed
	 */
    public function get_funnel_meta( $funnel_id, $key, $default = '' ) {
    	$value = $this->get_meta( $funnel_id, $key );
    	if( $value ) {
    		return $value;
		}
    	return $default;
	}


	/**
	 * get default order bump settings
	 *
	 * @return mixed|void
	 * @since 2.0.4
	 */
    public static function get_default_order_bump_meta() {
		return apply_filters('wpfunnels/get_default_order_bump_data', array(
			'selectedStyle' 	=> '',
			'position' 			=> '',
			'product' 			=> '',
			'quantity' 			=> '',
			'price' 			=> '',
			'salePrice' 		=> '',
			'htmlPrice'	 		=> '',
			'productImage' 		=> array(
				'url'	=> '',
				'id'	=> '',
			),
			'highLightText' 	=> '',
			'checkBoxLabel' 	=> '',
			'productDescriptionText' => '',
			'discountOption' 	=> '',
			'discountapply' 	=> '',
			'discountValue' 	=> '',
			'couponName' 		=> '',
			'obNextStep' 		=> '',
			'productName' 		=> '',
			'isEnabled' 		=> 'yes',
			'isReplace' 		=> '',
			'replace' 			=> '',
			'obPrimaryColor' 	=> '',
		));
	}


	/**
	 * get default offer data
	 *
	 * @return array
	 */
	public static function get_default_offer_product() {
    	return array(
			'step_id'                 => '',
			'id'                      => '',
			'name'                    => '',
			'desc'                    => '',
			'qty'                     => '',
			'original_price'          => '',
			'unit_price'              => '',
			'unit_price_tax'          => '',
			'args'                    => array(
				'subtotal' => '',
				'total'    => '',
			),
			'shipping_fee'            => '',
			'shipping_fee_incl_tax'   => '',
			'shipping_method_name'    => '',
			'price'                   => '',
			'url'                     => '',
			'total_unit_price_amount' => '',
			'total'                   => '',
			'cancel_main_order'       => '',
			'amount_diff'             => '',
		);
	}


	/**
	 * get main product ids of a
	 * funnel
	 *
	 * @param $funnel_id
	 * @param $step_id
	 * @return mixed|void
	 */
	public function get_main_product_ids( $funnel_id, $step_id ) {
		$main_products 		= $this->get_main_products( $funnel_id, $step_id );
		$main_product_ids 	= array();
		if( $main_products && is_array($main_products) ) {
			foreach ($main_products as $product) {
				$main_product_ids[] = $product['id'];
			}
		}
		return apply_filters( 'wpfunnels/main_product_ids', $main_product_ids, $funnel_id, $step_id );
	}


	/**
	 * get main product of a funnel
	 *
	 * @param $funnel_id
	 * @param $step_id
	 * @return mixed|void
	 */
	public function get_main_products( $funnel_id, $step_id ) {
		return apply_filters('wpfunnels/funnel_products', $this->get_checkout_meta_value( $step_id, '_wpfnl_checkout_products' ), $funnel_id, $step_id);
	}
}
