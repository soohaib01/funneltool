<template>
    <div class="wpfnl wpfnl-setup-wizard">
        <div class="wpfnl-setup-wizard__wrapper">
            <ul class="wpfnl-setup-wizard__nav">
                <li v-for="step in steps" :class="[{ active: step.isActive, completed: step.completed }, step.slug] ">
                    <span class="step-icon">
                        <img alt="Step Icon" v-if="!step.isActive && !step.completed" :src="step.icon" />
                        <img alt="Step Icon" v-if="step.isActive" :src="step.iconActive" />
                        <img alt="Step Icon" v-if="step.completed" :src="step.iconCompleted" />
                    </span>
                    <span class="step-name">{{step.name}}</span>
                </li>
            </ul>

            <FunnelType
                v-if="step==='type'"
                :wizard-slug="wizardSlug"
                :setUpType="setUpType"
                :showLoader="showLoader"
                @setPluginSlug="setPluginSlug"
                @changeSetUpType="changeSetUpType"
                @processSettings="processSettings"
            />
            <Builder
                v-if="step==='builder'"
                :wizard-slug="wizardSlug"
                :setUpType="setUpType"
                :showLoader="showLoader"
                :prevStepLink="prevStepLink"
                @setPluginSlug="setPluginSlug"
                @processSettings="processSettings"
            />
            <Permalink
                v-if="step==='permalink'"
                :setUpType="setUpType"
                :showLoader="showLoader"
                :prevStepLink="prevStepLink"
                @changeSetUpType="changeSetUpType"
                @saveFunnelPermalinks="saveFunnelPermalinks"
            />
            <Thankyou
                v-if="step==='thankyou'"
                :dashboardUrl="dashboardUrl"
                :wizard-slug="wizardSlug"
            />

            <div class="skip-wizard" v-if="step !=='thankyou'">
                <a :href="skipLink">skip setup wizard</a>
            </div>
        </div>
    </div>
</template>

