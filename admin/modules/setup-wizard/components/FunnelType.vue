<template>
    <div class="wizard-single-content funnel-type">
        <div class="step-header">
            <h4 class="title">Funnel Type</h4>
            <p class="description">Decide your purpose with the plugin.</p>
            <p class="description">To increase sales revenue, choose Sales Funnels.</p>
        </div>

        <div class="wizard-single-content__body">
            <select
                id="funnel-type"
                v-model="selected"
                name="page-builder"
                @change="onChange($event)"
            >
                <option
                    v-for="option in options"
                    :value="option.value"
                    :selected="option.value == 'sales'"
                >{{ option.name }}
                </option>
            </select>

            <div class="wpfnl-field-wrapper">
                <div class="wpfnl-fields">
                    <span class="wpfnl-checkbox no-title">
                        <input type="checkbox" name="install-cartlift" v-model="isIstallCartLift" id="install-cartlift" @change="isPermitted"/>
                        <label for="install-cartlift"></label>
                    </span>
                    <label>
                        Install CartLift
                        <span class="wpfnl-tooltip">
                            
                            <p>Enable to install CartLift</p>
                        </span>
                    </label>
                </div>
                
                <p class="hints">
                    Recommended If you want to set abandoned cart recovery campaigns for sales funnels, and WooCommerce store.
                </p>
            </div>
            
            <span class="hints">
                The following plugins will be installed for you: <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> <span v-if="isIstallCartLift">& <a href="https://wordpress.org/plugins/cart-lift/" target="_blank">CartLift</a></span>
            </span>
        </div>

        <div class="wizard-single-content__footer">
            <a href="#" class="wizard-btn btn-default next" @click="processWizardSubmission">Next
                <span class="wpfnl-loader" v-if="showLoader"></span>
            </a>
        </div>

    </div>
</template>

<script>
    import apiFetch from '@wordpress/api-fetch'
    import TooltipIcon from '../../../src/components/icons/TooltipIcon.vue'
    // eslint-disable-next-line no-undef
    var j = jQuery.noConflict()
    const nonce = window.setup_wizard_obj.nonce
    apiFetch.use(apiFetch.createNonceMiddleware(nonce))

    export default {
        name: 'FunnelType',
        components: {
            TooltipIcon
        },
        props: {
            // eslint-disable-next-line vue/require-default-prop
            wizardSlug: String,
            showLoader: Boolean
        },
        data: function () {
            return {
                options: [
                    { name: 'Sales Funnel', value: 'sales' },
                    // { name: 'Lead Funnel', value: 'leads' },
                    // { name: 'Both (Sales + Lead)', value: 'both' }
                ],
                selected: 'sales',
                isIstallCartLift: true
            }
        },
        mounted () {
            this.$emit('setPluginSlug', 'woocommerce',this.isIstallCartLift)
            this.$emit('changeSetUpType', 'plugin')
        },
        methods: {
            processWizardSubmission: function (e) {
                e.preventDefault();
                this.$emit('processSettings')
            },
            isPermitted: function (e) {
                e.preventDefault();
                this.$emit('setPluginSlug', 'woocommerce',this.isIstallCartLift)
            },
            onChange (event) {
                if (event.target.value === 'sales') {
                    this.$emit('setPluginSlug', 'woocommerce',this.isIstallCartLift)
                } else if (event.target.value === 'lead') {
                    this.$emit('setPluginSlug', 'fluentform')
                } else if (event.target.value === 'both') {
                    this.$emit('setPluginSlug', 'both')
                }
            },
        }
    }
</script>
