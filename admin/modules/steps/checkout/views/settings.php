<?php
    $pro_url = add_query_arg( 'wpfnl-dashboard', '1', 'https://getwpfunnels.com/' );
?>

<div class="steps-settings">
    <?php require WPFNL_DIR . '/admin/modules/steps/general/step-title.php'; ?>
    <!-- /steps-page__content-title-wrapper -->

    <ul class="steps-settings__tab-nav">
        <li class='active'>
            <a href="#product">
                <?php
                    require WPFNL_DIR . '/admin/partials/icons/product-icon.php';
                    echo __('Products', 'wpfnl');
                ?>
            </a>
        </li>

        <li>
            <a href="#order-bump">
                <?php
                    require WPFNL_DIR . '/admin/partials/icons/cart-icon2.php';
                    echo __('Order Bump', 'wpfnl');
                ?>
            </a>
        </li>

        <li>
            <a href="#edit-field">
                <?php
                    require WPFNL_DIR . '/admin/partials/icons/edit-icon.php';
                    echo __('Edit Fields', 'wpfnl');

                    if( !$is_pro_activated ){
                        echo '<span class="pro-tag">'.__( 'coming soon', 'wpfnl' ).'</span>';
                    }
                ?>
            </a>
        </li>

       <!--  <li>
            <a href="#settings">
                <?php
                    require WPFNL_DIR . '/admin/partials/icons/settings-icon.php';
                    echo __('Settings', 'wpfnl');
                ?>
            </a>
        </li> -->
    </ul>

    <div class="step-settings__tab-content-wrapper">
        <?php
            $product_module = \WPFunnels\Wpfnl::$instance->module_manager->get_admin_modules('product');
            $discount_module = \WPFunnels\Wpfnl::$instance->module_manager->get_admin_modules('discount');

            $product_module->set_products($this->get_internal_metas_by_key('_wpfnl_checkout_products'));
            $products_ids = $product_module->get_products();

            $discount_module->set_coupon($this->get_internal_metas_by_key('_wpfnl_checkout_discount'));
            $coupons = $discount_module->get_coupons();

            $back2_edit = add_query_arg(
                [
                    'page' => WPFNL_EDIT_FUNNEL_SLUG,
                    'id' => $this->step->get_funnel_id(),
                    'step_id' => $this->get_id(),
                ],
                admin_url('admin.php')
            );

        ?>

        <div class="step-settings__single-tab-content product" id="product">
            <div class="checkout-product-setting-tab__content-wrapper">
                <?php require WPFNL_DIR . '/admin/modules/steps/checkout/views/products-tab.php'; ?>
            </div>

            <div class="settings-content__footer">
                <a href="<?php echo $back2_edit; ?>" class="btn-default back2-edit"><?php echo __(' Back to Step', 'wpfnl'); ?></a>

                <button class="btn-default update" id="wpfnl-update-checkout-product-tab" data-id="<?php echo $this->get_id(); ?>">
                    <?php echo __('Update', 'wpfnl'); ?>
                    <span class="wpfnl-loader"></span>
                </button>
                <span class="wpfnl-alert box"></span>
            </div>

        </div>
        <!-- /step-settings__single-tab-content-product (product) -->


        <?php //do_action('wpfunnels/checkout_pro_settings'); ?>


        <div class="step-settings__single-tab-content edit-field" id="edit-field">
            <div class="checkout-edit-field-tab__content-wrapper">
                <?php if( !$is_pro_activated ){ ?>
                    <a href="<?php echo $pro_url; ?>" target="_blank" title="<?php _e( 'Click to Upgrade Pro', 'wpfnl' ); ?>">
                        <span class="pro-tag"><?php echo __( 'Get Pro', 'wpfnl' ); ?></span>
                    </a>
                <?php
                    }else{
                        do_action('wpfunnels/checkout_pro_settings');
                        ?>
                            <div class="settings-content__footer">
                                <a href="<?php echo $back2_edit; ?>" class="btn-default back2-edit"><?php echo __(' Back to Step', 'wpfnl'); ?></a>

                                <button class="btn-default update" id="wpfnl-update-checkout-product-tab" data-id="<?php echo $this->get_id(); ?>">
                                    <?php echo __('Update', 'wpfnl'); ?>
                                    <span class="wpfnl-loader"></span>
                                </button>
                                <span class="wpfnl-alert box"></span>
                            </div>
                        <?php
                    }
                ?>
            </div>
        </div>

        
        <!-- /step-settings__single-tab-content-edit-field -->

        <div class="step-settings__single-tab-content order-bump" id="order-bump">
            <div id="order-bump-wrapper"></div>
        </div>
        <!-- /step-settings__single-tab-content-design (order bump) -->

        <div class="step-settings__single-tab-content settings" id="settings">
            <h1>Coming soon</h1>
        </div>
        <!-- /step-settings__single-tab-content-settings -->
    </div>


</div>
