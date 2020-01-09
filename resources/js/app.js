require('./bootstrap');
import Vue from 'vue';
import Routes from './router';
import App from './components/App';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import './../css/app.css'
import { BootstrapVue } from 'bootstrap-vue'
import store from './store'
Vue.use(BootstrapVue);



const app = new Vue({
    el: '#app',
    store,
    router: Routes,
    render: h=> h(App),
});
