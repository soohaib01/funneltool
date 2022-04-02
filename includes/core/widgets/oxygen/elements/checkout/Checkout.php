<?php

namespace WPFunnels\Widgets\Oxygen;

use WPFunnels\Wpfnl_functions;

/**
 * Class Checkout
 * @package WPFunnels\Widgets\Oxygen
 */
class Checkout extends Elements {

    function init() {
        // Do some initial things here.
    }

    function afterInit() {
        // Do things after init, like remove apply params button and remove the add button.
        $this->removeApplyParamsButton();
        // $this->removeAddButton();
    }

    function name() {
        return 'WPF Checkout';
    }

    function slug() {
        return "wpf-checkout";
    }

    function icon() {
        return	plugin_dir_url(__FILE__) . 'icon/checkout.svg';
    }

//    function button_place() {
//        // return "interactive";
//    }

    function button_priority() {
        // return 9;
    }


    function render($options, $defaults, $content) {
		if (!Wpfnl_functions::check_if_this_is_step_type('checkout')){
			echo __('Sorry, Please place the element in WPFunnels Checkout page');
		}else{
			$step_id = isset($_GET['post_id']) ? $_GET['post_id'] : get_the_ID();
			add_filter('woocommerce_locate_template', array($this, 'wpfunnels_woocommerce_locate_template'), 20, 3);
			do_action( 'wpfunnels/before_checkout_form', $step_id );
			$html   =  '<div class="wpfnl-checkout '.$options['layout'].'" >';
			$html  .= do_shortcode('[woocommerce_checkout]');
			$html  .='</div>';
			echo $html;
		}


    }
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

