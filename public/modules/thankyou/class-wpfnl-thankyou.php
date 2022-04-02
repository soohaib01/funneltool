<?php

namespace WPFunnels\Modules\Frontend\Thankyou;

use WPFunnels\Data_Store\Wpfnl_Steps_Store_Data;
use WPFunnels\Frontend\Module\Wpfnl_Frontend_Module;
use WPFunnels\Wpfnl_functions;

class Module extends Wpfnl_Frontend_Module
{
    public function __construct()
    {
        add_action('template_redirect', [ $this, 'redirect_to_custom_url' ]);
        add_filter('woocommerce_order_item_name', [ $this, 'wpfnl_change_product_name' ], 10, 2);
        add_filter('woocommerce_display_item_meta', [ $this, 'wpfnl_remove_attribute' ], 10, 3);


//        add_action('wp_enqueue_scripts', [ $this, 'enqueue_scripts' ]);
    }


    /**
     * redirect thankyou page to
     * custom url
     *
     * @since 1.0.0
     */
    public function redirect_to_custom_url()
    {
        
        global $post;
        if (Wpfnl_functions::check_if_this_is_step_type('thankyou')) {
            
            $thankyou           = new Wpfnl_Steps_Store_Data();
            $thankyou->set_id($post->ID);
            $redirect_link = $thankyou->get_meta($post->ID, '_wpfnl_thankyou_redirect_link');
            if (! empty($redirect_link)) {
                exit(wp_redirect($redirect_link));
            }
        }
    }


    public function custom_thankyou_text($text, $order)
    {
        global $post;
        if(!$post) {
            return $text;
        }
        $thankyou       = new Wpfnl_Steps_Store_Data();
        $new_text       = $thankyou->get_meta($post->ID, '_wpfnl_thankyou_text');
       
        if (! empty($new_text)) {
            $woo_text = $new_text;
        } else {
            $woo_text = "Thank You";
        }
        return $woo_text;
    }


    public function enqueue_scripts()
    {
        if (Wpfnl_functions::check_if_this_is_step_type('thankyou')) {
            $style = $this->generate_style();
            wp_add_inline_style('wp-funnel', $style);
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


    /**
     * change product name in thank you page
     * 
     * @param mixed $item_name
     * @param mixed $item
     * 
     * @return String $item_name
     */
    public function wpfnl_change_product_name( $item_name, $item ) { 
        if (!Wpfnl_functions::check_if_this_is_step_type('thankyou')) {
            return $item_name; 
        }
        
        
        $product      = $item->get_product();
        if( $item['variation_id'] ){
            $meta_data = $item->get_formatted_meta_data( '_', true );
            $product = wc_get_product( $item['variation_id'] );
            $attributes = $product->get_attributes();
            foreach($attributes as $attribute_key=>$attribute_value){
                if( $attribute_value ){
                    
                    $selected_attr[] = $attribute_key;
                    $formatted_attr['attribute_'.$attribute_key] = $attribute_value;
                }else{
                    foreach( $meta_data as $data ){
                        if( $data->key == $attribute_key ){
                            $formatted_attr['attribute_'.$attribute_key] = $data->value;
                        }
                    }
                   
                }
            }
        }else{
            $product = wc_get_product( $item['product_id'] );
        }
        
        $item_name = $product->get_type() == 'variation' ? Wpfnl_functions::get_formated_product_name( $product, $formatted_attr ) : $item_name;
        
       
        return $item_name; 
    }


    /**
     * Remove variable attribute
     * 
     * @param mixed $html
     * @param mixed $item
     * @param mixed $args
     * 
     * @return String $html
     */
    public function wpfnl_remove_attribute( $html, $item, $args ){

        if (! Wpfnl_functions::check_if_this_is_step_type('thankyou') ) {
            return $html; 
        }
        $html = '';
        return $html;
    }
}
