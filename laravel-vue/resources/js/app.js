require('./bootstrap')

window.Vue = require('vue')

import Toasted from 'vue-toasted';
import VueRouter from 'vue-router';
import store from './stores/global-store';

Vue.use(Toasted, {duration: 3000})
Vue.use(VueRouter);

import NavbarComponent from './components/navbar'

const router = new VueRouter({routes: require('./routes').default})

router.beforeEach((to, from, next) => {
    if(to.matched && to.matched[0] && to.matched[0].components.default.auth) {
        const auth = to.matched[0].components.default.auth;
        if(auth.required === true) {
            const user = store.state.user ? store.state.user : JSON.parse(localStorage.getItem('user'));
            if(user == null) { // user is not logged in
                Vue.toasted.error('You need to be logged in to access this page')
                return next(false);
            } else if(auth.allowed && Array.isArray(auth.allowed)) { // file has specific roles
                if(auth.allowed.includes(user.type)) { // user is in allowed roles
                    return next(true);
                } else { // not allowed
                    Vue.toasted.error('You dont have the permissions to access this page')
                    return next(false);
                }
            }
        } else {
            return next(true);
        }
    } else {
        Vue.toasted.info(`ATTENTION: ROUTE WITHOUT AUTHENTICATION PARAMETERS ${to.fullPath}`)
        console.error(`ATTENTION: ROUTE WITHOUT AUTHENTICATION PARAMETERS:`, to);
        return next(true);
    }
    next(true);
})

const app = new Vue({
    components: {NavbarComponent},
    el: '#app',
    router,
    store,
    methods: {},
    created() {
        this.$store.dispatch('rebuildData', this);
    },
    mounted() {

    }
})
