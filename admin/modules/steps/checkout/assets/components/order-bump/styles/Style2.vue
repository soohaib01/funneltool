<template>
    <div class="wpfnl-order-bump__template-style2">
        <!-- <span class="preview-tag">preview</span> -->

        <div class="offer-checkbox">
            <span class="nav-arrow">
                <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.9081 6.72871L8.65523 12.9102C8.56587 12.9982 8.43279 13.0236 8.31471 12.9771C8.19788 12.9288 8.12226 12.816 8.12226 12.6908V9.2825H0.312405C0.139963 9.28247 0 9.14368 0 8.97264V4.01504C0 3.844 0.139963 3.70518 0.312405 3.70518H8.12229V0.309859C8.12229 0.18469 8.19853 0.0718956 8.31535 0.0235586C8.3541 0.00743675 8.39469 0 8.4347 0C8.51591 0 8.59589 0.0316048 8.65587 0.0904856L14.9088 6.28997C14.9675 6.34821 15 6.42693 15 6.50934C15 6.59175 14.9668 6.67044 14.9081 6.72871Z" fill="#EE8134"/>
                </svg>
            </span>

            <span class="wpfnl-checkbox">
                <input type="checkbox" id="take-offer-checkbox2" name="take-offer-checkbox2" value="" >
                <label for="take-offer-checkbox2">{{checkBoxLabel}}</label>
            </span>

            <span class="product-price" v-html="htmlPrice"></span>

        </div>

        <div class="template-preview-wrapper">
            <div class="template-img" :style="{ backgroundImage: 'url(' + productImage + ')' }">
                <img :src="productImage" alt="" class="for-mobile">
            </div>

            <div class="template-content">
                <h5 class="template-title">{{productName}}</h5>
                <p class="description">{{productDescriptionText}}</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Style2',
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
