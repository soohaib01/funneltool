<template>
<div class="wpfnl-box discount-settings">
    <div class="wpfnl-field-wrapper">
        <label class="has-tooltip" for="discount-options">
            Discount Type
            <span class="wpfnl-tooltip">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 0C2.68388 0 0 2.68352 0 6C0 9.31612 2.68352 12 6 12C9.31612 12 12 9.31648 12 6C12 2.68388 9.31648 0 6 0Z" fill="#6E42D3" />
                    <path d="M6.18136 8.25C5.66666 8.25 5.25 8.60793 5.25 9.0375C5.25 9.4568 5.65439 9.825 6.18136 9.825C6.70834 9.825 7.125 9.4568 7.125 9.0375C7.125 8.60793 6.69607 8.25 6.18136 8.25Z" fill="white" />
                    <path
                      d="M6.10214 3C4.66162 3 4 3.73543 4 4.23179C4 4.59032 4.35218 4.75578 4.64025 4.75578C5.21647 4.75578 4.98175 4.04796 6.07016 4.04796C6.60371 4.04796 7.03054 4.25017 7.03054 4.67306C7.03054 5.16943 6.43298 5.45445 6.08084 5.71179C5.77136 5.94161 5.36587 6.31851 5.36587 7.1091C5.36587 7.58706 5.51527 7.725 5.95274 7.725C6.47565 7.725 6.58234 7.52276 6.58234 7.3481C6.58234 6.87014 6.59299 6.59433 7.17986 6.19905C7.46801 6.00601 8.375 5.38091 8.375 4.51681C8.375 3.65271 7.46801 3 6.10214 3Z"
                      fill="white" />
                </svg>

                <p>Select discount type</p>
            </span>
        </label>

        <div class="wpfnl-fields">
            <select name="order-bump-template" id="discount-options" @change="changeData('discountOption', $event.target.value)">
                <option v-for="option in discountOptions" :value="option.value" :selected="option.value == discountOption">{{option.name}}
                </option>
            </select>
        </div>
    </div>

    <div class="wpfnl-field-wrapper" v-if="showDiscountMeta && discountOption !== 'coupon'">
        <label class="has-tooltip" for="discount-value">
            Discount Value
            <span class="wpfnl-tooltip">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 0C2.68388 0 0 2.68352 0 6C0 9.31612 2.68352 12 6 12C9.31612 12 12 9.31648 12 6C12 2.68388 9.31648 0 6 0Z" fill="#6E42D3" />
                    <path d="M6.18136 8.25C5.66666 8.25 5.25 8.60793 5.25 9.0375C5.25 9.4568 5.65439 9.825 6.18136 9.825C6.70834 9.825 7.125 9.4568 7.125 9.0375C7.125 8.60793 6.69607 8.25 6.18136 8.25Z" fill="white" />
                    <path
                      d="M6.10214 3C4.66162 3 4 3.73543 4 4.23179C4 4.59032 4.35218 4.75578 4.64025 4.75578C5.21647 4.75578 4.98175 4.04796 6.07016 4.04796C6.60371 4.04796 7.03054 4.25017 7.03054 4.67306C7.03054 5.16943 6.43298 5.45445 6.08084 5.71179C5.77136 5.94161 5.36587 6.31851 5.36587 7.1091C5.36587 7.58706 5.51527 7.725 5.95274 7.725C6.47565 7.725 6.58234 7.52276 6.58234 7.3481C6.58234 6.87014 6.59299 6.59433 7.17986 6.19905C7.46801 6.00601 8.375 5.38091 8.375 4.51681C8.375 3.65271 7.46801 3 6.10214 3Z"
                      fill="white" />
                </svg>

                <p>Select discount value</p>
            </span>
        </label>

        <div class="wpfnl-fields">
            <input type="number" v-bind:class = "(productType == 'variable-subscription')?'non-input-field':''" id="discount-value" name="discount-value" v-model="mutedDiscountValue" min="1" @change="discountPriceUpdate($event.target.value)" @input="changeData('discountValue', $event.target.value)" :disabled="productType == 'variable-subscription'" />
        </div>
    </div>

    <Coupon :discountValue="discountValue" :couponName="couponName" v-if="showDiscountMeta && discountOption === 'coupon'" @changeData="changeData" />

    <div class="wpfnl-field-wrapper">
        <label> Original Price </label>
        <div class="wpfnl-fields">
            <span class="non-input-field" v-html="price"></span>
        </div>
    </div>

    <div class="wpfnl-field-wrapper">
        <label> Sale Price </label>
        <div class="wpfnl-fields">
            <span class="non-input-field" v-html="salePrice"></span>
        </div>
    </div>

    <div class="wpfnl-field-wrapper" v-if="showDiscountMeta && discountOption !== 'coupon'">
        <label> Discounted Offer Price </label>
        <div class="wpfnl-fields">
            <span class="non-input-field">{{mutedDiscountPrice}}</span>
        </div>
    </div>

    <div class="wpfnl-field-wrapper top-align no-title-checkbox">
        <label> Replace First Product </label>
        <div class="wpfnl-fields">
            <span class="wpfnl-checkbox no-title">
                <!-- <input type="checkbox" name="replace-first-product" id="replace-first-product" /> -->
                <input type="checkbox" :checked="isReplace === 'yes'" name="replace-first-product" id="replace-first-product" @input="changeData('replace', $event.target.checked)" />
                <label for="replace-first-product"></label>
            </span>
            <span class="hints desktop">It will replace the first selected product (from checkout products) with the order bump product.</span>
        </div>
        <span class="hints mobile">It will replace the first selected product (from checkout products) with the order bump product.</span>
    </div>

    <div class="wpfnl-field-wrapper top-align">
        <label>On Order Bump Purchase - Next Step</label>
        <div class="wpfnl-fields">
            <select name="order-bump-next-step" @change="changeData('obNextStep', $event.target.value)">
                <option v-for="option in nextStepOptions" :value="option.value" :selected="option.value == obNextStep">{{option.name}}
                </option>
            </select>
            <span class="hints">Note: Select the step if you want to redirect to a different step on the order bump purchase.</span>
        </div>
    </div>
