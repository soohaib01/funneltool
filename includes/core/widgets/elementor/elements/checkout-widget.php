<?php

namespace WPFunnels\Widgets\Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use WPFunnels\Wpfnl_functions;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Funnel sell accept button
 *
 * @since 1.0.0
 */
class Checkout_Form extends Widget_Base
{
    /**
     * Retrieve the widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     *
     * @access public
     *
     */
    public function get_name()
    {
        return 'wpfnl-checkout';
    }

    /**
     * Retrieve the widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     *
     * @access public
     *
     */
    public function get_title()
    {
        return __('Checkout', 'wpfnl');
    }

    /**
     * Retrieve the widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     *
     * @access public
     *order-bump-settings
     */
    public function get_icon()
    {
        return 'icon-wpfnl checkout-icon';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @return array Widget categories.
     * @since 1.0.0
     *
     * @access public
     *
     */
    public function get_categories()
    {
        return ['wp-funnel'];
    }

    /**
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @return array Widget scripts dependencies.
     * @since 1.0.0
     *
     * @access public
     *
     */
    public function get_script_depends()
    {
        return ['wpfnl-checkout-widget'];
    }

    /**
     * Get all funnel steps
     * @since 1.0.0
     *
     * @access protected
     */
    protected function get_steps_array($type = 'upsell')
    {
        $options = $this->get_prev_next_link_options();
        $response = [];

        if (isset($options[$type])) {
            $prime_data = $options[$type];
            foreach ($prime_data as $data) {
                $response[$data['id']] = $data['title'];
            }
        }
        return $response;
    }

    public function get_prev_next_link_options()
    {
        $associate_funnel_id = get_post_meta(get_the_ID(), '_funnel_id', true);
        $steps_array = [
            'upsell' => 'Upsell',
            'downsell' => 'Downsell',
            'thankyou' => 'Thankyou'
        ];
        $option_group = [];
        foreach ($steps_array as $key => $value) {
            $args = [
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_type' => WPFNL_STEPS_POST_TYPE,
                'post_status' => 'publish',
                'post__not_in' => [$this->get_id()],
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => '_step_type',
                        'value' => $key,
                        'compare' => '=',
                    ],
                    [
                        'key' => '_funnel_id',
                        'value' => $associate_funnel_id,
                        'compare' => '=',
                    ],
                ],
            ];
            $query = new \WP_Query($args);
            $steps = $query->posts;
            if ($steps) {
                foreach ($steps as $s) {
                    $option_group[$key][] = [
                        'id' => $s->ID,
                        'title' => $s->post_title,
                    ];
                }
            }
        }
        return $option_group;
    }

    /**
     * Get all WC products
     * @since 1.0.0
     *
     * @access protected
     */
    protected function get_products_array()
    {
        $products = [];
        $ids = wc_get_products(['return' => 'ids', 'limit' => -1]);
        foreach ($ids as $id) {
            $title = get_the_title($id);
            $products[$id] = $title;
        }
        return $products;
    }

    /**
     * Order Bump Settings
     * @since 1.0.0
     *
     * @access protected
     */
    protected function order_bump_primary_settings()
    {
        $default = [
            'isEnabled' => 'no',
            'selectedStyle' => 'style1',
            'position' => 'after-order',
            'product' => '',
            'quantity' => '1',
            'price' => '',
            'salePrice' => '',
            'htmlPrice' => '',
            'productImage' => '',
            'highLightText' => 'Special one time offer',
            'checkBoxLabel' => 'Grab this offer with one click!',
            'productDescriptionText' => 'Get this scratch proof 6D Tempered Glass Screen Protector for your iPhone. Keep your phone safe and sound just like a new one. ',
            'discountOption' => '',
            'discountValue' => '',
            'couponName' => '',
            'obNextStep' => 'default',
            'productName' => '',
            'isReplace' => 'no',
        ];

        $settings = get_post_meta(get_the_ID(), 'order-bump-settings', true);
        if ($settings) {
            return $settings;
        }

        return $default;
    }


	/**
	 * get layout types
	 *
	 * @return array
	 */
    private function get_layout_types() {
    	$layouts = array(
			'wpfnl-col-1' 		=> __('1 Column', 'wpfnl'),
			'wpfnl-col-2' 		=> __('2 Column', 'wpfnl'),
			'wpfnl-multistep' 	=> __('Multistep', 'wpfnl'),
		);
    	return $layouts;
	}


    /**
     * Register cart controls controls.
     *
     * @since x.x.x
     * @access protected
     */
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Funnel Checkout', 'wpfnl'),
            ]
        );

        $this->add_control(
            'checkout_layout',
            [
                'label' 	=> __('Select Layout', 'wpfnl'),
                'type' 		=> \Elementor\Controls_Manager::SELECT,
                'default' 	=> 'wpfnl-col-2',
                'options' 	=> $this->get_layout_types(),
            ]
        );

		if ( ! Wpfnl_functions::is_wpfnl_pro_activated() ) {

			$this->add_control(
				'layout_upgrade_pro',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf( __( 'This is a pro feature. <a href="%s" target="_blank" rel="noopener">Upgrade Now!</a>.', 'wpfnl' ), 'https://getwpfunnels.com/pricing/' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
					'condition'       => array(
						'checkout_layout' => 'wpfnl-multistep',
					),
				)
			);
		}

        //=== Order Bump Start ===//
        $order_bump_enabler = '';
        // $order_bump_position = 'after-order';
        // $order_bump_style = 'style1';
        // $order_bump_product = '';
        // $order_bump_quantity = 1;
        // $order_bump_image = \Elementor\Utils::get_placeholder_image_src();
        // $checkbox_label = 'Grab this offer with one click!';
        // $highlight_text = 'Special one time offer';
        // $order_bump_description = 'Get this scratch proof 6D Tempered Glass Screen Protector for your iPhone. Keep your phone safe and sound just like a new one. ';
        // $order_bump_discount_type = 'original';
        // $order_bump_discount_value = '';
        // $order_bump_discount_coupon = '';
        // $order_bump_next_step = '';
        //
        $order_bump_enabler = get_post_meta(get_the_ID(), 'order-bump', true);
        if ($order_bump_enabler == 'yes') {
            $order_bump_enabler = 'yes';
        } else {
            $order_bump_enabler = 'no';
        }

        $ob_settings = $this->order_bump_primary_settings();

        if (isset($ob_settings['position'])) {
            $order_bump_position = $ob_settings['position'];
        }
        if (isset($ob_settings['position'])) {
            $order_bump_position = $ob_settings['position'];
        }
        if (isset($ob_settings['selectedStyle'])) {
            $order_bump_style = $ob_settings['selectedStyle'];
        }
        if (isset($ob_settings['productImage'])) {

            $order_bump_image = isset($ob_settings['productImage']['url']) ? $ob_settings['productImage']['url'] : '';
        }
        if (isset($ob_settings['checkBoxLabel'])) {
            $checkbox_label = $ob_settings['checkBoxLabel'];
        }
        if (isset($ob_settings['highLightText'])) {
            $highlight_text = $ob_settings['highLightText'];
        }
        if (isset($ob_settings['productDescriptionText'])) {
            $order_bump_description = $ob_settings['productDescriptionText'];
        }

        $this->add_control(
            'order_bump',
            [
                'label' => __('Enable Order Bump', 'wpfnl'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => $order_bump_enabler,
                'options' => [
                    'yes' => __('Yes', 'wpfnl'),
                    'no' => __('No', 'wpfnl'),
                ],
            ]
        );

        $this->add_control(
            'order_bump_position',
            [
                'label' => __('Order Bump Position', 'wpfnl'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => $order_bump_position,
                'options' => [
                    'after-order' 				=> __('Before Order Details', 'wpfnl'),
                    'before-checkout' 			=> __('Before Checkout Details', 'wpfnl'),
                    'after-customer-details' 	=> __('After Customer Details', 'wpfnl'),
                    'after-payment' 			=> __('After Payment Options', 'wpfnl'),
                    'before-payment'	 		=> __('Before Payment Options', 'wpfnl'),
                    'popup' 					=> __('Pop-up offer', 'wpfnl'),
                ],
                'condition' => [
                    'order_bump' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'order_bump_layout',
            [
                'label' => __('Select Style', 'wpfnl'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => $order_bump_style,
                'options' => [
                    'style1' => __('Style 1', 'wpfnl'),
                    'style2' => __('Style 2', 'wpfnl'),
                ],
                'condition' => [
                    'order_bump' => 'yes',
                    'order_bump_position!' => 'popup',
                ]
            ]
        );

        // $this->add_control(
        //     'order_bump_product_selector',
        //     [
        //         'label' => __(' Select Product', 'wpfnl'),
        //         'type' => \WPFunnels\Widgets\Elementor\Controls\Product_Control::ProductSelector,
        //         'options' => $this->get_products_array(),
        //         'multiple' => false,
        //         'default' => $order_bump_product,
        //         'minimumInputLength' => 3,
        //         'condition' => [
        //             'order_bump' => 'on',
        //         ]
        //     ]
        // );
        //
        // $this->add_control(
        //     'order_bump_quantity',
        //     [
        //     'label' => __('Quantity', 'wpfnl'),
        //     'type' => \Elementor\Controls_Manager::NUMBER,
        //     'min' => 1,
        //     'max' => 100,
        //     'step' => 1,
        //     'default' => $order_bump_quantity,
        //     'condition' => [
        //         'order_bump' => 'on',
        //     ]
        //   ]
        // );
        $this->add_control(
            'order_bump_image',
            [
                'label' => __('Choose Image', 'wpfnl'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => $order_bump_image,
                ],
                'condition' => [
                    'order_bump' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'order_bump_checkbox_label',
            [
                'label' => __('Checkbox Label', 'wpfnl'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => $checkbox_label,
                'placeholder' => __('Type your text here', 'wpfnl'),
                'condition' => [
                    'order_bump' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'order_bump_product_detail_header',
            [
                'label' => __('Highlight Text', 'wpfnl'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => $highlight_text,
                'placeholder' => __('Type your text here', 'wpfnl'),
                'condition' => [
                    'order_bump' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'order_bump_product_detail',
            [
                'label' => __('Product Detail', 'wpfnl'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 5,
                'default' => $order_bump_description,
                'placeholder' => __('Type your description here', 'wpfnl'),
                'condition' => [
                    'order_bump' => 'yes',
                ]
            ]
        );


        // $this->add_control(
        //     'order_bump_discount_type',
        //     [
        //         'label' => __('Discount Type', 'wpfnl'),
        //         'type' => \Elementor\Controls_Manager::SELECT,
        //         'default' => $order_bump_discount_type,
        //         'options' => [
        //             'original'  => __('Original', 'wpfnl'),
        //             'discount-percentage' => __('Discount percentage', 'wpfnl'),
        //             'discount-price' => __('Discount price', 'wpfnl'),
        //             'coupon' => __('Coupon', 'wpfnl'),
        //         ],
        //         'condition' => [
        //             'order_bump' => 'on',
        //         ]
        //    ]
        // );
        //
        // $this->add_control(
        //     'order_bump_discount_price',
        //     [
        //     'label' => __('Discount Value', 'wpfnl'),
        //     'type' => \Elementor\Controls_Manager::NUMBER,
        //     'min' => 1,
        //     'max' => 100,
        //     'step' => 1,
        //     'default' => $order_bump_discount_value,
        //     'condition' => [
        //         'order_bump_discount_type' => 'discount-price'
        //     ]
        //   ]
        // );
        //
        // $this->add_control(
        //     'order_bump_discount_value',
        //     [
        //     'label' => __('Discount Value', 'wpfnl'),
        //     'type' => \Elementor\Controls_Manager::NUMBER,
        //     'min' => 1,
        //     'max' => 100,
        //     'step' => 1,
        //     'default' => $order_bump_discount_value,
        //     'condition' => [
        //         'order_bump_discount_type' => 'discount-percentage'
        //     ]
        //   ]
        // );
        //
        // $this->add_control(
        //     'order_bump_discount_coupon',
        //     [
        //     'label' => __('Discount Coupon', 'wpfnl'),
        //     'type' => \Elementor\Controls_Manager::NUMBER,
        //     'min' => 1,
        //     'max' => 100,
        //     'step' => 1,
        //     'default' => $order_bump_discount_coupon,
        //     'condition' => [
        //         'order_bump_discount_type' => 'coupon'
        //     ]
        //   ]
        // );
        //
        // $this->add_control(
        //     'order_bump_is_replace',
        //     [
        //         'label' => __('Replace First Product', 'wpfnl'),
        //         'type' => \Elementor\Controls_Manager::SELECT,
        //         'default' => $order_bump_is_replace,
        //         'options' => [
        //             'yes'  => __('Yes', 'wpfnl'),
        //             'no' => __('No', 'wpfnl'),
        //         ],
        //         'condition' => [
        //             'order_bump' => 'on',
        //         ]
        //    ]
        // );
        //
        // $this->add_control(
        //     'order_bump_next_step',
        //     [
        //         'label' => __('Next Step', 'wpfnl'),
        //         'type' => \Elementor\Controls_Manager::SELECT,
        //         'default' => $order_bump_next_step,
        //         'groups' => [
        //             [
        //                 'label' => __('Upsell', 'wpfnl'),
        //                 'options' => self::get_steps_array('upsell')
        //             ],
        //             [
        //                 'label' => __('Thank You', 'wpfnl'),
        //                 'options' => self::get_steps_array('downsell')
        //             ],
        //             [
        //                 'label' => __('Thank You', 'wpfnl'),
        //                 'options' => self::get_steps_array('thankyou')
        //             ],
        //         ],
        //         'condition' => [
        //             'order_bump' => 'on',
        //         ]
        //    ]
        // );

        //=== Order Bump End ===//

        $this->end_controls_section();

        $this->register_billing_style_controls();
        $this->register_shipping_style_controls();
        $this->register_your_order_style_controls();
        $this->register_payment_section_style_controls();
        $this->register_coupon_style_controls();
        $this->register_error_style_controls();
        $this->register_multistep_style_controls();
    }

    /**
     * Register Multistep style Controls.
     *
     * @since x.x.x
     * @access protected
     */
    protected function register_multistep_style_controls()
    {
        $this->start_controls_section(
            'section_multistep_style_fields',
            [
                'label' => __('Multistep Section', 'wpfnl'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'checkout_layout' => 'wpfnl-multistep',
                ],
            ]
        );


        // ----start step title style-----
        $this->add_control(
            'step_title_heading',
            [
                'label' => __('Step Title', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'step_title_color',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '#363B4E',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li .step-title' => 'color: {{VALUE}};'
                ],


            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'step_title_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li .step-title',
            ]
        );

        // ----start active step style-----
        $this->add_control(
            'hr2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'multistep_checkout_state',
            [
                'label' => __('Step State', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'step_box_shadow',
				'label' => __( 'Box Shadow', 'wpfnl' ),
				'selector' => '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li .step-icon',
			]
		);

        //----start normal tab style---
        $this->start_controls_tabs('multistep_checkout_tab');
        $this->start_controls_tab(
            'multistep_checkout_normal',
            [
                'label' => __('Normal', 'wpfnl'),
            ]
        );

        $this->add_control(
            'step_normal_line_color',
            [
                'label' => __('Line Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '#eee',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_normal_box_bgcolor',
            [
                'label' => __('Box Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e8e8ed',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li .step-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_normal_icon_color',
            [
                'label' => __('Icon Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '#6E42D3',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li .step-icon svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_normal_box_border_style',
            [
                'label' => __('Border Style', 'wpfnl'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'solid',
                'options' => [
                    'inherit' => __('None', 'wpfnl'),
                    'solid' => __('Solid', 'wpfnl'),
                    'double' => __('Double', 'wpfnl'),
                    'dotted' => __('Dotted', 'wpfnl'),
                    'dashed' => __('Dashed', 'wpfnl'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li .step-icon' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_normal_box_border_size',
            [
                'label' => __('Border Width', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li .step-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'step_normal_box_border_style!' => 'inherit',
                ]
            ]
        );

        $this->add_control(
            'step_normal_box_border_color',
            [
                'label' => __('Border Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li .step-icon' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'step_normal_box_border_style!' => 'inherit',
                ]

            ]
        );

        $this->end_controls_tab();
        //----end normal tab style---

        //----active/completed tab style---
        $this->start_controls_tab(
            'multistep_checkout_active',
            [
                'label' => __('Active / Completed', 'wpfnl'),
            ]
        );

        $this->add_control(
            'step_active_line_color',
            [
                'label' => __('Line Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '#6E42D3',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard > li.completed:before,
                    {{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard > li.current:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_active_box_bgcolor',
            [
                'label' => __('Box Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '#6E42D3',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li.completed .step-icon,
                    {{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li.current .step-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_active_icon_color',
            [
                'label' => __('Icon Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li.completed .step-icon svg path,
                    {{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li.current .step-icon svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_active_box_border_style',
            [
                'label' => __('Border Style', 'wpfnl'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => 'solid',
                'options' => [
                    'inherit' => __('None', 'wpfnl'),
                    'solid' => __('Solid', 'wpfnl'),
                    'double' => __('Double', 'wpfnl'),
                    'dotted' => __('Dotted', 'wpfnl'),
                    'dashed' => __('Dashed', 'wpfnl'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li.completed .step-icon,
                    {{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li.current .step-icon' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'step_active_box_border_size',
            [
                'label' => __('Border Width', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li.completed .step-icon,
                    {{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li.current .step-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'step_active_box_border_style!' => 'inherit',
                ]
            ]
        );

        $this->add_control(
            'step_active_box_border_color',
            [
                'label' => __('Border Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '#6E42D3',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li.completed .step-icon,
                    {{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-wizard li.current .step-icon' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'step_active_box_border_style!' => 'inherit',
                ]

            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        // ----end active step style-----


        // ----start navigation button style-----
        $this->add_control(
            'hr3',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'step_navigation_btn',
            [
                'label' => __('Navigation Button', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        //----start normal tab style---
        $this->start_controls_tabs('step_navigation_btn_tab');
        $this->start_controls_tab(
            'step_navigation_btn_normal',
            [
                'label' => __('Normal', 'wpfnl'),
            ]
        );

        $this->add_control(
            'step_navigation_btn_color',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-navigation button[type=button]' => 'color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'step_navigation_btn_bgcolor',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-navigation button[type=button]' => 'background-color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->end_controls_tab();
        //----end normal tab style---

        //----hover tab style---
        $this->start_controls_tab(
            'step_navigation_btn_hover',
            [
                'label' => __('Hover', 'wpfnl'),
            ]
        );

        $this->add_control(
            'step_navigation_btn_hover_color',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-navigation button[type=button]:not(:disabled):hover' => 'color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'step_navigation_btn_hover_bgcolor',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-navigation button[type=button]:not(:disabled):hover' => 'background-color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        // ----end hover step style-----

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'step_navigation_btn_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-navigation button[type=button]',
            ]
        );

        $this->add_responsive_control(
            'step_navigation_btn_padding',
            [
                'label' => __('Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-navigation button[type=button]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'step_navigation_btn_radius',
            [
                'label' => __('Radius', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-multistep .wpfnl-multistep-navigation button[type=button]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Sections error Controls.
     *
     * @since x.x.x
     * @access protected
     */
    protected function register_error_style_controls()
    {
        $this->start_controls_section(
            'section_error_style_fields',
            [
                'label' => __('Field Validation & Error Messages', 'wpfnl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'error_fields_text',
            [
                'label' => __('Field Validation', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'error_label_color',
            [
                'label' => __('Label Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce form .form-row.woocommerce-invalid label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'error_field_border_color',
            [
                'label' => __('Field Border Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfunnels-checkout-form .select2-container--default.field-required .select2-selection--single,
                    {{WRAPPER}}  form .form-row input.input-text.field-required,
                    {{WRAPPER}}  form .form-row textarea.input-text.field-required,
                    {{WRAPPER}}  #order_review .input-text.field-required
                    {{WRAPPER}}  form .form-row.woocommerce-invalid .select2-container,
                    {{WRAPPER}}  form .form-row.woocommerce-invalid input.input-text,
                    {{WRAPPER}}  form .form-row.woocommerce-invalid select' => 'border-color: {{VALUE}};',

                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'error_fields_section',
            [
                'label' => __('Error Messages', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'error_text_color',
            [
                'label' => __('Error Message Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}  .woocommerce-error,
            {{WRAPPER}}  .woocommerce-NoticeGroup .woocommerce-error,
            {{WRAPPER}}  .woocommerce-notices-wrapper .woocommerce-error' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'error_bg_color',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .woocommerce-error,
            {{WRAPPER}}  .woocommerce-NoticeGroup .woocommerce-error,
            {{WRAPPER}}  .woocommerce-notices-wrapper .woocommerce-error' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'error_border_color',
            [
                'label' => __('Border Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .woocommerce-error,
                    {{WRAPPER}}  .woocommerce-NoticeGroup .woocommerce-error,
                    {{WRAPPER}}  .woocommerce-notices-wrapper .woocommerce-error' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}}  .woocommerce-error::before' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Sections Style Controls.
     *
     * @since x.x.x
     * @access protected
     */
    protected function register_payment_section_style_controls()
    {
        $this->start_controls_section(
            'section_payment_style_fields',
            [
                'label' => __('Payment Section', 'wpfnl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'payment_section_text_color',
            [
                'label' => __('Text Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout #payment .place-order,
                    {{WRAPPER}} .wpfnl-checkout #payment .place-order p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'payment_section_link_color',
            [
                'label' => __('Link Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout #payment .place-order a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'payment_section_bg_color',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'payment_section_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout #payment .place-order,
                {{WRAPPER}} .wpfnl-checkout #payment .place-order p',
            ]
        );

        $this->add_responsive_control(
            'payment_section_radius',
            [
                'label' => __('Radius', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        // ----Payment Method style-----
        $this->add_control(
            'payment_method_heading_style',
            [
                'label' => __('Payment Method Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'payment_method_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods li,
                {{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment div.payment_box',
            ]
        );

        $this->add_control(
            'payment_method_color',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'payment_method_bg_color',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods' => 'background-color: {{VALUE}};',
                ],

            ]
        );

        $this->add_control(
            'payment_method_border_style',
            [
                'label' => __('Border Style', 'wpfnl'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => '',
                'options' => [
                    '' => __('Inherit', 'wpfnl'),
                    'solid' => __('Solid', 'wpfnl'),
                    'double' => __('Double', 'wpfnl'),
                    'dotted' => __('Dotted', 'wpfnl'),
                    'dashed' => __('Dashed', 'wpfnl'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'payment_method_border_size',
            [
                'label' => __('Border Width', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'payment_method_border_color',
            [
                'label' => __('Border Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods' => 'border-color: {{VALUE}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'payment_method_radius',
            [
                'label' => __('Radius', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'payment_method_padding',
            [
                'label' => __('Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'payment_method_margin',
            [
                'label' => __('Margin', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment ul.payment_methods' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        // ----Payment Box style-----
        $this->add_control(
            'payment_box_heading_style',
            [
                'label' => __('Payment Box Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'payment_box_txt_color',
            [
                'label' => __('Text Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment div.payment_box' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'payment_box_bg_color',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment div.payment_box' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment div.payment_box:before' => 'border-bottom-color: {{VALUE}};',
                ],

            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'payment_box_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment div.payment_box',
            ]
        );
        $this->add_responsive_control(
            'payment_box_radius',
            [
                'label' => __('Radius', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment div.payment_box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'payment_box_padding',
            [
                'label' => __('Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment div.payment_box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'payment_box_margin',
            [
                'label' => __('Margin', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #payment div.payment_box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );


        // ----Order Button style-----
        $this->add_control(
            'order_button_heading_style',
            [
                'label' => __('Order Button Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'order_button_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce #payment #place_order',
            ]
        );

        $this->start_controls_tabs('order_button_tab');
        $this->start_controls_tab(
            'order_button_normal',
            [
                'label' => __('Normal', 'wpfnl'),
            ]
        );

        $this->add_control(
            'order_button_color',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce #payment #place_order' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'order_button_bg_color',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce #payment #place_order' => 'background-color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'order_button_box_shadow',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce #payment #place_order',
            ]
        );
        $this->end_controls_tab();

        //----hover style---
        $this->start_controls_tab(
            'order_button_button_hover',
            [
                'label' => __('Hover', 'wpfnl'),
            ]
        );

        $this->add_control(
            'order_button_color_hover',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce #payment #place_order:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'order_button_bg_color_hover',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce #payment #place_order:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'order_button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce #payment #place_order:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        //-----------------------

        $this->add_responsive_control(
            'order_button_radius',
            [
                'label' => __('Radius', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce #payment #place_order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'order_button_padding',
            [
                'label' => __('Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce #payment #place_order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'order_button_margin',
            [
                'label' => __('Margin', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce #payment #place_order' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register General Style Controls.
     *
     * @since x.x.x
     * @access protected
     */
    protected function register_global_style_controls()
    {
        $this->start_controls_section(
            'section_general_style_fields',
            [
                'label' => __('Global', 'wpfnl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'global_primary_color',
            [
                'label' => __('Primary Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}  .woocommerce-checkout .product-name .remove:hover,
            {{WRAPPER}}  #payment input[type=checkbox]:checked:before,
            {{WRAPPER}}  .woocommerce-shipping-fields [type="checkbox"]:checked:before,
            {{WRAPPER}} -info::before,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-message::before,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce a,
            {{WRAPPER}} .wpfunnels-checkout-form-two-step .wpfunnels-checkout-form-steps .wpfunnels-current .step-name,
            body .wpfunnels-pre-checkout-offer-wrapper .wpfunnels-content-main-head .wpfunnels-content-modal-title .wpfunnels_first_name' => 'color: {{VALUE}};',

                    '{{WRAPPER}} .wpfunnels-checkout-form .woocommerce .woocommerce-checkout .product-name .remove:hover,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #payment input[type=checkbox]:focus,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce .woocommerce-shipping-fields [type="checkbox"]:focus,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #payment input[type=radio]:checked:focus,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #payment input[type=radio]:not(:checked):focus,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce form.woocommerce-form-login .form-row button,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button.wpfunnels-btn-small,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.woocommerce-form-login .button,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.checkout_coupon .button,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.login .button:hover,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.checkout_coupon .button:hover,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #payment #place_order:hover,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button.wpfunnels-btn-small:hover,
            {{WRAPPER}} .wpfunnels-checkout-form-two-step .woocommerce .wpfunnels-checkout-form-nav-btns .wpfunnels-next-button,
            {{WRAPPER}} .wpfunnels-checkout-form-two-step .wpfunnels-checkout-form-note,
            body .wpfunnels-pre-checkout-offer-wrapper #wpfunnels-pre-checkout-offer-content button.wpfunnels-pre-checkout-offer-btn' => 'border-color: {{VALUE}};',

                    '{{WRAPPER}} .wpfunnels-checkout-form .woocommerce #payment input[type=radio]:checked:before,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce form.woocommerce-form-login .form-row button,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button.wpfunnels-btn-small,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.woocommerce-form-login .button,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.checkout_coupon .button,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.login .button:hover,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.checkout_coupon .button:hover,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #payment #place_order:hover,
            {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button.wpfunnels-btn-small:hover,
            {{WRAPPER}} .wpfunnels-checkout-form-two-step  .wpfunnels-checkout-form-steps .step-one.wpfunnels-current:before,
            {{WRAPPER}} .wpfunnels-checkout-form-two-step  .wpfunnels-checkout-form-steps .step-two.wpfunnels-current:before,
            {{WRAPPER}} .wpfunnels-checkout-form-two-step .wpfunnels-checkout-form-steps .steps.wpfunnels-current:before,
            {{WRAPPER}} .wpfunnels-checkout-form-two-step .wpfunnels-checkout-form-note,
            body .wpfunnels-pre-checkout-offer-wrapper .wpfunnels-nav-bar-step.active .wpfunnels-progress-nav-step,
            body .wpfunnels-pre-checkout-offer-wrapper .wpfunnels-nav-bar-step.active .wpfunnels-nav-bar-step-line:before,
            body .wpfunnels-pre-checkout-offer-wrapper .wpfunnels-nav-bar-step.active .wpfunnels-nav-bar-step-line:after' => 'background-color: {{VALUE}};',

                    '{{WRAPPER}} .wpfunnels-checkout-form-two-step .wpfunnels-checkout-form-note:before' => 'border-top-color: {{VALUE}};',

                    '{{WRAPPER}} .wpfunnels-checkout-form-two-step .woocommerce .wpfunnels-checkout-form-nav-btns .wpfunnels-next-button,
            {{WRAPPER}} .wpfunnels-checkout-form-two-step .woocommerce .wpfunnels-checkout-form-nav-btns a.wpfunnels-next-button,
            {{WRAPPER}} .wpfunnels-checkout-form form.checkout_coupon .button,
            body .wpfunnels-pre-checkout-offer-wrapper #wpfunnels-pre-checkout-offer-content button.wpfunnels-pre-checkout-offer-btn' => 'background-color: {{VALUE}}; color: #fff;',
                ],
            ]
        );

        $this->add_control(
            'global_text_color',
            [
                'label' => __('Text Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfunnels-checkout-form,
            {{WRAPPER}} .wpfunnels-checkout-form #payment .woocommerce-privacy-policy-text p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'global_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfunnels-checkout-form',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Button Style Controls.
     *
     * @since x.x.x
     * @access protected
     */
    protected function register_button_style_controls()
    {
        $this->start_controls_section(
            'button_section',
            [
                'label' => __('Buttons', 'wpfnl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'buttons_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button,
          {{WRAPPER}} .wpfunnels-checkout-form .woocommerce form.woocommerce-form-login .form-row button,
          {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button.wpfnl-btn-small,
          {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.woocommerce-form-login .button,
          {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.checkout_coupon .button,
          {{WRAPPER}} .wpfunnels-checkout-form form.checkout_coupon .button,
          {{WRAPPER}} .wpfunnels-checkout-form-two-step .woocommerce .wpfunnels-checkout-form-nav-btns .wpfnl-next-button,
          body .wpfnl-pre-checkout-offer-wrapper #wpfnl-pre-checkout-offer-content button.wpfnl-pre-checkout-offer-btn',
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __('Normal', 'wpfnl'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce form.woocommerce-form-login .form-row button,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button.wpfnl-btn-small,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.woocommerce-form-login .button,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.checkout_coupon .button,
                {{WRAPPER}} .wpfunnels-checkout-form form.checkout_coupon .button,
                {{WRAPPER}} .wpfunnels-checkout-form-two-step .woocommerce .wpfunnels-checkout-form-nav-btns .wpfnl-next-button,
                body .wpfnl-pre-checkout-offer-wrapper #wpfnl-pre-checkout-offer-content button.wpfnl-pre-checkout-offer-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_background_color',
                'label' => __('Background Color', 'wpfnl'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce form.woocommerce-form-login .form-row button,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button.wpfnl-btn-small,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.woocommerce-form-login .button,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.checkout_coupon .button,
              {{WRAPPER}} .wpfunnels-checkout-form form.checkout_coupon .button,
              {{WRAPPER}} .wpfunnels-checkout-form-two-step .woocommerce .wpfunnels-checkout-form-nav-btns .wpfnl-next-button,
              body .wpfnl-pre-checkout-offer-wrapper #wpfnl-pre-checkout-offer-content button.wpfnl-pre-checkout-offer-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'label' => __('Border', 'wpfnl'),
                'selector' => '{{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce form.woocommerce-form-login .form-row button,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button.wpfnl-btn-small,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.woocommerce-form-login .button,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.checkout_coupon .button,
              {{WRAPPER}} .wpfunnels-checkout-form form.checkout_coupon .button,
              {{WRAPPER}} .wpfunnels-checkout-form-two-step .woocommerce .wpfunnels-checkout-form-nav-btns .wpfnl-next-button,
              body .wpfnl-pre-checkout-offer-wrapper #wpfnl-pre-checkout-offer-content button.wpfnl-pre-checkout-offer-btn',
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => __('Rounded Corners', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce form.woocommerce-form-login .form-row button,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button.wpfnl-btn-small,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.woocommerce-form-login .button,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.checkout_coupon .button,
                {{WRAPPER}} .wpfunnels-checkout-form form.checkout_coupon .button,
                {{WRAPPER}} .wpfunnels-checkout-form-two-step .woocommerce .wpfunnels-checkout-form-nav-btns .wpfnl-next-button,
                body .wpfnl-pre-checkout-offer-wrapper #wpfnl-pre-checkout-offer-content button.wpfnl-pre-checkout-offer-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce form.woocommerce-form-login .form-row button,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button.wpfnl-btn-small,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.woocommerce-form-login .button,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.checkout_coupon .button,
              {{WRAPPER}} .wpfunnels-checkout-form form.checkout_coupon .button,
              {{WRAPPER}} .wpfunnels-checkout-form-two-step .woocommerce .wpfunnels-checkout-form-nav-btns .wpfnl-next-button,
              body .wpfnl-pre-checkout-offer-wrapper #wpfnl-pre-checkout-offer-content button.wpfnl-pre-checkout-offer-btn',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __('Hover', 'wpfnl'),
            ]
        );

        $this->add_control(
            'btn_hover_color',
            [
                'label' => __('Text Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.login .button:hover,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.checkout_coupon .button:hover,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #payment #place_order:hover,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button.wpfnl-btn-small:hover,
                {{WRAPPER}} .wpfunnels-checkout-form form.checkout_coupon .button:hover,
                {{WRAPPER}} .wpfunnels-checkout-form-two-step .woocommerce .wpfunnels-checkout-form-nav-btns .wpfnl-next-button:hover,
                body .wpfnl-pre-checkout-offer-wrapper #wpfnl-pre-checkout-offer-content button.wpfnl-pre-checkout-offer-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __('Border Hover Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.login .button:hover,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.checkout_coupon .button:hover,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #payment #place_order:hover,
                {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button.wpfnl-btn-small:hover,
                {{WRAPPER}} .wpfunnels-checkout-form form.checkout_coupon .button:hover,
                {{WRAPPER}} .wpfunnels-checkout-form-two-step .woocommerce .wpfunnels-checkout-form-nav-btns .wpfnl-next-button:hover,
                body .wpfnl-pre-checkout-offer-wrapper #wpfnl-pre-checkout-offer-content button.wpfnl-pre-checkout-offer-btn' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'button_background_hover_color',
                'label' => __('Background Color', 'wpfnl'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.login .button:hover,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce-checkout form.checkout_coupon .button:hover,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #payment #place_order:hover,
              {{WRAPPER}} .wpfunnels-checkout-form .woocommerce #order_review button.wpfnl-btn-small:hover,
              {{WRAPPER}} .wpfunnels-checkout-form form.checkout_coupon .button:hover,
              {{WRAPPER}} .wpfunnels-checkout-form-two-step .woocommerce .wpfunnels-checkout-form-nav-btns .wpfnl-next-button:hover,
              body .wpfnl-pre-checkout-offer-wrapper #wpfnl-pre-checkout-offer-content button.wpfnl-pre-checkout-offer-btn',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Function to get skin types.
     *
     * @since x.x.x
     * @access protected
     */
    protected function get_skin_types()
    {
        $skin_options = [];

        $skin_options = [
            'default' => __('Default', 'wpfnl'),
            'style-one' => __('Floating Labels', 'wpfnl'),
        ];

        return $skin_options;
    }

    /**
     * Register Billing Fields Style Controls.
     *
     * @since x.x.x
     * @access protected
     */
    protected function register_billing_style_controls()
    {
        $this->start_controls_section(
            'billing_input_section',
            [
                'label' => __('Billing Section', 'wpfnl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // ----headding style-----
        $this->add_control(
            'billing_heading_style',
            [
                'label' => __('Heading Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'billing_heading_text_color',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields h3,
                    {{WRAPPER}}  .wpfnl-checkout .woocommerce-billing-fields h3 span' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'billing_heading_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields h3,
                {{WRAPPER}} .woocommerce.woocommerce-checkout #customer_details .woocommerce-billing-fields h3,
                {{WRAPPER}} .woocommerce-page.woocommerce-checkout #customer_details .woocommerce-billing-fields h3',
            ]
        );

        $this->add_responsive_control(
            'billing_heading_margin',
            [
                'label' => __('Margin', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}}  .wpfnl-checkout .woocommerce-billing-fields h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'billing_heading_padding',
            [
                'label' => __('Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}}  .wpfnl-checkout .woocommerce-billing-fields h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'separator' => 'after',
            ]
        );
        //-----end heading style-----


        // -----label style------
        $this->add_control(
            'billing_label_style',
            [
                'label' => __('Label Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'billing_label_color',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields p.form-row label' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'billing_label_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields p.form-row label',
            ]
        );

        $this->add_responsive_control(
            'billing_label_margin',
            [
                'label' => __('Margin', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}}  .wpfnl-checkout .woocommerce-billing-fields p.form-row label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'separator' => 'after',
            ]
        );
        //-----end label style-----


        // -----input field style------
        $this->add_control(
            'billing_input_field_style',
            [
                'label' => __('Input Field Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'billing_input_color',
            [
                'label' => __('Text Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row input.input-text,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row textarea,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .select2-container--default .select2-selection--single,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .select2-container--default .select2-selection--single .select2-selection__rendered,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row select,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields ::placeholder,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields ::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'billing_input_text_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row input.input-text,
                {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row textarea,
                {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .select2-container--default .select2-selection--single,
                {{WRAPPER}} .woocommerce-billing-fields .select2-container--default .select2-selection--single .select2-selection__rendered,
                {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row select.select,
                {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row select',
            ]
        );

        $this->add_control(
            'billing_input_bgcolor',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row input.input-text,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row textarea,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .select2-container--default .select2-selection--single,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row select.select,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row select' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'billing_input_border_style',
            [
                'label' => __('Border Style', 'wpfnl'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => '',
                'options' => [
                    '' => __('Inherit', 'wpfnl'),
                    'solid' => __('Solid', 'wpfnl'),
                    'double' => __('Double', 'wpfnl'),
                    'dotted' => __('Dotted', 'wpfnl'),
                    'dashed' => __('Dashed', 'wpfnl'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row input.input-text,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row textarea,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .select2-container--default .select2-selection--single,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row select.select,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row select' => 'border-style: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'billing_input_border_size',
            [
                'label' => __('Border Width', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row input.input-text,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row textarea,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .select2-container--default .select2-selection--single,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row select.select,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .form-row select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'billing_input_border_color',
            [
                'label' => __('Border Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields .form-row input.input-text,
            {{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields .form-row textarea,
            {{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields .select2-container--default .select2-selection--single,
            {{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields .form-row select.select,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .select2-container--focus .select2-selection,
            {{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields .form-row select' => 'border-color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'billing_input_radius',
            [
                'label' => __('Radius', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields .form-row input.input-text,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields .form-row textarea,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields .select2-container--default .select2-selection--single,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields select.select,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields .form-row select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'billing_input_padding',
            [
                'label' => __('Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields .form-row input.input-text,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields .form-row textarea,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields select.select,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields .form-row select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-billing-fields .select2-container .select2-selection--single .select2-selection__rendered' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
                ],

            ]
        );

        $this->add_responsive_control(
            'billing_input_margin',
            [
                'label' => __('Margin', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout form .woocommerce-billing-fields p.form-row:not(#billing_address_1_field)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register Shipping Fields Style Controls.
     *
     * @since x.x.x
     * @access protected
     */
    protected function register_shipping_style_controls()
    {
        $this->start_controls_section(
            'shipping_input_section',
            [
                'label' => __('Additional Info / Shipping Section', 'wpfnl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // ----headding style-----
        $this->add_control(
            'shipping_heading_style',
            [
                'label' => __('Heading Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'shipping_heading_text_color',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields #ship-to-different-address label.woocommerce-form__label,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields > h3' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'shipping_heading_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields #ship-to-different-address span,
                {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields > h3'
            ]
        );

        $this->add_responsive_control(
            'shipping_heading_margin',
            [
                'label' => __('Margin', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields #ship-to-different-address,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields > h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'shipping_heading_padding',
            [
                'label' => __('Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields #ship-to-different-address,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields > h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'separator' => 'after',
            ]
        );
        //-----end heading style-----


        // -----label style------
        $this->add_control(
            'shipping_label_style',
            [
                'label' => __('Label Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'shipping_label_color',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields p.form-row label,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields p.form-row label' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'shipping_label_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields p.form-row label,
                                {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields p.form-row label',
            ]
        );

        $this->add_responsive_control(
            'shipping_label_margin',
            [
                'label' => __('Margin', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields p.form-row label,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields p.form-row label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'separator' => 'after',
            ]
        );
        //-----end label style-----


        // -----input field style------
        $this->add_control(
            'shipping_input_field_style',
            [
                'label' => __('Input Field Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'shipping_input_color',
            [
                'label' => __('Text Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row input.input-text,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields .form-row textarea,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .select2-container--default .select2-selection--single,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .select2-container--default .select2-selection--single .select2-selection__rendered,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row select,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields ::placeholder,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields ::-webkit-input-placeholder,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields ::placeholder,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields ::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'shipping_input_text_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row input.input-text,
                {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields .form-row textarea,
                {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .select2-container--default .select2-selection--single,
                {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .select2-container--default .select2-selection--single .select2-selection__rendered,
                {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row select.select,
                {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row select',
            ]
        );

        $this->add_control(
            'shipping_input_bgcolor',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row input.input-text,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields .form-row textarea,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .select2-container--default .select2-selection--single,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row select.select,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row select' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'shipping_input_border_style',
            [
                'label' => __('Border Style', 'wpfnl'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => '',
                'options' => [
                    '' => __('Inherit', 'wpfnl'),
                    'solid' => __('Solid', 'wpfnl'),
                    'double' => __('Double', 'wpfnl'),
                    'dotted' => __('Dotted', 'wpfnl'),
                    'dashed' => __('Dashed', 'wpfnl'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row input.input-text,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields .form-row textarea,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .select2-container--default .select2-selection--single,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row select.select,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row select' => 'border-style: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'shipping_input_border_size',
            [
                'label' => __('Border Width', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row input.input-text,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-additional-fields .form-row textarea,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .select2-container--default .select2-selection--single,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row select.select,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .form-row select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'shipping_input_border_color',
            [
                'label' => __('Border Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout form .woocommerce-shipping-fields .form-row input.input-text,
            {{WRAPPER}} .wpfnl-checkout form .woocommerce-additional-fields .form-row textarea,
            {{WRAPPER}} .wpfnl-checkout form .woocommerce-shipping-fields .select2-container--default .select2-selection--single,
            {{WRAPPER}} .wpfnl-checkout form .woocommerce-shipping-fields .form-row select.select,
            {{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .select2-container--focus .select2-selection,
            {{WRAPPER}} .wpfnl-checkout form .woocommerce-shipping-fields .form-row select' => 'border-color: {{VALUE}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'shipping_input_radius',
            [
                'label' => __('Radius', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout form .woocommerce-shipping-fields .form-row input.input-text,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-additional-fields .form-row textarea,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-shipping-fields .select2-container--default .select2-selection--single,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-shipping-fields select.select,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-shipping-fields .form-row select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'shipping_input_padding',
            [
                'label' => __('Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout form .woocommerce-shipping-fields .form-row input.input-text,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-additional-fields .form-row textarea,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-shipping-fields .select2-container--default .select2-selection--single,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-shipping-fields select.select,
                    {{WRAPPER}} .wpfnl-checkout form .woocommerce-shipping-fields .form-row select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-shipping-fields .select2-container .select2-selection--single .select2-selection__rendered' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
                ],

            ]
        );

        $this->add_responsive_control(
            'shipping_input_margin',
            [
                'label' => __('Margin', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout form .woocommerce-shipping-fields p.form-row:not(#shipping_address_1_field)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_section();
    }


    /**
     * Register Order Table Style Controls.
     *
     * @since x.x.x
     * @access protected
     */
    protected function register_your_order_style_controls()
    {
        $this->start_controls_section(
            'your_order_section',
            [
                'label' => __('Order Table Section', 'wpfnl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // ----headding style-----
        $this->add_control(
            'order_heading_style',
            [
                'label' => __('Heading Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'order_heading_color',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #order_review_heading' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'order_heading_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #order_review_heading',
            ]
        );

        $this->add_responsive_control(
            'order_heading_margin',
            [
                'label' => __('Margin', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #order_review_heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ],
            ]
        );

        $this->add_responsive_control(
            'order_heading_padding',
            [
                'label' => __('Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout #order_review_heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ],
                'separator' => 'after',
            ]
        );
        //-----end heading style-----

        // ----order table style-----
        $this->add_control(
            'order_table_style',
            [
                'label' => __('Table Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'your_order_text_color',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout table.woocommerce-checkout-review-order-table td,
                     {{WRAPPER}} .wpfnl-checkout .woocommerce-checkout table.woocommerce-checkout-review-order-table th' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'your_order_text_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce-checkout table.woocommerce-checkout-review-order-table td,
                {{WRAPPER}} .wpfnl-checkout .woocommerce-checkout table.woocommerce-checkout-review-order-table th',
            ]
        );

        $this->add_control(
            'order_table_border_style',
            [
                'label' => __('Border Style', 'wpfnl'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => '',
                'options' => [
                    '' => __('Inherit', 'wpfnl'),
                    'solid' => __('Solid', 'wpfnl'),
                    'double' => __('Double', 'wpfnl'),
                    'dotted' => __('Dotted', 'wpfnl'),
                    'dashed' => __('Dashed', 'wpfnl'),
                ],

                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table' => 'border-style: {{VALUE}}!important;',
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table thead th,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table thead td,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tbody th,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tbody td,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tfoot td,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tfoot th' => 'border-left-style: {{VALUE}}!important; border-top-style: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'order_table_border_size',
            [
                'label' => __('Border Width', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important; border-top: none!important;',

                    '{{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table thead th,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table thead td,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tbody th,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tbody td,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tfoot td,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tfoot th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',

                    '{{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table thead th:first-child,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table thead td:first-child,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tbody th:first-child,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tbody td:first-child,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tfoot td:first-child,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tfoot th:first-child' => 'border-left: none!important;',

                ],
            ]
        );

        $this->add_control(
            'order_table_border_color',
            [
                'label' => __('Border Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table' => 'border-color: {{VALUE}}!important;',
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table thead th,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table thead td,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tbody th,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tbody td,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tfoot td,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tfoot th' => 'border-left-color: {{VALUE}}!important; border-top-color: {{VALUE}}!important;',
                ],

            ]
        );

        $this->add_responsive_control(
            'order_table_radius',
            [
                'label' => __('Radius', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'order_table_cell_padding',
            [
                'label' => __('Cell Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table thead th,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table thead td,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tbody th,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tbody td,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tfoot td,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table tfoot th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
                ],

            ]
        );

        $this->add_responsive_control(
            'order_table_margin',
            [
                'label' => __('Margin', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce table.shop_table' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
                ],

            ]
        );

        $this->end_controls_section();
    }


    /**
     * Register Coupon Field Style Controls.
     *
     * @since x.x.x
     * @access protected
     */
    protected function register_coupon_style_controls()
    {
        $this->start_controls_section(
            'coupon_section',
            [
                'label' => __('Coupon Section', 'wpfnl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // ----toggle area style-----
        $this->add_control(
            'coupon_toggle_area_style',
            [
                'label' => __('Coupon Toggle Area Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'coupon_toggle_color',
            [
                'label' => __('Text Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'coupon_toggle_link_color',
            [
                'label' => __('Link Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'coupon_toggle_bgcolor',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'coupon_toggle_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info',
            ]
        );

        $this->add_control(
            'coupon_toggle_border_style',
            [
                'label' => __('Border Style', 'wpfnl'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => '',
                'options' => [
                    '' => __('Inherit', 'wpfnl'),
                    'solid' => __('Solid', 'wpfnl'),
                    'double' => __('Double', 'wpfnl'),
                    'dotted' => __('Dotted', 'wpfnl'),
                    'dashed' => __('Dashed', 'wpfnl'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'coupon_toggle_border_size',
            [
                'label' => __('Border Width', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'coupon_toggle_border_style!' => '',
                ]
            ]
        );

        $this->add_control(
            'coupon_toggle_border_color',
            [
                'label' => __('Border Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info::before' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'coupon_toggle_border_style!' => '',
                ]
            ]
        );


        $this->add_responsive_control(
            'coupon_toggle_radius',
            [
                'label' => __('Radius', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'coupon_toggle_padding',
            [
                'label' => __('Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info::before' => 'top: {{TOP}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'coupon_toggle_margin',
            [
                'label' => __('Margin', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'

            ]
        );


        // ----coupon form box style-----
        $this->add_control(
            'coupon_form_box_style',
            [
                'label' => __('Coupon Form Box Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'coupon_form_box_color',
            [
                'label' => __('Text Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'coupon_form_box_bgcolor',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'coupon_form_box_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon p',
            ]
        );

        $this->add_control(
            'coupon_form_box_border_style',
            [
                'label' => __('Border Style', 'wpfnl'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => '',
                'options' => [
                    '' => __('Inherit', 'wpfnl'),
                    'solid' => __('Solid', 'wpfnl'),
                    'double' => __('Double', 'wpfnl'),
                    'dotted' => __('Dotted', 'wpfnl'),
                    'dashed' => __('Dashed', 'wpfnl'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'coupon_form_box_border_size',
            [
                'label' => __('Border Width', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'coupon_form_box_border_style!' => '',
                ]
            ]
        );

        $this->add_control(
            'coupon_form_box_border_color',
            [
                'label' => __('Border Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'coupon_form_box_border_style!' => '',
                ]
            ]
        );

        $this->add_responsive_control(
            'coupon_form_box_radius',
            [
                'label' => __('Radius', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'coupon_form_box_padding',
            [
                'label' => __('Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'coupon_form_box_margin',
            [
                'label' => __('Margin', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'after'
            ]
        );

        // ----coupon input field style-----
        $this->add_control(
            'coupon_input_field_style',
            [
                'label' => __('Coupon Input field Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'coupon_input_field_color',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon .input-text,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon ::placeholder,
                    {{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon ::-webkit-input-placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'coupon_input_field_bgcolor',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon .input-text' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'coupon_input_field_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon .input-text',
            ]
        );

        $this->add_control(
            'coupon_input_field_border_style',
            [
                'label' => __('Border Style', 'wpfnl'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'default' => '',
                'options' => [
                    '' => __('Inherit', 'wpfnl'),
                    'solid' => __('Solid', 'wpfnl'),
                    'double' => __('Double', 'wpfnl'),
                    'dotted' => __('Dotted', 'wpfnl'),
                    'dashed' => __('Dashed', 'wpfnl'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon .input-text' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'coupon_input_field_border_size',
            [
                'label' => __('Border Width', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon .input-text' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
                ],
                'condition' => [
                    'coupon_input_field_border_style!' => '',
                ]
            ]
        );

        $this->add_control(
            'coupon_input_field_border_color',
            [
                'label' => __('Border Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon .input-text' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'coupon_input_field_border_style!' => '',
                ]
            ]
        );


        $this->add_responsive_control(
            'coupon_input_field_radius',
            [
                'label' => __('Radius', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon .input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'coupon_input_field_padding',
            [
                'label' => __('Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
                ],
                'separator' => 'after'
            ]
        );


        // ----Coupon Button style-----
        $this->add_control(
            'coupon_button_style',
            [
                'label' => __('Coupon Button Style', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'coupon_button_typography',
                'label' => 'Typography',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon button.button',
            ]
        );

        $this->start_controls_tabs('coupon_button_tab');
        $this->start_controls_tab(
            'coupon_button_normal',
            [
                'label' => __('Normal', 'wpfnl'),
            ]
        );

        $this->add_control(
            'coupon_button_color',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon button.button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'coupon_button_bg_color',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon button.button' => 'background-color: {{VALUE}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'coupon_button_box_shadow',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon button.button',
            ]
        );
        $this->end_controls_tab();

        //----hover style---
        $this->start_controls_tab(
            'coupon_button_button_hover',
            [
                'label' => __('Hover', 'wpfnl'),
            ]
        );

        $this->add_control(
            'coupon_button_color_hover',
            [
                'label' => __('Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon button.button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'coupon_button_bg_color_hover',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon button.button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'coupon_button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon button.button:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        //-----------------------

        $this->add_responsive_control(
            'coupon_button_radius',
            [
                'label' => __('Radius', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'coupon_button_padding',
            [
                'label' => __('Padding', 'wpfnl'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-checkout .woocommerce form.woocommerce-form-coupon button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();
    }


    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render()
    {

        $settings       = $this->get_settings_for_display();
        $checkout       = WC()->checkout();
        if( wp_doing_ajax() && isset($_REQUEST['action']) && 'elementor_ajax' === $_REQUEST['action'] ) {
			$checkout_id = absint( $_REQUEST['editor_post_id'] );
		} else {
			$checkout_id    = get_the_ID();
		}

        //===Coupon Enabler===//
        $coupon_enabler = get_post_meta(get_the_ID(), '_wpfnl_checkout_coupon', true);
        if ( $coupon_enabler != 'yes' ) {
            remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
        }

		$checkout_layout = isset($settings['checkout_layout']) ? $settings['checkout_layout'] : 'two-column';
        /** check if pro is activated or not */
        if( !Wpfnl_functions::is_wpfnl_pro_activated() && 'wpfnl-multistep' === $checkout_layout ) {
			$checkout_layout = 'wpfnl-col-2';
		}
        query_posts('post_type="checkout"');
        
		do_action( 'wpfunnels/before_elementor_checkout_form', $settings );
		do_action( 'wpfunnels/before_checkout_form', $checkout_id );
		?>
		<div class="wpfnl-checkout <?php echo $checkout_layout ?>">
			<?php echo do_shortcode('[woocommerce_checkout]'); ?>
		</div>
	<?php
    }

    public function render_popup_in_editor()
    {
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            
            add_action('woocommerce_before_checkout_form', [$this, 'render_order_bump'], 10);
        }
    }

    /**
     * Render order bump data with hooks.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    public function render_order_bump()
    {

        $step_id = get_the_ID();
        $order_bump = get_post_meta($step_id, 'order-bump', true);
        $order_bump_settings = get_post_meta($step_id, 'order-bump-settings', true);
        if (isset($order_bump_settings['product']) && $order_bump_settings['product'] != '') {
            $this->render_order_bump_template($order_bump_settings);
        }

    }

    public function render_order_bump_template($settings)
    {
        if (!empty($settings['selectedStyle'])) {

            if ($settings['position'] == 'popup') {
                if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                    echo '<h5 style="margin-bottom: 15px;"><strong>' . __('To see the pop-up offer in action, please preview or view the page.', 'wpfnl') . '</strong></h5>';
                } else {
                    require_once WPFNL_DIR . 'public/modules/checkout/templates-style/order-bump-template-' . $settings['selectedStyle'] . '.php';
                }
            } else {
                require_once WPFNL_DIR . 'public/modules/checkout/templates-style/order-bump-template-' . $settings['selectedStyle'] . '.php';
            }
        }
    }

}
