<template>
    <div>
        <div class="wpfnl-field-wrapper">
            <label for="wpfnl-ob-product-search">Select Product</label>
            <div class="wpfnl-fields">
                <div class="select-product">
                    <select class="wpfnl-ob-product-search" id="wpfnl-ob-product-search" name="wpfnl-ob-product-id" data-placeholder="Search for product">
                        <option v-if="product" :value="product">{{productName}}</option>
                    </select>
                </div>
            </div>
            <!-- <button type="wpfnl_update_selected_product" name="wpfnl_update_selected_product" @click="handleProductUpdate" >Update Product</button> -->
        </div>

        <div class="wpfnl-field-wrapper">
            <label for="product-quantity">Product Quantity</label>
            <div class="wpfnl-fields">
                <input type="number" id="product-quantity" name="product-quantity" :value='quantity' @input="changeData('quantity', $event.target.value)"  min="1" />
            </div>
        </div>

        <ImageUpload
            :imageUploadIcon="imageUploadIcon"
            :productImage="productImage"
            :imageUploaded="imageUploaded"
            @changeData="changeData"
        />

        <div class="wpfnl-field-wrapper">
            <label for="highlight-text"> Highlight Text </label>
            <div class="wpfnl-fields">
                <input type="text" id="highlight-text" name="highlight-text" :value="highLightText" @input="changeData('highLightText', $event.target.value)" />
            </div>
        </div>

        <div class="wpfnl-field-wrapper">
            <label for="checkbox-label"> Checkbox Label </label>
            <div class="wpfnl-fields">
                <input type="text" id="checkbox-label" name="checkbox-label" :value="checkBoxLabel" @input="changeData('checkBoxLabel', $event.target.value)" />
            </div>
        </div>

        <div class="wpfnl-field-wrapper top-align">
            <label for="product-description"> Product Description </label>
            <div class="wpfnl-fields">
                <textarea name="product-description" id="product-description" cols="30" rows="4" @input="changeData('productDescriptionText', $event.target.value)">{{productDescriptionText}}</textarea>
            </div>
        </div>
    </div>

</template>

<script>
import ImageUpload from "./ImageUpload.vue";
import apiFetch from "@wordpress/api-fetch";

const nonce = window.WPFunnelVars.nonce;
apiFetch.use(apiFetch.createNonceMiddleware(nonce));

var j = jQuery.noConflict();
export default {
    name: 'ProductSelection',
    props: {
        product: String,
        imageUploadIcon: String,
        productImage: String,
        quantity: String,
        highLightText: String,
        checkBoxLabel: String,
        productDescriptionText: String,
        productName: String,
        productType: String,
        imageUploaded: Boolean,
    },
    components: {
        ImageUpload,
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
                        action       : 'order_bump_search_products',
                        security     : window.CheckoutStep.security,
                    };
                },
                processResults: function( data ) {
                    var terms = [];
                    if ( data ) {
                        j.each( data, function( id, value ) {
                            terms.push( { id: id, text: value.name, price: value.price, sale_price: value.sale_price, title: value.title, html_price: value.html_price, productType: value.product_type, productImage: value.img } );
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

        j('#wpfnl-ob-product-search').on('select2:select', function (e) {
            var data = e.params.data;
            that.$emit('changeData', 'product', data.id)
            that.$emit('changeData', 'productName', data.title)
            that.$emit('changeData', 'price', data.price)
            that.$emit('changeData', 'salePrice', data.sale_price)
            that.$emit('changeData', 'htmlPrice', data.html_price)
            that.$emit('changeData', 'productImage', data.productImage)
            that.$emit('changeData', 'productType', data.productType)
            if(data.productImage) that.$emit('changeData', 'imageUploaded', true)
        });

        j('#wpfnl-ob-product-search').select2(select2Args);
    },
    methods: {
        changeData: function (param, value) {
            this.$emit('changeData', param, value)
        },

        handleProductUpdate: function () {
          apiFetch({
              path: window.CheckoutStep.rest_api_url + 'wpfunnels/v1/updateSelectedProduct?product=' + this.product,
              method: 'GET'
          }).then(response => {
              console.log(response);
              this.$emit('changeData', 'productName', response.title)
              this.$emit('changeData', 'price', response.price)
              this.$emit('changeData', 'salePrice', response.sale_price)
              this.$emit('changeData', 'htmlPrice', response.html_price)
              this.$emit('changeData', 'productImage', response.img)
          })
        }
    }
}
</script>
