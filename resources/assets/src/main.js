// Additional polyfills
import 'custom-event-polyfill'
import 'url-polyfill'

import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store/index'

import BootstrapVue from 'bootstrap-vue'

import globals from './globals'
import Popper from 'popper.js'

import notification from "./notification";

import VueGeoLocation from "vue-browser-geolocation"

import VueMoment from "vue-moment"


Vue.mixin(notification);

// Required to enable animations on dropdowns/tooltips/popovers
Popper.Defaults.modifiers.computeStyle.gpuAcceleration = false

Vue.config.productionTip = false

Vue.use(BootstrapVue)
Vue.use(VueGeoLocation)
Vue.use(VueMoment);

// Global RTL flag
Vue.mixin({
  data: globals
})

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
