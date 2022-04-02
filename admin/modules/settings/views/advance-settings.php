<div class="basic-tools-field">
	<h4 class="settings-title"> <?php echo __('Basic Tools', 'wpfnl'); ?> </h4>
	<div class="wpfnl-box">
		<div class="wpfnl-field-wrapper">
			<label>
				<?php echo __( 'Remove WPF Transient Cache', 'wpfnl' ); ?>
				<span class="wpfnl-tooltip">
					<?php require WPFNL_DIR . '/admin/partials/icons/question-tooltip-icon.php'; ?>
					<p><?php echo __('If you are facing issues such as not getting plugin updates or license not working, clear the transient cache and try again.', 'wpfnl'); ?></p>
				</span>
			</label>
			<div class="wpfnl-fields">
				<button class="btn-default clear-template" id="clear-transients">
					<span class="sync-icon"><?php require WPFNL_DIR . '/admin/partials/icons/sync-icon.php'; ?></span>
					<span class="check-icon"><?php require WPFNL_DIR . '/admin/partials/icons/check-icon.php'; ?></span>
					<?php echo __('Delete transients', 'wpfnl'); ?>
				</button>
				<span class="wpfnl-alert"></span>
			</div>
		</div>
		<!-- /field-wrapper -->
	</div>
</div>

<!-- rollback settings -->
<div class="rollback-field">
	<h4 class="settings-title"> <?php echo __('Rollback Settings', 'wpfnl'); ?> </h4>
	<div class="wpfnl-box">
		<div class="wpfnl-field-wrapper">
			<label><?php echo __('Current Version', 'wpfnl'); ?></label>
			<div class="wpfnl-fields">
				<b>v<?php echo WPFNL_VERSION; ?></b>
			</div>
		</div>
		<!-- /field-wrapper -->

		<div class="wpfnl-field-wrapper wpfnl-align-top">
			<label><?php echo __('Rollback to older Version', 'wpfnl'); ?></label>
			<div class="wpfnl-fields">
				<select name="wpfnl-rollback" id="wpfnl-rollback">
					<?php
						foreach ( $rollback_versions as $version ) {
							echo "<option value='{$version}'>$version</option>";
						}
					?>
				</select>
				<?php
					echo sprintf(
						'<a data-placeholder-text="' . esc_html__( 'Reinstall', 'wpfnl' ) . ' v{VERSION}" href="#" data-placeholder-url="%s" class="wpfnl-button-spinner wpfnl-rollback-button btn-default">%s</a>',
						wp_nonce_url( admin_url( 'admin-post.php?action=wpfunnels_rollback&version=VERSION' ), 'wpfunnels_rollback' ),
						__( 'Reinstall', 'wpfnl' )
					);
				?>
				<span class="hints wpfnl-error">
					<?php echo __('<b>Warning:</b> Please backup your database before making the rollback.', 'wpfnl'); ?>
				</span>
			</div>
		</div>
		<!-- /field-wrapper -->
	</div>
</div>
