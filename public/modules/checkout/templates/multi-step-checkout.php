<?php
/**
 * Checkout Form
 *
 * This is an overridden copy of the woocommerce/templates/checkout/form-checkout.php file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="rex-multistep-checkout">

    <?php

    // If checkout registration is disabled and not logged in, the user cannot checkout.
    if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
        echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'wpfnl' ) ) );
        return;
    }

    ?>

    <ul class="checkout-steps">
        <li class="billing"><a href="#billing"><span class="step-number">1</span><span class="title"><?php esc_html_e( 'Billing Details', 'wpfnl' ); ?></span></a></li>
        <li class="shipping"><a href="#shipping"><span class="step-number">2</span><span class="title"><?php esc_html_e( 'shipping', 'wpfnl' ); ?></span></a></li>
        <li class="review"><a href="#review"><span class="step-number">3</span><span class="title"><?php esc_html_e( 'Your order', 'wpfnl' ); ?></span></a></li>
    </ul>

    <div class="woocommerce-checkout-coupon-form">
        <?php do_action( 'rex__woocommerce_checkout_coupon_form' ); ?>
    </div>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

        <div class="multistep-wrapper" id="rex-checkout-tabs">
            <div class="single-step" id="billing">
                <?php
                    if ( $checkout->get_checkout_fields() ) {
                        do_action( 'woocommerce_checkout_before_customer_details' );
                        do_action( 'woocommerce_checkout_billing' );
                    }
                ?>

                <div class="navigation">
                    <button type="button" id="billingNext"><?php echo __( 'Next', 'wpfnl' ); ?></button>
                </div>
            </div>

            <div class="single-step" id="shipping">
                <?php
                    if ( $checkout->get_checkout_fields() ) {
                        do_action( 'woocommerce_checkout_shipping' );
                        do_action( 'woocommerce_checkout_after_customer_details' );
                    }
                ?>

                <div class="navigation">
                    <button type="button" id="shippingPrevious"><?php echo __( 'Previous', 'wpfnl' ); ?></button>
                    <button type="button" id="shippingNext"><?php echo __( 'Next', 'wpfnl' ); ?></button>
                </div>
            </div>

            <div class="single-step" id="review">
                <?php do_action( 'woocommerce_checkout_before_order_review' );
                ?>

                <div id="order_review" class="woocommerce-checkout-review-order">
                    <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                </div>
                <?php do_action( 'woocommerce_checkout_after_order_review' );
                ?>

                <div class="navigation">
                    <button type="button" id="reviewPrevious"><?php echo __( 'Previous', 'wpfnl' ); ?></button>
                </div>
            </div>
        </div>
        <!--/multistep-wrapper-->

    </form>

    <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

</div>
