<?php
    $pro_url = add_query_arg( 'wpfnl-dashboard', '1', 'https://getwpfunnels.com/' );
?>
<div class="wpfnl">

<div class="wpfnl-dashboard">
    <nav class="wpfnl-dashboard__nav">
        <?php require_once WPFNL_DIR . '/admin/partials/dashboard-nav.php'; ?>
    </nav>

    <div class="dashboard-nav__content">
        <div id="templates-library"></div>

        <div class="wpfnl-dashboard__header funnel-settings__header">
            <div class="title">
                <h1><?php echo __('Settings', 'wpfnl'); ?></h1>
            </div>

            <ul class="helper-link">
                <li><a href="https://getwpfunnels.com/docs/getting-started-with-wpfunnels/" target="_blank"><?php echo __('Need Help?', 'wpfnl'); ?></a></li>
                <li><a href="https://getwpfunnels.com/contact-us/" target="_blank"><?php echo __('Contact Us', 'wpfnl'); ?></a></li>
                <li><a href="?page=wpfunnels-setup"><?php echo __('Run Setup Wizard', 'wpfnl'); ?></a></li>
            </ul>
        </div>
        <!-- /funnel-settings__header -->

        <?php do_action('wpfunnels_before_settings'); ?>
        <div class="wpfnl-funnel-settings__inner-content">

            <div class="wpfnl-funnel-settings__wrapper">
                <nav class="wpfn-settings__nav">
                    <ul>
                        <li class="nav-li active" data-id="general-settings">
                            <?php include WPFNL_DIR . '/admin/partials/icons/settings-icon-2x.php'; ?>
                            <span><?php echo __('General Settings', 'wpfnl'); ?></span>
                        </li>

                        <li class="nav-li" data-id="permalink-settings">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 14C10.6583 14.6719 11.5594 15.0505 12.5 15.0505C13.4406 15.0505 14.3417 14.6719 15 14L19 10C19.9175 9.11184 20.2848 7.79798 19.961 6.56274C19.6372 5.32751 18.6725 4.36284 17.4373 4.03901C16.202 3.71519 14.8882 4.08252 14 5.00002L13.5 5.50002" stroke="#7A8B9A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 9.99997C13.3417 9.32809 12.4407 8.94946 11.5 8.94946C10.5594 8.94946 9.65836 9.32809 9.00004 9.99997L5.00004 14C3.65732 15.387 3.67524 17.5946 5.04031 18.9597C6.40538 20.3248 8.61299 20.3427 10 19L10.5 18.5" stroke="#7A8B9A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <span><?php echo __('Permalink', 'wpfnl'); ?></span>
                        </li>

                        <li class="nav-li <?php echo !$is_pro_activated ? ' disabled' : '' ?>" <?php echo $is_pro_activated ? ' data-id="offer-settings" ' : '' ?> >
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="8" width="18" height="4" rx="1" stroke="#7A8B9A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 8V21" stroke="#7A8B9A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M19 12V19C19 20.1046 18.1046 21 17 21H7C5.89543 21 5 20.1046 5 19V12" stroke="#7A8B9A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7.5 7.99994C6.11929 7.99994 5 6.88065 5 5.49994C5 4.11923 6.11929 2.99994 7.5 2.99994C9.474 2.96594 11.26 4.94894 12 7.99994C12.74 4.94894 14.526 2.96594 16.5 2.99994C17.8807 2.99994 19 4.11923 19 5.49994C19 6.88065 17.8807 7.99994 16.5 7.99994" stroke="#7A8B9A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <span><?php echo __('Offer Settings', 'wpfnl'); ?></span>

                            <?php
                                if( !$is_pro_activated ){
                                    echo '<span class="pro-tag">'.__( 'Pro', 'wpfnl' ).'</span>';
                                }
                            ?>
                        </li>

                        <li class="nav-li <?php echo !$is_pro_activated ? ' disabled' : '' ?>" <?php echo $is_pro_activated ? ' data-id="event-tracking-setting" ' : '' ?> >
                            <?php require WPFNL_DIR . '/admin/partials/icons/event-tracking-icon.php'; ?>
                            <span><?php echo __('Event tracking', 'wpfnl'); ?></span>
                            <?php
                                if( !$is_pro_activated ){
                                    echo '<span class="pro-tag">'.__( 'Pro', 'wpfnl' ).'</span>';
                                }
                            ?>
                        </li>

                        <!-- <li class="nav-li <?php echo !$is_pro_activated ? ' disabled' : '' ?>" <?php echo $is_pro_activated ? ' data-id="gtm-settings" ' : '' ?> >
                            <?php //require WPFNL_DIR . '/admin/partials/icons/gtm-icon.php'; ?>
                            <span><?php echo __('Google Tag Manager', 'wpfnl'); ?></span>
                            <?php
                                // if( !$is_pro_activated ){
                                //     echo '<span class="pro-tag">'.__( 'Pro', 'wpfnl' ).'</span>';
                                // }
                            ?>
                        </li> -->

                        <!-- <li class="nav-li <?php echo !$is_pro_activated ? ' disabled' : '' ?>" <?php echo $is_pro_activated ? ' data-id="utm-settings" ' : '' ?> >
                            <?php //require WPFNL_DIR . '/admin/partials/icons/utm-settings.php'; ?>
                            <span><?php echo __('UTM Settings', 'wpfnl'); ?></span>

                            <?php
                                // if( !$is_pro_activated ){
                                //     echo '<span class="pro-tag">'.__( 'Pro', 'wpfnl' ).'</span>';
                                // }
                            ?>
                        </li> -->

						<li class="nav-li" data-id="advance-settings">
							<?php require WPFNL_DIR . '/admin/partials/icons/advanced-settings.php'; ?>
							<span><?php echo __('Advance Settings', 'wpfnl'); ?></span>
						</li>

                    </ul>
                </nav>

                <div class="wpfnl-funnel__single-settings general" id="general-settings">
                    <h4 class="settings-title"><?php echo __('General Settings', 'wpfnl'); ?></h4>
                    <?php do_action('wpfunnels_before_general_settings'); ?>
                    <?php require WPFNL_DIR . '/admin/modules/settings/views/general-settings.php'; ?>
                    <?php do_action('wpfunnels_after_general_settings'); ?>
                </div>
                <!-- /General Settings -->

                <div class="wpfnl-funnel__single-settings offer" id="offer-settings">
                    <?php if( $is_pro_activated ){ ?>
                        <?php do_action('wpfunnels_before_offer_settings'); ?>
                        <?php require WPFNL_DIR . '/admin/modules/settings/views/offer-settings.php'; ?>
                        <?php do_action('wpfunnels_after_offer_settings'); ?>

                    <?php }else{ ?>
                        <a href="<?php echo $pro_url; ?>" target="_blank" title="<?php _e( 'Click to Upgrade Pro', 'wpfnl' ); ?>">
                            <span class="pro-tag"><?php echo __( 'Get Pro', 'wpfnl' ); ?></span>
                        </a>
                    <?php } ?>
                </div>
                <!-- /Offer Settings -->

                <div class="wpfnl-funnel__single-settings permalink" id="permalink-settings">
                    <?php do_action('wpfunnels_before_permalink_settings'); ?>
                    <h4 class="settings-title"><?php echo __('Permalink Settings', 'wpfnl'); ?></h4>
                    <?php require WPFNL_DIR . '/admin/modules/settings/views/permalink-settings.php'; ?>
                    <?php do_action('wpfunnels_after_permalink_settings'); ?>
                </div>
                <!-- /Permalink Settings -->

                <div class="wpfnl-funnel__single-settings event-tracking" id="event-tracking-setting">
					<?php if( $is_pro_activated ){ ?>

                        <div class="facebook-pixel">
                            <h4 class="settings-title">
                                <?php require WPFNL_DIR . '/admin/partials/icons/facebook-pixel-icon.php'; ?>
                                <?php echo __('Facebook Pixel Integration', 'wpfnl'); ?>
                                <a href="https://getwpfunnels.com/docs/funnel-integrations/facebook-pixel-integration/" target="_blank" title="Guide On Facebook Pixel Integration">
                                    <?php include WPFNL_DIR . '/admin/partials/icons/doc-icon.php'; ?>
                                </a>
                            </h4>
                            <?php do_action('wpfunnels_before_facebook_pixel_settings'); ?>
                            <?php require WPFNL_DIR . '/admin/modules/settings/views/facebook-pixel-settings.php'; ?>
                            <?php do_action('wpfunnels_after_facebook_pixel_settings'); ?>
                        </div>
                        <!-- /Facebook Pixel -->

                        <div class="gtm">
                            <h4 class="settings-title">
                                <?php require WPFNL_DIR . '/admin/partials/icons/gtm-icon.php'; ?>
                                <?php echo __('Google Tag Manager', 'wpfnl'); ?>
                                <a href="https://getwpfunnels.com/docs/funnel-integrations/google-tag-manager-integration/" target="_blank"  title="Guide On Google Tag Manager Integration">
                                    <?php include WPFNL_DIR . '/admin/partials/icons/doc-icon.php'; ?>
                                </a>
                            </h4>
                            <?php do_action('wpfunnels_before_gtm_settings'); ?>
                            <?php require WPFNL_DIR . '/admin/modules/settings/views/gtm-settings.php'; ?>
                            <?php do_action('wpfunnels_after_gtm_settings'); ?>
                        </div>
                        <!-- /GTM Settings -->

                        <div class="utm">
                            <h4 class="settings-title">
                                <?php require WPFNL_DIR . '/admin/partials/icons/utm-icon.php'; ?>
                                <?php echo __('UTM Settings', 'wpfnl'); ?>
                                <a href="https://getwpfunnels.com/docs/funnel-integrations/utm-parameters/" target="_blank"  title="Guide On UTM Integration">
                                    <?php include WPFNL_DIR . '/admin/partials/icons/doc-icon.php'; ?>
                                </a>
                            </h4>
                            <?php do_action('wpfunnels_before_utm_settings'); ?>
                            <?php require WPFNL_DIR . '/admin/modules/settings/views/utm-settings.php'; ?>
                            <?php do_action('wpfunnels_after_utm_settings'); ?>
                        </div>
                        <!-- /UTM Settings -->

					<?php }else{ ?>
						<a href="<?php echo $pro_url; ?>" target="_blank" title="<?php _e( 'Click to Upgrade Pro', 'wpfnl' ); ?>">
							<span class="pro-tag"><?php echo __( 'Get Pro', 'wpfnl' ); ?></span>
						</a>
					<?php } ?>
				</div>
				<!-- /event-tracking-setting -->

				<div class="wpfnl-funnel__single-settings advance-settings" id="advance-settings">
					<?php
					$rollback_versions = $this->get_roll_back_versions();
					?>
					<?php require WPFNL_DIR . '/admin/modules/settings/views/advance-settings.php'; ?>
				</div>
				<!-- /advance Settings -->

            </div>
            <!-- /funnel-settings__wrapper -->

            <div class="wpfnl-funnel-settings__footer">
                <span class="wpfnl-alert box"></span>
                <button class="btn-default update" id="wpfnl-update-global-settings" >
                    <?php echo __('Save', 'wpfnl'); ?>
                    <span class="wpfnl-loader"></span>
                </button>
            </div>

        </div>
        <!-- /funnel-settings__inner-content -->
        <?php do_action('wpfunnels_after_settings'); ?>
    </div>
</div>

</div>
<!-- /.wpfnl -->
