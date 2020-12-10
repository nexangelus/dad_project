require('./bootstrap')

window.Vue = require('vue')

import VueRouter from 'vue-router';

Vue.use(VueRouter);

import LoginComponent from './components/login'
import NavbarComponent from './components/navbar'
import IndexPage from './pages/index'

//Vue.component('users', UsersComponent);

const routes = [
    {path: '/login', component: LoginComponent},
    {path: '/', component: IndexPage},
];

const router = new VueRouter({routes})

const app = new Vue({
    components: {NavbarComponent},
    el: '#app',
    router,
    data: {},
    methods: {},
    mounted() {

    }
})
