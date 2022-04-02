import Vue from 'vue'
import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
Vue.use(VueFormWizard)
import Wizard from '../components/Wizard.vue'
new Vue({
  el: '#wpfunnels_setup_wizard',
  render: h => h(Wizard)
});
