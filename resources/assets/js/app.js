
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import '../css/app.css'

Vue.use(ElementUI);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('image-filter-home', require('./components/ImageFilterHome.vue'));
Vue.component('image-list', require('./components/ImageList.vue'));
Vue.component('image-filter-tool', require('./components/ImageFilterTool.vue'));
Vue.component('image-flow', require('./components/ImageFlow.vue'));

const app = new Vue({
    el: '#app'
});
