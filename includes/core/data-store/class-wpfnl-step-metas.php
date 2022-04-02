<?php

namespace WPFunnels\Metas;

class Wpfnl_Step_Meta_keys
{

    /**
     * list of all meta keys used
     * in different step initializations
     *
     * @param string $type
     * @return mixed
     */
    public static function get_meta_keys($type = 'landing')
    {
        $meta_keys = [
            'landing' => [],
            'checkout' => [
                '_wpfnl_checkout_products' => [],
                '_wpfnl_checkout_discount' => [],
                '_wpfnl_checkout_coupon' => '',
            ],
            'thankyou' => [
                '_wpfnl_thankyou_text' => '',
                '_wpfnl_thankyou_redirect_link' => '',
                '_wpfnl_thankyou_order_overview' => 'on',
                '_wpfnl_thankyou_order_details' => 'on',
                '_wpfnl_thankyou_billing_details' => 'on',
                '_wpfnl_thankyou_shipping_details' => 'on',
            ],
            'upsell' => [
                '_wpfnl_upsell_product' => [],
                '_wpfnl_upsell_discount_type' => '',
                '_wpfnl_upsell_discount_value' => '',
                '_wpfnl_upsell_product_price' => '',
                '_wpfnl_upsell_product_sale_price' => '',
                '_wpfnl_upsell_hide_image' => 'off',
                '_wpfnl_upsell_next_step_yes' => '',
                '_wpfnl_upsell_next_step_no' => '',
            ],
            'downsell' => [
                '_wpfnl_downsell_product' => [],
                '_wpfnl_downsell_discount_type' => '',
                '_wpfnl_downsell_discount_value' => '',
                '_wpfnl_downsell_product_price' => '',
                '_wpfnl_downsell_product_sale_price' => '',
                '_wpfnl_downsell_hide_image' => 'off',
                '_wpfnl_downsell_next_step_yes' => '',
                '_wpfnl_downsell_next_step_no' => '',
            ],
        ];
        return $meta_keys[$type];
    }
}
