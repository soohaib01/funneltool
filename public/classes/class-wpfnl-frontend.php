<?php
namespace WPFunnels\Frontend;


use WPFunnels\Wpfnl_functions;

class Wpfnl_Frontend
{
    public function __construct()
    {
        add_filter('woocommerce_get_checkout_order_received_url', [ $this, 'redirect_to_next_step' ], 10, 2);
    }


    /**
     * Next step url after checkout is
     * completed
     *
     * @param $order_received_url
     * @param $order
     * @return string
     */
    public function redirect_to_next_step($order_received_url, $order)
    {
        $order_key = $order->get_order_key();
        $order_id = $order->get_id();
        $current_page_id = get_post_meta($order_id, '_wpfunnel_checkout_id', true);
        $funnel_id = get_post_meta($order_id, '_wpfunnel_id', true);
        $next_step = Wpfnl_functions::get_next_step($funnel_id, $current_page_id);
        if ($next_step) {
            
            $custom_url = Wpfnl_functions::custom_url_for_thankyou_page( $next_step['id'] );
            if( $custom_url ){
                return $custom_url;
            }

            $next_step_link = get_page_link($next_step['id']);
            $permalink_structure = get_option('permalink_structure');
            if ($permalink_structure) {
                $redirect_link = $next_step_link.'?order_id='.$order_id.'&order_key='.$order_key;
            } else {
                $redirect_link = $next_step_link.'&order_id='.$order_id.'&order_key='.$order_key;
            }
            
            return $redirect_link;
        }
        return $order_received_url;
    }
}
