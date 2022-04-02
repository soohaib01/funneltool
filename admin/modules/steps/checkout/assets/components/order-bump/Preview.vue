<template>
    <div class="order-bump-template-preview-area">
        <div class="preview-header">
            <h4>Preview</h4>
        </div>
        <Style1
            v-if="selectedStyle=='style1'"
            :productImage="productImage"
            :checkBoxLabel="checkBoxLabel"
            :highLightText="highLightText"
            :productDescriptionText="productDescriptionText"
            :productName="productName"
            :price="price"
            :salePrice="salePrice"
            :htmlPrice="htmlPrice"
        />

        <Style2
            v-if="selectedStyle=='style2'"
            :productImage="productImage"
            :checkBoxLabel="checkBoxLabel"
            :highLightText="highLightText"
            :productDescriptionText="productDescriptionText"
            :productName="productName"
            :price="price"
            :salePrice="salePrice"
            :htmlPrice="htmlPrice"
        />
    </div>
</template>

<script>
import Style1 from "./styles/Style1.vue";
import Style2 from "./styles/Style2.vue";
export default {
    name: 'Preview',
    props: {
        productImage: String,
        checkBoxLabel: String,
        highLightText: String,
        productDescriptionText: String,
        productName: String,
        selectedStyle: String,
        price: String,
        salePrice: String,
        htmlPrice: String,
    },
    components: {
        Style1,
        Style2,
    }
}
</script>
