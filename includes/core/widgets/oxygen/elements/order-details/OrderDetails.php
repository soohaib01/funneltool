<?php

namespace WPFunnels\Widgets\Oxygen;
use WPFunnels\Wpfnl_functions;

/**
 * Class OrderDetails
 */
class OrderDetails extends Elements {

    function init() {
        // Do some initial things here.
    }

    function afterInit() {
        // Do things after init, like remove apply params button and remove the add button.
        $this->removeApplyParamsButton();
        // $this->removeAddButton();
    }

    function name() {
        return 'WPF Order Details';
    }

    function slug() {
        return "wpfnl-order-details";
    }

    function icon() {
		return	plugin_dir_url(__FILE__) . 'icon/order_details.svg';
    }

//    function button_place() {
//        // return "interactive";
//    }

    function button_priority() {
        // return 9;
    }


    function render($options, $defaults, $content) {
		if (!Wpfnl_functions::check_if_this_is_step_type('thankyou')){
			echo __('Sorry, Please place the element in WPFunnels Thank You page');
		}else{
			$step_id = isset($_GET['post_id']) ? $_GET['post_id'] : get_the_ID();

			$order_overview 	= 	get_post_meta($step_id, '_wpfnl_thankyou_order_overview', true) ? get_post_meta($step_id, '_wpfnl_thankyou_order_overview', true) : 'on';
			$order_details 		=  	get_post_meta($step_id, '_wpfnl_thankyou_order_details', true) ? get_post_meta($step_id, '_wpfnl_thankyou_order_details', true) : 'on';
			$billing_details 	= 	get_post_meta($step_id, '_wpfnl_thankyou_billing_details', true) ? get_post_meta($step_id, '_wpfnl_thankyou_billing_details', true) : 'on';
			$shipping_details 	= 	get_post_meta($step_id, '_wpfnl_thankyou_shipping_details', true) ? get_post_meta($step_id, '_wpfnl_thankyou_shipping_details', true) : 'on';

			add_filter( 'wpfunnels/show_dummy_order_details', '__return_true' );
			?>
			<div class = "wpfnl-elementor-order-details-form wpfnl-elementor-display-order-overview-<?=$order_overview?>'  wpfnl-elementor-display-order-details-<?=$order_details?> wpfnl-elementor-display-billing-address-<?=$billing_details?> wpfnl-elementor-display-shipping-address-<?=$shipping_details?>">
				<?=   do_shortcode( '[wpfunnels_order_details]' ) ?>
			</div>

			<?php
		}


    }
    function controls() {
		/*
        $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Enable Order Overview","wpfnl"),
                "slug" => 'enable_order_overview',
                "default" => "on"
            )
        )->setValue(array(
            'off'       => __('No' ),
            'on'       => __('Yes' ),
        ))->rebuildElementOnChange();

        $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Enable Order Details","wpfnl"),
                "slug" => 'enable_order_details',
                "default" => "on"
            )
        )->setValue(array(
            'off'       => __('No',"wpfnl"),
            'on'       => __('Yes',"wpfnl" ),
        ))->rebuildElementOnChange();

        $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Enable Billing Details","wpfnl"),
                "slug" => 'enable_billing_details',
                "default" => "on"
            )
        )->setValue(array(
            'off'       => __('No',"wpfnl" ),
            'on'       => __('Yes',"wpfnl" ),
        ))->rebuildElementOnChange();

        $this->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Enable Shipping Details","wpfnl"),
                "slug" => 'enable_shipping_details',
                "default" => "on"
            )
        )->setValue(array(
            'off'       => __('No',"wpfnl" ),
            'on'       => __('Yes',"wpfnl" ),
        ))->rebuildElementOnChange();
		*/
    }

    function defaultCSS() {

    }
}
