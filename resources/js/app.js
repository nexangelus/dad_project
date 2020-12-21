require('./bootstrap')

window.Vue = require('vue')

import Toasted from 'vue-toasted';
import VueRouter from 'vue-router';

Vue.use(Toasted, {duration: 3000})
Vue.use(VueRouter);

import LoginComponent from './components/login'
import NavbarComponent from './components/navbar'
import IndexPage from './pages/index'
import RegisterComponent from './components/register'
import DashboardPage from './pages/dashboard'

//Vue.component('users', UsersComponent);

const routes = [
    {name: 'Login', path: '/login', component: LoginComponent},
    {path: '/register', component: RegisterComponent},
    {path: '/', component: IndexPage},
    {name: "Dashboard", path: '/dashboard', component: DashboardPage},
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
