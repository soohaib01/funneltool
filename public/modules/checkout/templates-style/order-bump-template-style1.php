<?php

$product 			= wc_get_product($settings['product']);
$regular_price 		= $product->get_regular_price();
if( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ){
	$signUpFee = \WC_Subscriptions_Product::get_sign_up_fee( $product );
	$regular_price = $regular_price + $signUpFee;
}
$sale_price 		= $product->get_sale_price() ? $product->get_sale_price() : $product->get_regular_price();
$price 				= $product->get_price_html();
$quantity			= $settings['quantity'];
$orderbump_color 	= isset( $order_bump_settings['obPrimaryColor'] ) ? $order_bump_settings['obPrimaryColor'] : '#6E42D2';

if( $product->is_on_sale() ) {
	$price = wc_format_sale_price( $regular_price * $quantity, $sale_price * $quantity );
} else {
	$price = wc_price( $regular_price * $quantity );
}

if (isset($settings['discountOption'])) {
    if ($settings['discountOption'] == "discount-price" || $settings['discountOption'] == "discount-percentage") {
    	$discount_price = preg_replace('/[^\d.]/', '', $settings['discountPrice'] );
        if ($settings['discountapply'] == 'regular') {
			$price = wc_format_sale_price( $regular_price * $quantity, $discount_price * $quantity );
        } else {
            $price = wc_format_sale_price( $sale_price * $quantity, $discount_price * $quantity );
        }
    }
}
?>

<div class="wpfnl-reset wpfnl-order-bump__template-style1">
    <div class="oderbump-loader">
        <span class="wpfnl-loader"></span>
    </div>
    <div class="template-preview-wrapper">
        <?php
        $img = '';
        $img = wp_get_attachment_image_src(get_post_thumbnail_id($settings['product']), 'single-post-thumbnail');

        if (isset($img[0])) {
            $img = $img[0];
        }


        if (isset($settings['productImage'])) {
            if ($settings['productImage'] != "") {
                $img_id = attachment_url_to_postid($settings['productImage']['url']);
                if ($img_id) {
                    $thumbnail = wp_get_attachment_image_src( $img_id, 'medium' );
                    $img = $thumbnail[0];
                } else {
                    $img = $settings['productImage']['url'];
                }
            }
        }

        ?>
        <div class="template-img" style="background-image: url('<?php echo $img; ?>');">
            <img src="<?php echo $settings['productImage']['url'] ?>" alt="" class="for-mobile">
        </div>

        <div class="template-content">
            <h5 class="template-title"><?php echo $settings['productName'] ?></h5>
            <h6 class="subtitle"><?php echo $settings['highLightText'] ?></h6>
            <p class="description"><?php echo $settings['productDescriptionText'] ?></p>
            <span class="product-price">
                <?php echo '<strong>Price: </strong>' . $price . ''; ?>
            </span>
        </div>
    </div>

    <div class="offer-checkbox" style="background-color: <?php echo $orderbump_color; ?>">
        <span class="wpfnl-checkbox">
            <input
				id="wpfnl-order-bump-cb"
				class="wpfnl-order-bump-cb"
				type="checkbox"
				name="wpfnl-order-bump-cb"
				data-quantity="<?php echo $settings['quantity']; ?>"
				data-step="<?php echo get_the_ID(); ?>"
				value="<?php echo $settings['product'] ?>"
			>

            <label for="wpfnl-order-bump-cb"><?php echo $settings['checkBoxLabel'] ?></label>
        </span>
    </div>
</div>
