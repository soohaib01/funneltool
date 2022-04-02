<template>
    <div class="wpfnl-step__single-template" >
        <div class="template-preview-image">
            <div class="step-image-wrapper" :style="{ backgroundImage: `url(${image})` }">
                <!-- <img :src="image" alt="step template" class="template-img step-previw-img"> -->
            </div>
        </div>

        <div class="step-template-info">
            <span class="title">{{step.name}}</span>
            <div class="template-action">
                <a :href="step.preview_link" target="_blank" class="step-btn preview"> preview </a>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'SingleStepPreview',
        props: {
            step: Object,
            image: String,
            previewLink: String,
            isAddNewFunnelButtonDisabled: Boolean,
        },
        data: function () {
            return {
                showLoader : false
            }
        },
        methods: {
            importStep: function (e) {
                e.preventDefault();
                var that = this;
                this.showLoader = true;
                that.createStep( {'id' : this.step.id, 'type' : this.step.step_type, 'name' : this.step.step_type}, this.funnelID, that);
            },
        }
    }
</script>
