import './bootstrap'
import 'core-js/es6/promise';
import 'core-js/es6/string';
import 'core-js/es7/array';
import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import App from './App';
import router from './router';
import VueMarkdown from 'v-markdown-editor';
import 'v-markdown-editor/dist/index.css';
import VueSummernote from 'vue-summernote'

require('bootstrap')
require('bootstrap/dist/css/bootstrap.min.css')

Vue.use(BootstrapVue);
Vue.use(VueMarkdown);

Vue.use(VueSummernote, {
	dialogsFade: false
})

new Vue({
	el: '#app',
	router,
	template: '<App/>',
	components: {
		App
	}
});
