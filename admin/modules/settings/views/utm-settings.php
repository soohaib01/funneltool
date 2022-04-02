<div class="wpfnl-box">
	<!-- /field-wrapper -->
	<?php if( is_plugin_active( 'wpfunnels-pro/wpfnl-pro.php' ) ){?>
		<div class="wpfnl-field-wrapper analytics-stats">
			<label><?php echo __('UTM Settings', 'wpfnl'); ?></label>
			<div class="wpfnl-fields">
					<span class="wpfnl-checkbox no-title">
                        <input type="checkbox" name="wpfnl-utm-enable"  id="utm-enable" <?php if($this->utm_settings['utm_enable'] == 'on'){echo 'checked'; } ?>/>
                        <label for="utm-enable"></label>
                    </span>
			</div>
		</div>
		<div id="wpfnl-utm">
			<div class="wpfnl-field-wrapper utm-source" id="utm-source">
				<label>
					<?php echo __('Referrer (utm_source)', 'wpfnl'); ?>
				</label>
				<div class="wpfnl-fields">
					<input type="text" name="wpfnl-utm-source" id="wpfnl-utm-source" value="<?php echo isset($this->utm_settings['utm_source']) ? $this->utm_settings['utm_source']: '' ; ?>" required/>
				</div>
			</div>
			<div class="wpfnl-field-wrapper utm-medium" id="utm-medium">
				<label>
					<?php echo __('Medium (utm_medium)', 'wpfnl'); ?>
				</label>
				<div class="wpfnl-fields">
					<input type="text" name="wpfnl-utm-medium" id="wpfnl-utm-medium" value="<?php echo isset($this->utm_settings['utm_medium']) ? $this->utm_settings['utm_medium']: '' ; ?>" required/>
				</div>
			</div>
			<div class="wpfnl-field-wrapper utm-campaign" id="utm-campaign">
				<label>
					<?php echo __('Campaign (utm_campaign)', 'wpfnl'); ?>
				</label>
				<div class="wpfnl-fields">
					<input type="text" name="wpfnl-utm-campaign" id="wpfnl-utm-campaign" value="<?php echo isset($this->utm_settings['utm_campaign']) ? $this->utm_settings['utm_campaign']: '' ; ?>" required/>
				</div>
			</div>
			<div class="wpfnl-field-wrapper utm-content" id="utm-content">
				<label>
					<?php echo __('Content (utm_content)', 'wpfnl'); ?>
				</label>
				<div class="wpfnl-fields">
					<input type="text" name="wpfnl-utm-content" id="wpfnl-utm-content" value="<?php echo isset($this->utm_settings['utm_content']) ? $this->utm_settings['utm_content']: '' ; ?>" />
				</div>
			</div>
		</div>

	<?php } ?>

</div>
<!-- /settings-box -->
