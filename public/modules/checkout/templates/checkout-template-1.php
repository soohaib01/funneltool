<?php
/**
 * Checkout Form
 *
 * This is an overridden copy of the woocommerce/templates/checkout/form-checkout.php file.
 */

if (! defined('ABSPATH')) {
    exit;
}
?>
<!-- <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data"> -->
		<!-- <div class="funnel_billing">
				<?php
						// if ( $checkout->get_checkout_fields() ) {
						// 		do_action( 'woocommerce_checkout_before_customer_details' );
						// 		do_action( 'woocommerce_checkout_billing' );
						// }
				?>
		</div>

		<div class="funnel_shipping">
				<?php
						// if ( $checkout->get_checkout_fields() ) {
						// 		do_action( 'woocommerce_checkout_shipping' );
						// 		do_action( 'woocommerce_checkout_after_customer_details' );
						// }
				?>
		</div>

		<div class="funnel_order_review">
				<?php //do_action( 'woocommerce_checkout_before_order_review' );
				?>

				<div id="order_review" class="woocommerce-checkout-review-order">
						<?php //do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>
				<?php //do_action( 'woocommerce_checkout_after_order_review' );
				?>
		</div> -->
<!-- </form> -->

<?php // do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
