<div class="wpfnl-box">
	<!-- /field-wrapper -->
	<?php if( is_plugin_active( 'wpfunnels-pro/wpfnl-pro.php' ) ){?>
		<div class="wpfnl-field-wrapper analytics-stats">
			<label><?php echo __('Google Tag Manager', 'wpfnl'); ?></label>
			<div class="wpfnl-fields">
					<span class="wpfnl-checkbox no-title">
                        <input type="checkbox" name="wpfnl-gtm-enable"  id="gtm-enable" <?php if($this->gtm_settings['gtm_enable'] == 'on'){echo 'checked'; } ?>/>
                        <label for="gtm-enable"></label>
                    </span>
			</div>
		</div>
		<div id="wpfnl-gtm">
			<div class="wpfnl-field-wrapper gtm-snippet-head" id="gtm-snippet-head">
				<label>
					<?php echo __('GTM Container ID', 'wpfnl'); ?>
					<span class="wpfnl-tooltip">
						<?php require WPFNL_DIR . '/admin/partials/icons/question-tooltip-icon.php'; ?>
						<p><?php echo __('In your Google Tab Manager Workspace, near the top of the window, you will find your container ID, formatted as “GTM-XXXXXX“.', 'wpfnl'); ?></p>
					</span>
				</label>
				<div class="wpfnl-fields">
					<input type="text" name="wpfnl-gtm-container-id" id="wpfnl-gtm-container-id" value="<?php echo isset($this->gtm_settings['gtm_container_id']) ? $this->gtm_settings['gtm_container_id']: '' ; ?>" />
				</div>
			</div>
			<div class="wpfnl-field-wrapper analytics-stats">
				<label>
					<?php echo __('GTM Events', 'wpfnl'); ?>
					<span class="wpfnl-tooltip">
						<?php require WPFNL_DIR . '/admin/partials/icons/question-tooltip-icon.php'; ?>
						<p><?php echo __('Choose what events to track in your funnels via Google Tag Manager.', 'wpfnl'); ?></p>
					</span>
				</label>
				<div class="wpfnl-fields">
					<?php foreach( $this->gtm_events as $key => $events ) { ?>
						<span class="wpfnl-checkbox">
                        <input type="checkbox" name="wpfnl-gtm-events[]"  id="<?php echo $key; ?>-gtm-events" data-role="<?php echo $key; ?>"
							<?php if(isset($this->gtm_settings['gtm_events'][$key])){checked( $this->gtm_settings['gtm_events'][$key], 'true' );} ?>
						/>
                        <label for="<?php echo $key; ?>-gtm-events"><?php echo ucfirst($events); ?></label>
                    </span>
					<?php } ?>
				</div>
			</div>
		</div>

	<?php } ?>

</div>
<!-- /settings-box -->