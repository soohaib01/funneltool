<template>
    <div class="wpfnl-field-wrapper top-align upload-product-image">
        <label> Product Image </label>
        <div class="wpfnl-fields">
            <label for="orderbump-product-image" class="image-label" :class="imageUploaded? 'img-uploaded' : ''" id="orderbump-product-image" :style="[imageUploaded ? {backgroundImage: 'url(' + productImage + ')'} : '' ]">
                <input type="hidden" name="orderbump-product-image-id" value="" id="orderbump-product-image-id" />
                <input type="hidden" name="orderbump-product-image-url" value="" id="orderbump-product-image-url" />
                <span class="icon-wrapper">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.16671 2.66667H32.8334C36.05 2.66667 38.6667 5.28341 38.6667 8.5V20.8958C38.6667 21.4988 38.2953 22.0397 37.7327 22.2568C37.1699 22.4735 36.5316 22.3217 36.1272 21.8742L28.9742 13.9685L18.3486 27.4834L21.7016 31.8333H27.073C27.8785 31.8333 28.5313 32.4862 28.5313 33.2917C28.5313 34.0972 27.8785 34.75 27.073 34.75H7.16671C3.95011 34.75 1.33337 32.1333 1.33337 28.9167V8.5C1.33337 5.28341 3.95011 2.66667 7.16671 2.66667ZM29.9774 10.7299L35.75 17.1104V8.50001C35.75 6.89185 34.4415 5.58334 32.8334 5.58334H7.16671C5.55855 5.58334 4.25004 6.89185 4.25004 8.50001V28.9167C4.25004 30.5248 5.55855 31.8333 7.16671 31.8333H18.019L11.5428 23.432L9.48836 26.1031C8.99703 26.7414 8.08158 26.861 7.44299 26.3697C6.80469 25.8787 6.68534 24.9632 7.17639 24.3246L10.3859 20.1524C10.6616 19.7938 11.0885 19.5836 11.5409 19.5833H11.5417C11.994 19.5833 12.4207 19.7933 12.6967 20.1513L16.5117 25.1005L27.7494 10.8071C28.0152 10.4693 28.4162 10.266 28.8455 10.2509C29.2744 10.2352 29.6891 10.4112 29.9774 10.7299Z" fill="#6E42D3"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.16675 12.2917C7.16675 9.87916 9.12923 7.91667 11.5417 7.91667C13.9543 7.91667 15.9167 9.87916 15.9167 12.2917C15.9167 14.7042 13.9543 16.6667 11.5417 16.6667C9.12923 16.6667 7.16675 14.7042 7.16675 12.2917ZM10.0834 12.2917C10.0834 13.0957 10.7377 13.75 11.5417 13.75C12.3458 13.75 13.0001 13.0957 13.0001 12.2917C13.0001 11.4876 12.3458 10.8333 11.5417 10.8333C10.7377 10.8333 10.0834 11.4876 10.0834 12.2917Z" fill="#6E42D3"/>
                        <path d="M35.4931 24.7561C35.4888 24.7519 35.4846 24.7473 35.4803 24.743C34.791 24.0489 33.8741 23.6667 32.8983 23.6667C31.9253 23.6667 31.011 24.0466 30.3226 24.7368L27.507 27.5139C26.9337 28.0793 26.9274 29.0027 27.4931 29.5761C28.0585 30.1494 28.9819 30.156 29.5552 29.5903L31.44 27.7315V35.9167C31.44 36.7222 32.0928 37.375 32.8983 37.375C33.7038 37.375 34.3566 36.7222 34.3566 35.9167V27.7759L36.1605 29.6393C36.4464 29.9349 36.8273 30.0833 37.2084 30.0833C37.5738 30.0833 37.9395 29.9469 38.2226 29.6729C38.8014 29.1126 38.8162 28.1895 38.256 27.6107L35.4931 24.7561Z" fill="#6E42D3"/>
                    </svg>
                    <span class="title" v-if="imageUploaded" >Click to Change Image</span>
                    <span class="title" v-else >Click to Upload an Image</span>
                </span>
            </label>
            <span class="hints">Recommended image size 160px x 160px </span>
        </div>
    </div>
</template>

<script>
var j = jQuery.noConflict();
export default {
    name: 'ImageUpload',
    props: {
        productImage: String,
        imageUploadIcon: String,
        imageUploaded: Boolean,
    },
    mounted: function(){
        let frame,
            that = this;
        j('#orderbump-product-image').on('click', function(){
            if(frame){
                frame.open();
                return false;
            }
            frame = wp.media({
                title: 'Upload Image',
                button: {
                    text: 'Select Image'
                },
                multiple: false
            });

            frame.on('select', function(){
                var attachment = frame.state().get('selection').first().toJSON();
                that.$emit('changeData', 'productImage', attachment.url)
                that.$emit('changeData', 'imageUploaded', true)
            });

            frame.open();
            return false;
        });
    }
}
</script>
