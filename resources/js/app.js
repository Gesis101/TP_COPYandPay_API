
import 'bootstrap';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/dropdown';


window.Vue = require('vue');

import VueRouter from 'vue-router';
import routes from './routes'
import App from './App.vue'
Vue.use(VueRouter);

const app = new Vue({
    el: '#app',
    components: {
        App
    },
    router: new VueRouter(routes)
});
