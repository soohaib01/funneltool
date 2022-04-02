

jQuery(function($) {

    'use strict';
    $(document).ready(function ($) {

        if ($("input[name='wpfnl-utm-enable']").prop("checked")){
			$("#wpfnl-utm").show()
		}else{
			$("#wpfnl-utm").hide()
		}
		$('#utm-enable').on('click',function (){
			if ($("input[name='wpfnl-utm-enable']").prop("checked")){
				$("#wpfnl-utm").show()
			}else {
				$("#wpfnl-utm").hide()
            }
        })

        if ($("input[name='wpfnl-gtm-enable']").prop("checked")){
			$("#wpfnl-gtm").show()
		}else{
			$("#wpfnl-gtm").hide()
		}
		$('#gtm-enable').on('click',function (){
			if ($("input[name='wpfnl-gtm-enable']").prop("checked")){
				$("#wpfnl-gtm").show()
			}else {
				$("#wpfnl-gtm").hide()
            }
        })

		if ($("input[name='wpfnl-facebook-pixel-enable']").prop("checked")){
			$("#wpfnl-facebook-pixel").show()
		}else{
			$("#wpfnl-facebook-pixel").hide()
		}
		$('#facebook-pixel-enable').on('click',function (){
			if ($("input[name='wpfnl-facebook-pixel-enable']").prop("checked")){
				$("#wpfnl-facebook-pixel").show()
			}else {
				$("#wpfnl-facebook-pixel").hide()
			}
		})

        var GeneralSettingsHandler = function () {
            $(document.body)
                .on('click', '#wpfnl-update-global-settings', this.updateGeneralSettings)
                .on('click', '#clear-template', this.clearTemplates)
				.on('click', '#clear-transients', this.clearTransients)

            /**
             * settings page Permalink Settings permalink
             * change on keyup
             *
             * @since 1.0.0
             */
            $('code.stepbase').text( $('input[name="wpfnl-permalink-step-base"]').val() );
            $('code.funnelbase').text( $('input[name="wpfnl-permalink-funnel-base"]').val() );

            $('input[name="wpfnl-permalink-step-base"]').keyup(function () {
                if( $(this).val() != ''){
                    $('code.stepbase').text( $(this).val() );
                }else{
                    $('code.stepbase').text( 'wpfunnels' );
                }
            });

            $('input[name="wpfnl-permalink-funnel-base"]').keyup(function () {
                if( $(this).val() != ''){
                    $('code.funnelbase').text( $(this).val() );
                }else{
                    $('code.funnelbase').text( 'step' );
                }
            });

        };


        /**
         * update general settings
         *
         * @param e
         * @since 1.0.0
         */
        GeneralSettingsHandler.prototype.updateGeneralSettings = function (e) {
            e.preventDefault();
            var userRole 		= {};
            var gtmEvents 		= {};
			var fbTrackEvent 	= {};
			var advancedSettings= {};
            $("input[name='analytics-role[]']").map(function(){
                if($(this).prop("checked")){
                    userRole[$(this).data('role')] = 'true';
                }
                return  $(this).prop("checked") ? 1 : 0;
            }).get();


			$("input[name='wpfnl-gtm-events[]']").map(function(){
				if($(this).prop("checked")){
					gtmEvents[$(this).data('role')] = 'true';
                }
				return  $(this).prop("checked") ? 1 : 0;
			}).get();

			$("input[name='wpfnl-facebook_pixel_events[]']").map(function(){
				if($(this).prop("checked")){
					fbTrackEvent[$(this).data('role')] = 'true';
				}
				return  $(this).prop("checked") ? 1 : 0;
			}).get();

            var payload = {
                    'funnel_type'			    : $('#wpfunnels-funnel-type').val(),
                    'builder'				    : $('#wpfunnels-page-builder').val(),
                    'paypal_reference'		    : $('#wpfunnels-paypal-reference').is(':checked') ? 'on' : 'off',
                    'analytics_roles'		    : userRole,
                    'order_bump'			    : $('#wpfunnels-order-bump').is(':checked') ? 'on' : 'off',
                    'ab_testing'			    : $('#wpfunnels-ab-testing').is(':checked') ? 'on' : 'off',
                    'permalink_settings'	    : $("input[name='wpfunnels-set-permalink']:checked").val(),
                    'permalink_step_base'	    : $('#wpfunnels-permalink-step-base').val(),
                    'permalink_funnel_base'	    : $('#wpfunnels-permalink-funnel-base').val(),
                    'set_permalink'			    : $('input[name="wpfunnels-set-permalink"]:checked').val(),
                    'offer_orders'			    : $('input[name="offer-orders"]:checked').val(),
                    'skip_offer_step'                   : $('#wpfnl-skip-offer-step').is(':checked') ? 'on' : 'off',
                    'show_supported_payment_gateway'    : $('#wpfnl-show-supported-payment-gateway').is(':checked') ? 'on' : 'off',
                    'gtm_enable'			    : $('input[name="wpfnl-gtm-enable"]:checked').val(),
                    'gtm_container_id'			: $('#wpfnl-gtm-container-id').val(),
                    'gtm_events'		        : gtmEvents,
                    'enable_fb_pixel'			: $('input[name="wpfnl-facebook-pixel-enable"]:checked').val(),
                    'fb_tracking_code'			: $('#wpfnl-facebook-tracking-code').val(),
                    'fb_tracking_events'		: fbTrackEvent,
                    'utm_enable'			    : $('input[name="wpfnl-utm-enable"]:checked').val(),
                    'utm_source'			    : $('#wpfnl-utm-source').val(),
                    'utm_medium'			    : $('#wpfnl-utm-medium').val(),
                    'utm_campaign'			    : $('#wpfnl-utm-campaign').val(),
                    'utm_content'			    : $('#wpfnl-utm-content').val(),
                };

            var thisLoader = $(this).find('.wpfnl-loader');
            var thisAlert = $(this).siblings('.wpfnl-alert');

            thisLoader.fadeIn(); //show loader

            wpAjaxHelperRequest("update-general-settings", payload)
                .success(function (response) {
                    thisLoader.fadeOut();
                    thisAlert.text('Saved Successfully').addClass('box wpfnl-success').fadeIn();

                    setTimeout(function () {
                        thisAlert.fadeOut().text('').removeClass('box wpfnl-success');
                    }, 2000);

                })
                .error(function (response) {
                    thisLoader.fadeOut();
                    thisAlert.text('Erorr occurred').addClass('box wpfnl-error').fadeIn();
                    setTimeout(function () {
                        thisAlert.fadeOut().text('').removeClass('box wpfnl-error');
                    }, 2000);
                });

        };


        GeneralSettingsHandler.prototype.clearTemplates = function (e) {
            e.preventDefault();
            var sync_icon = $(this).find('.sync-icon');
            var check_icon = $(this).find('.check-icon');
            var thisAlert = $(this).siblings('.wpfnl-alert');

            sync_icon.show();

            var payload = {};
            wpAjaxHelperRequest("clear-templates", payload)
                .success(function (response) {
                    sync_icon.hide();
                    check_icon.fadeIn();
                    thisAlert.text('Successful').addClass('wpfnl-success').fadeIn();

                    setTimeout( function(){
                        check_icon.hide();
                        thisAlert.fadeOut().text('').removeClass('wpfnl-success');
                    }, 4000);
                    $("#wpfnl-update-global-settings").trigger("click");
                })

                .error(function (response) {
                    thisAlert.text('Erorr occurred').addClass('wpfnl-error').fadeIn();

                    setTimeout( function(){
                        thisAlert.fadeOut().text('').removeClass('wpfnl-error');
                    }, 2000);

                    console.log('error');
                });
        };


		/**
		 * clear all transients related to wpfunnels
		 * @param e
		 */
		GeneralSettingsHandler.prototype.clearTransients = function (e) {
			e.preventDefault();
			if ( confirm('Are you sure you want to run this tool?') ) {

				var sync_icon = $(this).find('.sync-icon');
				var check_icon = $(this).find('.check-icon');
				var thisAlert = $(this).siblings('.wpfnl-alert');

				sync_icon.show();

				var payload = {};
				wpAjaxHelperRequest("clear-transient", payload)
					.success(function (response) {
						sync_icon.hide();
						check_icon.fadeIn();
						thisAlert.text('Successful').addClass('wpfnl-success').fadeIn();

						setTimeout( function(){
							check_icon.hide();
							thisAlert.fadeOut().text('').removeClass('wpfnl-success');
						}, 4000);
						$("#wpfnl-update-global-settings").trigger("click");
					})

					.error(function (response) {
						thisAlert.text('Erorr occurred').addClass('wpfnl-error').fadeIn();

						setTimeout( function(){
							thisAlert.fadeOut().text('').removeClass('wpfnl-error');
						}, 2000);

						console.log('error');
					});
			}
        };

        new GeneralSettingsHandler();

    });

});
