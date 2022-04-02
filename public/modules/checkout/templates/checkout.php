<div id="wpfunnels-checkout-form" class="wpfunnels-checkout-form">
    <?php
        $checkout_html = do_shortcode('[woocommerce_checkout]');
        if (empty($checkout_html) || trim($checkout_html) == '<div class="woocommerce"></div>') {
            echo esc_html__('Your cart is currently empty.', 'wpfnl');
        } else {
            echo $checkout_html;
        }
    ?>
</div>
