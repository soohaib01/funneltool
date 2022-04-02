<div class="wpfnl-box single__settings-box">
    <div class="wpfnl-field-wrapper">
        <label><?php echo __('Apply Coupon', 'wpfnl'); ?></label>
        <div class="wpfnl-fields">
            <select name="">
                <option value="">Search for a coupon . . .</option>
                <option value="">product-1</option>
                <option value="">product-1</option>
                <option value="">product-1</option>
            </select>
        </div>
        <!-- /wpfnl-fields -->
    </div>
    <!-- /wpfnl-field-wrapper -->
</div>
<!-- /wpfnl-settings-box -->

<?php
$is_enable_product_options = true;
?>
<div class="wpfnl-box transparent single__settings-box">
    <div class="wpfnl-field-wrapper">
        <label><?php echo __('Enable Product Options', 'wpfnl'); ?></label>
        <div class="wpfnl-fields">
            <?php if ($is_enable_product_options) { ?>
                <span class="wpfnl-switcher">
                        <input type="checkbox" name="enable-product-option" id="enable-product-option" value="on" checked />
                        <label for="enable-product-option"></label>
                    </span>
            <?php } else { ?>
                <span class="wpfnl-switcher">
                        <input type="checkbox" name="enable-product-option" id="enable-product-option" value="off" />
                        <label for="enable-product-option"></label>
                    </span>
            <?php } ?>
        </div>
    </div>
</div>
<!-- /wpfnl-settings-box -->

<div class="wpfnl-box single__settings-box wpfnl-product-options" id='wpfnl-product-options'>
    <ul class="product-options__tab-nav">
        <li class="active"><a href="#selected-products"><?php echo __('Selected Products', 'wpfnl'); ?></a></li>
        <li><a href="#product-condition"><?php echo __('Product Options Conditions', 'wpfnl'); ?></a></li>
        <li><a href="#product-layout"><?php echo __('Layout options', 'wpfnl'); ?></a></li>
        <li><a href="#product-design"><?php echo __('Design', 'wpfnl'); ?></a></li>
    </ul>

    <div class="product-options__nav-content-wrapper">
        <div class="product-options__single-tab-content selected-products" id="selected-products">
            <div class="wpfnl-accordion">
                <div class="wpfnl__single-accordion">
                    <a href="#product1" class="wpfnl__accordion-title"><?php echo __('Photoshop (#13751)', 'wpfnl'); ?></a>
                    <div class="wpfnl__accordion-content" id="product1">
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Earum, aliquid?
                    </div>
                </div>

                <div class="wpfnl__single-accordion">
                    <a href="#product2" class="wpfnl__accordion-title"><?php echo __('T-Shirt (#114)', 'wpfnl'); ?></a>
                    <div class="wpfnl__accordion-content" id="product2">
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Earum, aliquid?
                    </div>
                </div>

                <div class="wpfnl__single-accordion">
                    <a href="#product3" class="wpfnl__accordion-title"><?php echo __('Photoshop (#13751)', 'wpfnl'); ?></a>
                    <div class="wpfnl__accordion-content" id="product3">
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Earum, aliquid?
                    </div>
                </div>
            </div>
            <!-- /wpfnl-accordion -->
        </div>
        <!-- /selected-products -->

        <div class="product-options__single-tab-content product-condition" id="product-condition">
            <h1>product-condition</h1>
        </div>
        <!-- /product-condition -->

        <div class="product-options__single-tab-content product-condition" id="product-layout">
            <div class="wpfnl-field-wrapper">
                <label><?php echo __('Section Title', 'wpfnl'); ?></label>
                <div class="wpfnl-fields">
                    <input type="text" name="product-title" />
                </div>
            </div>

            <div class="wpfnl-field-wrapper">
                <label><?php echo __('Section Position', 'wpfnl'); ?></label>
                <div class="wpfnl-fields">
                    <select name="section-position" id="">
                        <option value=""><?php echo __('Before Checkout Section', 'wpfnl'); ?></option>
                        <option value="before">Before</option>
                        <option value="after">After</option>
                    </select>
                </div>
            </div>

            <div class="wpfnl-field-wrapper">
                <label><?php echo __('Skins', 'wpfnl'); ?></label>
                <div class="wpfnl-fields">
                    <select name="section-position" id="">
                        <option value=""><?php echo __('Select Skin', 'wpfnl'); ?></option>
                        <option value="before">Classic</option>
                        <option value="after">Classic</option>
                    </select>
                </div>
            </div>

            <div class="wpfnl-field-wrapper">
                <label><?php echo __('Hide Image on Tab and Mobile', 'wpfnl'); ?></label>
                <div class="wpfnl-fields">
                        <span class="wpfnl-checkbox no-title">
                            <input type="checkbox" name="hide-img-mobile" id="hide-img-mobile" />
                            <label for="hide-img-mobile"></label>
                        </span>
                </div>
            </div>
            <!-- /wpfnl-field-wrapper -->
        </div>
        <!-- /product-layout -->

        <div class="product-options__single-tab-content product-condition" id="product-design">
            <h1>product-design</h1>
        </div>
        <!-- /product-design -->
    </div>
    <!-- /product-options__nav-content-wrapper -->
</div>
<!-- /wpfnl-product-options -->
