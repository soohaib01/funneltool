<?php
$is_pro_active = false;
?>


<div class="wpfnl">
    <div id="templates-library"></div>
    <div class="wpfnl-dashboard">
        <nav class="wpfnl-dashboard__nav">
            <?php require_once WPFNL_DIR . '/admin/partials/dashboard-nav.php'; ?>
        </nav>

        <div class="dashboard-nav__content">
            <div class="wpfnl-dashboard__header create-funnel__header">
                <div class="title">
                    <h1><?php echo __('Create A Funnel', 'wpfnl'); ?></h1>
                    <span class="subtitle"><?php echo __('Choose a template to start funnel', 'wpfnl'); ?></span>
                </div>

                <a href="#" class="btn-default">
                    <?php
                    require WPFNL_DIR . '/admin/partials/icons/play-icon.php';
                    echo __('How to use this funnel', 'wpfnl');
                    ?>
                </a>
            </div>
            <!-- /create-funnel__header -->

            <div class="wpfnl-create-funnel__inner-content">
                <div class="wpfnl-create-funnel__template-wrapper">

                    <div class="create-funnel__single-template create-funnel__from-scratch">
                        <p>Please activate <strong><?php echo ucfirst($this->builder); ?></strong> to see the templates. </p>
                    </div>

                    <div class="create-funnel__single-template create-funnel__from-scratch">
                        <a href="#" id="wpfnl-create-funnel" class="btn-default">
                            <?php echo __('Start From scratch', 'wpfnl'); ?>
                        </a>
                        <span class="helper-txt"><?php echo __('User step by step to create you own funnel', 'wpfnl'); ?></span>
                    </div>
                    <!-- /create-funnel__single-template -->
                </div>
                <!-- /wpfnl-create-funnel__template-wrapper -->

            </div>
            <!-- /wpfnl-create-funnel__inner-content -->

        </div>
    </div>

</div>
<!-- /.wpfnl -->