<script>
    import FunnelType from './FunnelType.vue'
    import Builder from './Builder.vue'
    import Permalink from './Permalink.vue'
    import Thankyou from './Thankyou.vue'
    import apiFetch from '@wordpress/api-fetch'
    var j = jQuery.noConflict()
    const nonce = window.setup_wizard_obj.nonce
    apiFetch.use(apiFetch.createNonceMiddleware(nonce))

    export default {
        name: 'Wizard',
        components: {
            FunnelType,
            Builder,
            Permalink,
            Thankyou
        },
        data: function () {
            return {
                skipLink:       window.setup_wizard_obj.settings_url,
                step:           window.setup_wizard_obj.current_step,
                pluginSlug:     'woocommerce',
                steps:          window.setup_wizard_obj.steps,
                wizardSlug:     window.setup_wizard_obj.wizard_url,
                currentStep:    window.setup_wizard_obj.current_step,
                redirectLink:   window.setup_wizard_obj.wizard_url + '&step=' + window.setup_wizard_obj.current_step,
                nextStepLink:   window.setup_wizard_obj.next_step_link,
                prevStepLink:   window.setup_wizard_obj.prev_step_link,
                isWCInstalled:  window.setup_wizard_obj.is_woo_installed,
                isElmInstalled: window.setup_wizard_obj.is_elementor_installed,
                isFFInstalled:  window.setup_wizard_obj.is_ff_installed,
                isCLInstalled:  window.setup_wizard_obj.is_cl_installed,
                isQBInstalled:  window.setup_wizard_obj.is_qb_installed,
                isWCActive:     window.setup_wizard_obj.is_woo_active,
                isElmActive:    window.setup_wizard_obj.is_elementor_active,
                isFFActive:     window.setup_wizard_obj.is_ff_active,
                isCLActive:     window.setup_wizard_obj.is_cl_active,
                isQBActive:     window.setup_wizard_obj.is_qb_active,
                dashboardUrl:   window.setup_wizard_obj.dashboard_url,
                setUpType:      'plugin',
                showLoader:      false,
                isPermitted:     false
            }
        },
        mounted () {
            // eslint-disable-next-line no-undef
            j(document).on('wp-plugin-install-success', this.installSuccess)
            j(document).on('wp-plugin-install-error', this.installError)
            // setPluginSlug(this.pluginSlug, isPermitted)

        },
        methods: {
            setPluginSlug: function (slug, isPermitted) {
                this.pluginSlug = slug
                this.isPermitted = isPermitted
            },
            changeSetUpType: function (type) {
                this.setUpType = type
            },
            processSettings: function (e) {
                this.showLoader = true;

                if (this.pluginSlug === 'woocommerce') {
                    if (this.isWCInstalled === 'yes') {
                        this.activateWCPlugins(this.isPermitted)
                    }
                    if (this.isWCInstalled === 'no') {
                        this.installPlugin('woocommerce')
                    }
                    if (this.isCLInstalled === 'no' && this.isPermitted === true) {
                        this.installPlugin('cart-lift')
                    }
                } else if (this.pluginSlug === 'elementor') {
                    if (this.isElmInstalled === 'yes') {
                        this.saveFunnelBuilder()
                    } else if (this.isElmActive === 'no') {
                        this.installPlugin('elementor')
                    }
                } else if (this.pluginSlug === 'gutenberg') {

					if (this.isQBInstalled === 'yes') {
						this.saveFunnelBuilder()
					} else if (this.isQBActive === 'no' && this.isPermitted == true) {
						this.installPlugin('qubely')
					}else{
                        this.saveFunnelBuilder();
                        window.location.href = this.nextStepLink
                    }
                } else {
					this.saveFunnelBuilder();
				}
            },
            activateWCPlugins: function(permission) {
                apiFetch({
                    path: `${window.setup_wizard_obj.rest_api_url}wpfunnels/v1/settings/activate_wc_plugins`,
                    method: 'POST',
                    data: {
						'permission' : permission,
					},
                }).then(response => {
                    if(response.success) {
                        this.saveFunnelType()
                    }

                }, error => {

                })
            },
            installPlugin: function (pluginSlug) {
                if (wp.updates.shouldRequestFilesystemCredentials && !wp.updates.ajaxLocked) {
                    wp.updates.requestFilesystemCredentials(event)
                    $document.on('credential-modal-cancel', function () {
                        var $message = $('.install-now.updating-message')
                        $message
                            .removeClass('updating-message')
                            .text(wp.updates.l10n.installNow)
                        wp.a11y.speak(wp.updates.l10n.updateCancel, 'polite')
                    })
                }
                wp.updates.installPlugin({
                    slug: pluginSlug
                })
            },
            installSuccess: function (event, args) {
                event.preventDefault()
                let plugin_slug = args.slug
                if (plugin_slug === 'woocommerce' || plugin_slug === 'cart-lift') {
                    this.activateWCPlugins(this.isPermitted)
                } else if ( plugin_slug === 'elementor' ) {
                    this.saveFunnelBuilder()
                } else if (plugin_slug === 'qubely') {
					this.saveFunnelBuilder()
				} else if (this.currentStep === 'permalink') {
                    this.saveFunnelPermalinks()
                }

            },
            installError: function (event, args) {
                event.preventDefault()
            },
            saveFunnelType: function () {
                var that = this
                apiFetch({
                    path: window.setup_wizard_obj.rest_api_url + 'wpfunnels/v1/settings/_wpfunnels_general_settings/funnel_type/',
                    method: 'POST',
                    data: { value: this.pluginSlug, type: 'ignore_activation', slug: this.pluginSlug }
                }).then(response => {
                    window.location.href = that.nextStepLink
                }, error => {

                })
            },
            saveFunnelBuilder: function () {
                var type = 'plugin_active';
                apiFetch({
                    path: window.setup_wizard_obj.rest_api_url + 'wpfunnels/v1/settings/_wpfunnels_general_settings/builder/',
                    method: 'POST',
                    data: {
                    	value: this.pluginSlug,
						type: type,
						slug: this.pluginSlug,
                        permission: this.isPermitted
                    }
                }).then(response => {
                    window.location.href = this.nextStepLink
                }, error => {
                    console.log(error)
                });
            },
            saveFunnelPermalinks: function (permalink) {
                this.showLoader = true;
                apiFetch({
                    path: window.setup_wizard_obj.rest_api_url + 'wpfunnels/v1/settings/_wpfunnels_permalink_settings/permalink/',
                    method: 'POST',
                    data: {
                        'funnelBase': permalink.funnelBase,
                        'stepBase': permalink.stepBase,
                        'settings': permalink.settings
                    }
                }).then(response => {
                    window.location.href = this.nextStepLink;
                    this.showLoader = false;
                }, error => {

                })
            }
        }
    }
</script>
