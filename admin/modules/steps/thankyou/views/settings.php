<div class="steps-settings thank-you">
    <?php require WPFNL_DIR . '/admin/modules/steps/general/step-title.php'; ?>
    <!-- /steps-page__content-title-wrapper -->

    <ul class="steps-settings__tab-nav">
        <li class="active">
            <a href="#thanku-edit-field">
                <?php
                    require WPFNL_DIR . '/admin/partials/icons/edit-icon.php';
                    echo __('Edit Fields', 'wpfnl');
                ?>
            </a>
        </li>
        <li>
            <a href="#thank-u-settings">
                <?php
                    require WPFNL_DIR . '/admin/partials/icons/settings-icon.php';
                    echo __('Settings', 'wpfnl');
                ?>
            </a>
        </li>
    </ul>

    <div class="step-settings__tab-content-wrapper">
        
        <div class="step-settings__single-tab-content thanku-edit-field" id="thanku-edit-field">
            <div class="wpfnl-box single__settings-box">
                <div class="wpfnl-field-wrapper">
                    <label><?php echo __('Enable Order Overview', 'wpfnl'); ?></label>
                    <div class="wpfnl-fields">
                        <div class="wpfnl-checkbox no-title">
                            <input type="checkbox" name="enable-order-overview" id="enable-order-overview" <?php checked($this->get_internal_metas_by_key('_wpfnl_thankyou_order_overview'), 'on'); ?>/>
                            <label for="enable-order-overview"></label>
                        </div>
                    </div>
                </div>
                <!-- /field-wrapper -->

                <div class="wpfnl-field-wrapper">
                    <label><?php echo __('Enable Order Details', 'wpfnl'); ?></label>
                    <div class="wpfnl-fields">
                        <div class="wpfnl-checkbox no-title">
                            <input type="checkbox" name="enable-order-details" id="enable-order-details" <?php checked($this->get_internal_metas_by_key('_wpfnl_thankyou_order_details'), 'on'); ?>/>
                            <label for="enable-order-details"></label>
                        </div>
                    </div>
                </div>
                <!-- /field-wrapper -->

                <div class="wpfnl-field-wrapper">
                    <label><?php echo __('Enable Billing Details', 'wpfnl'); ?></label>
                    <div class="wpfnl-fields">
                        <div class="wpfnl-checkbox no-title">
                            <input type="checkbox" name="enable-billing-details" id="enable-billing-details" <?php checked($this->get_internal_metas_by_key('_wpfnl_thankyou_billing_details'), 'on'); ?>/>
                            <label for="enable-billing-details"></label>
                        </div>
                    </div>
                </div>
                <!-- /field-wrapper -->

                <div class="wpfnl-field-wrapper">
                    <label><?php echo __('Enable Shipping Details', 'wpfnl'); ?></label>
                    <div class="wpfnl-fields">
                        <div class="wpfnl-checkbox no-title">
                            <input type="checkbox" name="enable-shipping-details" id="enable-shipping-details" <?php checked($this->get_internal_metas_by_key('_wpfnl_thankyou_shipping_details'), 'on'); ?>/>
                            <label for="enable-shipping-details"></label>
                        </div>
                    </div>
                </div>
                <!-- /field-wrapper -->
            </div>
            <!-- /settings-box -->
        </div>
        <!-- /step-settings__single-tab-content-edit-field -->

        <div class="step-settings__single-tab-content thank-u-settings" id="thank-u-settings">
            <div class="wpfnl-box single__settings-box">
                <div class="wpfnl-field-wrapper">
                    <label><?php echo __('Thank You Page Text', 'wpfnl'); ?></label>
                    <div class="wpfnl-fields">
                        <input type="text" name="thankyou-page-text" class="thankyou-page-text" value="<?php echo $this->get_internal_metas_by_key('_wpfnl_thankyou_text'); ?>"/>
                    </div>
                </div>
                <!-- /wpfnl-field-wrapper -->

                <div class="wpfnl-field-wrapper">
                    <label><?php echo __('Redirect Link After Purchase', 'wpfnl'); ?></label>
                    <div class="wpfnl-fields">
                        <input type="text" name="rerirect-link" class="thankyou-redirect-link" placeholder="https://" value="<?php echo $this->get_internal_metas_by_key('_wpfnl_thankyou_redirect_link'); ?>"/>
                    </div>
                </div>
                <!-- /wpfnl-field-wrapper -->

            </div>
            <!-- /wpfnl-settings-box -->
        </div>
        <!-- /step-settings__single-tab-content-settings -->
    </div>

    <?php
        $back2_edit = add_query_arg(
                    [
                'page' => WPFNL_EDIT_FUNNEL_SLUG,
                'id' => $this->step->get_funnel_id(),
                'step_id' => $this->get_id(),
            ],
                    admin_url('admin.php')
                );
    ?>
    
    <div class="settings-content__footer">
        <a href="<?php echo $back2_edit; ?>" class="btn-default back2-edit"><?php echo __(' Back to Step', 'wpfnl'); ?></a>
        <button class="btn-default update" id="wpfnl-update-thank-you-settings" data-id="<?php echo $this->get_id(); ?>">
            <?php echo __('Update', 'wpfnl'); ?>
            <span class="wpfnl-loader"></span>
        </button>
        <span class="wpfnl-alert box"></span>
    </div>
</div>