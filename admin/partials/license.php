<?php
/**
 * This file will be deleted on future. All license related functionalities will be moved
 * to the pro version
 */

if(is_multisite()) {
	$license 		= get_option( 'wpfunnels_pro_license_key' );
	$status  		= get_option( 'wpfunnels_pro_license_status' );
	$status_data  	= get_option( 'wpfunnels_pro_licence_data' );
} else {
	$license 		= get_option( 'wpfunnels_pro_license_key' );
	$status  		= get_option( 'wpfunnels_pro_license_status' );
	$status_data  	= get_option( 'wpfunnels_pro_licence_data' );
}
if( version_compare( WPFNL_PRO_VERSION, '1.2.9' , '>' ) ) {
	$addon          = \WPFunnelsPro\Addons::getInstance();
	$addon_lists    = $addon->get_addons();
} else {

	function wpf_check_plugin_installed( $plugin_slug ) {
		if ( ! function_exists( 'get_plugins' ) || ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$installed_plugins = get_plugins();
		return array_key_exists( $plugin_slug, $installed_plugins ) || in_array( $plugin_slug, $installed_plugins, true );
	}


	function wpf_get_button_text( $slug ) {
		if ( ! function_exists( 'get_plugins' ) || ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		if ( !wpf_check_plugin_installed( $slug ) ) {
			return __('Get the add-on', 'wpfnl');
		}
		if ( is_plugin_active( $slug ) ) {
			return __('Activated', 'wpfnl');
		}
		return __('Please enable', 'wpfnl-pro');
	}

	if ( ! function_exists( 'get_plugins' ) || ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$installed_plugins = get_plugins();
	$addon_lists    = array(
		'global_funnel' => array(
			'key'           => 'gbf',
			'name'          => __( 'Global Funnels for WooCommerce', 'wpfnl-pro' ),
			'description'   => __( 'Send buyers into a sales funnel directly from your WooCommerce store.', 'wpfnl-pro' ),
			'icon'          => WPFNL_DIR . 'admin/partials/icons/global-funnel-icon.php',
			'slug'          => 'wpfunnels-pro-gbf/wpfnl-pro-gb.php',
			'version'       => '',
			'plugin_status' => wpf_check_plugin_installed('wpfunnels-pro-gbf/wpfnl-pro-gb.php') ? 'installed' : 'not-installed',
			'license_key'   => '',
			'license_data'  => '',
			'license_status'=> '',
			'product_id'    => '',
			'dependency'    => 'pro',
			'btn_txt'       => wpf_get_button_text('wpfunnels-pro-gbf/wpfnl-pro-gb.php'),
			'btn_link'      => wpf_check_plugin_installed('wpfunnels-pro-gbf/wpfnl-pro-gb.php') ? admin_url('plugins.php') : 'https://getwpfunnels.com/pricing/',
		)
	);

}

?>
<div class="wpfnl wpfnl-license-page">
	<div class="wpfnl-license-wrapper">
		<div class="wpfnl-license-filed">
			<div class="field-area">
				<div class="field-header">
					<div class="single-field product-title">Plugin / Addons</div>
					<div class="single-field input-field">Licence Input</div>
					<div class="single-field btn-area"></div>
				</div>

				<form name="wpfnl-license" id="wpfnl-license" action="options.php" method="post">
					<div class="input-field-area">
						<div class="single-field product-title">
							<span class="addon-icon">
								<svg width="38" height="28" viewBox="0 0 38 28" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M7.01532 18H31.9847L34 11H5L7.01532 18Z" fill="#EE8134"/>
									<path d="M11.9621 27.2975C12.0923 27.7154 12.4792 28 12.9169 28H26.0831C26.5208 28 26.9077 27.7154 27.0379 27.2975L29 21H10L11.9621 27.2975Z" fill="#6E42D3"/>
									<path d="M37.8161 0.65986C37.61 0.247888 37.2609 0 36.8867 0H1.11326C0.739128 0 0.390003 0.247888 0.183972 0.65986C-0.0220592 1.07193 -0.0573873 1.59277 0.0898627 2.04655L1.69781 7H36.3022L37.9102 2.04655C38.0574 1.59287 38.022 1.07193 37.8161 0.65986Z" fill="#6E42D3"/>
								</svg>
							</span>
							<div class="icon-info">
								<h3>WPFunnels</h3>
							</div>
						</div>

						<div class="single-field input-field">
							<?php if( $status !== false && $status == 'active' ) { ?>
								<input id="wpfunnels_license_key" name="wpfunnels_license_key" type="password" placeholder="Enter your license code" value="<?php esc_attr_e( $license ); ?>"/>
							<?php } else { ?>
								<input id="wpfunnels_license_key" name="wpfunnels_license_key" type="password" placeholder="Enter your license code" value="<?php esc_attr_e( $license ); ?>"/>
							<?php } ?>

							<span class="license-status">
								<?php
								if( 'active' === $status ) {
									$start_date = isset($status_data['start_date']) ? $status_data['start_date'] : '';
									$end_date 	= isset($status_data['end_date']) ? $status_data['end_date'] : '';
									if ( $end_date ) {
										echo sprintf( '%s %s', __('Your license key will be expired on ', 'wpfnl'), $end_date );
									}
								}
								?>
							</span>
						</div>

						<div class="single-field btn-area">
							<?php if( $status !== false && $status == 'active' ) { ?>
								<?php wp_nonce_field( 'wpfunnels_pro_licensing_nonce', 'wpfunnels_pro_licensing_nonce' ); ?>
								<input type="submit" class="btn-default" name="wpfunnels_pro_license_deactivate" value="<?php _e('Deactivate License', 'wpfnl'); ?>" required/>
							<?php } else {
								wp_nonce_field( 'wpfunnels_pro_licensing_nonce', 'wpfunnels_pro_licensing_nonce' ); ?>
								<input type="submit" class="btn-default" name="wpfunnels_pro_license_activate" value="<?php _e('Activate License', 'wpfnl'); ?>"/>
							<?php } ?>
						</div>
					</div>
				</form>

				<?php if ( is_array($addon_lists) && !empty($addon_lists) ) {?>
					<div class="addons-license">
						<?php foreach ( $addon_lists as $key => $addon ) { ?>
							<form name="wpfnl-<?php echo $addon['key'] ?>-license" id="wpfnl-<?php echo $addon['key'] ?>-license" action="options.php" method="post">
								<div class="single-addons <?php echo $addon['plugin_status'] === 'active' ? '' : 'not-get-addons'; ?>">
									<div class="input-field-area">
										<div class="single-field product-title">
                                        <span class="addon-icon">
                                            <?php include WPFNL_DIR . '/admin/partials/icons/global-funnel-icon.php'; ?>
                                        </span>
											<div class="icon-info">
												<h3><?php echo $addon['name']; ?></h3>
												<span class="license-status"><?php echo $addon['description']; ?></span>
											</div>
										</div>
										<?php
										//                                        if( $addon['plugin_status'] === 'active' ) { ?>
										<!--                                            <div class="single-field input-field">-->
										<!--                                                --><?php //if( $addon['license_status'] == 'active' ) {
										//                                                    ?>
										<!--                                                    <input id="wpfunnels_--><?php //echo $addon['key'] ?><!--_license_key" name="wpfunnels_--><?php //echo $addon['key'] ?><!--_license_key" type="password" placeholder="--><?php //echo __('Enter your license code', 'wpfnl-pro'); ?><!--" value="--><?php //esc_attr_e( $addon['license_key'] ); ?><!--"/>-->
										<!--                                                --><?php //} else { ?>
										<!--                                                    <input id="wpfunnels_--><?php //echo $addon['key'] ?><!--_license_key" name="wpfunnels_--><?php //echo $addon['key'] ?><!--_license_key" type="password" placeholder="--><?php //echo __('Enter your license code', 'wpfnl-pro'); ?><!--" value="--><?php //esc_attr_e( $addon['license_key'] ); ?><!--"/>-->
										<!--                                                --><?php //} ?>
										<!--                                            </div>-->
										<!--                                        --><?php //}
										//                                        ?>
										<div class="single-field btn-area">
											<?php if($addon['plugin_status'] === 'active') { ?>
												<?php if( $addon['license_status'] == 'active' ) { ?>
													<?php wp_nonce_field( "wpfunnels_pro_{$addon['key']}_licensing_nonce", "wpfunnels_pro_{$addon['key']}_licensing_nonce" ); ?>
													<input type="submit" class="btn-default" name="wpfunnels_pro_<?php echo $addon['key']; ?>_license_deactivate" value="<?php _e('Deactivate License', 'wpfnl'); ?>" required/>
												<?php } else {
													wp_nonce_field( "wpfunnels_pro_{$addon['key']}_licensing_nonce", "wpfunnels_pro_{$addon['key']}_licensing_nonce" ); ?>
													<input type="submit" class="btn-default" name="wpfunnels_pro_<?php echo $addon['key']; ?>_license_activate" value="<?php _e('Activate License', 'wpfnl'); ?>"/>
												<?php } ?>
											<?php } else { ?>
												<a target="_blank" href="<?php echo $addon['btn_link']; ?>" class="btn-default"><?php echo $addon['btn_txt']; ?></a>
											<?php }?>
										</div>
									</div>
								</div>
							</form>

						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>

		<div class="promo-text-area">
			<div class="single-area manage-license-area">
                <span class="icon">
					<?php include WPFNL_DIR . '/admin/partials/icons/manage-license-icon.php'; ?>
                </span>
				<a href="https://useraccount.getwpfunnels.com/orders/" target="_blank" class="btn-default manage-license"><?php echo __('manage license', 'wpfnl'); ?></a>
			</div>

			<div class="single-area">
                <span class="icon">
					<?php include WPFNL_DIR . '/admin/partials/icons/repeat-icon.php'; ?>
                </span>
				<h4><?php echo __('Stay Updated', 'wpfnl'); ?></h4>
				<p><?php echo __('Update the plugin right from your WordPress Dashboard.', 'wpfnl'); ?></p>
			</div>

			<div class="single-area">
                <span class="icon">
					<?php include WPFNL_DIR . '/admin/partials/icons/star-icon.php'; ?>
                </span>
				<h4><?php echo __('Premium Support', 'wpfnl'); ?></h4>
				<p><?php echo __('Supported by professional and courteous staff.', 'wpfnl'); ?></p>
			</div>
		</div>
		<!-- /promo-text-area -->
	</div>

	<div class="cl-doc-row">
		<div class="single-col">
            <span class="icon">
				<?php include WPFNL_DIR . '/admin/partials/icons/doc-icon2.php'; ?>
            </span>
			<h4 class="title"><?php echo __('Documentation', 'wpfnl'); ?></h4>
			<p><?php echo __('Get detailed guide and documentation on WP Funnels and create highly converting sales funnels easily.', 'wpfnl'); ?></p>
			<a href="https://getwpfunnels.com/docs/" class="btn-default" target="_blank"><?php echo __('Documentation', 'wpfnl'); ?></a>
		</div>

		<div class="single-col">
            <span class="icon">
				<?php include WPFNL_DIR . '/admin/partials/icons/support-icon.php'; ?>
            </span>
			<h4 class="title"><?php echo __('Support', 'wpfnl'); ?></h4>
			<p><?php echo __('Can’t find solution with our documentation? Just post a ticket. Our professional team is here to solve your problems.', 'wpfnl'); ?></p>
			<a href="https://wordpress.org/support/plugin/wpfunnels/" target="_blank" class="btn-default"><?php echo __('Post A Ticket', 'wpfnl'); ?></a>
		</div>

		<div class="single-col">
            <span class="icon">
				<?php include WPFNL_DIR . '/admin/partials/icons/heart-icon.php'; ?>
            </span>
			<h4 class="title"><?php echo __('Show Your Love', 'wpfnl'); ?></h4>
			<p><?php echo __('We love to have you in WPFunnels family. Take your 2 minutes to review  and speed the love to encourage us to keep it going.', 'wpfnl'); ?></p>
			<a href="https://wordpress.org/plugins/wpfunnels/#reviews" class="btn-default"  target="_blank"><?php echo __('Leave a Review', 'wpfnl'); ?></a>
		</div>
	</div>
</div>
