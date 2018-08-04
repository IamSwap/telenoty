
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Bus = new Vue();

// Clipboard
import VueClipboard from 'vue-clipboard2';
Vue.use(VueClipboard)

// Import Vue Router
import VueRouter from 'vue-router';
Vue.use(VueRouter);

window.swal = require('sweetalert');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('dashboard', require('./components/Dashboard.vue'));

import routes from './routes';

const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'active',
    routes
});

const app = new Vue({
    router,
    el: '#app'
});


