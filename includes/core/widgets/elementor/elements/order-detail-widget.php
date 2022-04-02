<?php

namespace WPFunnels\Widgets\Elementor;

use Elementor\Widget_Base;


if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Funnel sell Reject button
 *
 * @since 1.0.0
 */
class Order_Details extends Widget_Base
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
        return 'wpfnl-order-detail';
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
        return __('Order Detail', 'wpfnl');
    }

    /**
     * Retrieve the widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     *
     * @access public
     *
     */
    public function get_icon()
    {
        return 'icon-wpfnl order-details';
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
        return ['funnel-order-detail-widget'];
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
            'section_content',
            [
                'label' => __('Funnel Order Details', 'wpfnl'),
            ]
        );

        $wpfnl_thankyou_order_overview = get_post_meta(get_the_id(), '_wpfnl_thankyou_order_overview', true);
        $wpfnl_thankyou_order_details = get_post_meta(get_the_id(), '_wpfnl_thankyou_order_details', true);
        $wpfnl_thankyou_billing_details = get_post_meta(get_the_id(), '_wpfnl_thankyou_billing_details', true);
        $wpfnl_thankyou_shipping_details = get_post_meta(get_the_id(), '_wpfnl_thankyou_shipping_details', true);

        if (!$wpfnl_thankyou_order_overview) {
            $wpfnl_thankyou_order_overview = 'on';
        }
        if (!$wpfnl_thankyou_order_details) {
            $wpfnl_thankyou_order_details = 'on';
        }
        if (!$wpfnl_thankyou_billing_details) {
            $wpfnl_thankyou_billing_details = 'on';
        }
        if (!$wpfnl_thankyou_shipping_details) {
            $wpfnl_thankyou_shipping_details = 'on';
        }

        $this->add_control(
            'enable_order_review',
            [
                'label' => __('Enable Order Overview', 'wpfnl'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => $wpfnl_thankyou_order_overview,
                'label_on' => __( 'On', 'wpfnl' ),
				'label_off' => __( 'Off', 'wpfnl' ),
                'return_value' => 'on',
            ]
        );

        $this->add_control(
            'enable_order_details',
            [
                'label' => __('Enable Order Details', 'wpfnl'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => $wpfnl_thankyou_order_details,
                'label_on' => __( 'On', 'wpfnl' ),
				'label_off' => __( 'Off', 'wpfnl' ),
                'return_value' => 'on',
            ]
        );

        $this->add_control(
            'enable_billing_details',
            [
                'label' => __('Enable Billing Details', 'wpfnl'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => $wpfnl_thankyou_billing_details,
                'label_on' => __( 'On', 'wpfnl' ),
				'label_off' => __( 'Off', 'wpfnl' ),
                'return_value' => 'on',
            ]
        );

        $this->add_control(
            'enable_shipping_details',
            [
                'label' => __('Enable Shipping Details', 'wpfnl'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => $wpfnl_thankyou_shipping_details,
                'label_on' => __( 'On', 'wpfnl' ),
				'label_off' => __( 'Off', 'wpfnl' ),
                'return_value' => 'on',
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
		$output 			= '';
		$order_overview 	= 'on';
		$order_details 		= 'on';
		$billing_details 	= 'on';
		$shipping_details 	= 'on';
        $settings = $this->get_settings();

        if (isset($settings['enable_order_review']) && !empty($settings['enable_order_review']) ) {
            $order_overview = $settings['enable_order_review'];
            update_post_meta(get_the_ID(), '_wpfnl_thankyou_order_overview', $settings['enable_order_review']);
        }else{
            $order_overview = 'off';
            update_post_meta( get_the_ID(), '_wpfnl_thankyou_order_overview', 'off' );
        }

        if (isset($settings['enable_order_details']) && !empty($settings['enable_order_details']) ) {
			$order_details = $settings['enable_order_details'];
			update_post_meta(get_the_ID(), '_wpfnl_thankyou_order_details', $settings['enable_order_details']);
        }else{
            $order_details = 'off';
            update_post_meta( get_the_ID(), '_wpfnl_thankyou_order_details', 'off' );
        }

        if (isset($settings['enable_billing_details']) && !empty($settings['enable_billing_details']) ) {
			$billing_details = $settings['enable_billing_details'];
			update_post_meta(get_the_ID(), '_wpfnl_thankyou_billing_details', $settings['enable_billing_details']);
        }else{
            $billing_details = 'off';
            update_post_meta( get_the_ID(), '_wpfnl_thankyou_billing_details', 'off' );
        }

        if (isset($settings['enable_shipping_details']) && !empty($settings['enable_shipping_details']) ) {
			$shipping_details = $settings['enable_shipping_details'];
			update_post_meta(get_the_ID(), '_wpfnl_thankyou_shipping_details', $settings['enable_shipping_details']);
        }else{
            $shipping_details = 'off';
            update_post_meta( get_the_ID(), '_wpfnl_thankyou_shipping_details', 'off' );
        }

       	?>
		<?php if( !isset($_GET['optin']) ) { ?>
			<div class = "wpfnl-elementor-order-details-form wpfnl-elementor-display-order-overview-<?php echo esc_attr( $order_overview ); ?> wpfnl-elementor-display-order-details-<?php echo esc_attr( $order_details ); ?> wpfnl-elementor-display-billing-address-<?php echo esc_attr( $billing_details ); ?> wpfnl-elementor-display-shipping-address-<?php echo esc_attr( $shipping_details ); ?>">
				<?php echo do_shortcode( '[wpfunnels_order_details]' ); ?>
			</div>
		<?php }

		echo $output;
    }

}
