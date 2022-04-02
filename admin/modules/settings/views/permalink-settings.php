<div class="wpfnl-box">
    <div class="wpfnl-field-wrapper wpfnl-align-top">
        <label>
            <?php echo __('Default Permalink', 'wpfnl'); ?>
        </label>
        <div class="wpfnl-fields">
            <div class="wpfnl-radiobtn">
                <input type="radio" name="wpfunnels-set-permalink" id="default-permalink" value="default" <?php checked($this->permalink_settings['structure'], 'default'); ?> />
                <label for="default-permalink"><?php echo __('Default WordPress Permalink', 'wpfnl'); ?></label>
            </div>
        </div>
    </div>
    <!-- /field-wrapper -->

    <div class="wpfnl-field-wrapper wpfnl-align-top">
        <label>
            <?php echo __('Funnel and Step Slug', 'wpfnl'); ?>
        </label>
        <div class="wpfnl-fields">
            <div class="wpfnl-radiobtn">
                <input type="radio" name="wpfunnels-set-permalink" id="funnel-step-permalink" value="funnel-step" <?php checked($this->permalink_settings['structure'], 'funnel-step'); ?> />
                <label for="funnel-step-permalink"><?php echo home_url() ?>/<code class="funnelbase"></code>/%funnelname%/<code class="stepbase"></code>/%stepname%/</label>
            </div>
        </div>
    </div>
    <!-- /field-wrapper -->

    <div class="wpfnl-field-wrapper wpfnl-align-top">
        <label>
            <?php echo __('Funnel Slug', 'wpfnl'); ?>
        </label>
        <div class="wpfnl-fields">
            <div class="wpfnl-radiobtn">
                <input type="radio" name="wpfunnels-set-permalink" id="funnel-slug-permalink" value="funnel" <?php checked($this->permalink_settings['structure'], 'funnel'); ?> />
                <label for="funnel-slug-permalink"><?php echo home_url() ?>/<code class="funnelbase"></code>/%funnelname%/%stepname%/</label>
            </div>
        </div>
    </div>
    <!-- /field-wrapper -->

    <div class="wpfnl-field-wrapper wpfnl-align-top">
        <label>
            <?php echo __('Step Slug', 'wpfnl'); ?>
        </label>
        <div class="wpfnl-fields">
            <div class="wpfnl-radiobtn">
                <input type="radio" name="wpfunnels-set-permalink" id="step-slug-permalink" value="step" <?php checked($this->permalink_settings['structure'], 'step'); ?> />
                <label for="step-slug-permalink"><?php echo home_url() ?>/%funnelname%/<code class="stepbase"></code>/%stepname%/</label>
            </div>
        </div>
    </div>
    <!-- /field-wrapper -->

    <div class="wpfnl-field-wrapper parmalink-base">
        <label>
            <?php echo __('Post Type Permalink Base', 'wpfnl'); ?>
        </label>
    </div>
    <!-- /field-wrapper -->

    <div class="wpfnl-field-wrapper">
        <label>
            <?php echo __('Funnel Base', 'wpfnl'); ?>
        </label>
        <div class="wpfnl-fields">
            <input type="text" name="wpfnl-permalink-funnel-base" id="wpfunnels-permalink-funnel-base" value="<?php echo $this->permalink_settings['funnel_base']; ?>" />
        </div>
    </div>
    <!-- /field-wrapper -->

    <div class="wpfnl-field-wrapper">
        <label>
            <?php echo __('Step Base', 'wpfnl'); ?>
        </label>
        <div class="wpfnl-fields">
            <input type="text" name="wpfnl-permalink-step-base" id="wpfunnels-permalink-step-base" value="<?php echo $this->permalink_settings['step_base']; ?>" />
        </div>
    </div>
    <!-- /field-wrapper -->


</div>
<!-- /settings-box -->
