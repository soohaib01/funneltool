<template>
    <div class="wpfnl-order-bump__template-style1">
        <div class="template-preview-wrapper">
            <div class="template-img" :style="{ backgroundImage: 'url(' + productImage + ')' }">
                <!-- <img :src="productImage" alt="" class="for-mobile"> -->
            </div>

            <div class="template-content">
                <h5 class="template-title">{{productName}}</h5>
                <h6 class="subtitle">{{highLightText}}</h6>
                <p class="description">{{productDescriptionText}}</p>

                <p class="product-price">
                    <strong>Price: </strong>
                    <span v-html="htmlPrice"></span>
                </p>

            </div>
        </div>

        <div class="offer-checkbox">
            <span class="wpfnl-checkbox">
                <input type="checkbox" name="take-offer-checkbox" id="take-offer-checkbox" />
                <label for="take-offer-checkbox">{{checkBoxLabel}}</label>
            </span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Style1',
    props: {
        productImage: String,
        checkBoxLabel: String,
        highLightText: String,
        productDescriptionText: String,
        productName: String,
        price: String,
        salePrice: String,
        htmlPrice: String,
    }
}
</script>
