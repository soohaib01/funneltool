<?php
	$offer_settings = \WPFunnels\Wpfnl_functions::get_offer_settings();
?>

<h4 class="settings-title"><?php echo __('Order Management For Upsell/Downsell Offers)', 'wpfnl'); ?></h4>
<div class="wpfnl-box">
    <div class="wpfnl-field-wrapper">
        <label class="has-tooltip">
            <?php echo __('Create a new child order for every accepted Upsell/Downsell offer', 'wpfnl'); ?>
            <span class="wpfnl-tooltip">
                <?php require WPFNL_DIR . '/admin/partials/icons/question-tooltip-icon.php'; ?>
                <p><?php echo __('Enabling this will create separate orders for every post-purchase offers you make.', 'wpfnl'); ?></p>
            </span>
        </label>
        <div class="wpfnl-fields">
            <div class="wpfnl-radiobtn no-title">
                <input type="radio" name="offer-orders" id="wpfunnels-offer-child-order" value="child-order" <?php checked( $offer_settings['offer_orders'], 'child-order' ) ?>/>
                <label for="wpfunnels-offer-child-order"></label>
            </div>
        </div>
    </div>
    <!-- /field-wrapper -->

    <div class="wpfnl-field-wrapper">
        <label class="has-tooltip">
            <?php echo __('Add all accepted offers (Upsell/Downsell) to the main order', 'wpfnl'); ?>
            <span class="wpfnl-tooltip">
                <?php require WPFNL_DIR . '/admin/partials/icons/question-tooltip-icon.php'; ?>
                <p><?php echo __('All purchases including main product, order bump, upsell(s), and downsell(s), will be included as part of a single order in WooCommerce.', 'wpfnl'); ?></p>
            </span>
        </label>
        <div class="wpfnl-fields">
            <div class="wpfnl-radiobtn no-title">
                <input type="radio" name="offer-orders" id="wpfunnels-offer-main-order" value="main-order" <?php checked( $offer_settings['offer_orders'], 'main-order' ) ?>/>
                <label for="wpfunnels-offer-main-order"></label>
            </div>
        </div>
    </div>
    <!-- /field-wrapper -->
</div>

<h4 class="settings-title"><?php echo __('Payment Management For Upsell/Downsell Offers)', 'wpfnl'); ?></h4>
<div class="wpfnl-box">
    <div class="wpfnl-field-wrapper">
        <label class="has-tooltip">
            <?php echo __('Only show supported payment gateways during funnel checkout', 'wpfnl'); ?>
            <span class="wpfnl-tooltip">
                <?php require WPFNL_DIR . '/admin/partials/icons/question-tooltip-icon.php'; ?>
                <p><?php echo __('You may have several payment gateways in your site. But since we have limited supported payment gateways for post purchase offers, you can use this option to only view supported payment gateways during the funnel checkout page. This means, you do not have to disable other payment gateways for the funnel.', 'wpfnl'); ?></p>
            </span>
        </label>
        <div class="wpfnl-fields">
                <span class="wpfnl-checkbox no-title">
                    <input type="checkbox" name="wpfnl-show-supported-payment-gateway"  id="wpfnl-show-supported-payment-gateway" <?php if($this->offer_settings['show_supported_payment_gateway'] == 'on'){echo 'checked'; } ?>/>
                    <label for="wpfnl-show-supported-payment-gateway"></label>
                </span>
        </div>
    </div>
    <!-- /field-wrapper -->
    
    <div class="wpfnl-field-wrapper">
        <label class="has-tooltip">
            <?php echo __('Skip upsell/downsell for unsupported payment gateways', 'wpfnl'); ?>
            <span class="wpfnl-tooltip">
                <?php require WPFNL_DIR . '/admin/partials/icons/question-tooltip-icon.php'; ?>
                <p><?php echo __('Use this option so that if a buyer chooses to use a payment option that is not supported by WPFunnels, they will not get the post-purchase offers. This means, all payment options will be shown at the checkout page.', 'wpfnl'); ?></p>
            </span>
        </label>
        <div class="wpfnl-fields">
                <span class="wpfnl-checkbox no-title">
                    <input type="checkbox" name="wpfnl-skip-offer-step"  id="wpfnl-skip-offer-step" <?php if($this->offer_settings['skip_offer_step'] == 'on'){echo 'checked'; } ?>/>
                    <label for="wpfnl-skip-offer-step"></label>
                </span>
        </div>
    </div>
    <!-- /field-wrapper -->

</div>
<!-- /settings-box -->
