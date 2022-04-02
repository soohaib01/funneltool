<?php
namespace WPFunnels\Widgets\Gutenberg\BlockTypes;

use WPFunnels\Data_Store\Wpfnl_Steps_Store_Data;

/**
 * OrderDetails class.
 */
class OrderDetails extends AbstractDynamicBlock {

    /**
     * Block name.
     *
     * @var string
     */
    protected $block_name = 'order-details';

	public function __construct( $block_name = '' )
	{
		parent::__construct($block_name);
		add_action('wp_ajax_show_order_details_markup', [$this, 'show_order_details_markup']);
	}

    /**
     * Render the Featured Product block.
     *
     * @param array  $attributes Block attributes.
     * @param string $content    Block content.
     * @return string Rendered block type output.
     */
    protected function render( $attributes, $content ) {
		if( !isset($_GET['optin']) ) {
			$output = sprintf('<div class="%1$s" style="%2$s">', esc_attr($this->get_classes($attributes)), esc_attr($this->get_styles($attributes)));
			$output .= '<div class="wpfnl-block-order-details__wrapper">';
			$output .= do_shortcode('[wpfunnels_order_details]');
			$output .= '</div>';
			$output .= '</div>';
			return $output;
		}
		return '';
    }


    /**
     * Get the styles for the wrapper element (background image, color).
     *
     * @param array       $attributes Block attributes. Default empty array.
     * @return string
     */
    public function get_styles( $attributes ) {
        $style      = '';
        return $style;
    }


    /**
     * Get class names for the block container.
     *
     * @param array $attributes Block attributes. Default empty array.
     * @return string
     */
    public function get_classes( $attributes ) {
		global $post;
		$thankyou = new Wpfnl_Steps_Store_Data();
		$thankyou->read($post->ID);
		$order_overview     = $thankyou->get_internal_metas_by_key('_wpfnl_thankyou_order_overview');
		$order_details      = $thankyou->get_internal_metas_by_key('_wpfnl_thankyou_order_details');
		$billing_details    = $thankyou->get_internal_metas_by_key('_wpfnl_thankyou_billing_details');
		$shipping_details   = $thankyou->get_internal_metas_by_key('_wpfnl_thankyou_shipping_details');

        $classes = array(
        	'wpfnl-block-' . $this->block_name,
        	'wpfnl-gutenberg-display-order-overview-' . $order_overview,
        	'wpfnl-gutenberg-display-order-details-' . $order_details,
        	'wpfnl-gutenberg-display-billing-address-' . $billing_details,
        	'wpfnl-gutenberg-display-shipping-address-' . $shipping_details,
		);
        return implode( ' ', $classes );
    }


    /**
     * Extra data passed through from server to client for block.
     *
     * @param array $attributes  Any attributes that currently are available from the block.
     *                           Note, this will be empty in the editor context when the block is
     *                           not in the post content on editor load.
     */
    protected function enqueue_data( array $attributes = [] ) {
        parent::enqueue_data( $attributes );
    }


	/**
	 * render order details markup
	 *
	 * @return string
	 *
	 * @since 2.0.3
	 */
    public function show_order_details_markup() {

		add_filter('wpfunnels/show_dummy_order_details', function () {
			return true;
		});
		$data['html'] = do_shortcode( '[wpfunnels_order_details]' );
		wp_send_json_success($data);
		die();
	}

}
