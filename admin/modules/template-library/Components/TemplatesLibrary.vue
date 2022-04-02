<template>
    <div>
        <div class="funnel-loader" id="create-funnel-loader" v-if="loader">
            <span class="loader-item-1"></span>
            <span class="loader-item-2"></span>
            <span class="loader-item-3"></span>
            <span class="loader-item-4"></span>
            <span class="loader-item-5"></span>
        </div>

        <div id="template-library-modal" class="template-library-modal" style="display: none">
            <div id="wpfnl-create-funnel__inner-content" class="wpfnl-create-funnel__inner-content">

                <div class="wpfnl-dashboard__header create-funnel__header">
                    <div class="title">
                        <h1>Create A Funnel</h1>
                    </div>

                    <a href="#" class="btn-default">
                        How to use this funnel
                    </a>
                </div>
                <!-- /create-funnel__header -->

                <div id="wpfnl-create-funnel__template-wrapper" class="wpfnl-create-funnel__templates-wrapper">
                    <div class="not-clickable-overlay"></div>

                    <div class="create-funnel-name-modal">
                        <div class="modal-header">
                            <h3 class="title">Enter Your Funnel Name</h3>
                            <span class="close" @click="closeFunnelNameModal">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 6L6 18" stroke="#7A8B9A" stroke-width="1.3" stroke-linecap="round"
                                          stroke-linejoin="round"/>
                                    <path d="M6 6L18 18" stroke="#7A8B9A" stroke-width="1.3" stroke-linecap="round"
                                          stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </div>

                        <div class="modal-body">
                            <form action="">
                                <input type="text" name="funnel-name" v-model="funnelName"/>
                                <button type="submit" @click="createFunnel" :disabled='disabled'>{{createFunnelTitle}}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="template-library-filter-wrapper">
                        <span class="wpfnl-modal-close" v-show="!showStepsPreview">
                            <ReturnIcon/>
                        </span>

                        <span class="back-to-templates" v-show="showStepsPreview" @click="backToTemplates">
                            <ReturnIcon/>
                        </span>

                        <CategoryNav v-if="!showStepsPreview" :categories="categories" :active-category="activeCategory"
                                     @doCatFilter="doCatFilter"/>

                        <ul class="pro-free__filter" v-if="showProFilter" v-show="!showStepsPreview">
                            <li data-filter="all" :class="templatesType == 'all' ? 'active' : '' "
                                @click="doFreeProFilter('all')">all
                            </li>
                            <li data-filter="free" :class="templatesType == 'free' ? 'active' : '' "
                                @click="doFreeProFilter('free')">free
                            </li>
                            <li data-filter="pro" :class="templatesType == 'pro' ? 'active' : '' "
                                @click="doFreeProFilter('pro')">
                                <svg width="20" height="14" viewBox="0 0 20 14" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M10 1L14 7L19 3L17 13H3L1 3L6 7L10 1Z" stroke="#7A8B9A" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                Premium
                            </li>
                        </ul>

                        <div class="import-funnel-name" v-show="showStepsPreview" v-if="!isAddNewFunnelButtonDisabled">
                            <input type="text" name="import-funnel-name"
                                   :value="this.activeTemplate ? this.activeTemplate.title : '' "
                                   placeholder="Write Funnel Name">
                        </div>

                        <div class="funnel-global-import" v-show="showStepsPreview" v-if="">
                            <a
								href="#"
								class="btn-default"
								id="funnel-global-import"
								@click="startImportTemplate"
								v-bind:style='{"pointer-events" : (disabled? "none" : "" )}'
								v-if="!isAddNewFunnelButtonDisabled"
								v-show="(isProActivated && activeTemplate.is_pro) || !activeTemplate.is_pro"
							>
                                <span class="global-import-progress"
                                      v-bind:style="{ width: globalImportProgress }"></span>
                                <span class="btn-text">{{loaderMessage}}</span>
                            </a>


                            <!-- <a :href="proUrl" class="btn-default" title="Click to Upgrade Pro" target="_blank" v-if="isAddNewFunnelButtonDisabled" >
                                Import
                                <span class="pro-tag">coming soon</span>
                            </a> -->
                        </div>

                        <!-- <div class="wpfnl-template__serarch-field" v-show="!showStepsPreview" >
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="32" height="32" viewBox="0 0 28 28" stroke-width="1.5" stroke="#7A8B9A" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="10" cy="10" r="7" />
                                <line x1="21" y1="21" x2="15" y2="15" />
                            </svg>

                            <input type="search" name="step-template-search" placeholder="Search">
                        </div> -->
                    </div>

                    <div class="templates-title-wrapper" v-if="!showStepsPreview">
                        <h2 class="title">{{totalTemplates > 1 ? totalTemplates + ' Templates' : totalTemplates + ' Template'}}</h2>
                    </div>

                    <div class="wpfnl-create-funnel__templates">
                        <div v-show="loader" class="wpfnl-create-funnel__loader">
                            <!-- <span class="wpfnl-loader">{{ message }}</span> -->
                        </div>

                        <div class="create-funnel__single-template create__from-scratch"
                             v-if="showProFilter && !showStepsPreview">
                            <a id="wpfnl-create-funnel" href="#" class="btn-default" @click="showFunnelNameModal"
                               v-if="!isAddNewFunnelButtonDisabled"> Start From scratch </a>

                            <!-- <a :href="proUrl" class="btn-default upgrade-top-pro-link" title="Click to Upgrade Pro" target="_blank" v-if="isAddNewFunnelButtonDisabled" >
                                Start From scratch
                                <span class="pro-tag">coming soon</span>
                            </a> -->

                            <div class="funnel-limit-notice" v-if="isAddNewFunnelButtonDisabled">
                                <p><b>You have reached your limit and built 3/3 funnels!</b><br/><br/>
                                    To create unlimited funnels, please upgrade to Pro.
                                </p>
                            </div>

                            <div class="wpfnl-single-remote-wrapper">
                                <div class="wpfnl-single-remote-template">
                                    <div class="template-image-wrapper"></div>
                                </div>
                                <div class="funnel-template-info">
                                    <span class="title">title</span>
                                </div>
                            </div>
                        </div>

                        <template v-if="isAnyPluginMissing == 'no'">
                            <SingleTemplate
                                v-for="(data, index) in templates"
                                :data="data"
                                :active-category="activeCategory"
                                :isPro="data.is_pro"
                                :key="index"
                                :isAddNewFunnelButtonDisabled="isAddNewFunnelButtonDisabled"
                                @toggleStepsPreview="toggleStepsPreview"
                                @initSteps="initSteps"
                                @setActiveTemplate="setActiveTemplate"
                                v-if="!showStepsPreview"
                            />
                        </template>

                        <div class="create-funnel__single-template wpfnl-missing-plugin-notice" v-else>
                            <div v-if="builder === 'gutenberg'">
                                <h4 class="wpfnl-notice-title">Oops! It looks like {{dependencyPlugins[builder].name}} is inactive.</h4>
                                <div class="wpfnl-notice-notice-body">
                                    <p>
                                        It seems like you have selected {{ builder.charAt(0).toUpperCase() + builder.slice(1) }} as your preferred page builder, but you
                                        do not have {{dependencyPlugins[builder].name}} activated on your site. You see, we create funnel templates for
                                        {{ builder.charAt(0).toUpperCase() + builder.slice(1) }} using {{dependencyPlugins[builder].name}}.
										<br>Please install and activate {{dependencyPlugins[builder].name}} to import funnel templates for {{ builder.charAt(0).toUpperCase() + builder.slice(1) }}
                                        <a href="#" @click="pluginInstallationAction">
                                            Click here to install & activate {{dependencyPlugins[builder].name}}
                                            <span class="dot-wrapper" v-if="pluginInstallLoader">
                                                <span class="dot-one">.</span>
                                                <span class="dot-two">.</span>
                                                <span class="dot-three">.</span>
                                            </span>
                                        </a>
                                    </p>
                                    <p>If you want to create & design funnel pages without using {{dependencyPlugins[builder].name}}, then don't worry.
										<b>You can go ahead and create funnels from scratch using any page builder/editor. It will work just fine.</b></p>

                                    <span class="wpfnl-plugin-installation-error"
                                        v-html="pluginInstallationErrorMessage"></span>
                                </div>
                            </div>

                            <div  v-else-if="builder === 'elementor'">
                                <h4 class="wpfnl-notice-title">Oops! It looks like the page builder you selected is inactive.</h4>
                                <div class="wpfnl-notice-notice-body">
                                    <p>
										It seems like you have selected {{ builder.charAt(0).toUpperCase() + builder.slice(1) }} as your preferred page builder, but you do not have
										the plugin {{dependencyPlugins[builder].name}}  activated on your site.  <br>

										Please install and activate {{ builder.charAt(0).toUpperCase() + builder.slice(1) }} to import ready funnel templates.
                                        <a href="#" @click="pluginInstallationAction">
											Click here to install & activate {{ builder.charAt(0).toUpperCase() + builder.slice(1) }}
                                            <span class="dot-wrapper" v-if="pluginInstallLoader">
                                                <span class="dot-one">.</span>
                                                <span class="dot-two">.</span>
                                                <span class="dot-three">.</span>
                                            </span>
                                        </a>
                                    </p>

                                    <p>If you want to create & design funnel pages without using {{dependencyPlugins[builder].name}}, then
                                        don't worry. <b>You can go ahead and create funnels from scratch using any page builder/editor. It will work just fine.</b></p>


                                    <span class="wpfnl-plugin-installation-error"
                                        v-html="pluginInstallationErrorMessage"></span>
                                </div>
                            </div>

							<div  v-else-if="builder === 'divi-builder'">
								<h4 class="wpfnl-notice-title">Oops! It looks like the page builder you selected is inactive.</h4>
								<div class="wpfnl-notice-notice-body">
									<p>
										It seems like you have selected {{ builder.charAt(0).toUpperCase() + builder.slice(1) }} as your preferred page builder, but you do not have
										the plugin {{dependencyPlugins[builder].name}}  activated on your site.  <br>

										Please install and activate {{ builder.charAt(0).toUpperCase() + builder.slice(1) }} to import ready funnel templates.
									</p>

									<p>If you want to create & design funnel pages without using {{dependencyPlugins[builder].name}}, then
										don't worry. <b>You can go ahead and create funnels from scratch using any page builder/editor. It will work just fine.</b></p>


									<span class="wpfnl-plugin-installation-error"
										  v-html="pluginInstallationErrorMessage"></span>
								</div>
							</div>

							<div  v-else-if="builder === 'oxygen'">
								<h4 class="wpfnl-notice-title">Oops! It looks like the page builder you selected is inactive.</h4>
								<div class="wpfnl-notice-notice-body">
									<p>
										It seems like you have selected Oxygen builder as your preferred page builder, but you do not have
										the plugin Oxygen builder  activated on your site.  <br>

										Please install and activate Oxygen builder to import ready funnel templates.
									</p>

									<p>If you want to create & design funnel pages without using Oxygen builder, then
										don't worry. <b>You can go ahead and create funnels from scratch using any page builder/editor. It will work just fine.</b></p>


									<span class="wpfnl-plugin-installation-error"
										  v-html="pluginInstallationErrorMessage"></span>
								</div>
							</div>
                        </div>

                        <div class="steps-preview-wrapper" v-if="showStepsPreview">
                            <SingleStepPreview
                                v-for="(step, index) in this.steps"
                                :step="step"
                                :key="index"
                                :image="step.featured_image"
                                :isAddNewFunnelButtonDisabled="isAddNewFunnelButtonDisabled"
                                :previewLink="step.link"
                            />

                            <div class="step-title-wrapper">
                                <h2 class="title">{{steps.length > 1 ? steps.length + ' Steps' : steps.length + ' Step'}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <StepImporter
                :steps="steps"
                :stepCategories="stepCategories"
                :activeStep="activeStep"
                :activeStepCategory="activeStepCategory"
                :stepTemplateType="stepTemplateType"
                @doStepCatFilter="doStepCatFilter"
                @doStepFreeProFilter="doStepFreeProFilter"
                @setActiveStep="setActiveStep"
            />
        </div>
    </div>
</template>

<script>
import SingleTemplate from './SingleTemplate.vue'
import CategoryNav from './CategoryNav.vue'
import StepImporter from './StepImporter.vue'
import SingleStepPreview from './SingleStepPreview.vue'
import ReturnIcon from '../../../src/components/icons/ReturnIcon.vue'
import apiFetch from '@wordpress/api-fetch'

var j = jQuery.noConflict()
const nonce = window.template_library_object.nonce
apiFetch.use(apiFetch.createNonceMiddleware(nonce))

export default {
    name: 'TemplatesLibrary',
    components: {
        SingleTemplate,
        CategoryNav,
        StepImporter,
        SingleStepPreview,
        ReturnIcon
    },
    data: function () {
        return {
            showModal: j('#template-library-modal').attr('data-modal-visibility'),
            proUrl: window.template_library_object.pro_url,
            templates: [],
            activeTemplate: '',
            allTemplates: [],
            categories: [],
            stepCategories: [],
            steps: [],
            allSteps: [],
            totalTemplates: 0,
            loader: true,
            message: '',
			isProTemplateSelected: false,
            loaderMessage: 'Import',
            activeCategory: 'all',
            activeStepCategory: 'all',
            activeStep: 'landing',
            templatesType: 'all',
            stepTemplateType: 'all',
            showProFilter: true,
            showStepsPreview: false,
            image_path: window.template_library_object.image_path,
            globalImportProgress: '',
            disabled: false,
            templateNewName: '',
            funnelName: '',
            createFunnelTitle: 'Create Funnel',
            totalFunnels: window.WPFunnelVars.totalFunnels,
            totalAllowedFunnels: window.WPFunnelVars.totalAllowedFunnels,
            dependencyPlugins: window.WPFunnelVars.dependencyPlugins,
            isAnyPluginMissing: window.WPFunnelVars.isAnyPluginMissing,
			isProActivated: window.WPFunnelVars.isProActivated,
            builder: window.WPFunnelVars.builder,
            pluginInstallLoader: false,
            pluginInstallationErrorMessage: '',
			isRemoteFunnel: true
        }

    },
    computed: {
        isAddNewFunnelButtonDisabled: function () {
            if (!this.isProActivated) {
                if (this.totalFunnels >= this.totalAllowedFunnels) {
                    return true
                }
            }
            return false
        }
    },
    mounted() {
        j(document).on(
            'wp-plugin-install-success',
            this.pluginInstalledSuccess
        );

        j(document).on(
            'wp-plugin-install-error',
            this.pluginInstalledError
        );


        if(!this.$store.isRemoteFunnel) {
			apiFetch({
				path: window.template_library_object.rest_api_url + 'wpfunnels/v1/templates/get_templates',
				method: 'GET'
			}).then(response => {
				if (response.success) {
					this.templates = response.templates
					this.allTemplates = response.templates
					this.templatesType = 'all'
					this.categories = response.categories
					this.stepCategories = response.categories
					this.steps = response.steps
					this.allSteps = response.steps
					if (response.templates) {
						this.totalTemplates = this.isAnyPluginMissing === 'yes' ? 0 : response.templates.length
					}

					this.loader = false
				}
			})
		} else {
			this.loader = false
		}
    },
    methods: {
        createFunnel: function (e) {
            e.preventDefault();
            this.disabled = true;
            this.createFunnelTitle = "Creating Funnel..."
            var payload = {
                funnelName: this.funnelName
            };
            wpAjaxHelperRequest("create-funnel", payload)
                .success(function (response) {
                    window.location.href = response.redirectUrl;
                    this.disabled = false;
                })
                .error(function (response) {

                });
        },
        showFunnelNameModal: function (e) {
            e.preventDefault()

            j('.wpfnl-create-funnel__templates-wrapper .not-clickable-overlay').fadeIn().css({
                "background-color": "rgba(0, 0, 0, 0.3)",
            });
            j('.wpfnl-create-funnel__templates-wrapper .create-funnel-name-modal').addClass('show');
        },
        closeFunnelNameModal: function (e) {
            e.preventDefault()
            j('.wpfnl-create-funnel__templates-wrapper .not-clickable-overlay').fadeOut().css({
                "background-color": "rgba(0, 0, 0, 0.3)",
            });
            j('.wpfnl-create-funnel__templates-wrapper .create-funnel-name-modal').removeClass('show');
        },
        startImportTemplate: function (e) {
            e.preventDefault();
            if (this.isAddNewFunnelButtonDisabled) return false;

            this.disabled = true;

            let data = {
                    action: 'wpfunnel_import_funnel',
                    steps: this.steps,
                    name: j('.import-funnel-name input').val(),
                    source: 'remote',
                },
                funnelData = this.activeTemplate.funnel_data,
                that = this;

            that.loaderMessage = 'Please Wait...',
                this.activeTemplate.title = data.name;

            wpAjaxHelperRequest("wpfunnel-import-funnel", data)
                .success(function (response) {
                    let looper = j.Deferred().resolve(),
                        first_step_id = 0;
                    j.when.apply(j, j.map(that.steps, function (step, index) {
                        looper = looper.then(function () {
                            return that.createStep(step, response.funnelID, index, that, funnelData);
                        });
                        return looper;
                    })).then(function () {
                        that.afterFunnelCreationRedirect(response.funnelID);
                    });
                })
                .error(function (response) {
                    console.log("Uh, oh!");
                    console.log(response.statusText);
                });
        },
        createStep: function (step, funnelID, index, that, funnelData) {
            let deferred = j.Deferred(),
                payload = {
                    'step'		: step,
                    'funnelID'	: funnelID,
                    'source'	: 'remote',
                    'funnelData': JSON.stringify(funnelData),
                    'importType': 'templates',
                };
            wpAjaxHelperRequest("wpfunnel-import-step", payload)
                .success(function (response) {
                    that.loaderMessage = `Importing Step: ` + (parseInt(index) + 1);
                    that.globalImportProgress = (100 * (parseInt(index) + 1)) / that.steps.length + '%';
                    console.log(response);
                    deferred.resolve(response);
                })
                .error(function (response) {
                    console.log("Uh, oh!");
                    console.log(response.statusText);
                    deferred.reject(response);
                });
            return deferred.promise();
        },
        afterFunnelCreationRedirect: function (funnelId) {
            var payload = {
                'funnelID': funnelId,
                'source': 'remote'
            };
            wpAjaxHelperRequest("wpfunnel-after-funnel-creation", payload)
                .success(function (response) {
                    window.location = response.redirectLink
                })
                .error(function (response) {
                    console.log(response)
                });
        },
        doCatFilter: function (value) {
            this.activeCategory = value === '' ? 'all' : value
            if (value !== '') {
                this.templates = this.allTemplates.filter(function (template) {
                    return template.industry.slug === value;
                });
            } else {
                this.templates = this.allTemplates
            }
            this.totalTemplates = this.templates.length
        },
        doStepCatFilter: function (value) {
            this.activeStepCategory = value === '' ? 'all' : value
            let activeStep = this.activeStep
            if (value !== '') {
                this.steps = this.allSteps.filter(function (step) {
                    return step.industry.slug === value && step.step_type === activeStep;
                });
            } else {
                this.steps = this.allSteps
            }
        },
        setActiveStep: function (value) {
            this.activeStep = value;
        },
        doStepFilter: function (value) {
            this.steps = this.allSteps.filter(function (step) {
                return step.step_type === value;
            });
        },
        doStepFreeProFilter: function (value) {
            this.stepTemplateType = value
            this.steps = this.allSteps.filter(function (step) {
                return value === 'pro' ? step.is_pro : !step.is_pro;
            });
        },
        doFreeProFilter: function (value) {
            this.templatesType = value
            let activeCategory = this.activeCategory
            if (value === 'all') {
                this.templates = this.allTemplates
            } else {
                this.templates = this.allTemplates.filter(function (template) {
                    return ( activeCategory !== 'all' ? template.industry.slug === activeCategory : 1) && (value === 'pro' ? template.is_pro : !template.is_pro );
                });
            }
            this.totalTemplates = this.templates.length
        },
        toggleLoader: function (e) {
            this.loader = !this.loader
        },
        showLoaderMessage: function (e, message) {
            this.message = message
        },
        getBackBtnValue: function (params) {
            this.showBackBtn = params;
        },
        hideProFilter: function () {
            this.showProFilter = !this.showProFilter
        },
        toggleStepsPreview: function () {
            this.showStepsPreview = !this.showStepsPreview;
        },
        initSteps: function (steps) {
            this.steps = steps;
        },
        backToTemplates: function (e) {
            this.showStepsPreview = false
        },
        funnelNewName: function (data) {
            this.templateNewName = j('.import-funnel-name input').val();
        },
        setActiveTemplate: function (data) {
            this.activeTemplate = data;
            // if(data.is_pro && this.isProActivated) {
			// 	this.isProTemplateSelected = true;
			// } else if(!data.isPro) {
			// 	this.isProTemplateSelected = true;
			// }else {
			// 	this.isProTemplateSelected = false;
			// }
        },
        activatePlugin: function (payload, that) {
            wpAjaxHelperRequest("wpfunnels-activate-plugin", payload)
                .success(function (response) {
                    that.isAnyPluginMissing = 'no'
                })
                .error(function (response) {

                });
        },
        pluginInstallationAction: function (e) {
            e.preventDefault();

            this.pluginInstallLoader = true;

            var plugin = this.dependencyPlugins[this.builder],
                pluginFile = plugin.plugin_file,
                payload = {
                    pluginFile: pluginFile
                },
                action = this.dependencyPlugins[this.builder].action,
                that = this;
            if (action == 'activate') {
                this.activatePlugin(payload, that);
            } else {
                wp.updates.queue.push({
                    action: 'install-plugin',
                    data: {
                        slug: plugin.slug,
                    },
                });
                wp.updates.queueChecker();
            }

        },
        pluginInstalledSuccess: function (e, response) {
            e.preventDefault();
            var plugin = this.dependencyPlugins[this.builder],
                pluginFile = plugin.plugin_file,
                payload = {
                    pluginFile: pluginFile
                },
                that = this;
            this.activatePlugin(payload, that);
        },
        pluginInstalledError: function (e, response) {
            e.preventDefault();
            this.pluginInstallLoader = false;
            this.pluginInstallationErrorMessage = response.errorMessage
        }

    }
}
</script>
