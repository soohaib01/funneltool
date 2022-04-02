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
$orderbump_color 	= isset($order_bump_settings['obPrimaryColor']) ? $order_bump_settings['obPrimaryColor'] : '#6E42D2';

if( $product->is_on_sale() ) {
	$price = wc_format_sale_price( $regular_price * $quantity, $sale_price * $quantity );
} else {
	$price = wc_price( $regular_price * $quantity );
}

if (isset($settings['discountOption'])) {
    if ($settings['discountOption'] == "discount-price" || $settings['discountOption'] == "discount-percentage") {
		$discount_price = preg_replace('/[^\d.]/', '', $settings['discountPrice'] );

		if ($settings['discountapply'] == 'regular') {
            $price = wc_format_sale_price( $regular_price, $discount_price );
        } else {
            $price = wc_format_sale_price( $sale_price, $discount_price );
        }
    }
}
?>

<div class="wpfnl-reset wpfnl-order-bump__template-style2">
    <div class="oderbump-loader">
        <span class="wpfnl-loader"></span>
    </div>
    <div class="offer-checkbox" style="background-color: <?php echo $orderbump_color; ?>">
        <span class="nav-arrow">
            <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M14.9081 6.72871L8.65523 12.9102C8.56587 12.9982 8.43279 13.0236 8.31471 12.9771C8.19788 12.9288 8.12226 12.816 8.12226 12.6908V9.2825H0.312405C0.139963 9.28247 0 9.14368 0 8.97264V4.01504C0 3.844 0.139963 3.70518 0.312405 3.70518H8.12229V0.309859C8.12229 0.18469 8.19853 0.0718956 8.31535 0.0235586C8.3541 0.00743675 8.39469 0 8.4347 0C8.51591 0 8.59589 0.0316048 8.65587 0.0904856L14.9088 6.28997C14.9675 6.34821 15 6.42693 15 6.50934C15 6.59175 14.9668 6.67044 14.9081 6.72871Z"
                    fill="#EE8134"/>
            </svg>
        </span>

        <span class="wpfnl-checkbox">
            <input type="checkbox" id="wpfnl-order-bump-cb" data-quantity="<?php echo $settings['quantity']; ?>"
                   data-step="<?php echo get_the_ID(); ?>" class="wpfnl-order-bump-cb" name="wpfnl-order-bump-cb"
                   value="<?php echo $settings['product'] ?>">

            <label for="wpfnl-order-bump-cb"><?php echo $settings['checkBoxLabel'] ?></label>
        </span>

        <span class="product-price">
            <?php echo $price; ?>
        </span>
    </div>
    <?php
    $img = wp_get_attachment_image_src(get_post_thumbnail_id($settings['product']), 'single-post-thumbnail');
	if (isset($img[0])) {
		$img = $img[0];
	}

    if ( isset($settings['productImage']) ) {
		if ($settings['productImage'] != "") {
			$img = $settings['productImage'];
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
    <div class="template-preview-wrapper">
        <div class="template-img" style="background-image: url('<?php echo $img; ?>');">
            <img src="<?php echo $settings['productImage']['url'] ?>" alt="" class="for-mobile">
        </div>

        <div class="template-content">
            <h5 class="template-title"><?php echo $settings['productName'] ?></h5>
            <h6 class="subtitle"><?php echo $settings['highLightText'] ?></h6>
            <p class="description"><?php echo $settings['productDescriptionText'] ?></p>
        </div>
    </div>
</div>
