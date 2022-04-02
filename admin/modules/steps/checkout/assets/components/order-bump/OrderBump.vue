<template>
    <div>
        <div class="checkout-order-bump-setting-tab__content-wrapper">
            <div class="order-bump-settings-area">
                <h4 class="title">Order Bump</h4>

                <div class="wpfnl-box order-bump-settings enable-order-bump">
                    <div class="wpfnl-field-wrapper no-title-checkbox">
                        <label for="enable-order-bump"> Enable Order Bump </label>
                        <div class="wpfnl-fields">
                            <span class="wpfnl-checkbox no-title">
                                <input
                                    type="checkbox"
                                    :checked="isEnabled === 'yes'"
                                    name="enable-order-bump"
                                    id="enable-order-bump"
                                    @input="changeData('showOb', $event.target.checked)"
                                />
                                <label for="enable-order-bump"></label>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="wpfnl-box order-bump-settings" v-if="showOb">

                    <div class="wpfnl-field-wrapper">
                        <label class="has-tooltip">
                            Select Template
                            <span class="wpfnl-tooltip">
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6 0C2.68388 0 0 2.68352 0 6C0 9.31612 2.68352 12 6 12C9.31612 12 12 9.31648 12 6C12 2.68388 9.31648 0 6 0Z"
                                        fill="#6E42D3"/>
                                    <path
                                        d="M6.18136 8.25C5.66666 8.25 5.25 8.60793 5.25 9.0375C5.25 9.4568 5.65439 9.825 6.18136 9.825C6.70834 9.825 7.125 9.4568 7.125 9.0375C7.125 8.60793 6.69607 8.25 6.18136 8.25Z"
                                        fill="white"/>
                                    <path
                                        d="M6.10214 3C4.66162 3 4 3.73543 4 4.23179C4 4.59032 4.35218 4.75578 4.64025 4.75578C5.21647 4.75578 4.98175 4.04796 6.07016 4.04796C6.60371 4.04796 7.03054 4.25017 7.03054 4.67306C7.03054 5.16943 6.43298 5.45445 6.08084 5.71179C5.77136 5.94161 5.36587 6.31851 5.36587 7.1091C5.36587 7.58706 5.51527 7.725 5.95274 7.725C6.47565 7.725 6.58234 7.52276 6.58234 7.3481C6.58234 6.87014 6.59299 6.59433 7.17986 6.19905C7.46801 6.00601 8.375 5.38091 8.375 4.51681C8.375 3.65271 7.46801 3 6.10214 3Z"
                                        fill="white"/>
                                </svg>
                                <p>Select your template</p>
                            </span>
                        </label>

                        <div class="wpfnl-fields">
                            <select name="order-bump-template"
                                    @change="changeData('selectedStyle', $event.target.value)">
                                <option
                                    v-for="option in styles"
                                    :value="option.value"
                                    :selected="option.value == selectedStyle"
                                >{{option.name}}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="wpfnl-field-wrapper">
                        <label> Order Bump Position </label>
                        <div class="wpfnl-fields">
                            <select name="order-bump-template" @change="changeData('position', $event.target.value)">
                                <option
                                    v-for="option in positionOptions"
                                    :value="option.value"
                                    :selected="option.value == position"
                                >{{option.name}}
                                </option>
                            </select>
                        </div>
                    </div>

                    <ProductSelection
                        :product="product"
                        :productName="productName"
                        :productType="productType"
                        :quantity="quantity"
                        :highLightText="highLightText"
                        :checkBoxLabel="checkBoxLabel"
                        :productDescriptionText="productDescriptionText"
                        :productImage="productImage"
                        :imageUploadIcon="imageUploadIcon"
                        :imageUploaded="imageUploaded"
                        @setUpProductData="setUpProductData"
                        @changeData="changeData"
                    />

                </div>

                <Discount
                    :discountOption="discountOption"
                    :discountValue="discountValue"
                    :product="product"
                    :productType="productType"
                    :tooltipIcon="tooltipIcon"
                    :obNextStep="obNextStep"
                    :isReplace="isReplace"
                    :replace="replace"
                    :couponName="couponName"
                    :price="price"
                    :salePrice="salePrice"
                    :htmlPrice="htmlPrice"
                    :discountPrice="discountPrice"
                    @changeData="changeData"
                    v-if="showOb"
                />
            </div>

            <Preview
                :productImage="productImage"
                :checkBoxLabel="checkBoxLabel"
                :highLightText="highLightText"
                :productDescriptionText="productDescriptionText"
                :productName="productName"
                :selectedStyle="selectedStyle"
                :price="price"
                :salePrice="salePrice"
                :htmlPrice="htmlPrice"
                v-if="showOb"
            />
        </div>

        <div class="settings-content__footer">
            <span class="wpfnl-alert box" :class="message ? 'wpfnl-success' : ''" v-if="message" style="display: block;">{{message}}</span>
            <button class="btn-default update" id="save-order-bump" @click="saveSettings">
                Save Changes
                <span class="wpfnl-loader" v-if="loader" :style="{display: loader ? 'block': 'none'}"></span>
            </button>
        </div>

    </div>
