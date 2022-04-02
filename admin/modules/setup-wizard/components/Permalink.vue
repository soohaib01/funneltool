<template>
    <div class="wizard-single-content permalinks">
        <div class="step-header">
            <h4 class="title">Permalinks</h4>
            <p class="description">Please Select how you want your Permalinks to be arranged for your Funnel pages.</p>
        </div>

        <div class="wizard-single-content__body">
            <div class="wpfnl-box">
                <div class="wpfnl-field-wrapper wpfnl-align-top">
                    <label> Default Permalink </label>
                    <div class="wpfnl-fields">
                        <div class="wpfnl-radiobtn">
                            <input
                            id="default-permalink"
                            v-model="checkedValue"
                            type="radio"
                            name="wpfunnels-set-permalink"
                            value="default"
                            checked>
                            <label for="default-permalink">Default WordPress Permalink</label>
                        </div>
                    </div>
                </div>
                <!-- /field-wrapper -->

                <div class="wpfnl-field-wrapper wpfnl-align-top">
                    <label>Funnel and Step Slug</label>
                    <div class="wpfnl-fields">
                        <div class="wpfnl-radiobtn">
                            <input
                            id="funnel-step-permalink"
                            v-model="checkedValue"
                            type="radio"
                            name="wpfunnels-set-permalink"
                            value="funnel-step">
                            <label for="funnel-step-permalink">{{ homeUrl }}/<code class="funnelbase">{{ funnelBase }}</code>/%funnelname%/<code class="stepbase">{{ stepBase }}</code>/%stepname%/</label>
                        </div>
                    </div>
                </div>
                <!-- /field-wrapper -->

                <div class="wpfnl-field-wrapper wpfnl-align-top">
                    <label>Funnel Slug</label>
                    <div class="wpfnl-fields">
                        <div class="wpfnl-radiobtn">
                            <input
                            id="funnel-slug-permalink"
                            v-model="checkedValue"
                            type="radio"
                            name="wpfunnels-set-permalink"
                            value="funnel">
                            <label for="funnel-slug-permalink">{{ homeUrl }}/<code class="funnelbase">{{ funnelBase }}</code>/%funnelname%/%stepname%/</label>
                        </div>
                    </div>
                </div>
                <!-- /field-wrapper -->

                <div class="wpfnl-field-wrapper wpfnl-align-top">
                    <label>Step Slug</label>
                    <div class="wpfnl-fields">
                        <div class="wpfnl-radiobtn">
                            <input
                            id="step-slug-permalink"
                            v-model="checkedValue"
                            type="radio"
                            name="wpfunnels-set-permalink"
                            value="step">
                            <label for="step-slug-permalink">{{ homeUrl }}/%funnelname%/<code class="stepbase">{{ stepBase }}</code>/%stepname%/</label>
                        </div>
                    </div>
                </div>
                <!-- /field-wrapper -->

                <div class="wpfnl-field-wrapper parmalink-base">
                    <label>Post Type Permalink Base</label>
                </div>
                <!-- /field-wrapper -->

                <div class="wpfnl-field-wrapper">
                    <label>Funnel Base</label>
                    <div class="wpfnl-fields">
                        <input
                            id="wpfunnels-permalink-funnel-base"
                            v-model="funnelBase"
                            type="text"
                            name="wpfnl-permalink-funnel-base" >
                    </div>
                </div>
                <!-- /field-wrapper -->

                <div class="wpfnl-field-wrapper">
                    <label>Step Base</label>
                    <div class="wpfnl-fields">
                        <input
                            id="wpfunnels-permalink-step-base"
                            v-model="stepBase"
                            type="text"
                            name="wpfnl-permalink-step-base">
                    </div>
                </div>
                <!-- /field-wrapper -->

            </div>
            <!-- /settings-box -->
        </div>

        <div class="wizard-single-content__footer">
            <a :href="prevStepLink" class="wizard-btn btn-default prev">previous</a>
            <a href="#" class="wizard-btn btn-default next" @click="saveFunnelPermalinks">Next
                <span class="wpfnl-loader" v-if="showLoader"></span>
            </a>
        </div>

    </div>
</template>

<script>
    import apiFetch from '@wordpress/api-fetch'
    export default {
        name: 'Permalink',
        props: {
            prevStepLink: String,
            showLoader: Boolean
        },
        data: function () {
            return {
                homeUrl: window.setup_wizard_obj.home_url,
                funnelBase: 'funnel',
                stepBase: 'steps',
                options: [
                    { name: 'Default Permalink', label: 'Default WordPress Permalink', value: 'default' },
                    { name: 'Funnel and Step Slug', label: `${window.setup_wizard_obj.home_url}/<code class="funnelbase">${this.funnelBase}</code>/%funnelname%/<code class="stepbase">${this.stepBase}</code>/%stepname%/`, value: 'funnel-step' },
                    { name: 'Funnel Slug\n', label: `${window.setup_wizard_obj.home_url}/<code class="funnelbase">${this.funnelBase}</code>/%funnelname%/%stepname%/`, value: 'funnel' },
                    { name: 'Step Slug', label: `${window.setup_wizard_obj.home_url}/%funnelname%/<code class="stepbase">${this.stepBase}</code>%stepname%/`, value: 'step' }
                ],
                count: 3,
                checkedValue: 'default'
            }
        },
        mounted () {
            this.$emit('setPluginSlug', 'elementor')
            this.$emit('changeSetUpType', 'permalink')
        },
        methods: {
            saveFunnelPermalinks: function (e) {
                e.preventDefault();

                this.$emit('saveFunnelPermalinks', {
                    'funnelBase': this.funnelBase,
                    'stepBase': this.stepBase,
                    'settings': this.checkedValue
                })
            }
        }
    }
</script>
