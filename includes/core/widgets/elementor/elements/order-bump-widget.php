<?php

namespace WPFunnels\Widgets\Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Funnel sell Reject button
 *
 * @since 1.0.0
 */
class Order_Bump extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'wpfnl-order-bump';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Order Bump', 'wpfnl');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'icon-wpfnl next-step';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return [ 'wp-funnel' ];
    }

    /**
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return [ 'order-bump-widget' ];
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
        $ids = wc_get_products([ 'return' => 'ids', 'limit' => -1 ]);
        foreach ($ids as $id) {
            $title = get_the_title($id);
            $products[$id] = $title;
        }
        return $products;
    }

    /**
     * Register the widget controls.
     * @since 1.0.0
     *
     * @access protected
     */
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_button_controller',
            [
                'label' => __('Order Bump Controller', 'wpfnl'),
            ]
        );

        $this->add_control(
            'order_bump_layout',
            [
                'label' => __('Select Layout', 'wpfnl'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal'  => __('Horizontal', 'wpfnl'),
                    'vertical' => __('Vertical', 'wpfnl'),
                ],
           ]
        );

        $this->add_control(
            'order_bump_product_selector',
            [
                'label' => __(' Select Product', 'wpfnl'),
                'type' => \WPFunnels\Widgets\Elementor\Controls\Product_Control::ProductSelector,
                'options' => $this->get_products_array(),
                'multiple' => false,
                'minimumInputLength' => 3,
            ]
        );

        $this->add_control(
            'order_bump_image',
            [
            'label' => __('Choose Image', 'wpfnl'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => [
              'url' => \Elementor\Utils::get_placeholder_image_src(),
            ],
          ]
        );

        $this->add_control(
            'order_bump_checkbox_label',
            [
            'label' => __('Checkbox Label', 'wpfnl'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Try this product', 'wpfnl'),
            'placeholder' => __('Type your text here', 'wpfnl'),
          ]
        );

        $this->add_control(
            'order_bump_product_detail_header',
            [
            'label' => __('Product Detail Header', 'wpfnl'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Amazing Product', 'wpfnl'),
            'placeholder' => __('Type your text here', 'wpfnl'),
          ]
        );

        $this->add_control(
            'order_bump_product_detail',
            [
            'label' => __('Product Detail', 'wpfnl'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 5,
            'default' => __('Product description', 'wpfnl'),
            'placeholder' => __('Type your description here', 'wpfnl'),
          ]
        );

        $this->add_control(
            'order_bump_next_step_selector',
            [
                'label' => __('Select Next Step', 'wpfnl'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'groups' => [
                    [
                        'label' => __('Upsell', 'wpfnl'),
                        'options' => self::get_steps_array('upsell')
                    ],
                    [
                        'label' => __('Thank You', 'wpfnl'),
                        'options' => self::get_steps_array('downsell')
                    ],
                    [
                        'label' => __('Thank You', 'wpfnl'),
                        'options' => self::get_steps_array('thankyou')
                    ],
                ],
           ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Order Bump', 'wpfnl'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'order_bump_color',
            [
                'label' => __('Text Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-bump-order-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'order_bump_background_color',
            [
                'label' => __('Background Color', 'wpfnl'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpfnl-bump-order-content' => 'background-color: {{VALUE}};',
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
        $settings = $this->get_settings();
        $checker = "";
        $order_bump = get_post_meta(get_the_ID(), 'order_bump', true);
        if (isset($settings['order_bump_next_step_selector'])) {
            $order_bump_next_step = $settings['order_bump_next_step_selector'];
            update_post_meta(get_the_ID(), 'order_bump_next_step_selector', $settings['order_bump_next_step_selector']);
        }

        if (isset($order_bump["checker"]) && $order_bump["checker"] == "true") {
            $checker = "checked";
        } ?>
        <div class="wpfnl-bump-order-content <?php echo $settings['order_bump_layout'] == 'vertical' ? 'vertical' : ''; ?>">

            <div class="wpfnl-content-container">
                <div class="wpfnl-bump-order-offer-content-left">
                    <img src="<?php echo $settings["order_bump_image"]["url"]; ?>" alt="Order bump image" class="wpfnl-image">
                </div>

                <div class="wpfnl-bump-order-offer-content-right">
                    <div class="wpfnl-bump-order-field-wrap">
                        <label>
                            <input type="checkbox" id="wpfnl-order-bump-cb" data-step="<?php echo get_the_ID(); ?>" class="wpfnl-order-bump-cb" name="wpfnl-order-bump-cb" value="<?php echo $settings["order_bump_product_selector"]; ?>" <?php echo $checker; ?>>
                            <span class="wpfnl-bump-order-label"><?php echo $settings["order_bump_checkbox_label"]; ?></span>
                        </label>
                    </div>

                    <div class="wpfnl-bump-order-offer-title">
                        <span class="wpfnl-bump-order-bump-highlight"><?php echo $settings["order_bump_product_detail_header"]; ?></span>
                    </div>
                    <div class="wpfnl-bump-order-desc">
                        <?php echo $settings["order_bump_product_detail"]; ?>
                    </div>
                </div>
            </div>
      	</div>
        <?php
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
        foreach ($steps_array as $key=>$value) {
            $args = [
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'post_type'      => WPFNL_STEPS_POST_TYPE,
                'post_status'    => 'publish',
                'post__not_in'   => [ $this->get_id() ],
                'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key'     => '_step_type',
                        'value'   => $key,
                        'compare' => '=',
                    ],
                    [
                        'key'     => '_funnel_id',
                        'value'   => $associate_funnel_id,
                        'compare' => '=',
                    ],
                ],
            ];
            $query = new \WP_Query($args);
            $steps = $query->posts;
            if ($steps) {
                foreach ($steps as $s) {
                    $option_group[$key][] = [
                        'id'    => $s->ID,
                        'title' => $s->post_title,
                    ];
                }
            }
        }
        return $option_group;
    }

}
