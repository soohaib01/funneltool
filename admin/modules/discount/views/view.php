<?php
$coupons = $this->get_coupons();

?>

<div class="wpfnl-box single__settings-box">
    <div class="wpfnl-field-wrapper">
        <label><?php echo __('Apply Coupon', 'wpfnl'); ?></label>
        <div class="wpfnl-fields">
            <?php if (count($coupons)) {
    foreach ($coupons as $key => $coupon) { ?>
                    <select class="wpfnl-discount-search wpfnl-checkout-discount" name="wpfnl_step_discount_codes" data-placeholder="<?php esc_attr_e('Search for coupon', 'wpfnl'); ?>">
                        <?php echo '<option value="' . esc_attr($key) . '"' . selected(true, true, false) . '>' . wp_kses_post($coupon) . '</option>'; ?>
                    </select>
                <?php } ?>
            <?php
} else {?>
                <select class="wpfnl-discount-search wpfnl-checkout-discount" name="wpfnl_step_discount_codes" data-placeholder="<?php esc_attr_e('Search for coupon', 'wpfnl'); ?>">
                </select>
            <?php } ?>

        </div>
        <!-- /wpfnl-fields -->
    </div>
    <!-- /wpfnl-field-wrapper -->
</div>