    function controls() {

        //--------Checkout Layout-----
        $layout = $this->addControlSection("optin_layout", __("Layout",'wpfnl'), "assets/icon.png", $this);
        $layout->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Layout",'wpfnl'),
                "slug" => 'layout',
                "default" => "wpfnl-col-2"
            )

        )->setValue(array(
            'wpfnl-col-1'       => __('1 column','wpfnl' ),
            'wpfnl-col-2'       => __('2 column','wpfnl' ),
            'wpfnl-multistep'       => __('Multistep','wpfnl' ),
        ))->rebuildElementOnChange();


        /*------------------------------
            Billing section Style
        ----------------------------*/
        $billing_header = $this->addControlSection("billing_header", __("Billing Heading",'wpfnl'), "assets/icon.png", $this);
        $billing_header_selector = '.wpfnl-checkout .woocommerce-billing-fields > h3';

        $billing_header->typographySection( __("Heading Typography",'wpfnl'), $billing_header_selector, $this );

        $billing_header->addPreset(
            "padding",
            "billing_header_padding",
            __("Padding",'wpfnl'),
            $billing_header_selector
        )->whiteList();

        $billing_header->addPreset(
            "margin",
            "billing_header_margin",
            __("Margin",'wpfnl'),
            $billing_header_selector
        )->whiteList();

        $billing_header->addStyleControls(
			array(
				array(
					"name" => __('Background Color','wpfnl'),
					"selector" => $billing_header_selector,
					"property" => 'background-color',
				),

			)
		);
        $billing_header->borderSection(
            __("Heading Border",'wpfnl'),
            $billing_header_selector,
            $this
        );


        //--------Billing Label Style-----
        $billing_input_label = $this->addControlSection("billing_label", __("Billing Input Label",'wpfnl'), "assets/icon.png", $this);
        $billing__label = '.wpfnl-checkout .woocommerce-billing-fields p.form-row label';

        $billing_input_label->addPreset(
            "margin",
            "billing_input_label_margin",
            __("Label Margin",'wpfnl'),
            $billing__label
        )->whiteList();

        $billing_input_label->typographySection( __("Label Typography",'wpfnl'), $billing__label, $this );

        //--------Billing Input Style-----
        $billing_input_field = $this->addControlSection("billing_inputs", __("Billing Input Field",'wpfnl'), "assets/icon.png", $this);
        $billing_input = '.wpfnl-checkout .woocommerce-billing-fields .form-row input.input-text, .wpfnl-checkout .woocommerce-billing-fields .form-row textarea, .wpfnl-checkout .woocommerce-billing-fields .select2-container--default .select2-selection--single, .wpfnl-checkout .woocommerce-billing-fields .form-row select, .wpfnl-checkout .woocommerce-billing-fields ::placeholder, .wpfnl-checkout .woocommerce-billing-fields ::-webkit-input-placeholder';

        $billing_input_field->addPreset(
            "padding",
            "billing_input_padding",
            __("Input Padding",'wpfnl'),
            '.wpfnl-checkout .woocommerce-billing-fields .form-row input.input-text, .wpfnl-checkout .woocommerce-billing-fields .form-row textarea, .wpfnl-checkout .woocommerce-billing-fields .select2-container .select2-selection--single .select2-selection__rendered, .wpfnl-checkout .woocommerce-billing-fields .form-row select'
        )->whiteList();

        $billing_input_field->addPreset(
            "margin",
            "billing_input_margin",
            __("Input Margin",'wpfnl'),
            $billing_input
        )->whiteList();

        $billing_input_field->addStyleControls(
			array(
				array(
					"name" => __('Background Color','wpfnl'),
					"selector" => $billing_input,
					"property" => 'background-color',
				),

			)
		);

        $billing_input_field->typographySection( __("Input Typography",'wpfnl'), $billing_input, $this );
        $billing_input_field->borderSection( __("Input Border",'wpfnl'), $billing_input, $this );


        /*------------------------------
            Shipping section Style
        ----------------------------*/
        $shipping_header = $this->addControlSection("shipping_header", __("Shipping Heading",'wpfnl'), "assets/icon.png", $this);

        $shipping_header->typographySection(
            __("Heading Typography",'wpfnl'),
            '.wpfnl-checkout .woocommerce-shipping-fields #ship-to-different-address span, .wpfnl-checkout .woocommerce-additional-fields > h3',
            $this
        );

        $shipping_header->addPreset(
            "padding",
            "shipping_header_padding",
            __("Padding",'wpfnl'),
            '.wpfnl-checkout .woocommerce-shipping-fields #ship-to-different-address, .wpfnl-checkout .woocommerce-additional-fields > h3'
        )->whiteList();

        $shipping_header->addPreset(
            "margin",
            "shipping_header_margin",
            __("Margin",'wpfnl'),
            '.wpfnl-checkout .woocommerce-shipping-fields #ship-to-different-address, .wpfnl-checkout .woocommerce-additional-fields > h3'
        )->whiteList();

        $shipping_header->addStyleControls(
			array(
				array(
					"name" => __('Background Color','wpfnl'),
					"selector" => '.wpfnl-checkout .woocommerce-additional-fields > h3, .wpfnl-checkout .woocommerce-shipping-fields > #ship-to-different-address',
					"property" => 'background-color',
				),

			)
		);
        $shipping_header->borderSection(
            __("Heading Border",'wpfnl'),
            '.wpfnl-checkout .woocommerce-additional-fields > h3, .wpfnl-checkout .woocommerce-shipping-fields > #ship-to-different-address',
            $this
        );


        //--------Shipping Label Style-----
        $shipping_input_label = $this->addControlSection("shipping_label", __("Shipping Input Label",'wpfnl'), "assets/icon.png", $this);
        $shipping__label = '.wpfnl-checkout .woocommerce-shipping-fields p.form-row label, .wpfnl-checkout .woocommerce-additional-fields p.form-row label';

        $shipping_input_label->addPreset(
            "margin",
            "shipping_input_label_margin",
            __("Label Margin",'wpfnl'),
            $shipping__label
        )->whiteList();

        $shipping_input_label->typographySection( __("Label Typography",'wpfnl'), $shipping__label, $this );

        //--------Shipping Input Style-----
        $shipping_input_field = $this->addControlSection("shipping_inputs", __("Shipping Input Field",'wpfnl'), "assets/icon.png", $this);
        $shipping_input = '.wpfnl-checkout .woocommerce-shipping-fields .form-row input.input-text, .wpfnl-checkout .woocommerce-additional-fields .form-row textarea, .wpfnl-checkout .woocommerce-shipping-fields .form-row select, .wpfnl-checkout .woocommerce-shipping-fields ::placeholder, .wpfnl-checkout .woocommerce-shipping-fields ::-webkit-input-placeholder, .wpfnl-checkout .woocommerce-additional-fields ::placeholder, .wpfnl-checkout .woocommerce-additional-fields ::-webkit-input-placeholder';

        $shipping_input_field->addPreset(
            "padding",
            "shipping_input_padding",
            __("Input Padding",'wpfnl'),
            '.wpfnl-checkout .woocommerce-shipping-fields .form-row input.input-text, .wpfnl-checkout .woocommerce-additional-fields .form-row textarea, .wpfnl-checkout .woocommerce-shipping-fields .select2-container .select2-selection--single .select2-selection__rendered, .wpfnl-checkout .woocommerce-shipping-fields .form-row select'
        )->whiteList();

        $shipping_input_field->addPreset(
            "margin",
            "shipping_input_margin",
            __("Input Margin",'wpfnl'),
            $shipping_input
        )->whiteList();

        $shipping_input_field->addStyleControls(
			array(
				array(
					"name" => __('Background Color','wpfnl'),
					"selector" => $shipping_input,
					"property" => 'background-color',
				),

			)
		);

        $shipping_input_field->typographySection( __("Input Typography"), $shipping_input, $this );
        $shipping_input_field->borderSection( __("Input Border",'wpfnl'), $shipping_input, $this );


        /*------------------------------
            Order section Style
        ----------------------------*/
        $order_header = $this->addControlSection("order_header", __("Order Heading",'wpfnl'), "assets/icon.png", $this);
        $order_header_selector = '.wpfnl-checkout .woocommerce-checkout #order_review_heading';

        $order_header->typographySection(
            __("Heading Typography",'wpfnl'),
            $order_header_selector,
            $this
        );

        $order_header->addPreset(
            "padding",
            "order_header_padding",
            __("Padding",'wpfnl'),
            $order_header_selector
        )->whiteList();

        $order_header->addPreset(
            "margin",
            "order_header_margin",
            __("Margin",'wpfnl'),
            $order_header_selector
        )->whiteList();

        $order_header->addStyleControls(
			array(
				array(
					"name" => __('Background Color','wpfnl'),
					"selector" => $order_header_selector,
					"property" => 'background-color',
				),

			)
		);
        $order_header->borderSection(
            __("Heading Border",'wpfnl'),
            $order_header_selector,
            $this
        );

        //-------table style-----
        $order_table_section = $this->addControlSection("order_table_section", __("Order Table",'wpfnl'), "assets/icon.png", $this);
        $order_table = '.wpfnl-checkout .woocommerce-checkout table.woocommerce-checkout-review-order-table td, .wpfnl-checkout .woocommerce-checkout table.woocommerce-checkout-review-order-table th';

        $order_table_section->addPreset(
            "margin",
            "order_table_margin",
            __("Table Margin",'wpfnl'),
            '.wpfnl-checkout .woocommerce table.shop_table'
        )->whiteList();

        $order_table_section->addPreset(
            "padding",
            "order_tbl_cell_padding",
            __("Cell Padding",'wpfnl'),
            '.wpfnl-checkout .woocommerce table.shop_table thead th, .wpfnl-checkout .woocommerce table.shop_table thead td, .wpfnl-checkout .woocommerce table.shop_table tbody th, .wpfnl-checkout .woocommerce table.shop_table tbody td, .wpfnl-checkout .woocommerce table.shop_table tfoot td, .wpfnl-checkout .woocommerce table.shop_table tfoot th'
        )->whiteList();

        $order_table_section->addStyleControls(
			array(
				array(
					"name" => __('Background Color','wpfnl'),
					"selector" => '.wpfnl-checkout .woocommerce table.shop_table',
					"property" => 'background-color',
				),

			)
		);
        $order_table_section->typographySection( __("Table Typography",'wpfnl'), $order_table, $this );
        $order_table_section->borderSection(
            __("Table Border",'wpfnl'),
            '.wpfnl-checkout .woocommerce table.shop_table',
            $this
        );


        /*------------------------------
            Payment section Style
        ----------------------------*/
        $payment_section = $this->addControlSection("payment_section", __("Payment Section",'wpfnl'), "assets/icon.png", $this);
        $payment_section->addStyleControls(
			array(
				array(
					"name" => __('Payment Section Background Color','wpfnl'),
					"selector" => '.wpfnl-checkout .woocommerce-checkout #payment',
					"property" => 'background-color',
				),

			)
		);
        $payment_section->addStyleControls(
			array(
				array(
					"name" => __('Payment Section Link Color','wpfnl'),
					"selector" => '.wpfnl-checkout #payment .place-order a, .wpfnl-checkout .woocommerce-checkout #payment .woocommerce-SavedPaymentMethods > li > label a, .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods > li > label a',
					"property" => 'color',
				),

			)
		);
        $payment_section->typographySection(
            __("Payment Section Typography",'wpfnl'),
            '.wpfnl-checkout #payment .place-order p, .woocommerce-terms-and-conditions-wrapper .woocommerce-form__label-for-checkbox .woocommerce-terms-and-conditions-checkbox-text',
            $this
        );
        $payment_section->borderSection(
            __("Payment Section Border",'wpfnl'),
            '.wpfnl-checkout .woocommerce-checkout #payment',
            $this
        );

        //----checkbox and radio button style-----
        // $payment_section->addStyleControls(
		// 	array(
		// 		array(
		// 			"name" => __('Payment Section Checkbox/Radio button default color','wpfnl'),
		// 			"selector" => '.wpfnl-checkout .woocommerce-checkout #payment .woocommerce-SavedPaymentMethods > li > label:before, .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods > li > label:before, .woocommerce-terms-and-conditions-wrapper .woocommerce-form__label-for-checkbox .woocommerce-terms-and-conditions-checkbox-text:before',
		// 			"property" => 'border-color',
        //             $this
		// 		),

		// 	)
		// );

        // $payment_section->addStyleControls(
		// 	array(
		// 		array(
		// 			"name" => __('Payment Section Checkbox/Radio button Active color','wpfnl'),
		// 			"selector" => '.wpfnl-checkout .woocommerce-checkout #payment .woocommerce-SavedPaymentMethods > li > input[type=radio]:checked + label:after, .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods > li > input[type=radio]:checked + label:after, .woocommerce-terms-and-conditions-wrapper .woocommerce-form__label-for-checkbox input[type=checkbox]:checked + .woocommerce-terms-and-conditions-checkbox-text::before',
		// 			"property" => 'background-color',
        //             $this
		// 		),

		// 	)
		// );


        //-------payment method style-------
        $payment_section->typographySection(
            __("Payment Method Typography",'wpfnl'),
            '.wpfnl-checkout .woocommerce-checkout #payment .woocommerce-SavedPaymentMethods > li > label, .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods > li > label',
            $this
        );
        $payment_section->borderSection(
            __("Payment Method Border",'wpfnl'),
            '.wpfnl-checkout .woocommerce-checkout #payment .woocommerce-SavedPaymentMethods > li, .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods > li',
            $this
        );
        $payment_section->addPreset(
            "padding",
            "payment_method_padding",
            __("Payment Method Padding",'wpfnl'),
            '.wpfnl-checkout .woocommerce-checkout #payment .woocommerce-SavedPaymentMethods > li, .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods > li'
        )->whiteList();

        //-------payment method tooltip style-------
        $payment_section->addStyleControls(
			array(
				array(
					"name" => __('Payment Method Tooltip Background Color','wpfnl'),
					"selector" => '.wpfnl-checkout .woocommerce-checkout #payment div.payment_box',
					"property" => 'background-color',
				),
				array(
					"name" => __('Payment Method Tooltip Border Color','wpfnl'),
					"selector" => '.wpfnl-checkout .woocommerce-checkout #payment div.payment_box:before',
					"property" => 'border-bottom-color',
				),

			)
		);
        $payment_section->addPreset(
            "padding",
            "payment_method_tooltip_padding",
            __("Payment Method Tooltip Padding",'wpfnl'),
            '.wpfnl-checkout .woocommerce-checkout #payment div.payment_box'
        )->whiteList();

        $payment_section->typographySection(
            __("Payment Method Tooltip Typography",'wpfnl'),
            '.wpfnl-checkout .woocommerce-checkout #payment div.payment_box, .wpfnl-checkout .woocommerce-checkout #payment div.payment_box p',
            $this
        );


        /*------------------------------
            Order button Style
        ----------------------------*/
        $order_btn_section = $this->addControlSection("order_btn_section", __("Order Button Section",'wpfnl'), "assets/icon.png", $this);
        $order_btn = '.wpfnl-checkout .woocommerce #payment #place_order';
        $order_btn_section->addPreset(
            "padding",
            "order_btn_padding",
            __("Padding",'wpfnl'),
            $order_btn
        )->whiteList();

        $order_btn_section->addPreset(
            "margin",
            "order_btn_margin",
            __("Margin",'wpfnl'),
            $order_btn
        )->whiteList();

        $order_btn_section->typographySection(
            __("Typography",'wpfnl'),
            $order_btn,
            $this
        );
        $order_btn_section->addStyleControls(
			array(
				array(
					"name" => __('Background Color','wpfnl'),
					"selector" => $order_btn,
					"property" => 'background-color',
				),

			)
		);
        $order_btn_section->addStyleControls(
			array(
				array(
					"name" => __('Background Hover Color','wpfnl'),
					"selector" => $order_btn.':hover',
					"property" => 'background-color',
				),

			)
		);
        $order_btn_section->addStyleControls(
			array(
				array(
					"name" => __('Text Hover Color','wpfnl'),
					"selector" => $order_btn.':hover',
					"property" => 'color',
				),

			)
		);
        $order_btn_section->borderSection( __("Border",'wpfnl'), $order_btn, $this );


        /*------------------------------
            Coupon section Style
        ----------------------------*/
        $coupon_section = $this->addControlSection("coupon_section", __("Coupon Section",'wpfnl'), "assets/icon.png", $this);
        $coupon_toggle_box = '.wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info';
        $coupon_section->addPreset(
            "padding",
            "coupon_toggle_box_padding",
            __("Coupon Toggle Box Padding",'wpfnl'),
            $coupon_toggle_box,
        )->whiteList();

        $coupon_section->addPreset(
            "margin",
            "coupon_toggle_box_margin",
            __("Coupon Toggle Box Margin",'wpfnl'),
            $coupon_toggle_box
        )->whiteList();

        $coupon_section->addStyleControls(
			array(
				array(
					"name" => __('Coupon Toggle Box Background Color','wpfnl'),
					"selector" => $coupon_toggle_box,
					"property" => 'background-color',
				),

			)
		);

        $coupon_section->addStyleControls(
			array(
				array(
					"name" => __('Coupon Toggle Box Link Color','wpfnl'),
					"selector" => $coupon_toggle_box.' a',
					"property" => 'color',
				),

			)
		);

        $coupon_section->typographySection(
            __("Coupon Toggle Box Typography",'wpfnl'),
            $coupon_toggle_box,
            $this
        );

        $coupon_section->borderSection( __("Coupon Toggle Box Border",'wpfnl'), $coupon_toggle_box, $this );

        //----------coupon form style---------
        $coupon_form = '.wpfnl-checkout .checkout_coupon.woocommerce-form-coupon';
        $coupon_section->addPreset(
            "padding",
            "coupon_form_padding",
            __("Coupon Form Padding",'wpfnl','wpfnl'),
            $coupon_form,
        )->whiteList();

        $coupon_section->addPreset(
            "margin",
            "coupon_form_margin",
            __("Coupon Form Margin",'wpfnl'),
            $coupon_form
        )->whiteList();

        $coupon_section->addStyleControls(
			array(
				array(
					"name" => __('Coupon Form Background Color','wpfnl'),
					"selector" => $coupon_form,
					"property" => 'background-color',
				),

			)
		);

        $coupon_section->typographySection(
            __("Coupon Form Typography",'wpfnl'),
            $coupon_form.' p:not(.form-row)',
            $this
        );

        $coupon_section->borderSection( __("Coupon Form Border",'wpfnl'), $coupon_form, $this );


        //----------coupon Input field style---------
        $coupon_input_field = '.wpfnl-checkout .checkout_coupon.woocommerce-form-coupon input.input-text';
        $coupon_section->typographySection(
            __("Coupon Form Input Field Typography",'wpfnl'),
            $coupon_input_field,
            $this
        );
        $coupon_section->addStyleControls(
			array(
				array(
					"name" => __('Coupon Form Input Field Background Color','wpfnl'),
					"selector" => $coupon_input_field,
					"property" => 'background-color',
				),

			)
		);
        $coupon_section->borderSection( __("Coupon Form Input Field Border",'wpfnl'), $coupon_input_field, $this );
        $coupon_section->addPreset(
            "padding",
            "coupon_input_field_padding",
            __("Coupon Form Input Field Padding",'wpfnl'),
            $coupon_input_field,
        )->whiteList();

        //----------coupon button style---------
        $coupon_btn = '.wpfnl-checkout .checkout_coupon.woocommerce-form-coupon button[type=submit]';
        $coupon_section->addPreset(
            "padding",
            "coupon_btn_padding",
            __("Coupon Button Padding",'wpfnl'),
            $coupon_btn,
        )->whiteList();

        $coupon_section->addStyleControls(
			array(
				array(
					"name" => __('Coupon Button Background Color','wpfnl'),
					"selector" => $coupon_btn,
					"property" => 'background-color',
				),

			)
		);
        $coupon_section->addStyleControls(
			array(
				array(
					"name" => __('Coupon Button Hover Background Color','wpfnl'),
					"selector" => $coupon_btn.':hover',
					"property" => 'background-color',
				),

			)
		);
        $coupon_section->addStyleControls(
			array(
				array(
					"name" => __('Coupon Button Hover Text Color','wpfnl'),
					"selector" => $coupon_btn.':hover',
					"property" => 'color',
				),

			)
		);

        $coupon_section->typographySection( __("Coupon Button Typography",'wpfnl'), $coupon_btn, $this );
        $coupon_section->borderSection( __("Coupon Button Border",'wpfnl'), $coupon_btn, $this );


        /*------------------------------
            Multistep Style
        ----------------------------*/
        $multistep_section = $this->addControlSection("multistep_section", __("Multistep Section",'wpfnl'), "assets/icon.png", $this);
        $step_title = '.wpfnl-multistep .wpfnl-multistep-wizard li .step-title';

        $multistep_section->typographySection( __("Step Title Typography",'wpfnl'), $step_title, $this );
        $multistep_section->addStyleControls(
			array(
				array(
					"name" => __('Step Normal Line Color','wpfnl'),
					"selector" => '.wpfnl-multistep .wpfnl-multistep-wizard:before',
					"property" => 'background-color',
				),

			)
		);
        $multistep_section->addStyleControls(
			array(
				array(
					"name" => __('Step Active/Completed Line Color','wpfnl'),
					"selector" => '.wpfnl-multistep .wpfnl-multistep-wizard > li.completed:before, .wpfnl-multistep .wpfnl-multistep-wizard > li.current:before',
					"property" => 'background-color',
				),

			)
		);

        $multistep_section->addStyleControls(
			array(
				array(
					"name" => __('Step Box Normal Background Color','wpfnl'),
					"selector" => '.wpfnl-multistep .wpfnl-multistep-wizard li .step-icon',
					"property" => 'background-color',
				),

			)
		);
        $multistep_section->addStyleControls(
			array(
				array(
					"name" => __('Step Box Active/Completed Background Color','wpfnl'),
					"selector" => '.wpfnl-multistep .wpfnl-multistep-wizard li.completed .step-icon, .wpfnl-multistep .wpfnl-multistep-wizard li.current .step-icon',
					"property" => 'background-color',
				),

			)
		);

        $multistep_section->addStyleControls(
			array(
				array(
					"name" => __('Step Box Icon Normal Color','wpfnl'),
					"selector" => '.wpfnl-multistep .wpfnl-multistep-wizard li .step-icon svg path',
					"property" => 'fill',
				),

			)
		);
        $multistep_section->addStyleControls(
			array(
				array(
					"name" => __('Step Box Icon Active/Completed Color','wpfnl'),
					"selector" => '.wpfnl-multistep .wpfnl-multistep-wizard li.completed .step-icon svg path, .wpfnl-multistep .wpfnl-multistep-wizard li.current .step-icon svg path',
					"property" => 'fill',
				),

			)
		);

        $multistep_section->borderSection( __("Step Box Border",'wpfnl'), '.wpfnl-multistep .wpfnl-multistep-wizard li .step-icon', $this );
        $multistep_section->borderSection(
            __("Step Box Active/Completed Border",'wpfnl'),
            '.wpfnl-multistep .wpfnl-multistep-wizard li.completed .step-icon, .wpfnl-multistep .wpfnl-multistep-wizard li.current .step-icon',
            $this
        );

        //--------navigation button-------
        $multistep_section_navigation = $this->addControlSection("multistep_section_navigation", __("Multistep Navigation",'wpfnl'), "assets/icon.png", $this);
        $multistep_nav = '.wpfnl-multistep .wpfnl-multistep-navigation button[type=button]';

        $multistep_section_navigation->typographySection( __("Navigation Button Typography",'wpfnl'), $multistep_nav, $this );
        $multistep_section_navigation->borderSection( __("Navigation Button Border",'wpfnl'), $multistep_nav, $this );
        $multistep_section_navigation->addPreset(
            "padding",
            "multistep_nav_padding",
            __("Navigation Button Padding",'wpfnl'),
            $multistep_nav
        )->whiteList();

        $multistep_section_navigation->addStyleControls(
			array(
				array(
					"name" => __('Navigation Button Background Color','wpfnl'),
					"selector" => $multistep_nav,
					"property" => 'background-color',
				),

			)
		);
        $multistep_section_navigation->addStyleControls(
			array(
				array(
					"name" => __('Navigation Button Hover Background Color','wpfnl'),
					"selector" => '.wpfnl-multistep .wpfnl-multistep-navigation button[type=button]:not(:disabled):hover',
					"property" => 'background-color',
				),

			)
		);

        $multistep_section_navigation->addStyleControls(
			array(
				array(
					"name" => __('Navigation Button Hover Color','wpfnl'),
					"selector" => '.wpfnl-multistep .wpfnl-multistep-navigation button[type=button]:not(:disabled):hover',
					"property" => 'color',
				),

			)
		);

        $multistep_section_navigation->addStyleControls(
			array(
				array(
					"name" => __('Navigation Button Hover Border Color','wpfnl'),
					"selector" => '.wpfnl-multistep .wpfnl-multistep-navigation button[type=button]:not(:disabled):hover',
					"property" => 'border-color',
				),

			)
		);

    }

    function defaultCSS() {

//        error_log(print_r(file_get_contents(__DIR__.'/'.basename(__FILE__, '.php')),1));
//        return file_get_contents(__DIR__.'/'.basename(__FILE__, '.php').'.css');

    }

}
