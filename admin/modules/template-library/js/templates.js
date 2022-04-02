import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
var j = jQuery.noConflict()
Vue.use(VueAxios, axios)
import TemplatesLibrary from '../Components/TemplatesLibrary.vue'

const store = Vue.observable({
	isRemoteFunnel: false,
});
Vue.prototype.$store = store
if(document.getElementById("templates-library")){
  new Vue({ // eslint-disable-line no-new
      el: '#templates-library',
      render: h => h(TemplatesLibrary)
  });
}

j(document).ready(function (j) {

    let TemplateLibrary = function() {
        j('#wpfnl-tab-create_funnel, .add-new-funnel-btn').on('click', this.showTemplateLibrary)
        j('.wpfnl-modal-close').on('click', this.closeTemplateLibraryModal)
        j('#create-first-step, #add-new-step').on('click', this.getStepTemplates)
    }

    /**
     * show template library
     * @param e
     */
    TemplateLibrary.prototype.showTemplateLibrary = function(e) {
        e.preventDefault();
        j('#create-funnel-loader').addClass('show');
        j('#template-library-modal').show();
        j('#wpfnl-create-funnel__inner-content').show();
        j('#wpfnl-create-steps_inner-content').hide();

        j('.wpfnl-dashboard__header:not(.create-funnel__header)').hide();
        j('.wpfnl-dashboard__inner-content').hide();
        j('.wpfnl-funnel-settings__inner-content').hide();
    }

    /**
     * get all step templates
     * @param e
     */
    TemplateLibrary.prototype.getStepTemplates = function(e) {
        e.preventDefault();
        j('#wpfnl-create-funnel__inner-content').hide();
        j('#wpfnl-create-steps_inner-content').show();
        j('#template-library-modal').show();

        //---for steps---
        j('.steps-page__content').addClass('p0');
        j('.wpfnl-single-step__content').hide();
        j('.steps-settings').hide();
        j('div.create-first-step').hide();
    }


    /**
     * close template modals
     * @param e
     */
    TemplateLibrary.prototype.closeTemplateLibraryModal = function (e) {
        e.preventDefault();
        j('#template-library-modal').hide();
        j('#create-funnel-loader').removeClass('show');

        j('.wpfnl-dashboard__header:not(.create-funnel__header)').show();
        j('.wpfnl-dashboard__inner-content').show();
        j('.wpfnl-funnel-settings__inner-content').show();

        //---for steps---
        j('.steps-page__content').removeClass('p0');
        j('.wpfnl-single-step__content').show();
        j('.steps-settings').show();
        j('div.create-first-step').show();
    }
    new TemplateLibrary();
})
