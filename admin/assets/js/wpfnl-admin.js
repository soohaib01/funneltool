(function($) {
    'use strict';

    var product_option = window.WPFunnelVars.products;

    /**
     * All of the code for your admin-facing JavaScript source
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


    jQuery(document).ready(function($) {

        //date format
        function PresentDateForm(date){
            var date = new Date(date),
                day = date.getDate(),
                month = date.getMonth() + 1, //Months are zero based
                year = date.getFullYear(),
                date = new Date(year+'-'+month+'-'+day).toDateString();
                return date;
        }

        function CompareDateForm(date){
            var date = new Date(date),
			    day  = date.getDate(),
			    month = date.getMonth() + 1, //Months are zero based
			    year = date.getFullYear()-1,
                date = new Date(year+'-'+month+'-'+day).toDateString();
                return date;
        }

        // -------analytics line chart filter-------
        function WpfnlDatePicker() {
            var dateFormat = "M d, yy",
            from = $( "#analytics-from" ).datepicker( {
                    dateFormat: "M d, yy",
                    numberOfMonths: 1,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "2021:2040",
                    beforeShow:function(textbox, instance){
                        $('.div-to-append-calender').append($('#ui-datepicker-div'));
                    },
                    onSelect: function(dateText) {
                        var presentDate = PresentDateForm(this.value);
                        $('.select-from-date').text(presentDate.slice(4));
                        var compareDate = CompareDateForm(this.value);
                        $('.compare-from-date').text(compareDate.slice(4));

                        //for highlighting select date
                        $("#ui-datepicker-div").find(".ui-state-default").removeClass("ui-state-active");
                        var date = new Date(this.value),
                            day = date.getDate(),
                            month = date.getMonth(),
                            year = date.getFullYear();
                        $("#ui-datepicker-div").find(".ui-state-default").each(function(index, obj){
                            if($(obj).text() == day && $(obj).parent().data('month') == month && $(obj).parent().data('year') == year){
                                $(obj).addClass("ui-state-active");
                            }
                        });
                    }
                } ).datepicker( "setDate", '-6d' ).on( "change", function () {
                    to.datepicker( "option", "minDate" );
                } ),

            to = $( "#analytics-to" ).datepicker( {
                dateFormat: "M d, yy",
                numberOfMonths: 1,
                changeMonth: true,
                changeYear: true,
                yearRange: "2021:2040",
                beforeShow:function(textbox, instance){
                    $('.div-to-append-calender').append($('#ui-datepicker-div'));

                },
                onSelect: function(dateText) {
                    var presentDate = PresentDateForm(this.value);
                    $('.select-to-date').text(presentDate.slice(4));

                    var compareDate = CompareDateForm(this.value);
                    $('.compare-to-date').text(compareDate.slice(4));

                    //for highlighting select date
                    $("#ui-datepicker-div").find(".ui-state-default").removeClass("ui-state-active");
                    var date = new Date(this.value),
                        day = date.getDate(),
                        month = date.getMonth(),
                        year = date.getFullYear();
                    $("#ui-datepicker-div").find(".ui-state-default").each(function(index, obj){
                        if($(obj).text() == day && $(obj).parent().data('month') == month && $(obj).parent().data('year') == year){
                            $(obj).addClass("ui-state-active");
                        }
                    });


                }
            } ).datepicker( "setDate", new Date() ).on( "change", function () {
                    from.datepicker( "option", "maxDate" );
                } );

            $('.chart-calendar-filter .vs').hide();
            $('.chart-calendar-filter .compare-date').hide();
            var date = new Date($( "#analytics-from" ).val());
            var day = date.getDate();
            var month = date.getMonth() + 1; //Months are zero based
            var year = date.getFullYear();
            var date = new Date(year+'-'+month+'-'+day).toDateString();

            $('.select-from-date').text(date.slice(4));

            var date = new Date($( "#analytics-to" ).val());
            var day = date.getDate();
            var month = date.getMonth() + 1; //Months are zero based
            var year = date.getFullYear();
            var date = new Date(year+'-'+month+'-'+day).toDateString();


            $('.select-to-date').text(date.slice(4));

            var date = new Date($( "#analytics-from" ).val());
			var day = date.getDate();
			var month = date.getMonth() + 1; //Months are zero based
			var year = date.getFullYear()-1;
            var date = new Date(year+'-'+month+'-'+day).toDateString();

            $('.compare-from-date').text(date.slice(4));

            var date = new Date($( "#analytics-to" ).val());
			var day = date.getDate();
			var month = date.getMonth() + 1; //Months are zero based
			var year = date.getFullYear()-1;
            var date = new Date(year+'-'+month+'-'+day).toDateString();

            $('.compare-to-date').text(date.slice(4));

        }

        $(".wpfnl_muliple_select").select2();
        // edit field error handle
        $('.wpfnl-section-type-error').hide();
        $('.wpfnl-field-type-error').hide();
        $('.wpfnl-name-type-error').hide();
        $('.wpfnl-label-type-error').hide();


        //--------analytics calendar filter dropdown----------
        $(document).on("click", ".filter-selectbox", function(e) {
            e.stopPropagation();
            $(this).toggleClass('show-calendar');
        });
        $(document).on("click", ".calendar-dorpdown", function(e) {
            e.stopPropagation();
        });
        $(document).on("click", "body, .calendar-dorpdown .btn-list .update", function() {
            $('.filter-selectbox').removeClass('show-calendar');
        });


        //--------analytics filter header dropdown----------
        $(document).on("click", ".funnel-steps > li", function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('active');
            $(this).siblings('li').removeClass('active');
            $(this).children('.wpfnl-dropdown').toggleClass('show-dropdown');
            $(this).siblings('li').children('.wpfnl-dropdown').removeClass('show-dropdown');
        });
        $(document).on("click", "body", function() {
            $('.chart-header .funnel-steps .wpfnl-dropdown').removeClass('show-dropdown');
        });


        // -------show/hide step stats info-------
        $(document).on("change", "#stats", function(e) {
            if( $(this).is(':checked') ){
                $('.node-body .node-stats').show();
            }else{
                $('.node-body .node-stats').hide();
            }
        });

        var FunnelHandler = function() {
            this.initStepSortable();
            this.initProductSortable();
            $(document.body)
                .on('submit', '#wpfnl-change-funnel-name', this.changeFunnelName)

            $('.wpfnl-duplicate-funnel').on('click', this.cloneFunnel);
            $('.wpfnl-delete-funnel').on('click', this.deleteFunnel);
        };

        /**
         * Select quantity
         *
         * @since 1.0.0
         */
        $('input[name=quantity]').change(function() {
            var val = parseInt(this.value);
            var product = $(this).attr('data-product');

            for (var i in product_option) {
                if (product_option[i].id == product) {
                    product_option[i].quantity = val;
                    break; //Stop this loop, we found it!
                }
            }
        });

        /**
         * Subtext Setup
         *
         * @since 1.0.0
         */
        $('input[name=subtext]').change(function() {
            var val = $(this).val();
            var product = $(this).attr('data-product');

            for (var i in product_option) {
                if (product_option[i].id == product) {
                    product_option[i].subtext = val;
                    break; //Stop this loop, we found it!
                }
            }
        });

        /**
         * Highlight Text Setup
         *
         * @since 1.0.0
         */
        $('input[name=text-highlight]').change(function() {
            var val = $(this).val();
            var product = $(this).attr('data-product');

            for (var i in product_option) {
                if (product_option[i].id == product) {
                    product_option[i].text_highlight = val;
                    break; //Stop this loop, we found it!
                }
            }
        });

        /**
         * Enable Highlight setup
         *
         * @since 1.0.0
         */
        $('input[name=hide-img-mobile]').change(function() {
            if (this.checked) {
                var val = 'on';
            } else {
                var val = 'off';
            }

            var product = $(this).attr('data-product');

            for (var i in product_option) {
                if (product_option[i].id == product) {
                    product_option[i].enable_highlight = val;
                    break; //Stop this loop, we found it!
                }
            }
        });


        /**
         * Sort step order
         *
         * @since 1.0.0
         */
        FunnelHandler.prototype.initStepSortable = function() {
            $("#wpfnl-funnel-step-lists").sortable({
                axis: "y",
                start: function(event, ui) {

                },
                update: function(event, ui) {
                    var order = $("#wpfnl-funnel-step-lists").sortable("toArray");

                    var funnel_id = getUrlParameter('id');

                    var payload = {
                        funnel_id: funnel_id,
                        order: order,
                    };
                    wpAjaxHelperRequest("funnel-drag-order", payload)
                        .success(function(response) {

                        })
                        .error(function(response) {
                            // console.log('error');
                        });
                }
            });
            $("#wpfnl-funnel-step-lists").disableSelection();
        };


        /**
         * Sort product accordion order
         *
         * @since 1.0.0
         */
        FunnelHandler.prototype.initProductSortable = function() {
            $(".product-single-accordion__sortable-wrapper").sortable({
                axis: "y",
                start: function(event, ui) {

                },
                update: function(event, ui) {
                    var product_order = $(".product-single-accordion__sortable-wrapper").sortable("toArray");
                    console.log(product_option);
                    product_option = [];
                    var funnel_id = getUrlParameter('id');

                    for (var i in product_order) {
                        var dragger_id = product_order[i];
                        var prod_id = dragger_id.replace("product__single-accordion-", "");
                        var quantity = $('#product-quantity-' + prod_id).val();
                        product_option.push({
                            'id': prod_id,
                            'quantity': quantity
                        });
                    }
                    console.log(product_option);
                }
            });
            $(".product-single-accordion__sortable-wrapper").disableSelection();
        };


        /**
         *
         * Ajax handler for duplicate
         * funnel action
         *
         * @param event
         * @since 1.0.0
         */
        FunnelHandler.prototype.cloneFunnel = function(event) {
            event.preventDefault();
            var funnel_id = $(this).attr('data-id'),
                loader    = $(this).find('.wpfnl-loader');
            var payload = {
                funnel_id: funnel_id
            };

            $(this).css('pointer-events', 'none');
            loader.show();

            wpAjaxHelperRequest("clone-funnel", payload)
                .success(function(response) {
                    window.location.href = response.redirectUrl;
                    loader.hide();
                })
                .error(function(response) {
                    loader.hide();
                });
        };


        /**
         * delete funnel and all the related
         * data
         *
         * @param event
         * @since 1.0.0
         */
        FunnelHandler.prototype.deleteFunnel = function(event) {
            event.preventDefault();
            var funnel_id = $(this).attr('data-id');
            var payload = {
                funnel_id: funnel_id
            };
            if (confirm("Are you sure?")) {
                wpAjaxHelperRequest("delete-funnel", payload)
                    .success(function(response) {

                        window.location.href = response.redirectUrl;
                    })
                    .error(function(response) {

                    });
            }
        };


        /**
         * Realtime funnel name change on header
         *
         * @param event
         * @since 1.0.0
         */
        var fnl_name = '';
        var fnl_name2 = '';
         $('.funnel-name-edit').on('click', function(e) {
            e.preventDefault();
            fnl_name = $(this).parents('.steps-page__fnl-name').find('.funnel-name').text();
            $(this).hide();
            $('.funnel-name-cancel').show();
            $(this).parents('.steps-page__fnl-name').find('.funnel-name-input').text(fnl_name).show();
            $(this).parents('.steps-page__fnl-name').find('.funnel-name').hide();
            $(this).parents('.steps-page__fnl-name').find('.funnel-name-submit').show();
        });

        $('.funnel-name-input').keyup(function() {
            $(this).parents('.steps-page__fnl-name').find('.funnel-name').text($(this).val());
        });


        $('.funnel-name-submit').on('click', function(e) {
            e.preventDefault();
            fnl_name2 = $(this).parents('.steps-page__fnl-name').find('.funnel-name-input').val();
            $(this).hide();
            $('.funnel-name-cancel').hide();
            $(this).parents('.steps-page__fnl-name').find('.funnel-name-input').val(fnl_name2).hide();
            $(this).parents('.steps-page__fnl-name').find('.funnel-name').text(fnl_name2).show();
            $(this).parents('.steps-page__fnl-name').find('.funnel-name-edit').show();
        });

        $('.funnel-name-cancel').on('click', function(e) {
            e.preventDefault();
            $(this).hide();
            $('.funnel-name-cancel').hide();
            $(this).parents('.steps-page__fnl-name').find('.funnel-name-input').val(fnl_name).hide();
            $(this).parents('.steps-page__fnl-name').find('.funnel-name').text(fnl_name).show();
            $(this).parents('.steps-page__fnl-name').find('.funnel-name-submit').hide();
            $(this).parents('.steps-page__fnl-name').find('.funnel-name-edit').show();
        });


        /**
         * Realtime automation name change on automation drawer
         *
         * @param event
         * @since 1.0.0
         */
        var automation_name = '';
        var automation_name2 = '';
        $(document).on("click", ".automation-name-edit", function(e) {
            e.preventDefault();

            automation_name = $(this).parents('.automation-name-wrapper').find('.automation-name-preview').text();
            $(this).hide();
            $('.automation-name-cancel').css('display', 'flex');
            $(this).parents('.automation-name-wrapper').find('.automation-name-input').val(automation_name).css('display', 'flex');
            $(this).parents('.automation-name-wrapper').find('.automation-name-preview').hide();
            $(this).parents('.automation-name-wrapper').find('.automation-name-submit').css('display', 'flex');
        });

        // $(document).on("click", ".automation-name-edit", function(e) {
        $('.automation-name-input').keyup(function() {
            $(this).parents('.automation-name-wrapper').find('.automation-name-preview').text($(this).val());
        });

        $(document).on("click", ".automation-name-submit", function(e) {
            e.preventDefault();
            automation_name2 = $(this).parents('.automation-name-wrapper').find('.automation-name-input').val();
            $(this).hide();
            $('.automation-name-cancel').hide();
            $(this).parents('.automation-name-wrapper').find('.automation-name-input').val(automation_name2).hide();
            $(this).parents('.automation-name-wrapper').find('.automation-name-preview').text(automation_name2).css('display', 'flex');
            $(this).parents('.automation-name-wrapper').find('.automation-name-edit').css('display', 'flex');
        });

        $(document).on("click", ".automation-name-cancel", function(e) {
            e.preventDefault();
            $(this).hide();
            $('.automation-name-cancel').hide();
            $(this).parents('.automation-name-wrapper').find('.automation-name-input').val(automation_name).hide();
            $(this).parents('.automation-name-wrapper').find('.automation-name-preview').text(automation_name).css('display', 'flex');
            $(this).parents('.automation-name-wrapper').find('.automation-name-submit').hide();
            $(this).parents('.automation-name-wrapper').find('.automation-name-edit').css('display', 'flex');
        });

        $(document).on("click", "#save_automation", function(e) {
            e.preventDefault();
            $('.event-trigger').each(function(){
                if( $(this).children("option:selected").val() == '' ){
                    $(this).parents('.wpfnl-form-group').addClass( 'field-required' );
                }else{
                    $(this).parents('.wpfnl-form-group').removeClass( 'field-required' );
                }
            })
            $('.list-trigger').each(function(){
                if( $(this).children("option:selected").val() == '' ){
                    $(this).parents('.wpfnl-form-group').addClass( 'field-required' );
                }else{
                    $(this).parents('.wpfnl-form-group').removeClass( 'field-required' );
                }
            })
            $('.tag-trigger').each(function(){
                if( $(this).children("option:selected").val() == '' ){
                    $(this).parents('.wpfnl-form-group').addClass( 'field-required' );

                }else{
                    $(this).parents('.wpfnl-form-group').removeClass( 'field-required' );
                }
            })

        });

        //-----remove "field-required" class when data selected-------
        $(document).on("change", ".single-trigger .selectbox-wrapper select", function(e) {
            if( $(this).children("option:selected").val() != '' ){
                $(this).parents('.wpfnl-form-group').removeClass( 'field-required' );
            }
        });



        /**
         * change funnel name
         *
         * @param event
         * @since 1.0.0
         */
        FunnelHandler.prototype.changeFunnelName = function(event) {
            event.preventDefault();
            var payload = {
                data: $(this).serialize(),
            },
            button = $(this).find('.funnel-name-submit');
            button.hide();
            button.parents('.steps-page__fnl-name').find('.funnel-name-input').hide();
            button.parents('.steps-page__fnl-name').find('.funnel-name').show();
            button.parents('.steps-page__fnl-name').find('.funnel-name-edit').show();
            wpAjaxHelperRequest("funnel-name-change", payload)
                .success(function(response) {
                    location.reload();
                })
                .error(function(response) {

                });
        };


        var StepHandler = function() {
            $(document.body)
                .on('click', '#wpfnl-delete-step', this.deleteStep)
                .on('click', '#wpfnl-update-checkout-product-tab', this.updateCheckoutProduct)
                .on('click', '#wpfnl-update-thank-you-settings', this.updateThankYouSettings)
                .on('click', '#wpfnl-update-upsell-settings', this.updateUpsellSettings)
                .on('click', '#wpfnl-update-downsell-settings', this.updateDownsellSettings)
                .on('change', '#wpfnl-choose-step-type', this.stepTypeChange)
                // .on('click', '#wpfnl-add-product', this.addProduct)
        };



        /**
         * add product
         * @param event
         */
        StepHandler.prototype.addProduct = function(event) {
            event.preventDefault();
            var _prObj = $('#wpfnl-checkout-products').select2('data')[0],
                step_id = $(this).attr('data-id');
            if (_prObj) {
                product_option.push({
                    'id': _prObj.id,
                    'quantity': 1
                });
                var payload = {
                        id: _prObj.id,
                        step_id: step_id,
                        products: JSON.stringify(product_option),
                        index: parseInt($('.product__single-accordion').length)
                    },
                    that = $(this);

                wpAjaxHelperRequest("wpfnl-add-product", payload)
                    .success(function(response) {
                        if (response.success) {
                            $('.no-product-notice').hide();
                            $('.accordion-head').show();
                            // $('.product-accordion__wrapper').append(response.html);
                            $('.product-single-accordion__sortable-wrapper').append(response.html);
                            $('#wpfnl-checkout-products').val(null).trigger('change');
                            if (product_option.length) that.html('Add product');
                            // $('.product__single-accordion:nth-child(2)').addClass('active');
                        }
                        console.log("Woohoo!");
                        console.log(response);
                        $('#setp-list-' + step_id + ' span').css('color', 'black');

                        $(".product-single-accordion__sortable-wrapper").sortable({
                            axis: "y",
                            start: function(event, ui) {

                            },
                            update: function(event, ui) {
                                var product_order = $(".product-single-accordion__sortable-wrapper").sortable("toArray");
                                console.log(product_option);
                                product_option = [];
                                var funnel_id = getUrlParameter('id');

                                for (var i in product_order) {
                                    var dragger_id = product_order[i];
                                    var prod_id = dragger_id.replace("product__single-accordion-", "");
                                    var quantity = $('#product-quantity-' + prod_id).val();
                                    product_option.push({
                                        'id': prod_id,
                                        'quantity': quantity
                                    });
                                }
                                console.log(product_option);
                            }
                        });
                        $(".product-single-accordion__sortable-wrapper").disableSelection();

                    })
                    .error(function(response) {

                    });
            }

        };


        /**
         * delete product from checkout
         *
         * @param event
         */
        StepHandler.prototype.deleteproduct = function(event) {
            event.preventDefault();
            var index = $(this).attr('data-index');
            product_option.splice(index, 1);
            var payload = {
                step_id: $(this).attr('data-id'),
                products: JSON.stringify(product_option),
            };
            wpAjaxHelperRequest("wpfnl-update-checkout-product-tab", payload)
                .success(function(response) {
                    if (response.success) {
                        location.reload();
                        return false;
                    }
                    console.log("Woohoo!");
                    console.log(response);
                })
                .error(function(response) {

                });
        };

        /**
         * save function for checkout steps
         * product tab
         *
         * @param event
         * @since 1.0.0
         */
        StepHandler.prototype.updateCheckoutProduct = function(event) {
            event.preventDefault();
            var thisLoader = $(this).find('.wpfnl-loader');
            var thisAlert = $(this).siblings('.wpfnl-alert');

            //=== Enable additional checkout fields===//
            var coupon = 'no';
            if ($('#enable-checkout-coupon').prop("checked") == true) {
                coupon = 'yes';
            } else if ($('#enable-checkout-coupon').prop("checked") == false) {
                coupon = 'no';
            }

            //=== Enable additional checkout fields===//

            thisLoader.fadeIn();
            var payload = {
                step_id: $(this).attr('data-id'),
                coupon: coupon,
                products: JSON.stringify(product_option),
                // discount: $(".wpfnl-checkout-discount").val(),
            };
            wpAjaxHelperRequest("wpfnl-update-checkout-product-tab", payload)
                .success(function(response) {
                    thisLoader.fadeOut();
                    thisAlert.text('Saved Successfully').addClass('box wpfnl-success').fadeIn();

                    setTimeout(function() {
                        thisAlert.fadeOut().text('').removeClass('box wpfnl-success');
                    }, 2000);

                    console.log("Woohoo!");
                    console.log(response);
                })
                .error(function(response) {
                    console.log("Uh, oh!");
                    console.log(response.statusText);

                    thisLoader.fadeOut();
                    thisAlert.text('Erorr occurred').addClass('box wpfnl-error').fadeIn();
                    setTimeout(function() {
                        thisAlert.fadeOut().text('').removeClass('box wpfnl-error');
                    }, 2000);
                });
        };

        /**
         * Set minimum time for custom redirection in thankyou page
         *
         * @param event
         *
         */
        $(document).on("keyup", "#set-time", function() {
            if( $("#set-time").val() && $("#set-time").val() < 1 ){
                $("#set-time").val(1)
            }
        })

        /**
         * save thankyou settings
         * options
         *
         * @param event
         * @since 1.0.0
         */
        StepHandler.prototype.updateThankYouSettings = function(event) {
            event.preventDefault();
            var thisLoader = $(this).find('.wpfnl-loader');
            var thisAlert = $(this).siblings('.wpfnl-alert');
            thisLoader.fadeIn()
            var payload = {
                step_id: $(this).attr('data-id'),
                text: $(".thankyou-page-text").val(),
                redirect_link: $(".thankyou-redirect-link").val(),
                order_overview: $("#enable-order-overview").is(':checked') ? 'on' : 'off',
                order_details: $("#enable-order-details").is(':checked') ? 'on' : 'off',
                billing_details: $("#enable-billing-details").is(':checked') ? 'on' : 'off',
                shipping_details: $("#enable-shipping-details").is(':checked') ? 'on' : 'off',
                is_custom_redirect: $("#enable-custom-redirect").is(':checked') ? 'on' : 'off',

                is_direct_redirect: $("#direct-custom-redirect").is(':checked') ? 'on' : 'off',
                set_time: $("#set-time").val(),
                custom_redirect_url: $("#custom-redirect-url").val(),
            };

            wpAjaxHelperRequest("update-thankyou-settings", payload)
                .success(function(response) {
                    thisLoader.fadeOut();
                    thisAlert.text('Saved Successfully').addClass('box wpfnl-success').fadeIn();
                    setTimeout(function() {
                        thisAlert.fadeOut().text('').removeClass('box wpfnl-success');
                    }, 2000);
                    console.log("Woohoo!");
                    console.log(response);
                })
                .error(function(response) {
                    console.log("Uh, oh!");
                    console.log(response.statusText);
                    thisLoader.fadeOut();
                    thisAlert.text('Erorr occurred').addClass('box wpfnl-error').fadeIn();
                    setTimeout(function() {
                        thisAlert.fadeOut().text('').removeClass('box wpfnl-error');
                    }, 2000);
                });
        };


        /**
         * save upsell settings
         * options
         *
         * @param event
         * @since 1.0.0
         */
        StepHandler.prototype.updateUpsellSettings = function(event) {
            event.preventDefault();
            var thisLoader = $(this).find('.wpfnl-loader');
            var thisAlert = $(this).siblings('.wpfnl-alert');
            thisLoader.fadeIn()
            var payload = {
                step_id: $(this).attr('data-id'),
                product: $(".wpfnl-product-search").val(),
                quantity: $("#upsell-product-quantity").val(),
                product_price: $("#upsell-original-price").val(),
                product_sale_price: $("#upsell-sale-price").val(),
                discount_type: $("#upsell-discount-type").val(),
                discount_value: $("#upsell-discount-value").val(),
                hide_image: $("#img-hide-mobile").prop('checked') ? 'on' : 'off',
                next_step_yes: $("#next-step-yes").val(),
                next_step_no: $("#next-step-no").val(),
            };
            wpAjaxHelperRequest("update-upsell-settings", payload)
                .success(function(response) {
                    thisLoader.fadeOut();
                    thisAlert.text('Saved Successfully').addClass('box wpfnl-success').fadeIn();
                    setTimeout(function() {
                        thisAlert.fadeOut().text('').removeClass('box wpfnl-success');
                    }, 2000);
                    console.log("Woohoo!");
                    console.log(response);
                })
                .error(function(response) {
                    console.log("Uh, oh!");
                    console.log(response.statusText);
                    thisLoader.fadeOut();
                    thisAlert.text('Erorr occurred').addClass('box wpfnl-error').fadeIn();
                    setTimeout(function() {
                        thisAlert.fadeOut().text('').removeClass('box wpfnl-error');
                    }, 2000);
                });
        };



        /**
         * save downsell settings
         * options
         *
         * @param event
         * @since 1.0.0
         */
        StepHandler.prototype.updateDownsellSettings = function(event) {
            event.preventDefault();
            var thisLoader = $(this).find('.wpfnl-loader');
            var thisAlert = $(this).siblings('.wpfnl-alert');
            thisLoader.fadeIn()
            var payload = {
                step_id: $(this).attr('data-id'),
                product: $(".wpfnl-product-search").val(),
                quantity: $("#downsell-product-quantity").val(),
                product_price: $("#downsell-original-price").val(),
                product_sale_price: $("#downsell-sale-price").val(),
                discount_type: $("#downsell-discount-type").val(),
                discount_value: $("#downsell-discount-value").val(),
                hide_image: $("#img-hide-mobile").prop('checked') ? 'on' : 'off',
                next_step_yes: $("#next-step-yes").val(),
                next_step_no: $("#next-step-no").val(),
            };
            wpAjaxHelperRequest("update-downsell-settings", payload)
                .success(function(response) {
                    thisLoader.fadeOut();
                    thisAlert.text('Saved Successfully').addClass('box wpfnl-success').fadeIn();
                    setTimeout(function() {
                        thisAlert.fadeOut().text('').removeClass('box wpfnl-success');
                    }, 2000);
                    console.log("Woohoo!");
                    console.log(response);
                })
                .error(function(response) {
                    console.log("Uh, oh!");
                    console.log(response.statusText);
                    thisLoader.fadeOut();
                    thisAlert.text('Erorr occurred').addClass('box wpfnl-error').fadeIn();
                    setTimeout(function() {
                        thisAlert.fadeOut().text('').removeClass('box wpfnl-error');
                    }, 2000);
                });
        };



        /**
         * Ajax handler for step
         * deletion action
         *
         * @param event
         */
        StepHandler.prototype.deleteStep = function(event) {
            event.preventDefault();
            var step_id = $(this).attr('data-id');
            var payload = {
                step_id: step_id
            };
            if (confirm("Are you sure?")) {
                wpAjaxHelperRequest("delete-step", payload)
                    .success(function(response) {
                        console.log("Woohoo!");
                        console.log(response);
                        window.location.href = response.redirectUrl;
                    })
                    .error(function(response) {
                        console.log("Uh, oh!");
                        console.log(response.statusText);
                    });
            }
        };


        /**
         * Ajax handler for step
         * creation
         *
         * @param e
         * @since 1.0.0
         */
        StepHandler.prototype.createStep = function(e) {
            e.preventDefault();
            var thisLoader = $(this).find('.wpfnl-loader');
            var thisAlert = $(this).siblings('.wpfnl-alert');

            thisLoader.fadeIn();

            var wrapper = $('#create-step-form'),
                type = $(this).attr('data-step-type'),
                funnelId = $(this).attr('data-funnel-id'),
                stepListWrapper = $('#wpfnl-funnel-step-lists');

            var payload = {
                funnel_id: funnelId,
                step_type: type,
            };
            wpAjaxHelperRequest("create-step", payload)
                .success(function(response) {
                    console.log('success');
                    thisLoader.fadeOut();
                    thisAlert.text('Successfully Step Created').addClass('box wpfnl-success').fadeIn();

                    setTimeout(function() {
                        thisAlert.fadeOut().text('').removeClass('box wpfnl-success');
                    }, 2000);

                    if (response.success) {
                        window.location.href = response.redirectUrl;
                    } else {
                        console.log(response);
                    }

                })
                .error(function(response) {
                    thisLoader.fadeOut();
                    thisAlert.text('Erorr Occurred').addClass('box wpfnl-error').fadeIn();

                    setTimeout(function() {
                        thisAlert.fadeOut().text('').removeClass('box wpfnl-error');
                    }, 2000);
                });
        };



        StepHandler.prototype.stepTypeChange = function(event) {
            event.preventDefault();
            var wrapper = $('.choose-step-type'),
                step_type = $(this).find(":selected").val(),
                saveBtn = $('#wpfnl-create-step');
            if (WPFunnelVars.is_wc_installed === 'no' && (step_type === 'checkout' || step_type === 'upsell' || step_type === 'downsell')) {
                $('.wpfnl-modal__body').find('p').remove();
                wrapper.after("<p style='text-align: center; color: red'>You need install and active WooCommerce to use this step type. </p>");
                saveBtn.addClass('disabled').css('pointer-events', 'none');
                return;
            } else {
                $('.wpfnl-modal__body').find('p').remove();
                saveBtn.removeClass('disabled').css('pointer-events', 'inherit');
            }
        };



        var DataSerachHandler = function() {
            this.initProductSearch();
            this.initCouponSearch();
        };

        /**
         * initialize product search
         * for checkout
         *
         * @param event
         * @since 1.0.0
         */
        DataSerachHandler.prototype.initProductSearch = function(event) {
            var select2Args = {
                minimumInputLength: 3,
                allowClear: true,
                maximumSelectionLength: 1,
                ajax: {
                    url: WPFunnelVars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term,
                            action: 'wpfnl_product_search',
                            security: WPFunnelVars.security,
                        };
                    },
                    processResults: function(data) {
                        var terms = [];
                        if (data) {
                            $.each(data, function(id, value) {
                                terms.push({
                                    id: id,
                                    text: value.name,
                                    price: value.price,
                                    sale_price: value.sale_price
                                });
                            });
                        }
                        return {
                            results: terms
                        };
                    },
                    cache: true
                }
            };

            $('.wpfnl-product-search').on('select2:select', function(e) {
                var data = e.params.data;
                $('#upsell-original-price').val(data.price);
                $('#upsell-sale-price').val(data.sale_price);

                $('#downsell-original-price').val(data.price);
                $('#downsell-sale-price').val(data.sale_price);
            });
            $('.wpfnl-product-search').select2(select2Args);
        };


        /**
         * initialize coupon search
         * for checkout
         *
         * @param event
         * @since 1.0.0
         */
        DataSerachHandler.prototype.initCouponSearch = function(event) {
            var select2Args = {
                minimumInputLength: 3,
                allowClear: true,
                ajax: {
                    url: WPFunnelVars.ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term,
                            action: 'wpfnl_coupon_search',
                            security: WPFunnelVars.security,
                        };
                    },
                    processResults: function(data) {
                        var terms = [];
                        if (data) {
                            $.each(data, function(id, text) {
                                terms.push({
                                    id: id,
                                    text: text
                                });
                            });
                        }
                        return {
                            results: terms
                        };
                    },
                    cache: true
                }
            }
            $('.wpfnl-discount-search').select2(select2Args);
        };



		var AdminNotices = function() {
			$(document.body)
				.on('click', '#wpfnl-install-plugin', this.installPlugin)
				.on('wp-plugin-install-success', this.installSuccess)
			$( document ).on(
				'wp-plugin-install-success',
				self._installSuccess
			);
		};
		AdminNotices.prototype.installPlugin = function(e) {
			e.preventDefault();
			let slug = $(this).attr('data-slug');
			wp.updates.queue.push( {
				action: 'install-plugin', // Required action.
				data: {
					slug: slug,
				},
			} );
			wp.updates.queueChecker();
		}
		AdminNotices.prototype.installSuccess = function(event, response) {
			event.preventDefault();
			console.log(response)
		}


        new FunnelHandler();
        new StepHandler();
        new DataSerachHandler();
        new AdminNotices();

        /**
         * funnel list show more action on click
         *
         * @since 1.0.0
         */
        $('body').on('click', function() {
            $('.funnel-list__more-action').removeClass('show-actions');
        });

        $('.funnel-list__more-action').on('click', function(e) {
            e.stopPropagation();
            $(this).toggleClass('show-actions');
            $(this).parents('.funnel__single-list').siblings().find('.funnel-list__more-action').removeClass('show-actions');
        });

        $('.funnel-list__more-action .wpfnl-dropdown').on('click', function(e) {
            e.stopPropagation();
        });


        /**
         * copy to clipboard
         *
         * @since 1.0.0
         */
        $('.wpfnl-copy-clipboard').on('click', function() {
            var getID = $(this).attr('data-id');
            var getText = $('#' + getID).val();
            CopyToClipboard(getText);

            $(this).siblings('.copied-msg').text('Copied').fadeIn(500);

            setTimeout(function() {
                $('.copied-msg').fadeOut('slow');
            }, 2500);

        });

        /**
         * copy to clipboard helper function
         *
         * @since 1.0.0
         */
        function CopyToClipboard(text) {
            if (window.clipboardData && window.clipboardData.setData) {
                return clipboardData.setData("Text", text);

            } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
                var textarea = document.createElement("textarea");
                textarea.textContent = text;
                textarea.style.position = "fixed";
                document.body.appendChild(textarea);
                textarea.select();

                try {
                    return document.execCommand("copy");
                } catch (ex) {
                    console.warn("Copy to clipboard failed.", ex);
                    return false;
                } finally {
                    document.body.removeChild(textarea);
                }
            }
        }



        /**
         * open modal when add new step
         *
         * @param event
         * @since 1.0.0
         */
        // $('body').on('click', function(){
        //     $('.wpfnl-modal').fadeOut(200);
        // });

        // $('#create-first-step, #add-new-step').on('click', function(e){
        //     e.preventDefault();
        //     e.stopPropagation();
        //     $(this).parents('.wpfnl').find('.wpfnl-modal').fadeIn(200);
        // });

        $('.wpfnl-modal-close').on('click', function(e) {
            e.preventDefault();
            $(this).parents('.wpfnl-modal').fadeOut(200);
        });

        $('.wpfnl-modal__wrapper').on('click', function(e) {
            e.stopPropagation();
        });


        /**
         * step name change on step page
         *
         * @param event
         * @since 1.0.0
         */
        var stepName = '';
        $('.step-name-edit').on('click', function(e) {
            e.preventDefault();
            $(this).hide();
            stepName = $(this).parents('.title-area').find('.step-name-input').val();
            $(this).parents('.title-area').find('.step-name-input').show();
            $(this).parents('.title-area').find('.step-name-noupdate').show();
            $(this).parents('.title-area').find('.step-name').hide();
            $(this).parents('.title-area').find('.step-name-update').show();
        });

        $('.step-name-update').on('click', function(e) {
            e.preventDefault();
            $(this).hide();
            $(this).parents('.title-area').find('.step-name-input').hide();
            $(this).parents('.title-area').find('.step-name-noupdate').hide();
            $(this).parents('.title-area').find('.step-name').show();
            $(this).parents('.title-area').find('.step-name-edit').show();
        });

        $('.step-name-noupdate').on('click', function(e) {
            e.preventDefault();
            $(this).hide();
            $(this).parents('.title-area').find('.step-name-input').val(stepName).hide();
            $(this).parents('.title-area').find('.step-name-update').hide();
            $(this).parents('.title-area').find('.step-name').text(stepName).show();
            $(this).parents('.title-area').find('.step-name-edit').show();
        });

        $('.step-name-input').keyup(function() {
            $(this).parents('.title-area').find('.step-name').text($(this).val());
        });


        /**
         * step settings tab
         *
         * @since 1.0.0
         */
        $('.step-settings__single-tab-content:first-child').show();

        $(document).on("click", ".steps-settings__tab-nav a", function(e) {
            if( ! $(this).parent('li').hasClass('disabled') ){
                e.preventDefault();
                var dataID = $(this).attr('href');

                $(this).parent('li').addClass('active').siblings().removeClass('active');
                $(this).parents('.steps-settings').find(dataID).show();
                $(this).parents('.steps-settings').find(dataID).siblings().hide();
            }

        });


        /**
         * step settings product options tab
         *
         * @since 1.0.0
         */
        $('.product-options__tab-nav a').on('click', function(e) {
            e.preventDefault();

            var dataID = $(this).attr('href');
            $(this).parent('li').addClass('active').siblings().removeClass('active');

            $(this).parents('.wpfnl-product-options').find(dataID).show();
            $(this).parents('.wpfnl-product-options').find(dataID).siblings().hide();

        });


        /**
         * wpfnl accordion
         *
         * @since 1.0.0
         */
        $('.wpfnl__accordion-content').hide();
        $('.wpfnl__single-accordion:first-child .wpfnl__accordion-content').show();
        $('.wpfnl__accordion-title').on('click', function(e) {
            e.preventDefault();

            var dataID = $(this).attr('href');

            $(this).parent('.wpfnl__single-accordion').find(dataID).slideToggle();
            $(this).parents('.wpfnl__single-accordion').siblings().find('.wpfnl__accordion-content').slideUp();

        });


        /**
         * steps-header-hamburger toggle menu
         *
         * @since 1.0.0
         */
        $('body').on('click', function() {
            $('#steps-header-hamburger').removeClass('show');
        });
        // $(document).on("click", "#steps-header-hamburger", function(e) {
        $('#steps-header-hamburger').on('click', function(e) {
            e.stopPropagation();
            if( !$(this).hasClass( "show" ) ){
                $(this).removeClass('show');
            }else{
                $(this).addClass('show');
            }

            WpfnlDatePicker();
        });



        /**
         * wpfnl step title edit
         *
         * @param event
         * @since 1.0.0
         */
        function step_edit(e) {
            e.preventDefault();
            var step_id = $(this).attr("data-id");
            var input = $('#step-name-input-' + step_id).val();
            var payload = {
                step_id: step_id,
                input: input,
            };
            wpAjaxHelperRequest("step-edit", payload)
                .success(function(response) {
                    $('#setp-list-' + step_id + ' .title-txt').text(input);

                    if (response.success) {
                        console.log('success');
                    } else {
                        console.log(response);
                    }

                })
                .error(function(response) {
                    console.log('error');
                });
        }
        $('.step-name-update').on('click', step_edit);

    });

    function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    }

    /**
     * settings page tab nav
     *
     * @since 1.0.0
     */
    $('.wpfn-settings__nav .nav-li:not(.disabled)').on('click', function(e) {
        var dataID = $(this).attr('data-id');
        $(this).addClass('active');
        $(this).siblings('.nav-li').removeClass('active');

        $('#' + dataID).show();
        $('#' + dataID).siblings('.wpfnl-funnel__single-settings').hide();
    });





    /**
     * Checkout Edit Field delete alert modal
     *
     * @since 1.0.0
     */
    $(document).on("click", ".delete-checkout-field", function() {
        $(this).parents('.checkout__single-field').find('.wpfnl-delete-alert-wrapper').css('display', 'flex');
    });

    $(document).on("click", ".wpfnl-delete-confirm-btn .cancel", function() {
        $(this).parents('.wpfnl-delete-alert-wrapper').hide();
    });

    /**
     * edit-field settings tab
     *
     * @since 1.0.0
     */
    $('.edit-field-settings__single-tab-content:first-child').show();

    $(document).on("click", ".edit-field-settings__tab-nav a", function(e) {
        e.preventDefault();
        var dataID = $(this).attr('href');
        $(this).parent('li').addClass('active').siblings().removeClass('active');
        $(this).parents('.checkout-edit-field-tab__content-wrapper').find(dataID).show();
        $(this).parents('.checkout-edit-field-tab__content-wrapper').find(dataID).siblings().hide();
    });



    /**
     * add new checkout field drawer
     *
     * @since 1.0.0
     */
    $('.add-new-checkout-field-btn').on('click', function(e) {
        e.preventDefault();
        $(this).parents('.checkout-edit-field-tab__content-wrapper').find('.add-checkout-field-wrapper').addClass('show-drawer');
    });

    $('.add-checkout-field-close').on('click', function(e) {
        e.preventDefault();
        $(this).parents('.add-checkout-field-wrapper').removeClass('show-drawer');
    });


    /**
     * edit checkout field drawer
     *
     * @since 1.0.0
     */
    // $('button.edit-field').on('click', function(e) {
    $(document).on("click", "button.edit-field", function(e) {
        e.preventDefault();
        $(this).parents('.checkout-edit-field-tab__content-wrapper').find('.edit-checkout-field-wrapper').addClass('show-drawer');
    });

    $('.add-checkout-field-close').on('click', function(e) {
        e.preventDefault();
        $(this).parents('.edit-checkout-field-wrapper').removeClass('show-drawer');
    });


    /**
     * show edit field type options
     *
     * @since 1.0.0
     */
    $('.wpfnl-edit-field-type').on('change', function(e) {
        e.preventDefault();
        var thisVal = $(this).val();

        if (thisVal == 'select') {
            $(this).parents('.field-body').find('.field-type-options').show();
        } else {
            $(this).parents('.field-body').find('.field-type-options').hide();
        }
    });


    jQuery(document).ready(function() {
        var selectedType = $('.wpfnl-edit-field-type').val();

        if (selectedType == 'select') {
            $('.wpfnl-edit-field-type').parents('.field-body').find('.field-type-options').show();
        } else {
            $('.wpfnl-edit-field-type').parents('.field-body').find('.field-type-options').hide();
        }

    });


    /**
     * Step preview permalink copy to clipboard
     *
     * @since 2.2.5
    */
    $(document).on("click", ".wpfnl-drawing-window .funnel-name-edit-wrapper .hints", function(e) {
        e.preventDefault();
        $(this).addClass('copy-confirmed');
        var $temp = $("<input>");
        $(this).parent('.field-group').append($temp);
        $temp.val($(this).parent('.field-group').find('#step-preview-link').val()).select();
        document.execCommand("copy");
        $temp.remove();

        setTimeout(function() {
            $('.wpfnl-drawing-window .funnel-name-edit-wrapper .hints').removeClass('copy-confirmed');
        }, 1000);

    });


	/**
	 * rollback feature for WPF
	 */
	$('select#wpfnl-rollback').on('change', function () {
		var $this = $(this),
			$rollbackButton = $this.next('.wpfnl-rollback-button'),
			placeholderText = $rollbackButton.data('placeholder-text'),
			placeholderUrl = $rollbackButton.data('placeholder-url');
		$rollbackButton.html(placeholderText.replace('{VERSION}', $this.val()));
		$rollbackButton.attr('href', placeholderUrl.replace('VERSION', $this.val()));
	}).trigger('change');


	$('.wpfnl-rollback-button').on('click', function (event) {
		event.preventDefault();
		var $this = $(this);
		if ( confirm("Are you sure?") ) {
			$this.addClass('show-loader');
			$this.addClass('loading');
			location.href = $this.attr('href');
		}
	});

})(jQuery);


