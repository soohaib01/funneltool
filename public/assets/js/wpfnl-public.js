(function ($) {

	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).on("click", "#wpfunnels_next_step_controller", function (e) {
		e.preventDefault();
		var ajaxurl = window.wpfnl_obj.ajaxurl;

		// === Detect editor ===//
		var sPageURL = '';
		var sURLVariables = '';
		sPageURL = window.location.search.substring(1);
		sURLVariables = sPageURL.split('=');
		if (sURLVariables[0] == "elementor-preview") {
			console.log("elementor");
		} else {
			$('#wpfnl-next-button-loader').show();
			var products = $(this).attr('data-products');
			var step_id = window.wpfnl_obj.step_id;
			jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				data: {
					action: "wpfnl_next_button_ajax",
					step_id: step_id,
					products: products,
				},
				success: function (response) {
					$('#wpfnl-next-button-loader').hide();
					if (response == 'error') {
						console.log(response);
					} else {
						console.log(response)
						window.location.href = response;
					}
				}
			});
		}
		// === Detect editor ===//
	});

	jQuery(document).ready(function () {

		//-------multistep checkout------
		var is_user_logged_in = window.wpfnl_obj.is_user_logged_in;
		var is_login_reminder = window.wpfnl_obj.is_login_reminder;

		function scroll_to_top(){
			$('html, body').animate({
				scrollTop: $('.wpfnl-multistep, .wpfnl-checkout-form-wpfnl-multistep').offset().top - 100
			}, 800);
		}

		function show_checkout_step( targetID ){
			if( 'login' == targetID ){

				//------for Elementor widget-------
				$('.wpfnl-multistep .woocommerce-form-login-toggle').show();

				$('.wpfnl-multistep .woocommerce-form-coupon').fadeOut();
				$('.wpfnl-multistep #wpfnl_checkout_billing').fadeOut();
				$('.wpfnl-multistep #wpfnl_checkout_shipping').fadeOut();
				$('.wpfnl-multistep #order_review').fadeOut();
				$('.wpfnl-multistep .woocommerce-form-coupon-toggle').fadeOut().removeClass('show-form');

				//------for Gutenberg block-------
				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-login-toggle').show();

				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-coupon').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep #wpfnl_checkout_billing').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep #wpfnl_checkout_shipping').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep #order_review').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-coupon-toggle').fadeOut().removeClass('show-form');

			}else if( 'billing' == targetID ){
				//------for Elementor widget-------
				$('.wpfnl-multistep #wpfnl_checkout_billing').fadeIn();

				$('.wpfnl-multistep .woocommerce-form-login').fadeOut();
				$('.wpfnl-multistep .woocommerce-form-login-toggle').fadeOut();
				$('.wpfnl-multistep .woocommerce-form-coupon-toggle').fadeOut().removeClass('show-form');
				$('.wpfnl-multistep .woocommerce-form-coupon').fadeOut();
				$('.wpfnl-multistep #wpfnl_checkout_shipping').fadeOut();
				$('.wpfnl-multistep #order_review').fadeOut();

				//------for Gutenberg block-------
				$('.wpfnl-checkout-form-wpfnl-multistep #wpfnl_checkout_billing').fadeIn();

				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-login').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-login-toggle').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-coupon-toggle').fadeOut().removeClass('show-form');
				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-coupon').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep #wpfnl_checkout_shipping').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep #order_review').fadeOut();

			}else if( 'shipping' == targetID ){
				//------for Elementor widget-------
				$('.wpfnl-multistep #wpfnl_checkout_shipping').fadeIn();

				$('.wpfnl-multistep .woocommerce-form-login-toggle').fadeOut();
				$('.wpfnl-multistep .woocommerce-form-login').fadeOut();
				$('.wpfnl-multistep .woocommerce-form-coupon-toggle').fadeOut().removeClass('show-form');
				$('.wpfnl-multistep .woocommerce-form-coupon').fadeOut();
				$('.wpfnl-multistep #wpfnl_checkout_billing').fadeOut();
				$('.wpfnl-multistep #order_review').fadeOut();

				//------for Gutenberg block-------
				$('.wpfnl-checkout-form-wpfnl-multistep #wpfnl_checkout_shipping').fadeIn();

				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-login-toggle').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-login').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-coupon-toggle').fadeOut().removeClass('show-form');
				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-coupon').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep #wpfnl_checkout_billing').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep #order_review').fadeOut();

			}else if( 'order-review' == targetID ){
				//------for Elementor widget-------
				$('.wpfnl-multistep #order_review').fadeIn();
				$('.wpfnl-multistep .woocommerce-form-coupon-toggle').fadeIn();

				$('.wpfnl-multistep .woocommerce-form-login-toggle').fadeOut();
				$('.wpfnl-multistep .woocommerce-form-login').fadeOut();
				$('.wpfnl-multistep .woocommerce-form-coupon').fadeOut();
				$('.wpfnl-multistep #wpfnl_checkout_billing').fadeOut();
				$('.wpfnl-multistep #wpfnl_checkout_shipping').fadeOut();

				//------for Gutenberg block-------
				$('.wpfnl-checkout-form-wpfnl-multistep #order_review').fadeIn();
				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-coupon-toggle').fadeIn();

				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-login-toggle').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-login').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep .woocommerce-form-coupon').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep #wpfnl_checkout_billing').fadeOut();
				$('.wpfnl-checkout-form-wpfnl-multistep #wpfnl_checkout_shipping').fadeOut();
			}
		}


		//-------when wizard button click------
		$(".wpfnl-multistep-wizard > li > button").on("click", function () {
			var targetID = $(this).attr('data-target');

			checkoutFieldValidation();
			var isValidate = true;

			if( 'billing' == targetID ){
				// Login validation goes here if needed
			}else if( 'shipping' == targetID ){

				checkoutFieldValidation('#wpfnl_checkout_billing');

				$('#wpfnl_checkout_billing .validate-required').each(function(){

					if($(this).find('.field-required').length){
						isValidate =  false;
					}

				});

				if(isValidate == false){
					return false;
				}

			}else if( 'order-review' == targetID ){
				var is_enabled_dirrerent_address = $('input[name="ship_to_different_address"]').is(":checked");

				//----billing validation---
				checkoutFieldValidation('#wpfnl_checkout_billing');
				$('#wpfnl_checkout_billing .validate-required').each(function(){

					if($(this).find('.field-required').length){
						isValidate =  false;
					}

				});

				if(isValidate == false){
					return false;
				}

				//----shipping validation---
				if ( is_enabled_dirrerent_address == true ){
					checkoutFieldValidation('#wpfnl_checkout_shipping');

					$('#wpfnl_checkout_shipping .validate-required').each(function(){

						if($(this).find('.field-required').length){
							isValidate =  false;
						}

					});

					if(isValidate == false){
						return false;
					}
				} else {
					checkoutFieldValidation('.woocommerce-additional-fields');

					$('.woocommerce-additional-fields .validate-required').each(function(){

						if($(this).find('.field-required').length){
							isValidate =  false;
						}

					});

					if(isValidate == false){
						return false;
					}
				}
			}

			$(this).parent('li').addClass('current');
			$(this).parent('li').prevAll().addClass('completed').removeClass('current');
			$(this).parent('li').nextAll().removeClass('completed current');

			show_checkout_step(targetID);

			if( 'login' == targetID ){
				$('.wpfnl-multistep-navigation button.previous').attr('data-target', '').prop('disabled', true);
				$('.wpfnl-multistep-navigation button.next').attr('data-target', 'billing').prop('disabled', false);

			}else if( 'billing' == targetID ){
				if( is_user_logged_in ){
					$('.wpfnl-multistep-navigation button.previous').attr('data-target', 'login').prop('disabled', true);
				}else{
					if( 'yes' === is_login_reminder ){
						$('.wpfnl-multistep-navigation button.previous').attr('data-target', 'login').prop('disabled', false);
					}else{
						$('.wpfnl-multistep-navigation button.previous').attr('data-target', 'login').prop('disabled', true);
					}
				}

				$('.wpfnl-multistep-navigation button.next').attr('data-target', 'shipping').prop('disabled', false);

			}else if( 'shipping' == targetID ){
				$('.wpfnl-multistep-navigation button.previous').attr('data-target', 'billing').prop('disabled', false);
				$('.wpfnl-multistep-navigation button.next').attr('data-target', 'order-review').prop('disabled', false);

			}else if( 'order-review' == targetID ){
				$('.wpfnl-multistep-navigation button.previous').attr('data-target', 'shipping').prop('disabled', false);
				$('.wpfnl-multistep-navigation button.next').attr('data-target', '').prop('disabled', true);

			}

		});

		//-------when next step button click------
		$(".wpfnl-multistep-navigation button.next").on("click", function () {
			var targetID = $(this).attr('data-target');

			var isValidate = true;

			if( 'billing' == targetID ){
				// Login validation goes here if needed
			}else if( 'shipping' == targetID ){
				checkoutFieldValidation('#wpfnl_checkout_billing');

				$('#wpfnl_checkout_billing .validate-required').each(function(){

					if($(this).find('.field-required').length){
						isValidate =  false;
					}

				});

				if(isValidate == false){
					return false;
				}

			}else if( 'order-review' == targetID ){
				var is_enabled_dirrerent_address = $('input[name="ship_to_different_address"]').is(":checked");

				if ( is_enabled_dirrerent_address == true ){
					checkoutFieldValidation('#wpfnl_checkout_shipping');

					$('#wpfnl_checkout_shipping .validate-required').each(function(){

						if($(this).find('.field-required').length){
							isValidate =  false;
						}

					});

					if(isValidate == false){
						return false;
					}
				} else {
					checkoutFieldValidation('.woocommerce-additional-fields');

					$('.woocommerce-additional-fields .validate-required').each(function(){

						if($(this).find('.field-required').length){
							isValidate =  false;
						}

					});

					if(isValidate == false){
						return false;
					}
				}

			}

			scroll_to_top();

			$('.wpfnl-multistep-wizard > li.'+targetID).addClass('current');
			$('.wpfnl-multistep-wizard > li.'+targetID).prevAll().addClass('completed').removeClass('current');
			$('.wpfnl-multistep-wizard > li.'+targetID).nextAll().removeClass('completed current');

			show_checkout_step(targetID);

			if( 'billing' == targetID ){
				$(this).siblings().attr('data-target', 'login').prop('disabled', false);
				$(this).attr('data-target', 'shipping');

			}else if( 'shipping' == targetID ){
				$(this).siblings().attr('data-target', 'billing').prop('disabled', false);
				$(this).attr('data-target', 'order-review');

			}else if( 'order-review' == targetID ){
				$(this).siblings().attr('data-target', 'shipping');
				$(this).prop('disabled', true);
			}
		});

		function checkoutFieldValidation(step){

			$(step+' .validate-required input').each(function(){
				var fieldValue = $(this).val();
				if( !fieldValue ){
					$(this).parent().find('.field-required').remove();
					$(this).parent().append('<span class="field-required">Field required</span>');
				} else if( fieldValue){
					$(this).parent().find('.field-required').remove();
				}
            })

			$(step+' .validate-required select').each(function(){
				var fieldValue = $(this).children('option:selected').val();
				if( !fieldValue ){
					$(this).parent().find('.field-required').remove();
					$(this).parent().append('<span class="field-required">Field required</span>');
				} else if( fieldValue){
					$(this).parent().find('.field-required').remove();
				}
            })

			$(step+' .validate-required input[type="checkbox"]').each(function(){
				var fieldValue = $(this).is(":checked");
				if( !fieldValue ){
					$(this).parent().find('.field-required').remove();
					$(this).parent().append('<span class="field-required">Field required</span>');
				} else if( fieldValue){
					$(this).parent().find('.field-required').remove();
				}
            })

		}

		//-------when previous button click------
		$(".wpfnl-multistep-navigation button.previous").on("click", function () {
			var targetID = $(this).attr('data-target');

			scroll_to_top();

			$('.wpfnl-multistep-wizard > li.'+targetID).addClass('current');
			$('.wpfnl-multistep-wizard > li.'+targetID).prevAll().addClass('completed').removeClass('current');
			$('.wpfnl-multistep-wizard > li.'+targetID).nextAll().removeClass('completed current');

			show_checkout_step(targetID);

			if( 'login' == targetID ){
				$(this).attr('data-target', '').prop('disabled', true);
				$(this).siblings().attr('data-target', 'billing').prop('disabled', false);

			}else if( 'billing' == targetID ){
				if( is_user_logged_in ){
					$(this).attr('data-target', 'login').prop('disabled', true);
				}else{
					if( 'yes' === is_login_reminder ){
						$(this).attr('data-target', 'login').prop('disabled', false);
					}else{
						$(this).attr('data-target', 'login').prop('disabled', true);
					}
				}

				$(this).siblings().attr('data-target', 'shipping').prop('disabled', false);

			}else if( 'shipping' == targetID ){
				$(this).attr('data-target', 'billing');
				$(this).siblings().attr('data-target', 'order-review').prop('disabled', false);

			}
		});
		//-------end multistep checkout------


		/**
		 *
		 * @param response
		 */
		var wpf_remove_spinner = function (response ) {
			if ( $( '.wc_payment_methods' ).length ) {
				if (response.hasOwnProperty('wc_custom_fragments')) {
					// update the fragments
					if ( response.hasOwnProperty( 'fragments' ) ) {
						$.each( response.fragments, function ( key, value ) {
							$( key ).replaceWith( value );
						} );
					}

					if ( parseFloat( response.cart_total ) <= 0 ) {
						$( 'body' ).trigger( 'update_checkout' );
					}
				}
			} else {
				$( 'body' ).trigger( 'update_checkout' );
			}
		}
		$(document).on('change', '.wpfnl-update-variation', function(e){
			e.preventDefault();
			var ajaxurl = window.wpfnl_obj.ajaxurl;

			let variations = [],
				i = 0,
				thisProductID = $(this).data('product-id');
			$('.wpfnl-update-variation').each(function() {
				if( thisProductID == $(this).data('product-id') ){
					variations[i] = {
						'attr' 			: $(this).data('attr'),
						'product_id' 	: $(this).data('product-id'),
						'variation_id' 	: $(this).data('variation-id'),
						'quantity' 		: $(this).data('quantity'),
						'value' 		: $(this).val().trim(),
					}
					i++;
				}
			});
			console.log(variations);
			jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				data: {
					action			: "wpfnl_update_variation",
					variations  	: variations,
				},
				success: function (response) {
					$( 'body' ).trigger( 'update_checkout' );
				}
			});
		})
		$(document).on("change", ".wpfnl-order-bump-cb", function (e) {
			e.preventDefault();

			$('.oderbump-loader').css('display', 'flex');

			var ajaxurl = window.wpfnl_obj.ajaxurl;
			var step_id = $(this).attr('data-step');
			var quantity = $(this).attr('data-quantity');
			var product = $(this).val();
			let checker = false,
				main_products = $(this).attr('data-main-products');

			if ( $(this).prop("checked") == true ) {
				checker = true;
			} else if ( $(this).prop("checked") == false ) {
				checker = false;
			}

			if(checker) {
				$( '[name=_wpfunnels_order_bump_product]' ).val(product);
			} else {
				$( '[name=_wpfunnels_order_bump_product]' ).val('');
			}

			jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				data: {
					action			: "wpfnl_order_bump_ajax",
					step_id			: step_id,
					quantity		: quantity,
					product			: product,
					checker			: checker,
					main_products 	: main_products
				},
				success: function (response) {
					wpf_remove_spinner( response );
					$('.oderbump-loader').css('display', 'none');
				}
			});
		});

		//----show order bump modal-----
		var inner_height = $('.wpfnl-order-bump__popup-wrapper').innerHeight() + 30;
		$('.wpfnl-order-bump__popup-wrapper').css('top', '-' + inner_height + 'px');

		$(window).on('load', function () {
			setTimeout(function () {
				$('.wpfnl-order-bump__popup-wrapper').addClass('show').css('top', '30px');
			}, 2000);

		});

		$('.close-order-bump').on('click', function () {
			$('.wpfnl-order-bump__popup-wrapper').removeClass('show').css('top', '-' + inner_height + 'px');
		});


		//--------woocommerce checkout page coupon toggle add class-----------
		$('.wpfnl-checkout .woocommerce-form-coupon-toggle .showcoupon').on('click', function(){
			$(this).parents('.woocommerce-form-coupon-toggle').toggleClass('show-form');
		});


		/**
		 * Elementor optin form submission ajax
		 */
		$('.wpfnl-elementor-optin-form-wrapper form').on('submit', function (e) {
			e.preventDefault();
			$('.wpfnl-elementor-optin-form-wrapper .response').css('display','none');
			if( ( $('#wpfnl-email').val() == '' && $('#wpfnl-email').prop('required') ) || ($('#wpfnl-last-name').val() == '' && $('#wpfnl-last-name').prop('required') ) || ( $('#wpfnl-first-name').val() == '' && $('#wpfnl-first-name').prop('required')) || ( $('#wpfnl-acceptance_checkbox').val() == '' && $('#wpfnl-acceptance_checkbox').prop('required')) || ( $('#wpfnl-phone').val() == '' && $('#wpfnl-phone').prop('required') ) ){
				$('.wpfnl-elementor-optin-form-wrapper .response').css('color', 'red');
				$('.wpfnl-elementor-optin-form-wrapper .response').text('Please fill all the required fields');
				$('.wpfnl-elementor-optin-form-wrapper .response').css('display','flex');
				return false;
			}

			var ajaxurl 	= wpfnl_obj.ajaxurl,
				security 	= wpfnl_obj.optin_form_nonce,
				step_id 	= wpfnl_obj.step_id,
				email		= '',
				data   		= {
					action 		: 'wpfnl_optin_submission',
					security 	: security,
					step_id 	: step_id,
					postData 	: $(this).serialize()
				},
				postData = data.postData.split('&'),
				form 		= $(this)
			form.find('.wpfnl-loader').show();
			$.ajax({
				type	: "POST",
				url		: ajaxurl,
				data	: data,
				dataType: 'json',
				success	: function (response) {
					if(response.success) {
						form.hide();
						$('.wpfnl-elementor-optin-form-wrapper .response').fadeIn('fast');
						$('.wpfnl-elementor-optin-form-wrapper .response').css('color', 'green');
						$('.wpfnl-elementor-optin-form-wrapper .response').text(response.notification_text);
						if( response.redirect ) {
							setTimeout(function() {
								window.location.href = response.redirect_url;
							}, 1000)
						}
					}
				}
			});
		})


		/**
		 * Shortcode optin form submission ajax
		 */
		 $('.wpfnl-shortcode-optin-form-wrapper form').on('submit', function (e) {
			e.preventDefault();
			optinSubmit();
		})

		/**
		 * Divi optin form submission ajax
		 */
		 $('#wpfunnels_optin-button').on('click', function (e) {
			e.preventDefault();
			optinSubmit();
		})

		/**
		 * Optin form submission for Shortcode and Divi
		 */
		function optinSubmit(){
			$('.wpfnl-shortcode-optin-form-wrapper .response').css('display','none');
			if( ( $('#wpfnl-email').val() == '' ) || ($('#wpfnl-last-name').val() == '' && $('#wpfnl-last-name').prop('required') ) || ( $('#wpfnl-first-name').val() == '' && $('#wpfnl-first-name').prop('required')) || ( $('#wpfnl-acceptance_checkbox').val() == '' && $('#wpfnl-acceptance_checkbox').prop('required')) || ( $('#wpfnl-phone').val() == '' && $('#wpfnl-phone').prop('required') ) ){

				$('.wpfnl-shortcode-optin-form-wrapper .response').css('color', 'red');
				if( $('#wpfnl-email').val() == '' ){
					$('.wpfnl-shortcode-optin-form-wrapper .response').text('Email field is required');
				}else{
					$('.wpfnl-shortcode-optin-form-wrapper .response').text('Please fill all the required fields');
				}

				$('.wpfnl-shortcode-optin-form-wrapper .response').css('display','flex');
				return false;
			}

			var ajaxurl 	= wpfnl_obj.ajaxurl,
				security 	= wpfnl_obj.optin_form_nonce,
				step_id 	= wpfnl_obj.step_id,
				email		= '',
				data   		= {
					action 		: 'wpfnl_shortcode_optin_submission',
					security 	: security,
					step_id 	: step_id,
					postData 	: $('.wpfnl-shortcode-optin-form-wrapper form').serialize()
				},
				postData = data.postData.split('&'),
				form 		= $('.wpfnl-shortcode-optin-form-wrapper form')
			form.find('.wpfnl-loader').css('display','inline-block');
			$.ajax({
				type	: "POST",
				url		: ajaxurl,
				data	: data,
				dataType: 'json',
				success	: function (response) {
					if(response.success) {
						form.hide();
						$('.wpfnl-shortcode-optin-form-wrapper .response').fadeIn('fast');
						$('.wpfnl-shortcode-optin-form-wrapper .response').css('color', 'green');
						$('.wpfnl-shortcode-optin-form-wrapper .response').text(response.notification_text);
						if( response.redirect ) {
							setTimeout(function() {
								window.location.href = response.redirect_url;
							}, 1000)
						}
					}
				}
			});
		}


		/**
		 * Gutenberg optin form submission ajax
		 */
		 $('.wpfnl-gutenberg-optin-form-wrapper form').on('submit', function (e) {
			e.preventDefault();
			$('.wpfnl-gutenberg-optin-form-wrapper .response').css('display','none');
			if( ($('#wpfnl-email').val() == '' && $('#wpfnl-email').prop('required') ) || ($('#wpfnl-last-name').val() == '' && $('#wpfnl-last-name').prop('required') ) || ( $('#wpfnl-first-name').val() == '' && $('#wpfnl-first-name').prop('required')) || ( $('#wpfnl-acceptance_checkbox').val() == '' && $('#wpfnl-acceptance_checkbox').prop('required')) || ( $('#wpfnl-phone').val() == '' && $('#wpfnl-phone').prop('required') ) ){
				$('.wpfnl-gutenberg-optin-form-wrapper .response').css('color', 'red');
				$('.wpfnl-gutenberg-optin-form-wrapper .response').text('Please fill all the required fields');
				$('.wpfnl-gutenberg-optin-form-wrapper .response').css('display','flex');
				// setTimeout(function() {
				// 	$('.wpfnl-gutenberg-optin-form-wrapper .response').css('display','none');
				//  }, 2000);

				return false;
			}
			$('.wpfnl-optin-form .wpfnl-optin-form-group .btn-default').addClass('show-loader');
			var ajaxurl 	= wpfnl_obj.ajaxurl,
				security 	= wpfnl_obj.optin_form_nonce,
				step_id 	= wpfnl_obj.step_id,
				email		= '',
				data   		= {
					action 		: 'wpfnl_gutenberg_optin_submission',
					security 	: security,
					step_id 	: step_id,
					postData 	: $(this).serialize()
				},
				form 		= $(this);
			form.find('.wpfnl-loader').show();
			$.ajax({
				type	: "POST",
				url		: ajaxurl,
				data	: data,
				dataType: 'json',
				success	: function (response) {

					if(response.success) {
						form.hide();
						let post_action = response.post_action;
						$('.wpfnl-optin-form .wpfnl-optin-form-group .btn-default').removeClass('show-loader');
						$('.wpfnl-gutenberg-optin-form-wrapper .response').fadeIn('fast');
						$('.wpfnl-gutenberg-optin-form-wrapper .response').css('color', 'green');
						$('.wpfnl-gutenberg-optin-form-wrapper .response').text(response.notification_text);
						if( 'notification' !== post_action ) {
							setTimeout(function() {
								window.location.href = response.redirect_url;
							}, 1000)
						}
					}
				}
			});
		})
	});

})(jQuery);
