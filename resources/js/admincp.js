/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

import Vue from 'vue'
import Notifications from 'vue-notification'
import Vuelidate from 'vuelidate'
import VueProgressBar from 'vue-progressbar'
import  router  from './routes/routes.js'
import './filters/filter.js';

Vue.use(Vuelidate,Notifications)

import Router from 'vue-router'
Vue.use(Router)

import VTooltip from 'v-tooltip'
const optionstt = {
  autoHide: true,
  delay: {show: 500, hide: 0 }
};
Vue.use(VTooltip,optionstt)
// const originalPush = Router.prototype.push
// Router.prototype.push = function push(location) {
//   return originalPush.call(this, location).catch(err => err)
// }
import Paginate from 'vuejs-paginate'
Vue.component('paginate', Paginate)

const originalPush = Router.prototype.push
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err)
}

import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
Vue.component('v-select', vSelect)

import VuePageTransition from 'vue-page-transition'

Vue.use(VuePageTransition)

import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/vi';

Vue.component('date-picker', DatePicker);

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';



const options1 = {
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#607D8B',
  cancelButtonText: '<span class="fa fa-close" ></span> Hủy bỏ',
  confirmButtonText: '<span class="fa fa-check" ></span> Xác nhận'
}
Vue.use(VueSweetalert2,options1);

import ToggleButton from 'vue-js-toggle-button'
Vue.use(ToggleButton)

import PrettyInput from 'pretty-checkbox-vue/input';
import PrettyCheck from 'pretty-checkbox-vue/check';
import PrettyRadio from 'pretty-checkbox-vue/radio';
import 'pretty-checkbox/src/pretty-checkbox.scss';

Vue.component('p-input', PrettyInput);
Vue.component('p-check', PrettyCheck);
Vue.component('p-radio', PrettyRadio);

import VueHtmlToPaper from 'vue-html-to-paper'

const optionsx = {
  name: '_blank',
  specs: [
    'fullscreen=yes',
    'titlebar=yes',
    'scrollbars=yes'
  ],
  styles: [
    '/hrm/public/admin/all.css'
  ]
}




Vue.use(VueHtmlToPaper, optionsx)

const options = {
  color: '#2196F3',
  failedColor: '#874b4b',
  thickness: '2px',
  transition: {
    speed: '20s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
}

Vue.use(VueProgressBar, options)

import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
Vue.component('loading', Loading)

import VuejsDialog from 'vuejs-dialog';
import VuejsDialogMixin from 'vuejs-dialog/dist/vuejs-dialog-mixin.min.js'; // only needed in custom components

// include the default style
import 'vuejs-dialog/dist/vuejs-dialog.min.css';

let optionsss = {
  html: false, // set to true if your message contains HTML tags. eg: "Delete <b>Foo</b> ?"
  loader: true, // set to true if you want the dailog to show a loader after click on "proceed"
  reverse: false, // switch the button positions (left to right, and vise versa)
  okText: 'Xác Nhận',
  cancelText: 'Đóng',
  animation: 'zoom', // Available: "zoom", "bounce", "fade"
  type: 'basic', // coming soon: 'soft', 'hard'
  verification: 'continue', // for hard confirm, user will be prompted to type this to enable the proceed button
  verificationHelp: 'Type "[+:verification]" below to confirm', // Verification help text. [+:verification] will be matched with 'options.verification' (i.e 'Type "continue" below to confirm')
  clicksCount: 3, // for soft confirm, user will be asked to click on "proceed" btn 3 times before actually proceeding
  backdropClose: false, // set to true to close the dialog when clicking outside of the dialog window, i.e. click landing on the mask
  customClass: '' // Custom class to be injected into the parent node for the current dialog instance
};


// Tell Vue to install the plugin.
Vue.use(VuejsDialog,optionsss);


Vue.component('headertop', require('./components/admincp/layouts/Header.vue').default);
Vue.component('menu-left', require('./components/admincp/layouts/MenuLeft.vue').default);
Vue.component('footermain', require('./components/admincp/layouts/Footer.vue').default);




/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
