<?php
$product_module = \WPFunnels\Wpfnl::$instance->module_manager->get_admin_modules('product');
$products = $this->get_internal_metas_by_key('_wpfnl_checkout_products');
$product_module->set_products($products);
$step_id = 0;
if (isset($_GET['step_id'])) {
    $step_id = filter_input( INPUT_GET, 'step_id', FILTER_VALIDATE_INT );
}

$coupon_enable = get_post_meta($step_id, '_wpfnl_checkout_coupon', true);
$coupon_enable_check = '';
if($coupon_enable == 'yes') {
  $coupon_enable_check = 'checked';
}
?>

<div class="product-area">
    <div class="product-head">
        <h4 class="title"><?php echo __('Funnel Products', 'wpfnl'); ?></h4>

        <div class="product-search">
            <?php $product_module->get_view(); ?>
        </div>

        <!-- Conditional Add product -->
        <?php
          if (isset($products[0]['id']) && $products[0]['id'] != '') {
                ?>
                <div class="add-product-btn">
                    <button class="btn-default" id="wpfnl-add-product"
                            data-id="<?php echo $step_id; ?>"><?php echo __('Add product', 'wpfnl'); ?></button>
                </div>
                <?php
          }
          else {
            ?>
            <div class="add-product-btn">
                <button class="btn-default" id="wpfnl-add-product"
                        data-id="<?php echo $step_id; ?>"><?php echo __('Add Your First Product', 'wpfnl'); ?></button>
            </div>
            <?php
          }
        ?>
        <!-- Conditional Add product -->
    </div>

    <div class="product-accordion__wrapper">
        <?php
        if (is_array($products)) {
            if ($products) { ?>
                <div class="accordion-head">
                    <div class="accordion-col expand-action"></div>
                    <div class="accordion-col product-name"><?php echo __('Product', 'wpfnl'); ?></div>
                    <div class="accordion-col product-price"><?php echo __('Price', 'wpfnl'); ?></div>
                    <div class="accordion-col product-quantity"><?php echo __('Quantity', 'wpfnl'); ?></div>
                    <!--            <div class="accordion-col product-discount">-->
                    <?php //echo __('Discount', 'wpfnl'); ?><!--</div>-->
                    <div class="accordion-col product-action"><?php echo __('Actions', 'wpfnl'); ?></div>
                </div>

                <div class="product-single-accordion__sortable-wrapper">
                    <?php foreach ($products as $key => $_product) {
                        $product = wc_get_product($_product['id']);
                        if (!empty($product)) {
                            $text_highlight_enabler = '';
                            $text_highlight = '';
                            $subtext = '';

                            $title = $product->get_title();
                            $price = $product->get_price();
                            $qty = $_product['quantity'];
                            if (isset($_product['subtext']) && $_product['subtext'] != '') {
                            $subtext = $_product['subtext'];
                            }

                            if (isset($_product['text_highlight']) && $_product['text_highlight'] != '') {
                            $text_highlight = $_product['text_highlight'];
                            }

                            if (isset($_product['enable_highlight']) && $_product['enable_highlight'] != '') {
                            $text_highlight_enabler = $_product['enable_highlight'];
                            }

                            $enabler = '';
                            if(  isset($_product['enable_highlight']) && $_product['enable_highlight'] == 'on') {
                            $enabler = 'checked';
                            }
                            $pr_image = wp_get_attachment_image_src($product->get_image_id(), 'single-post-thumbnail');
                            $description = substr($product->get_description(), 0, 20);
                            require WPFNL_DIR . '/admin/modules/steps/checkout/views/products.php';
                        }
                    }
                    ?>

                </div>

            <?php } else { ?>
                <div class="accordion-head" style="display: none;">
                    <div class="accordion-col expand-action"></div>
                    <div class="accordion-col product-name"><?php echo __('Product', 'wpfnl'); ?></div>
                    <div class="accordion-col product-price"><?php echo __('Price', 'wpfnl'); ?></div>
                    <div class="accordion-col product-quantity"><?php echo __('Quantity', 'wpfnl'); ?></div>
                    <div class="accordion-col product-action"><?php echo __('Actions', 'wpfnl'); ?></div>
                </div>
                <div class="product-single-accordion__sortable-wrapper">
                </div>
                <?php
            }
        } elseif (empty($products)) {?>
            <div class="accordion-head" style="display: none;">
                <div class="accordion-col expand-action"></div>
                <div class="accordion-col product-name"><?php echo __('Product', 'wpfnl'); ?></div>
                <div class="accordion-col product-price"><?php echo __('Price', 'wpfnl'); ?></div>
                <div class="accordion-col product-quantity"><?php echo __('Quantity', 'wpfnl'); ?></div>
                <div class="accordion-col product-action"><?php echo __('Actions', 'wpfnl'); ?></div>
            </div>

            <div class="product-single-accordion__sortable-wrapper">
            </div>
            <?php echo '<h4 class="no-product-notice">' . __('No product added', 'wpfnl') . '</h4>';
        } else {
            ?>
            <div class="product-single-accordion__sortable-wrapper">
            </div>
            <?php
            echo '<h4 class="no-product-notice">' . __('No product added', 'wpfnl') . '</h4>';
        } ?>

    </div>

