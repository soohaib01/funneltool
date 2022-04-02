<?php
// Exit if accessed directly.
use WPFunnels\Wpfnl;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$checkout_layout = Wpfnl::get_instance()->meta->get_checkout_meta_value($checkout_id, 'wpfnl_checkout_layout', 'two-column');

?>
<div id="wpfnl-checkout-form" class="wpfnl-checkout-form wpfnl-checkout-form-<?php echo esc_attr( $checkout_layout ); ?>">
	<?php
	$checkout_html = do_shortcode( '[woocommerce_checkout]' );
	if (empty( $checkout_html ) || trim( $checkout_html ) == '<div class="woocommerce"></div>') {
		echo esc_html__( 'Your cart is currently empty.', 'wpfnl' );
	} else {
		echo $checkout_html;
	}
	?>
</div>
