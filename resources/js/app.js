require('./bootstrap')

window.Vue = require('vue')

import Toasted from 'vue-toasted';
import VueRouter from 'vue-router';

Vue.use(Toasted, {duration: 3000})
Vue.use(VueRouter);

import LoginComponent from './components/login'

//Vue.component('users', UsersComponent);

const routes = [
    {path: '/login', component: LoginComponent},
];

const router = new VueRouter({routes})

const app = new Vue({
    el: '#app',
    router,
    data: {},
    methods: {},
    mounted() {

    }
})
