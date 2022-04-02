<?php

$settings_link = add_query_arg(
    [
        'page'          => WPFNL_EDIT_FUNNEL_SLUG,
        'id'            => $this->step->get_funnel_id(),
        'step_id'       => $this->step->get_id(),
        'show_settings' => 1,
    ],
    admin_url('admin.php')
);

$id = $this->get_id();
$title = get_the_title($id);
$link  = get_the_permalink($id);
?>

<div id="step-checkout" class="wpfnl-single-step__content">
    <?php require WPFNL_DIR . '/admin/modules/steps/general/step-title.php'; ?>
    <!-- /steps-page__content-title-wrapper -->

    <div class="steps-content_boxs-wrapper">
        <div class="steps-box">
            <?php
                $products = $this->step->get_internal_metas_by_key('_wpfnl_checkout_products');

                if (!$products) { ?>
                    <span class="no-product-tag"><?php echo __('Product not added', 'wpfnl'); ?></span>
                <?php }
                else {
                  ?>
                      <span style="color: #1aab1a;" class="no-product-tag"><?php echo __('Product added', 'wpfnl'); ?></span>
                  <?php
                }
            ?>
            <?php require WPFNL_DIR . '/admin/partials/icons/cart-icon.php'; ?>
            <span class="step-page-title"><?php echo __('Checkout page', 'wpfnl'); ?></span>
            <?php
            $post_edit_link = get_edit_post_link($id);
            $builder = get_option('_wpfunnels_general_settings');
            if (isset($builder['builder']) && $builder['builder'] == 'elementor') {
                $post_edit_link = home_url() . '/wp-admin/post.php?post=' . $id . '&action=elementor';
            }
            ?>
            <div class="actions-area">
                <a href="<?php echo $post_edit_link; ?>" title="Edit" target="_blank" class="btn-default step-edit">
                    <?php require WPFNL_DIR . '/admin/partials/icons/edit-icon.php'; ?>
                    <?php echo __('Edit', 'wpfnl'); ?>
                </a>
                <a href="<?php echo $link; ?>" target="_blank" title="Preview" class="single-action step-preview">
                    <?php require WPFNL_DIR . '/admin/partials/icons/eye-icon.php'; ?>
                </a>
                <!-- <a href="#" title="Duplicate" class="single-action step-duplicate">
                    <?php //require WPFNL_DIR . '/admin/partials/icons/duplicate-icon.php';?>
                </a>
                <a href="#" title="AB Testing" class="single-action step-ab-testing">
                    <?php //require WPFNL_DIR . '/admin/partials/icons/ab-testing-icon.php';?>
                </a> -->
                <a href="#" title="Delete" class="single-action step-delete" id="wpfnl-delete-step" data-id="<?php echo $id; ?>">
                    <?php require WPFNL_DIR . '/admin/partials/icons/delete-icon.php'; ?>
                </a>
                <a href="<?php echo $settings_link; ?>" title="Settings" class="single-action step-settings">
                    <?php require WPFNL_DIR . '/admin/partials/icons/settings-icon.php'; ?>
                </a>
            </div>
        </div>

        <div class="steps-box create-vairation">
            <?php require WPFNL_DIR . '/admin/partials/icons/funnel-icon-xl.php'; ?>
            <span class="no-product-tag coming-soon-tag"><?php echo __('Coming Soon', 'wpfnl'); ?></span>
            <span class="step-page-title"><?php echo __('Start Split Test', 'wpfnl'); ?></span>

            <div class="actions-area">
                <a href="" title="Create Variations" class="btn-default add-variation">
                    <?php require WPFNL_DIR . '/admin/partials/icons/plus-icon.php'; ?>
                    <?php echo __('Create Variations', 'wpfnl'); ?>
                </a>
                <span class="helper-text"><?php echo __('Optimize your lead and sales with split tests', 'wpfnl'); ?></span>
            </div>
        </div>
        <!-- /steps box -->

    </div>
</div>
<!-- /#step-checkout -->