</div>
</template>

<script>
import Coupon from "./Coupon.vue";
import apiFetch from "@wordpress/api-fetch";

//const nonce = window.CheckoutStep.nonce;
const nonce = window.WPFunnelVars.nonce;
apiFetch.use(apiFetch.createNonceMiddleware(nonce));

export default {
    name: 'Discount',
    props: {
        replace: false,
        product: String,
        productType: String,
        discountOption: String,
        discountPrice: {String, Number},
        couponName: String,
        tooltipIcon: String,
        discountValue: String,
        obNextStep: String,
        price: String,
        salePrice: String,
        htmlPrice: String,
        isReplace: String,
    },
    components: {
        Coupon
    },
    data: function() {
        return {
            mutedDiscountValue : '',
            discountOptions: [{
                    name: 'Original',
                    value: 'original'
                },
                {
                    name: 'Percentage',
                    value: 'discount-percentage'
                },
                {
                    name: 'Flat Amount',
                    value: 'discount-price'
                }
            ],
            nextStepOptions: [{
                name: 'Default',
                value: 'default'
            }, ],
        }
    },
    created() {
        this.mutedDiscountValue = this.discountValue
        apiFetch({
            path: window.CheckoutStep.rest_api_url + 'wpfunnels/v1/getOtherSteps?step_id=' + window.CheckoutStep.step_id + '&from=plugin',
            method: 'GET'
        }).then(response => {
            this.nextStepOptions = response;
        })

    },
    methods: {
        changeData: function(param, value) {
            this.$emit('changeData', param, value)
        },
        discountPriceUpdate: function(val) {
          // this.discountValue = parseFloat(val).toFixed(2);
          val = parseFloat(val).toFixed(2);
          this.$emit('change', val);
          apiFetch({
              path: window.CheckoutStep.rest_api_url + 'wpfunnels/v1/calculateDiscountPrice?discount_type=' + this.discountOption + '&discount_value=' + val + '&product=' + this.product,
              method: 'GET'
          }).then(response => {
              this.$emit('changeData', 'discountPrice', response)
          })
        },
    },
    computed: {
        showDiscountMeta() {
            return this.discountOption !== 'original';
        },
        Replace() {
            return this.replace ? 'yes' : 'no';
        },
        // mutedDiscountValue: {
        //     get(){
        //       return this.discountValue
        //     },
        //     set(newValue){
        //         return '50'
        //     }
        // },
        mutedDiscountPrice: {
            get(){
                if (this.discountValue != "") {
                  return window.CheckoutStep.wc_currency + this.discountPrice
                }
                return this.discountPrice
            },
            set(newValue){
                return newValue
            }
        },
    },
    watch: {
      // whenever question changes, this function will run
      discountValue: function (newmutedDiscountValue, oldmutedDiscountValue) {
        
        let calc = parseFloat(newmutedDiscountValue).toFixed(2)
        if (parseFloat(newmutedDiscountValue) == calc) {
          this.mutedDiscountValue = newmutedDiscountValue
        }
        else {
          this.mutedDiscountValue = calc
        }
      }
    }
}
</script>
