<template>
    <div class="wizard-single-content builder-type">
        <div class="step-header">
            <h4 class="title">Builder Type</h4>
            <p class="description">Choose which page builder or editor you want to use to design your funnel pages. WPFunnels is exclusively compatible with Gutenberg, Elementor, Divi and Oxygen with dedicated blocks and widgets. Also, get tons of amazing templates for both Gutenberg, Elementor, Divi and Oxygen for many different niches.</p>
        </div>

        <div class="wizard-single-content__body">
            <select
                id="builder-type"
                v-model="selected"
                name="builder-type"
                @change="onChange($event)"
            >
                <option
                    v-for="option in options"
                    :value="option.value"
					:data-file="option.data.file"
                    :selected="option.value == 'classic'"
                >{{option.name}}
                </option>
            </select>

            <div class="wpfnl-field-wrapper" v-if="selected=='gutenberg'">
                <div class="wpfnl-fields">
                    <span class="wpfnl-checkbox no-title">
                        <input type="checkbox" name="install-qubely" v-model="isIstallQubely" id="install-qubely" @change="isPermitted"/>
                        <label for="install-qubely"></label>
                    </span>
                    <label>
                        Install Qubely
                        <span class="wpfnl-tooltip">
                            <p>Enable to install Qubely</p>
                        </span>
                    </label>
                </div>
                <p class="hints" v-if="selected === 'gutenberg' ">
                    Recommended if you want to use our pre-made Gutenberg funnel templates.
                </p>
            </div>

            <span class="hints" v-if="selected === 'elementor' ">
                    The following plugin will be installed for you: <a href="https://wordpress.org/plugins/elementor/" target="_blank">Elementor</a>
                </span>
			<span class="hints" v-if="selected === 'gutenberg' && isIstallQubely">
                The following plugin will be installed for you: <a href="https://wordpress.org/plugins/qubely/" target="_blank">Qubely</a>
            </span>
        </div>

        <div class="wizard-single-content__footer">
            <a :href="prevStepLink" class="wizard-btn btn-default prev">previous</a>
            <a href="#" class="wizard-btn btn-default next" @click="processSettings">Next
                <span class="wpfnl-loader" v-if="showLoader"></span>
            </a>
        </div>

    </div>
</template>


<script>
    import apiFetch from "@wordpress/api-fetch";
    import TooltipIcon from '../../../src/components/icons/TooltipIcon.vue'
    const nonce = window.setup_wizard_obj.nonce;
    apiFetch.use(apiFetch.createNonceMiddleware(nonce));

    export default {
        name: 'Builder',
        components: {
            TooltipIcon
        },
        props: {
            // eslint-disable-next-line vue/require-default-prop
            wizardSlug: String,
            prevStepLink: String,
            showLoader: Boolean
        },
        data: function () {
            return {
                count: 0,
                options: [
                    {
                    	name: 'Gutenberg',
						value: 'gutenberg',
						data: {
                    		file: 'qubely/qubely.php'
						}
					},
                    {
                    	name: 'Elementor',
						value: 'elementor',
						data: {
							file: 'elementor/elementor.php'
						}
					},
					{
						name: 'Divi',
						value: 'divi-builder',
						data: {
							file: 'divi-builder/divi-builder.php'
						}
					},
					{
						name: 'Oxygen',
						value: 'oxygen',
						data: {
							file: 'oxygen/functions.php'
						}
					},
                    // {name: 'Classic Editor (Legacy Editor)', value: 'classic'},
                ],
                selected: 'elementor',
                isIstallQubely: true
            }
        },
        mounted () {
            this.$emit('setPluginSlug', 'elementor')
            this.$emit('changeSetUpType', 'plugin')
        },
        methods: {
            onChange (event) {
                if (event.target.value === 'elementor') {
                    this.$emit('setPluginSlug', 'elementor',this.isIstallQubely)
                } else if (event.target.value === 'gutenberg') {
                    this.$emit('setPluginSlug', 'gutenberg',this.isIstallQubely)
                } else if (event.target.value === 'divi-builder') {
					this.$emit('setPluginSlug', 'divi-builder',this.isIstallQubely)
				}else if (event.target.value === 'oxygen') {
					this.$emit('setPluginSlug', 'oxygen',this.isIstallQubely)
				}
            },
            processSettings: function (e) {
                e.preventDefault();
                this.$emit('processSettings')
            },
            isPermitted: function (e) {
                e.preventDefault();
                this.$emit('setPluginSlug', 'gutenberg',this.isIstallQubely)
            },
        }
    }
</script>
