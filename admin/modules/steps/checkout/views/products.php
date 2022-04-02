<div class="product__single-accordion" data-product="<?php echo $product->get_id(); ?>" id="product__single-accordion-<?php echo $product->get_id(); ?>">

    <div class="wpfnl-delete-alert-wrapper">
        <div class="wpfnl-delete-confirmation">
            <span class="icon">
                <?php require WPFNL_DIR . '/admin/partials/icons/cross-icon.php'; ?>
            </span>
            <h3><?php echo __('Are you sure you want to delete this product?', 'wpfnl'); ?></h3>
            <ul class="wpfnl-delete-confirm-btn">
                <li><button class="btn-default cancel"><?php echo __('Cancel', 'wpfnl'); ?></button></li>
                <li>
                    <button type="button" class="btn-default yes product-trash" data-index="<?php echo $key; ?>" data-id="<?php echo $step_id; ?>">
                        <?php echo __('Yes', 'wpfnl'); ?>
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="product-single__accordion-head">
        <!-- <div class="accordion-col expand-action">
            <span class="wpfnl-product-accordion-expand">
                <svg width="12" height="7" viewBox="0 0 12 7" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1L6 6L11 1" stroke="#7A8B9A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </div> -->
        <?php
            $product_link = '';
            if( $product->is_type('variation') ){
              $parent_id  = wp_get_post_parent_id($product->get_id());
              $product_link = get_edit_post_link($parent_id);
            }
            else {
              $product_link = get_edit_post_link($product->get_id());
            }
        ?>
        <div class="accordion-col product-name">
            <ul>
                <li class="product-img">
                    <a href="<?php echo $product_link; ?>" target="_blank">
                        <?php if ($pr_image) { ?>
                            <img src="<?php echo $pr_image[0]; ?>" alt="product img">
                        <?php } else { ?>
                            <img src="<?php echo WPFNL_URL . 'admin/assets/images/product-placeholder.jpg'; ?>"
                                alt="product img">
                        <?php } ?>

                    </a>
                </li>
                <li class="product-title">
                    <a href="<?php echo $product_link; ?>" class="title"
                    target="_blank"><?php echo $title; ?></a>
                    <!--                                    <span class="price">-->
                    <?php //echo __('Discounted Price', 'wpfnl'); ?><!--: $15</span>-->
                </li>
            </ul>
        </div>

        <div class="accordion-col product-price">
            <?php echo wc_price($price); ?>
        </div>

        <div class="accordion-col product-quantity">
            <input data-product = "<?php echo $product->get_id(); ?>" id="product-quantity-<?php echo $product->get_id(); ?>" type="number" name="quantity" min="1" value="<?php echo $qty; ?>">
        </div>

        <!--                        <div class="accordion-col product-discount">-->
        <!--                            <div class="discount-group">-->
        <!--                                <select name="discount-unit" id="discount-unit">-->
        <!--                                    <option value="">Unit 1</option>-->
        <!--                                    <option value="">Unit 2</option>-->
        <!--                                </select>-->
        <!--                                <input type="number" name="" placeholder="20">-->
        <!--                            </div>-->
        <!--                        </div>-->

        <div class="accordion-col product-action">
            <button type="button" class="product-delete" title="Delete product">
                <?php require WPFNL_DIR . '/admin/partials/icons/delete-icon.php'; ?>
            </button>
        </div>
    </div>

    <div class="product-single__accordion-body">
        <div class="product-single__accordion-body-content">
            <div class="subtext-settings">
                <div class="wpfnl-field-wrapper">
                    <label><?php echo __('Subtext', 'wpfnl'); ?></label>
                    <div class="wpfnl-fields">
                        <input data-product = "<?php echo $product->get_id(); ?>" type="text" name="subtext" value="<?php echo $subtext; ?>">
                        <span class="hints">Use {{quantity}}, {{discount_value}}, {{discount_percent}} to dynamically fetch respective product details.</span>
                    </div>
                </div>
                <!-- /field-wrapper -->
            </div>
            <div class="highlights">
                <div class="wpfnl-field-wrapper">
                    <label><?php echo __('Highlight Text', 'wpfnl'); ?></label>
                    <div class="wpfnl-fields">
                        <input data-product = "<?php echo $product->get_id(); ?>" type="text" name="text-highlight" value="<?php echo $text_highlight; ?>">
                    </div>
                </div>

                <div class="wpfnl-field-wrapper">
                    <label><?php echo __('Enable Highlight', 'wpfnl'); ?></label>
                    <div class="wpfnl-fields">
                        <span class="wpfnl-checkbox no-title">
                            <input data-product = "<?php echo $product->get_id(); ?>" type="checkbox" name="hide-img-mobile" id="<?php echo 'hide-img-mobile'.$product->get_id(); ?>" />
                            <label for="<?php echo 'hide-img-mobile'.$product->get_id(); ?>"></label>
                        </span>
                    </div>
                </div>
                <!-- /field-wrapper -->
            </div>
        </div>
    </div>
</div>
