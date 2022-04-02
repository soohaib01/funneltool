<template>
    <div class="create-funnel__single-template">
        <div class="templates-title-wrapper" v-if="showStepsPreview">
            <span class="back" @click="toggleStepsPreview">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-left" width="30" height="30" viewBox="0 0 24 24" stroke-width="1.5" stroke="#363B4E" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <line x1="5" y1="12" x2="9" y2="16" />
                    <line x1="5" y1="12" x2="9" y2="8" />
                </svg>
            </span>
            <h2 class="title">{{steps.length > 1 ? steps.length + ' Steps' : steps.length + ' Step'}}</h2>
        </div>

        <div class="wpfnl-single-remote-wrapper" :class="classNames" v-if="!showStepsPreview">

            <div class="wpfnl-single-remote-template" @click="toggleStepsPreview">
                <div class="importar-loader" v-show="loader">
                    <span class="title-wrapper">
                        <span class="title">{{loaderMessage}}</span>
                        <span class="dot-wrapper">
                            <span class="dot-one">.</span>
                            <span class="dot-two">.</span>
                            <span class="dot-three">.</span>
                        </span>
                    </span>
                </div>

                <div class="template-image-wrapper" :style="{ backgroundImage: `url(${data.featured_image})` }">
                    <!-- <img :src="data.featured_image" alt="funnel template" class="template-img"> -->
                </div>
            </div>

            <div class="funnel-template-info">
                <span class="title">{{data.title}}</span>
                <div class="template-action">
                    <span class="steps">{{ data.steps_order.length }} steps</span>

                    <a :href="data.link" target="_blank" class="btn-default preview"> Preview </a>

                    <a
						href="#"
                        v-if="!isAddNewFunnelButtonDisabled"
						v-show="(isProActivated && isPro) || !isPro"
                        class="btn-default import wpfnl-import-funnel"
                        @click="startImportTemplate"
                        v-bind:style='{"pointer-events" : (disabled? "none" : "" )}'
                    > Import </a>

                    <!-- <a :href="proUrl"
                        class="btn-default disabled"
                        v-if="isAddNewFunnelButtonDisabled"
                        title="Click to Upgrade Pro"
                        target="_blank"
                    > Import (pro) </a> -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import apiFetch from "@wordpress/api-fetch";
    const nonce = window.template_library_object.nonce;
    apiFetch.use(apiFetch.createNonceMiddleware(nonce));
    var j = jQuery.noConflict();
    export default {
        name: 'SingleTemplate',
        data: function () {
            let freePro = 'free';
            if(this.isPro) {
                freePro = 'pro'
            } else {
                freePro = 'free'
            }
            return {
                classNames: 'slug' in this.data.industry ? this.data.industry.slug : '',
                proUrl: window.template_library_object.pro_url,
                freePro: freePro,
                freProSelector: this.isPro ? 'pro' : 'free',
                steps: this.data.steps_order,
                loader: false,
                loaderMessage: '',
                showStepPreviewClass: '',
                showBackBtn: false,
                disabled: false,
				isProActivated: window.WPFunnelVars.isProActivated
            }
        },
        props: {
            data: Object,
            activeCategory: String,
            isPro: Boolean,
            freeProFilter: String,
            showStepsPreview: Boolean,
            isAddNewFunnelButtonDisabled: Boolean
        },
		watch: {
			data: function (newData) {
				this.steps = newData.steps_order
			}
		},
        methods: {
            startImportTemplate: function (e) {
                e.preventDefault();
                if(this.isAddNewFunnelButtonDisabled) return false;
                j('.wpfnl-create-funnel__templates-wrapper .not-clickable-overlay').show();

                this.disabled = true;
                this.loader = true;
                this.loaderMessage = 'Import Started. Be patient';
                let funnelData = this.data.funnel_data

                let data = {
                        action  : 'wpfunnel_import_funnel',
                        steps   : this.steps,
                        source  : 'remote',
                        name    : this.data.title,
                    },
                    that = this;
                wpAjaxHelperRequest( "wpfunnel-import-funnel", data )
                    .success( function( response ) {
                        let looper = j.Deferred().resolve(),
                            first_step_id = 0;
                        j.when.apply(j, j.map(that.steps, function(step, index) {
                            looper = looper.then(function() {
                                return that.createStep(step, response.funnelID, index, that, funnelData);
                            });
                            return looper;
                        })).then(function() {
                            that.afterFunnelCreationRedirect(response.funnelID);
                        });
                    })
                    .error( function( response ) {
                        console.log( "Uh, oh!" );
                        console.log( response.statusText );
                    });
            },
            createStep: function (step, funnelID, index, that, funnelData) {
                let deferred = j.Deferred(),
                    payload = {
                        'step'          : step,
                        'funnelID'      : funnelID,
                        'source'        : 'remote',
                        'funnelData'    : JSON.stringify(funnelData),
						'importType'	: 'templates',
					};
                wpAjaxHelperRequest( "wpfunnel-import-step", payload )
                    .success( function( response ) {
                        that.loaderMessage = `Importing Step: ` + ( parseInt(index)+1 );
                        console.log(response);
                        deferred.resolve(response);
                    })
                    .error( function( response ) {
                        console.log( "Uh, oh!" );
                        console.log( response.statusText );
                        deferred.reject(response);
                    });
                return deferred.promise();
            },
            afterFunnelCreationRedirect: function (funnelId) {
                var payload = {
                    'funnelID'  : funnelId,
                    'source'    : 'remote'
                };
                wpAjaxHelperRequest( "wpfunnel-after-funnel-creation", payload )
                    .success( function( response ) {
                        window.location = response.redirectLink
                    })
                    .error( function( response ) {
                        console.log(response)
                    });
            },
            toggleStepsPreview: function (e) {
                e.preventDefault();
                this.$emit('toggleStepsPreview');
                this.$emit('initSteps', this.steps);
                this.$emit('setActiveTemplate', this.data);
            }
        },
    }
</script>