</div>

<div class="checkout-product-options wpfnl-box">
    <!-- <div class="additional-options">
        <h4 class="option-title"><?php echo __('Additional Options', 'wpfnl'); ?></h4>

        <ul class="options-wrapper">
            <li class="single-option">
                <span class="wpfnl-radiobtn">
                    <input type="radio" name="additional-options" id="restrict-user" value=""/>
                    <label
                        for="restrict-user"><?php echo __('Restrict user to purchase all products', 'wpfnl'); ?></label>
                </span>
            </li>
            <li class="single-option">
                <span class="wpfnl-radiobtn">
                    <input type="radio" name="additional-options" id="user-select-one-product" value=""/>
                    <label
                        for="user-select-one-product"><?php echo __('Let user select one product from all options', 'wpfnl'); ?></label>
                </span>
            </li>
            <li class="single-option">
                <span class="wpfnl-radiobtn">
                    <input type="radio" name="additional-options" id="user-select-multiple-product" value=""/>
                    <label
                        for="user-select-multiple-product"><?php echo __('Let user select multiple products from all options', 'wpfnl'); ?></label>
                </span>
            </li>
        </ul>
    </div> -->

    <div class="variation-options">
        <h4 class="option-title"><?php echo __('Additional Options', 'wpfnl'); ?></h4>

        <div class="options-wrapper">
            <div class="wpfnl-field-wrapper">
                <label>
                    <?php echo __('Allow Use Of Coupon', 'wpfnl'); ?>
                    <span class="wpfnl-tooltip">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 0C2.68388 0 0 2.68352 0 6C0 9.31612 2.68352 12 6 12C9.31612 12 12 9.31648 12 6C12 2.68388 9.31648 0 6 0Z" fill="#6E42D3" />
                            <path d="M6.18136 8.25C5.66666 8.25 5.25 8.60793 5.25 9.0375C5.25 9.4568 5.65439 9.825 6.18136 9.825C6.70834 9.825 7.125 9.4568 7.125 9.0375C7.125 8.60793 6.69607 8.25 6.18136 8.25Z" fill="white" />
                            <path
                            d="M6.10214 3C4.66162 3 4 3.73543 4 4.23179C4 4.59032 4.35218 4.75578 4.64025 4.75578C5.21647 4.75578 4.98175 4.04796 6.07016 4.04796C6.60371 4.04796 7.03054 4.25017 7.03054 4.67306C7.03054 5.16943 6.43298 5.45445 6.08084 5.71179C5.77136 5.94161 5.36587 6.31851 5.36587 7.1091C5.36587 7.58706 5.51527 7.725 5.95274 7.725C6.47565 7.725 6.58234 7.52276 6.58234 7.3481C6.58234 6.87014 6.59299 6.59433 7.17986 6.19905C7.46801 6.00601 8.375 5.38091 8.375 4.51681C8.375 3.65271 7.46801 3 6.10214 3Z"
                            fill="white" />
                        </svg>

                        <p><?php echo __('Enable this option if you want to allow buyers to implement coupon codes during checkout.', 'wpfnl'); ?></p>
                    </span>
                </label>

                <div class="wpfnl-fields">
                    <span class="wpfnl-checkbox no-title">
                        <input type="checkbox" name="enable-checkout-coupon" id="enable-checkout-coupon" <?php echo $coupon_enable_check; ?> />
                        <label for="enable-checkout-coupon"></label>
                    </span>
                </div>
            </div>
            <!-- /field-wrapper -->
        </div>
    </div>
</div>
