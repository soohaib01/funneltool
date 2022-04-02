<?php
    $pro_url = add_query_arg( 'wpfnl-dashboard', '1', 'https://rextheme.com/wpfunnels' );
?>

<div class="wpfnl-modal" id="create-step-form">
    <div class="wpfnl-modal__inner">
        <div class="wpfnl-modal__wrapper">
            <div class="wpfnl-modal__header">
                <div class="title">
                    <h2>
                        <?php echo __('Choose Template', 'wpfnl'); ?>
                    </h2>
                </div>
                <span class="wpfnl-modal-close">
                    <?php require WPFNL_DIR . '/admin/partials/icons/cross-icon.php'; ?>
                </span>
            </div>

            <!-- modal body -->
            <div class="wpfnl-modal__body">
                <ul class="wpfnl-create-step__filter-nav" id="wpfnl-create-step__filter-nav" style="display: flex;">
                    <li class="active" id="create-step-landing" data-filter="landing">Landing</li>
                    <li id="create-step-checkout" data-filter="checkout">Checkout</li>
                    <li id="create-step-thankyou" data-filter="thankyou">Thankyou</li>

                    <?php if($is_pro_activated){ ?>
                        <li id="create-step-upsell" data-filter="upsell">Upsell</li>
                        <li id="create-step-downsell" data-filter="downsell">Downsell</li>

                    <?php }else{ ?>
                        <li class="disabled" >Upsell
                            <a href="<?php echo $pro_url; ?>" target="_blank" title="<?php _e( 'Click to Upgrade Pro', 'wpfnl' ); ?>">
                                <span class="pro-tag"><?php echo __( 'coming soon', 'wpfnl' ); ?></span>
                            </a>
                        </li>

                        <li class="disabled" >Downsell
                            <a href="<?php echo $pro_url; ?>" target="_blank" title="<?php _e( 'Click to Upgrade Pro', 'wpfnl' ); ?>">
                                <span class="pro-tag"><?php echo __( 'coming soon', 'wpfnl' ); ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>

                <div class="wpfnl-create-step__template-wrapper" id="wpfnl-create-step__template-wrapper">
                    <div class="create-step__single-template create-step__from-scratch">
                        <a href="#" id="wpfnl-create-step" class="btn-default" data-step-type="landing" data-funnel-id="<?php if(isset($_GET['id'])){ echo sanitize_text_field($_GET['id']);} ?>">
                            <?php echo __('Start From scratch', 'wpfnl'); ?>
                        </a>
                    </div>

                    <div class="wpfnl-loader__wrapper"><span class="wpfnl-loader"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>
