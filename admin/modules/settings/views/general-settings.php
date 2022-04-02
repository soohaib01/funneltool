<?php
	$builders = \WPFunnels\Wpfnl_functions::get_supported_builders();

?>


<div class="wpfnl-box">
    <div class="wpfnl-field-wrapper">
        <label><?php echo __('Funnel Type', 'wpfnl'); ?></label>
        <div class="wpfnl-fields">
            <select name="page-builder" id="wpfunnels-funnel-type">
                <!-- <option value=""><?php echo __('Select Funnel Type', 'wpfnl'); ?></option> -->
                <option value="sales" <?php selected($this->general_settings['funnel_type'], 'sales'); ?> ><?php echo __('Sales (WooCommerce)', 'wpfnl'); ?></option>
            </select>
        </div>
    </div>
    <!-- /field-wrapper -->

    <div class="wpfnl-field-wrapper">
        <label><?php echo __('Page Builder', 'wpfnl'); ?></label>
        <div class="wpfnl-fields">
            <select name="page-builder" id="wpfunnels-page-builder">
				<?php
					foreach ( $builders as $key => $value ) { ?>
						<option value="<?php echo $key; ?>" <?php selected($this->general_settings['builder'], $key); ?> ><?php echo $value; ?></option>
					<?php }
				?>
			</select>
        </div>
    </div>


    <div class="wpfnl-field-wrapper sync-template">
        <label class="has-tooltip">
            <?php echo __('Sync Template', 'wpfnl'); ?>

            <span class="wpfnl-tooltip">
                <?php require WPFNL_DIR . '/admin/partials/icons/question-tooltip-icon.php'; ?>
                <p><?php echo __('Click to get the updated funnel templates, made using your preferred page builder, when creating funnels.', 'wpfnl'); ?></p>
            </span>
        </label>
        <div class="wpfnl-fields">
            <button class="btn-default clear-template" id="clear-template">
                <span class="sync-icon"><?php require WPFNL_DIR . '/admin/partials/icons/sync-icon.php'; ?></span>
                <span class="check-icon"><?php require WPFNL_DIR . '/admin/partials/icons/check-icon.php'; ?></span>
                Sync Templates
            </button>
            <span class="wpfnl-alert"></span>
        </div>
    </div>

    <!-- /field-wrapper -->
    <?php if( apply_filters( 'wpfunnels/is_wpfnl_pro_active', false ) ){?>
        <div class="wpfnl-field-wrapper analytics-stats">
            <label><?php echo __('Disable Analytics Tracking For', 'wpfnl'); ?>
                <span class="wpfnl-tooltip">
                    <?php require WPFNL_DIR . '/admin/partials/icons/question-tooltip-icon.php'; ?>
                    <p><?php echo __('If you want WPFunnels not to track traffic, conversion, & revenue count for Analytics from certain user roles in your site, then you may do so using these options.', 'wpfnl'); ?></p>
                </span>
            </label>

            <div class="wpfnl-fields">
                <?php foreach( $this->user_roles as $role ) { ?>
                    <span class="wpfnl-checkbox">
                        <input type="checkbox" name="analytics-role[]"  id="<?php echo $role; ?>-analytics-role" data-role="<?php echo $role; ?>" <?php if(isset($this->general_settings['disable_analytics'][$role])){checked( $this->general_settings['disable_analytics'][$role], 'true' );} ?> />
                        <label for="<?php echo $role; ?>-analytics-role"><?php echo str_replace("_"," ",ucfirst($role)); ?></label>
                    </span>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

<!--	<div class="wpfnl-field-wrapper sync-template">-->
<!--		<label class="has-tooltip">-->
<!--			--><?php //echo __( 'WPFunnels Transients', 'wpfnl' ); ?>
<!--			<span class="wpfnl-tooltip">-->
<!--                --><?php //require WPFNL_DIR . '/admin/partials/icons/question-tooltip-icon.php'; ?>
<!--                <p>--><?php //echo __('This tool will clear the WPFunnels transients cache.', 'wpfnl'); ?><!--</p>-->
<!--            </span>-->
<!--		</label>-->
<!--		<div class="wpfnl-fields">-->
<!--			<button class="btn-default clear-template" id="clear-transients">-->
<!--				<span class="sync-icon">--><?php //require WPFNL_DIR . '/admin/partials/icons/sync-icon.php'; ?><!--</span>-->
<!--				<span class="check-icon">--><?php //require WPFNL_DIR . '/admin/partials/icons/check-icon.php'; ?><!--</span>-->
<!--				--><?php //echo __('Delete transients', 'wpfnl'); ?>
<!--			</button>-->
<!--			<span class="wpfnl-alert"></span>-->
<!--		</div>-->
<!--	</div>-->
</div>
<!-- /settings-box -->
