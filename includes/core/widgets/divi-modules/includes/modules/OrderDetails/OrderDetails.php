<?php

namespace WPFunnels\Widgets\DiviModules\Modules;

use ET_Builder_Element;
use ET_Builder_Module;
use WPFunnels\Wpfnl_functions;

class WPFNL_Order_details extends ET_Builder_Module {

    public $slug       = 'wpfnl_order_details';
    public $vb_support = 'on';

    protected $module_credits = array(
        'module_uri' => '',
        'author'     => '',
        'author_uri' => '',
    );
    /**
     * Module properties initialization
     */
    public function init() {

        $this->name = __( 'WPF Order Details', 'wpfnl-pro' );

        $this->icon_path        =  plugin_dir_path( __FILE__ ) . 'order_details.svg';

        $this->settings_modal_toggles  = array(
            'general'  => array(
                'toggles' => array(
                    'order_details'     => __( 'Order Details', 'wpfnl-pro' ),
                ),
            ),
        );
    }

    /**
     * Module's specific fields
     *
     *
     * The following modules are automatically added regardless being defined or not:
     *   Tabs     | Toggles          | Fields
     *   --------- ------------------ -------------
     *   Content  | Admin Label      | Admin Label
     *   Advanced | CSS ID & Classes | CSS ID
     *   Advanced | CSS ID & Classes | CSS Class
     *   Advanced | Custom CSS       | Before
     *   Advanced | Custom CSS       | Main Element
     *   Advanced | Custom CSS       | After
     *   Advanced | Visibility       | Disable On
     * @return array
     */

    public function get_fields() {
        return array(
            'enable_order_overview'       => array(
                'label'            => __( 'Enable Order Overview', 'wpfnl-pro' ),
                'description'      => __( 'Enable Order Overview', 'wpfnl-pro' ),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => array(
                    'off' => __( 'No','wpfnl' ),
                    'on'  => __( 'Yes','wpfnl' ),
                ),
                'default'          => 'on',
                'default_on_front' => 'on',
                'toggle_slug'      => 'order_details',
                'computed_affects' => array(
                    '__orderDetails',
                ),
            ),
            'enable_order_details'       => array(
                'label'            => __( 'Enable Order Details', 'wpfnl-pro' ),
                'description'      => __( 'Enable Order Details', 'wpfnl-pro' ),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => array(
                    'off' => __( 'No','wpfnl' ),
                    'on'  => __( 'Yes','wpfnl' ),
                ),
                'default'          => 'on',
                'default_on_front' => 'on',
                'toggle_slug'      => 'order_details',
                'computed_affects' => array(
                    '__orderDetails'
                ),
            ),
            'enable_billing_details'       => array(
                'label'            => __( 'Enable Billing Details', 'wpfnl-pro' ),
                'description'      => __( 'Enable Billing Details', 'wpfnl-pro' ),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => array(
                    'off' => __( 'No','wpfnl' ),
                    'on'  => __( 'Yes','wpfnl' ),
                ),
                'default'          => 'on',
                'default_on_front' => 'on',
                'toggle_slug'      => 'order_details',
                'computed_affects' => array(
                    '__orderDetails'
                ),
            ),
            'enable_shipping_details'       => array(
                'label'            => __( 'Enable Shipping Details', 'wpfnl-pro' ),
                'description'      => __( 'Enable Shipping Details', 'wpfnl-pro' ),
                'type'             => 'yes_no_button',
                'option_category'  => 'configuration',
                'options'          => array(
                    'off' => __( 'No','wpfnl' ),
                    'on'  => __( 'Yes','wpfnl' ),
                ),
                'default'          => 'on',
                'default_on_front' => 'on',
                'toggle_slug'      => 'order_details',
                'computed_affects' => array(
                    '__orderDetails'
                ),
            ),

            '__orderDetails'        => array(
                'type'                => 'computed',
                'computed_callback'   => array(
                    'WPFunnels\Widgets\DiviModules\Modules\WPFNL_Order_details',
                    'get_order_details',
                ),
                'computed_depends_on' => array(
                    'enable_order_overview',
                    'enable_shipping_details',
                    'enable_billing_details',
                    'enable_order_details'
                )
            ),
        );
    }

    /**
     * Computed checkout form
     * @param $props
     * @return string
     */

    public static  function get_order_details($props) {

        $step_id = isset($_POST['current_page']['id']) ? $_POST['current_page']['id'] : get_the_ID();
        if (isset($props['enable_order_overview']) && !empty($props['enable_order_overview']) ) {
            $order_overview = $props['enable_order_overview'];
            update_post_meta($step_id, '_wpfnl_thankyou_order_overview', $props['enable_order_overview']);
        }else{
            $order_overview = 'off';
            update_post_meta( $step_id, '_wpfnl_thankyou_order_overview', 'off' );
        }

        if (isset($props['enable_order_details']) && !empty($props['enable_order_details']) ) {
            $order_details = $props['enable_order_details'];
            update_post_meta($step_id, '_wpfnl_thankyou_order_details', $props['enable_order_details']);
        }else{
            $order_details = 'off';
            update_post_meta( $step_id, '_wpfnl_thankyou_order_details', 'off' );
        }

        if (isset($props['enable_billing_details']) && !empty($props['enable_billing_details']) ) {
            $billing_details = $props['enable_billing_details'];
            update_post_meta($step_id, '_wpfnl_thankyou_billing_details', $props['enable_billing_details']);
        }else{
            $billing_details = 'off';
            update_post_meta( $step_id, '_wpfnl_thankyou_billing_details', 'off' );
        }

        if (isset($props['enable_shipping_details']) && !empty($props['enable_shipping_details']) ) {
            $shipping_details = $props['enable_shipping_details'];
            update_post_meta($step_id, '_wpfnl_thankyou_shipping_details', $props['enable_shipping_details']);
        }else{
            $shipping_details = 'off';
            update_post_meta( $step_id, '_wpfnl_thankyou_shipping_details', 'off' );
        }


        add_filter( 'wpfunnels/show_dummy_order_details', '__return_true' );

        $html = '<div class = "wpfnl-elementor-order-details-form wpfnl-elementor-display-order-overview-'.$order_overview.'  wpfnl-elementor-display-order-details-'.$order_details .' wpfnl-elementor-display-billing-address-'.$billing_details.' wpfnl-elementor-display-shipping-address-'.$shipping_details.'">
				 '.do_shortcode( '[wpfunnels_order_details]' ).'
			</div>';
        return $html;
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

    /**
     * Render Checkout form
     * @param array $attrs
     * @param null $content
     * @param string $render_slug
     * @return bool|string|null
     */

    public function render( $attrs, $content = null, $render_slug ) {
        $output = self::get_order_details( $this->props );
        return $output;
    }


}

new WPFNL_Order_details;
