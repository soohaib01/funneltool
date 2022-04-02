<template>
    <div class="wpfnl-step__single-template" v-if="activeStep == step.step_type">
        <div class="template-preview-image">
            <div class="importar-loader" v-show="showLoader">
                <span class="title-wrapper">
                    <span class="title">Step Importing</span>
                    <span class="dot-wrapper">
                        <span class="dot-one">.</span>
                        <span class="dot-two">.</span>
                        <span class="dot-three">.</span>
                    </span>
                </span>
            </div>

            <div class="step-image-wrapper" :style="{ backgroundImage: `url(${step.featured_image})` }">
                <!-- <img :src="step.featured_image" alt="step template" class="template-img step-previw-img"> -->
            </div>
        </div>

        <div class="step-template-info">
            <span class="title">{{step.title ? step.title.rendered : ''}}</span>
            <div class="template-action">
                <a :href="step.link" target="_blank" class="step-btn  preview"> preview </a>
                <a href="#" class="step-btn import wpfnl-import-step" :data-funnel-id="funnelID" @click="importStep"> import </a>
            </div>
        </div>
    </div>
</template>
<script>
    var j = jQuery.noConflict();
    export default {
        name: 'SingleStep',
        props: {
            step: Object,
            funnelID: String,
            activeStep: String,
        },
        data: function () {
            return {
                showLoader : false,
            }
        },
        methods: {
            importStep: function (e) {
                e.preventDefault();
                j('.wpfnl-create-step_inner-content .not-clickable-overlay').show();

                var that = this;
                this.showLoader = true;
                that.createStep( {'id' : this.step.id, 'step_type' : this.step.step_type, 'name' : this.step.title ? this.step.title.rendered : this.step.step_type}, this.funnelID, that);
            },
            createStep: function (step, funnelID, that) {
                var that = this,
                    payload = {
                        'step'      : step,
                        'funnelID'  : funnelID,
                        'source'    : 'remote'
                    };
                wpAjaxHelperRequest( "wpfunnel-import-step", payload )
                    .success( function( response ) {
                        that.showLoader = false;
                        that.afterStepCreationRedirect(response.stepID, funnelID);
                        console.log(response)
                    })
                    .error( function( response ) {
                        that.showLoader = false;
                        console.log( "Uh, oh!" );
                        console.log( response.statusText );
                    });
            },
            afterStepCreationRedirect: function (stepID, funnelID) {
                var payload = {
                    'stepID'    : stepID,
                    'funnelID'  : funnelID,
                    'source'    : 'remote',
                };
                wpAjaxHelperRequest( "wpfunnel-after-step-creation", payload )
                    .success( function( response ) {
                        window.location = response.redirectLink
                    })
                    .error( function( response ) {
                        console.log(response)
                    });
            },
        }
    }
</script>
