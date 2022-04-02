<template>
    <div class="wpfnl-field-wrapper select-coupon">
        <label for="wpfnl-ob-coupon-search">Select Coupon</label>
        <div class="wpfnl-fields">
            <div class="coupon">
                <select class="wpfnl-select2 wpfnl-ob-coupon-search" id="wpfnl-ob-coupon-search" name="wpfnl-ob-coupon-search" data-placeholder="Search for coupon">
                    <option v-if="discountValue" :value="discountValue">{{couponName}}</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
var j = jQuery.noConflict();
export default {
    name: 'Coupon',
    props: {
        discountValue: String,
        couponName: String,
    },
    mounted:function(){
        let select2Args = {
                minimumInputLength: 3,
                allowClear: true,
                ajax: {
                    url:         window.CheckoutStep.ajaxurl,
                    dataType:    'json',
                    delay:       250,
                    data:        function( params ) {
                        return {
                            term         : params.term,
                            action       : 'order_bump_search_coupons',
                            security     : window.CheckoutStep.security,
                        };
                    },
                    processResults: function( data ) {
                        var terms = [];
                        if ( data ) {
                            j.each( data, function( coupon, name ) {
                                terms.push( { id: coupon, text: name } );
                            });
                        }
                        return {
                            results: terms
                        };
                    },
                    cache: true
                }
            },
            that = this

        j('#wpfnl-ob-coupon-search').on('select2:select', function (e) {
            var data = e.params.data;
            that.$emit('changeData', 'discountValue', data.id)
            that.$emit('changeData', 'couponName', data.text)
        });

        j('#wpfnl-ob-coupon-search').select2(select2Args);
    },
}
</script>
