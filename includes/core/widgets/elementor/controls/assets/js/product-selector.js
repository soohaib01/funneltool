jQuery( window ).on( 'elementor:init', function($) {
    var ControlProductView = elementor.modules.controls.BaseData.extend( {

        onReady: function() {
            var self = this,
                options = {
                    minimumInputLength: 3,
                    allowClear: true,
                    maximumSelectionLength: 1,
                    placeholder :'Select Product',
                    ajax: {
                        url:         WPFunnelVars.ajaxurl,
                        dataType:    'json',
                        delay:       250,
                        data:        function( params ) {
                            return {
                                term         : params.term,
                                action       : 'wpfnl_product_search',
                                security     : WPFunnelVars.security,
                            };
                        },
                        processResults: function( data ) {
                            var terms = [];
                            if ( data ) {
                                Object.keys(data).forEach(function(key) {
                                    terms.push({ id: key, text: data[key].name });
                                });
                            }

                            return {
                                results: terms
                            };
                        },
                        cache: true
                    }
                };
            this.ui.select.select2(options);
        },
        onBeforeDestroy: function() {
            this.ui.select.select2( 'destroy' );
        }

    } );
    elementor.addControlView( 'product_selector', ControlProductView );
} );