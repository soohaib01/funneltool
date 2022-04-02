<?php

namespace WPFunnels\Modules\Admin\Product;

use WPFunnels\Admin\Module\Wpfnl_Admin_Module;
use WPFunnels\Traits\SingletonTrait;
use WPFunnels\Wpfnl_functions;
use \WC_Subscriptions_Product;
class Module extends Wpfnl_Admin_Module
{
    use SingletonTrait;

    protected $products = [];

    public function get_view()
    {
        // TODO: Implement get_view() method.
        require_once WPFNL_DIR . '/admin/modules/product/views/view.php';
    }


    public function set_products($products)
    {
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            if ($products) {
                foreach ($products as $saved_product) {
                    $product = wc_get_product($saved_product['id']);
                    if (is_object($product)) {
                        $product_name = $product->get_name();
                        $this->products[] = [
                            'id'            =>  $saved_product['id'],
                            'name'          =>  $product_name,
                            'quantity'      =>  $saved_product['quantity'],
                        ];
                    }
                }
            }
        }
    }

    public function get_products()
    {
        return $this->products;
    }

    public function init_ajax()
    {
        // TODO: Implement init_ajax() method.
        add_action('wp_ajax_wpfnl_product_search', [ $this, 'fetch_products' ]);
        add_action('wp_ajax_wpfnl_product_search_gbf', [ $this, 'fetch_products_gbf' ]);
        add_action('wp_ajax_wpfnl_product_search_for_gbf_type', [ $this, 'fetch_specific_products_gbf' ]);
        add_action('wp_ajax_wpfnl_product_search_by_category_and_name', [ $this, 'wpfnl_product_search_by_category_and_name' ]);

        wp_ajax_helper()->handle('global-funnel-add-upsell-product')
            ->with_callback([ $this, 'global_funnel_add_upsell_product' ])
            ->with_validation($this->get_validation_data());
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
            $term = (string) sanitize_text_field( wp_unslash($_GET['term']) );
        }
        if (empty($term)) {
            wp_die();
        }

        $products        = [];
        $data_store = \WC_Data_Store::load('product');
        $ids        = $data_store->search_products($term, '', false, false, 10);

        $product_objects = array_filter(array_map('wc_get_product', $ids), 'wc_products_array_filter_readable');
        foreach ($product_objects as $product_object) {
            if( ( $product_object->managing_stock() && $product_object->get_stock_quantity() > 0 ) || ( !$product_object->managing_stock() && $product_object->get_stock_status() !== 'outofstock' ) ){
                $formatted_name = $product_object->get_name();
                if($product_object->get_type() == 'variable' || $product_object->get_type() == 'variable-subscription') {
                    if( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ){
                        $signUpFee = \WC_Subscriptions_Product::get_sign_up_fee( $product_object );
                    }
                    $variations = $product_object->get_available_variations();
                    $isPro 		= Wpfnl_functions::is_wpfnl_pro_activated();
                    if($isPro){
                        if( isset($_GET['searchType']) && $_GET['searchType'] === 'checkout' ){
                            $parent_id = $product_object->get_id();
                            $products[$parent_id] = [
                                'name' => $formatted_name,
                                'price' => $product_object->get_regular_price(),
                                'sale_price' => $product_object->get_sale_price(),
                            ];
                        }
                    }
                    
                    foreach ($variations as $variation) {
                        $variation_product = wc_get_product($variation['variation_id']);
                        if( ( $variation_product->managing_stock() && $variation_product->get_stock_quantity() > 0 ) || ( !$variation_product->managing_stock() && $variation_product->get_stock_status() !== 'outofstock' ) ){
                            $products[$variation['variation_id']] = [
                                'name'  =>   Wpfnl_functions::get_formated_product_name( $variation_product ),
                                'price' =>   wc_price($variation_product->get_regular_price()),
                                'sale_price' => $variation_product->get_sale_price() ? wc_price($variation_product->get_sale_price()) : wc_price($variation_product->get_regular_price()),
                            ];
                        }
                    }

                }else {
                $products[$product_object->get_id()] = [
                    'name' => rawurldecode($formatted_name),
                    'price' => $product_object->get_regular_price(),
                    'sale_price' => $product_object->get_sale_price(),
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
    public function fetch_products_gbf()
    {
        check_ajax_referer('wpfnl-admin', 'security');
        if (isset($_GET['term'])) {
            $term = (string) sanitize_text_field( wp_unslash($_GET['term']) );
        }
        if (empty($term)) {
            wp_die();
        }

        $products        = [];
        $data_store = \WC_Data_Store::load('product');
        $ids        = $data_store->search_products($term, '', false, false, 10);

        $product_objects = array_filter(array_map('wc_get_product', $ids), 'wc_products_array_filter_readable');
        foreach ($product_objects as $product_object) {
            if( ( $product_object->managing_stock() && $product_object->get_stock_quantity() > 0 ) || ( !$product_object->managing_stock() && $product_object->get_stock_status() !== 'outofstock' ) ){
                $formatted_name = $product_object->get_name();
                if($product_object->get_type() == 'variable' || $product_object->get_type() == 'variable-subscription') {
                    if( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ){
                        $signUpFee = \WC_Subscriptions_Product::get_sign_up_fee( $product_object );
                    }
                    $variations = $product_object->get_available_variations();
                    $isPro 		= Wpfnl_functions::is_wpfnl_pro_activated();
                    if($isPro){
                        if( isset($_GET['searchType']) && $_GET['searchType'] === 'checkout' ){
                            $parent_id = $product_object->get_id();
                            $products[] = [
                                'name' => $formatted_name,
                                'id'    => $parent_id,
                                'regular_price' => $product_object->get_regular_price(),
                                'sale_price' => $product_object->get_sale_price(),
                            ];
                        }
                    }
                    foreach ($variations as $variation) {
                        $variation_product = wc_get_product($variation['variation_id']);
                        if( ( $variation_product->managing_stock() && $variation_product->get_stock_quantity() > 0 ) || ( !$variation_product->managing_stock() && $variation_product->get_stock_status() !== 'outofstock' ) ){
                            $products[] = [

                                'name'  =>   Wpfnl_functions::get_formated_product_name( $variation_product ),
                                'id'    => $variation['variation_id'],
                                'regular_price' =>  $variation['display_price'],
                                'sale_price' => $variation['display_regular_price'],
                            ];
                        }
                    }

                }else {
                $products[] = [
                    'name' => rawurldecode($formatted_name),
                    'id'    => $product_object->get_id(),
                    'regular_price' => $product_object->get_regular_price(),
                    'sale_price' => $product_object->get_sale_price(),
                ];
                }
            }
        }
        wp_send_json($products);
    }
    
    /**
     * fetch specific product from WC data store for gbf type
     * 
     * @return [type]
     */
    public function fetch_specific_products_gbf()
    {
        check_ajax_referer('wpfnl-admin', 'security');
        if (isset($_GET['term'])) {
            $term = (string) sanitize_text_field( wp_unslash($_GET['term']) );
        }
        if (empty($term)) {
            wp_die();
        }

        $products        = [];
        $data_store = \WC_Data_Store::load('product');
        $ids        = $data_store->search_products($term, '', false, false, 10);

        $product_objects = array_filter(array_map('wc_get_product', $ids), 'wc_products_array_filter_readable');
        foreach ($product_objects as $product_object) {
            if( ( $product_object->managing_stock() && $product_object->get_stock_quantity() > 0 ) || ( !$product_object->managing_stock() && $product_object->get_stock_status() !== 'outofstock' ) ){
                $formatted_name = $product_object->get_name();
                if($product_object->get_type() == 'variable' || $product_object->get_type() == 'variable-subscription') {
                    if( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ){
                        $signUpFee = \WC_Subscriptions_Product::get_sign_up_fee( $product_object );
                    }
                    $variations = $product_object->get_available_variations();
                    foreach ($variations as $variation) {
                        $variation_product = wc_get_product($variation['variation_id']);
                        if( ( $variation_product->managing_stock() && $variation_product->get_stock_quantity() > 0 ) || ( !$variation_product->managing_stock() && $variation_product->get_stock_status() !== 'outofstock' ) ){
                            $products[] = [

                                'name'  =>  Wpfnl_functions::get_formated_product_name( $variation_product ),
                                'id'    => $variation['variation_id'],
                                'regular_price' =>  $variation['display_price'],
                                'sale_price' => $variation['display_regular_price'],
                            ];
                        }
                    }

                }else {
                $products[] = [
                    'name' => rawurldecode($formatted_name),
                    'id'    => $product_object->get_id(),
                    'regular_price' => $product_object->get_regular_price(),
                    'sale_price' => $product_object->get_sale_price(),
                ];
                }
            }
        }
        wp_send_json($products);
    }

    public function get_name()
    {
        // TODO: Implement get_name() method.
        return 'product';
    }

    /**
     * wpfnl_product_search_by_category_and_name
     */
    public function wpfnl_product_search_by_category_and_name(){
        check_ajax_referer('wpfnl-admin', 'security');
        $category_id    = $_GET['category'];
        $product_name   = $_GET['term'];
        $args = array(
            'post_status'       => 'publish',
            'posts_per_page'    => -1,
            'post_type'         => ['product','product_variation'],
            's'                 => $product_name,
            // 'tax_query'         => array(
            //     array(
            //         'taxonomy'    => 'product_cat',
            //         'field'       => 'term_id',
            //         // 'terms'       =>  $category_id,
            //         // 'operator'    =>  'IN',
            //     )
            // )
        );
        $products = get_posts($args);
        if(empty($products)){
            $data = [
                'status'  => 'success',
                'data'    => 'Product not found',
            ];
        }else{
            foreach($products as $key => $product){
                $pro = wc_get_product($product->ID);
                $products[$key]->price = $pro->get_price();
            }
            $data = [
                'status'  => 'success',
                'data'    => $products,
            ];
        }
        wp_send_json($data);
    }
}