</template>


<script>
import ProductSelection from "./ProductSelection.vue";
import ImageUpload from "./ImageUpload.vue";
import Discount from "./Discount.vue";
import Preview from "./Preview.vue";
import apiFetch from "@wordpress/api-fetch";

// const nonce = window.CheckoutStep.nonce;
const nonce = window.WPFunnelVars.nonce;
apiFetch.use(apiFetch.createNonceMiddleware(nonce));

var j = jQuery.noConflict()
export default {
    name: 'OrderBump',
    components: {
        ProductSelection,
        ImageUpload,
        Discount,
        Preview,
    },
    data: function () {
        return {
            showOb: false,
            tooltipIcon: window.CheckoutStep.tooltipIcon,
            imageUploadIcon: window.CheckoutStep.imageUploadIcon,
            stepID: window.CheckoutStep.step_id,
            styles: [
                {name: 'Style 1', value: 'style1'},
                {name: 'Style 2', value: 'style2'}
            ],
            positionOptions: [
                {name: 'Before Order Details', value: 'after-order'},
                {name: 'Before Checkout Details', value: 'before-checkout'},
                {name: 'After Customer Details', value: 'after-customer-details'},
                {name: 'Before Payment Options', value: 'before-payment'},
                {name: 'After Payment Options', value: 'after-payment'},
                {name: 'Pop-up offer', value: 'popup'},
            ],
            selectedStyle: 'style1',
            position: 'after-order',
            product: '',
            productName: '6D Screen Protector (20% OFF) ',
            productType: '',
            price: '',
            salePrice: '',
            quantity: '1',
            htmlPrice: '$23',
            discountPrice: '',
            productImage: 'https://via.placeholder.com/150x156',
            highLightText: 'Special one time offer',
            checkBoxLabel: 'Grab this offer with one click!',
            productDescriptionText: 'Get this scratch proof 6D Tempered Glass Screen Protector for your iPhone. Keep your phone safe and sound just like a new one. ',
            discountOption: 'original',
            discountValue: '',
            couponName: '',
            obNextStep: 'default',
            isReplace: 'no',
            replace: '',
            loader: false,
            message: '',
            imageUploaded: false,
            backToStep: window.CheckoutStep.back,
        }
    },
    created() {
        apiFetch({
            path: `${window.CheckoutStep.rest_api_url}wpfunnels/v1/order-bump/${this.stepID}`,
            method: 'GET'
        }).then(response => {
            if (response.success) {
                Object.keys(response).map((key,index) => {
                    if(key === 'isEnabled') {
                        this.showOb = response.isEnabled === 'yes'
                    }
                    if(key === 'productImage') {
                        this.imageUploaded = !!response.productImage
                    }
                    if(key === 'productImage') {
                        this.imageUploaded = !!response.productImage
                    }
                    if(Object.keys(this.$data).includes(key)) {
                        this[key] = response[key]
                    }
                })
            }
        })

    },
    methods: {
        setUpProductData: function (data) {
            // this.quantity = '1';
        },
        changeData: function (paramName, value) {
            
            this[paramName] = value
        },
        saveSettings: function (e) {
            e.preventDefault();
            let that = this;
            that.loader = true;

            if(this.replace) {
                this.isReplace = 'yes';
            }
            else {
              this.isReplace = 'no';
            }

            if (this.discountValue) {
              this.discountValue = parseFloat(this.discountValue).toFixed(2)
            }

            return new Promise((resolve, reject) => {
                apiFetch({
                    path: window.CheckoutStep.rest_api_url + 'wpfunnels/v1/order-bump',
                    method: 'POST',
                    data: {
                        'selectedStyle': this.selectedStyle,
                        'position': this.position,
                        'product': this.product,
                        'quantity': this.quantity,
                        'price': this.price,
                        'salePrice': this.salePrice,
                        'htmlPrice': this.htmlPrice,
                        'productImage': this.productImage,
                        'highLightText': this.highLightText,
                        'checkBoxLabel': this.checkBoxLabel,
                        'productDescriptionText': this.productDescriptionText,
                        'discountOption': this.discountOption,
                        'discountValue': this.discountValue,
                        'couponName': this.couponName,
                        'obNextStep': this.obNextStep,
                        'stepID': this.stepID,
                        'productName': this.productName,
                        'productType': this.productType,
                        'isEnabled': this.isEnabled,
                        'isReplace': this.isReplace,
                        'replace': this.replace,
                    },

                }).then(response => {
                    // resolve(response.success);
                    that.loader = false;
                    that.message = 'Saved Successfully'

                    setTimeout( function(){
                        that.message = '';
                    }, 1500);

                }, error => {
                    reject(error);
                });
            });
        }
    },
    computed: {
        isEnabled() {
            return this.showOb ? 'yes' : 'no';
        }
    }
}
</script>
