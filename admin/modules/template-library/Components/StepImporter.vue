<template>
    <div id="wpfnl-create-steps_inner-content" class="wpfnl-create-step_inner-content">
        <div class="not-clickable-overlay">
            <span class="import-message">Creating Step...</span>
        </div>

        <div id="wpfnl-create-step__template-wrapper" class="wpfnl-create-step__template-wrapper template-library-modal__body">

            <div class="steps-template__filter-wrapper">
                <span class="wpfnl-modal-close" @click="hideStepImporter">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.25 14C13.25 14.4142 13.5858 14.75 14 14.75C14.4142 14.75 14.75 14.4142 14.75 14H13.25ZM1 5L0.46967 4.46967C0.255171 4.68417 0.191005 5.00676 0.30709 5.28701C0.423176 5.56727 0.696653 5.75 1 5.75L1 5ZM5.53033 1.53033C5.82322 1.23744 5.82322 0.762563 5.53033 0.46967C5.23744 0.176777 4.76256 0.176777 4.46967 0.46967L5.53033 1.53033ZM4.46967 9.53033C4.76256 9.82322 5.23744 9.82322 5.53033 9.53033C5.82322 9.23744 5.82322 8.76256 5.53033 8.46967L4.46967 9.53033ZM1.53033 4.46967C1.23744 4.17678 0.762563 4.17678 0.46967 4.46967C0.176777 4.76256 0.176777 5.23744 0.46967 5.53033L1.53033 4.46967ZM14.75 14V8H13.25V14H14.75ZM14.75 8C14.75 5.92893 13.0711 4.25 11 4.25V5.75C12.2426 5.75 13.25 6.75736 13.25 8H14.75ZM11 4.25H1V5.75H11V4.25ZM1.53033 5.53033L5.53033 1.53033L4.46967 0.46967L0.46967 4.46967L1.53033 5.53033ZM5.53033 8.46967L1.53033 4.46967L0.46967 5.53033L4.46967 9.53033L5.53033 8.46967Z" fill="#7A8B9A"/>
                    </svg>
                </span>

                <StepCategorySelect :categories="stepCategories" :active-category="activeStepCategory" @doStepCatFilter="doStepCatFilter"/>

                <ul class="pro-free__filter">
                    <li data-filter="all" :class="stepTemplateType == 'all' ? 'active' : '' " @click="doStepFreeProFilter('all')">all</li>
                    <li data-filter="free" :class="stepTemplateType == 'free' ? 'active' : '' " @click="doStepFreeProFilter('free')">free</li>
                    <li data-filter="pro" :class="stepTemplateType == 'pro' ? 'active' : '' " @click="doStepFreeProFilter('pro')">
                        <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10 1L14 7L19 3L17 13H3L1 3L6 7L10 1Z" stroke="#7A8B9A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Premium
                    </li>
                </ul>

                <!-- <div class="wpfnl-template__serarch-field">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="32" height="32" viewBox="0 0 28 28" stroke-width="1.5" stroke="#7A8B9A" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="10" cy="10" r="7" />
                        <line x1="21" y1="21" x2="15" y2="15" />
                    </svg>

                    <input type="search" name="step-template-search" placeholder="Search">
                </div> -->
            </div>


            <ul class="wpfnl-create-step__filter-nav" id="wpfnl-create-step__filter-nav">
                <li :class="{ active : activeItem == 'landing' }" id="create-step-landing" data-filter="landing" @click="doStepFilter">Landing</li>
                <li :class="{ active : activeItem == 'checkout' }" id="create-step-checkout" data-filter="checkout" @click="doStepFilter">Checkout</li>
                <li :class="{ active : activeItem == 'thankyou' }" id="create-step-thankyou" data-filter="thankyou" @click="doStepFilter">Thankyou</li>

                <!-- for upsell check if pro activated or not -->
                <li v-if="!isPro" class="disabled">
                    Upsell
                    <a :href="proUrl " target="_blank" title="Click to Upgrade Pro">
                        <span class="pro-tag">coming soon</span>
                    </a>
                </li>
                <li v-else :class="{ active : activeItem == 'upsell' }" data-filter="upsell" :id="isPro ? 'create-step-upsell' : ''" @click="doStepFilter" >Upsell</li>

                <!-- for downsell check if pro activated or not -->
                <li v-if="!isPro" class="disabled">
                    Downsell
                    <a :href="proUrl " target="_blank" title="Click to Upgrade Pro">
                        <span class="pro-tag">coming soon</span>
                    </a>
                </li>
                <li v-else data-filter="downsell" :class="{ active : activeItem == 'downsell' }" :id="isPro ? 'create-step-downsell' : ''" @click="doStepFilter" >Downsell </li>
            </ul>

            <div class="wpfnl-step__templates-wrapper-before">
                <div class="wpfnl-step__templates-wrapper">
                    <div class="wpfnl-step__single-template create__from-scratch">
                        <a href="#" id="wpfnl-create-step" class="btn-default" :data-step-type="activeStep" :data-funnel-id="funnelID" @click="createStep" v-bind:style='{"pointer-events" : (disabled? "none" : "" )}'>Start From scratch</a>

                        <div class="template-preview-image">
                            <div class="step-image-wrapper">
                            </div>
                        </div>
                        <div class="step-template-info">
                            <span class="title">title</span>
                        </div>
                    </div>

                    <SingleStep
                        v-for="(step, index) in steps"
                        :step="step"
                        :key="index"
                        :funnelID="funnelID"
                        :activeStep="activeStep"
                        v-if="activeStep === step.step_type"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import SingleStep from './SingleStep.vue'
    import StepCategorySelect from './StepCategorySelect.vue'
    import axios from 'axios'
    var j = jQuery.noConflict();
    export default {
        name: 'StepImporter',
        components: {
            SingleStep,
            StepCategorySelect
        },
        props: {
            steps: Array,
            categories: Array,
            activeStepCategory: String,
            activeStep: String,
            stepTemplateType: String,
            stepCategories: Array,
        },
        data: function () {
            return {
                activeClass : '',
                activeCat   : 'all',
                isPro       : window.template_library_object.is_pro,
                proUrl      : window.template_library_object.pro_url,
                funnelID    : window.template_library_object.funnel_id,
                activeItem   : 'landing',
                disabled: false,
            }
        },
        methods: {
            doStepFilter: function (e) {
                this.$emit('setActiveStep', e.currentTarget.getAttribute('data-filter') )
                this.$emit('doStepFilter', e.currentTarget.getAttribute('data-filter') )
                this.activeItem = e.currentTarget.getAttribute('data-filter');
            },
            doStepCatFilter: function (value) {
                this.$emit('doStepCatFilter', value )
            },
            doStepFreeProFilter: function (value) {
                this.$emit('doStepFreeProFilter', value )
            },

            createStep: function (e) {
                e.preventDefault()

                j('.wpfnl-create-step_inner-content .not-clickable-overlay .import-message').css("display", "inline-block");
                j('.wpfnl-create-step_inner-content .not-clickable-overlay').css({
                    "background-color": "rgba(0, 0, 0, 0.3)",
                    "display": "flex"
                });

                this.disabled = true;
                var that = this,
                    payload = {
                        'funnel_id'  : window.template_library_object.funnel_id,
                        'step_type'  : this.activeItem,
                    };
                wpAjaxHelperRequest( "create-step", payload )
                    .success( function( response ) {
                        payload.step_id = response.step_id;
                        payload.step_edit_link = response.step_edit_link;
                        payload.step_view_link = response.step_view_link;
                        that.$emit('hideStepImporter');
                        that.$emit('renderNode', payload);
                        that.disabled = false;
                        j('.wpfnl-create-step_inner-content .not-clickable-overlay .import-message').css("display", "none");
                        j('.wpfnl-create-step_inner-content .not-clickable-overlay').css({
                            "display": "none"
                        });
                    })
                    .error( function( response ) {
                        console.log(response)
                    });
            },
            renderNode: function (data) {
                this.$emit('renderNode', data )
            },

            hideStepImporter: function (e) {
                e.preventDefault();
                this.$emit('hideStepImporter');
            }
        }
    }
</script>
