/* global jQuery, WPFunnelCommonJS */
( function( $ ) {
	'use strict';
	var WPFunnelCommonJS = {
		installPlugin: function(e) {
			e.preventDefault();
			$('.wpfnl-notice .wpfnl-notice-button .notice-loader').css('display', 'inline-block');

			let slug = $(this).attr('data-slug');
			wp.updates.queue.push( {
				action: 'install-plugin', // Required action.
				data: {
					slug: slug,
				},
			} );
			wp.updates.queueChecker();
		},

		installSuccess: function(e, response) {
			e.preventDefault();
			var pluginFile = 'woocommerce/woocommerce.php';
			$.ajax( {
				url: window.wpfnl_common_vars.ajaxurl,
				type: 'POST',
				data: {
					action			: 'plugin_install_success',
					plugin_init		: pluginFile,
					security		: window.wpfnl_common_vars.wpfnl_activate_plugin_nonce,
				},
				success: function(response) {
					if(response.success) {
						window.location.reload();
					}
				}
			}).done( function ( request, status, XHR ) {
				if(status.success) {
					window.location.reload();
				}
			});
		},

		installError: function(e, response) {
			e.preventDefault();
			$('.wpfnl-notice-message').html(response.errorMessage);
			$('.wpfnl-notice .wpfnl-notice-button .notice-loader').css('display', 'none');
		},

		activatePlugin: function(e) {
			e.preventDefault();
			var pluginFile = 'woocommerce/woocommerce.php';
			$.ajax( {
				url: window.wpfnl_common_vars.ajaxurl,
				type: 'POST',
				data: {
					action			: 'plugin_install_success',
					plugin_init		: pluginFile,
					security		: window.wpfnl_common_vars.wpfnl_activate_plugin_nonce,
				},
				success: function(response) {
					if(response.success) {
						window.location.reload();
					}
				}
			}).done( function ( request, status, XHR ) {
				if(status.data.success) {
					window.location.reload();
				}
			});
		},

		init: function() {
			$(document)
				.on('click', '#wpfnl-install-plugin', this.installPlugin)
				.on('click', '#wpfnl-activate-plugin', this.activatePlugin)
				.on('wp-plugin-install-success', this.installSuccess)
				.on('wp-plugin-install-error', this.installError)
		},
	}


	$( function() {
		WPFunnelCommonJS.init();
	} );

}( jQuery ) );
