/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import SweetModal from 'sweet-modal-vue/src/plugin.js'
import VModal from 'vue-js-modal'
import VueSweetalert2 from 'vue-sweetalert2';
import {ClientTable} from 'vue-tables-2';
import Datetime from 'vue-datetime'

window.Vue = require('vue');
window.Vue.use(SweetModal);
window.Vue.use(VueSweetalert2);
window.Vue.use(ClientTable, {}, false, 'bulma', 'default');
window.Vue.use(VModal);
window.Vue.use(Datetime);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
