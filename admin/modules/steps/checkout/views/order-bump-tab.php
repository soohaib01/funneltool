        <div class="wpfnl-field-wrapper">
            <div class="wpfnl-fields">
                <select name="order-bump-template">
                    <option value="" ><?php echo __('Search for a template...', 'wpfnl'); ?></option>
                    <option value="template1"><?php echo __('Template 1', 'wpfnl'); ?></option>
                    <option value="template2"><?php echo __('Template 2', 'wpfnl'); ?></option>
                </select>
            </div>
        </div>

        <div class="wpfnl-field-wrapper">
            <label> <?php echo __('Order Bump Position', 'wpfnl'); ?> </label>
            <div class="wpfnl-fields">
                <select name="order-bump-template">
                    <option value="after-order"><?php echo __('Before Order Details', 'wpfnl'); ?></option>
                    <option value="before-checkout"><?php echo __('Before Checkout Details', 'wpfnl'); ?></option>
                    <option value="after-customer-details"><?php echo __('After Customer Details', 'wpfnl'); ?></option>
                    <option value="before-payment"><?php echo __('Before Payment', 'wpfnl'); ?></option>
                    <option value="after-payment"><?php echo __('After Payment', 'wpfnl'); ?></option>
                    <option value="popup"><?php echo __('Pop-up', 'wpfnl'); ?></option>
                </select>
            </div>
        </div>


        <div class="wpfnl-field-wrapper">
            <label class="has-tooltip">
                <?php echo __('Select Product', 'wpfnl'); ?>
                <span class="wpfnl-tooltip">
                    <?php require WPFNL_DIR . '/admin/partials/icons/question-tooltip-icon.php'; ?>
                    <p><?php echo __('Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dicta, animi!', 'wpfnl'); ?></p>
                </span>
            </label>

            <div class="wpfnl-fields">
                <select name="order-bump-template">
                    <option value="" ><?php echo __('Search for a product...', 'wpfnl'); ?></option>
                    <option value="product1"><?php echo __('Product 1', 'wpfnl'); ?></option>
                    <option value="product2"><?php echo __('Product 2', 'wpfnl'); ?></option>
                </select>
            </div>
        </div>

        <div class="wpfnl-field-wrapper">
            <label> <?php echo __('Product Quantity', 'wpfnl'); ?> </label>
            <div class="wpfnl-fields">
                <input type="number" name="product-quantity" value="" min="1" />
            </div>
        </div>

        <div class="wpfnl-field-wrapper top-align upload-product-image">
            <label> <?php echo __('Product Image', 'wpfnl'); ?> </label>
            <div class="wpfnl-fields">
                <label for="orderbump-product-image" class="image-label" id="orderbump-product-image">
                    <input type="hidden" name="orderbump-product-image-id" value="" id="orderbump-product-image-id" />
                    <input type="hidden" name="orderbump-product-image-url" value="" id="orderbump-product-image-url" />
                    <span class="icon-wrapper">
                        <?php require WPFNL_DIR . '/admin/partials/icons/image-upload-icon.php'; ?>
                        <span class="title"><?php echo __('Click to Upload an Image', 'wpfnl'); ?></span>
                    </span>
                </label>
            </div>
        </div>

        <div class="wpfnl-field-wrapper">
            <label> <?php echo __('Highlight Text', 'wpfnl'); ?> </label>
            <div class="wpfnl-fields">
                <input type="text" name="highlight-text" value="" />
            </div>
        </div>

        <div class="wpfnl-field-wrapper">
            <label> <?php echo __('Checkbox Label', 'wpfnl'); ?> </label>
            <div class="wpfnl-fields">
                <input type="text" name="checkbox-label" value="" />
            </div>
        </div>

        <div class="wpfnl-field-wrapper top-align">
            <label> <?php echo __('Product Description', 'wpfnl'); ?> </label>
            <div class="wpfnl-fields">
                <textarea name="product-description" id="" cols="30" rows="4"></textarea>
            </div>
        </div>
        <!-- /field-wrapper -->
    </div>
    <!-- /.order-bump-settings -->

    <div class="wpfnl-box discount-settings">
        <div class="wpfnl-field-wrapper">
            <label class="has-tooltip">
                <?php echo __('Discount Type', 'wpfnl'); ?>
                <span class="wpfnl-tooltip">
                    <?php require WPFNL_DIR . '/admin/partials/icons/question-tooltip-icon.php'; ?>
                    <p><?php echo __('Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dicta, animi!', 'wpfnl'); ?></p>
                </span>
            </label>

            <div class="wpfnl-fields">
                <select name="order-bump-template">
                    <option value="original"><?php echo __('Original', 'wpfnl'); ?></option>
                    <option value="discount-percentage"><?php echo __('Discount Percentage', 'wpfnl'); ?></option>
                    <option value="discount-price"><?php echo __('Discount Price', 'wpfnl'); ?></option>
                    <option value="coupon"><?php echo __('Coupon', 'wpfnl'); ?></option>
                </select>
            </div>
        </div>

        <div class="wpfnl-field-wrapper">
            <label class="has-tooltip">
                <?php echo __('Discount Value', 'wpfnl'); ?>
                <span class="wpfnl-tooltip">
                    <?php require WPFNL_DIR . '/admin/partials/icons/question-tooltip-icon.php'; ?>
                    <p><?php echo __('Lorem ipsum dolor sit amet consectetur, adipisicing elit. Dicta, animi!', 'wpfnl'); ?></p>
                </span>
            </label>

            <div class="wpfnl-fields">
                <input type="number" name="discount-value" value="" min="1" />
            </div>
        </div>

        <div class="wpfnl-field-wrapper">
            <label> <?php echo __('Original Price', 'wpfnl'); ?> </label>
            <div class="wpfnl-fields">
                <input type="text" name="original-price" value="" readonly />
            </div>
        </div>

        <div class="wpfnl-field-wrapper">
            <label> <?php echo __('Sell Price', 'wpfnl'); ?> </label>
            <div class="wpfnl-fields">
                <input type="text" name="sell-price" value="" readonly />
            </div>
        </div>

        <div class="wpfnl-field-wrapper top-align no-title-checkbox">
            <label> <?php echo __('Replace First Product', 'wpfnl'); ?> </label>
            <div class="wpfnl-fields">
                <span class="wpfnl-checkbox no-title">
                    <input type="checkbox" name="replace-first-product" id="replace-first-product" />
                    <label for="replace-first-product"></label>
                </span>
                <span class="hints desktop"><?php echo __('It will replace the first selected product (from checkout products) with the order bump product.', 'wpfnl'); ?></span>
            </div>
            <span class="hints mobile"><?php echo __('It will replace the first selected product (from checkout products) with the order bump product.', 'wpfnl'); ?></span>
        </div>

        <div class="wpfnl-field-wrapper top-align">
            <label><?php echo __('On Order Bump Purchase - Next Step', 'wpfnl'); ?> </label>
            <div class="wpfnl-fields">
                <select name="order-bump-next-step">
                    <!-- <option value="default"><?php echo __('Default', 'wpfnl'); ?></option>
                    <option value="thank-you-page"><?php echo __('Thank You Page', 'wpfnl'); ?></option> -->
                </select>
                <span class="hints"><?php echo __('Note: Select the step if you want to redirect to a different step on the order bump purchase.', 'wpfnl'); ?></span>
            </div>
        </div>
        <!-- /field-wrapper -->

    </div>
    <!-- /.discount-settings -->

</div>

<div class="order-bump-template-preview-area">
    <div class="wpfnl-order-bump__template-style1">
        <span class="preview-tag">preview</span>

        <div class="template-preview-wrapper">
            <div class="template-img">
                <img src="" alt="">
            </div>

            <div class="template-content">
                <h5 class="template-title"><?php echo __('Enter Title Text', 'wpfnl'); ?></h5>
                <h6 class="subtitle"><?php echo __('Social one time offer $29!', 'wpfnl'); ?></h6>
                <p class="description"><?php echo __('Lorem ipsum dolor sit amet, cons adipiscing elit, sed do eiusmod te incididunt ut labore.', 'wpfnl'); ?></p>
            </div>
        </div>

        <div class="offer-checkbox">
            <span class="wpfnl-checkbox">
                <input type="checkbox" name="take-offer-checkbox" id="take-offer-checkbox" />
                <label for="take-offer-checkbox"><?php echo __('Don’t Miss this amazing offer', 'wpfnl'); ?></label>
            </span>
        </div>
    </div>
</div>
