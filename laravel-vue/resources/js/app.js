require('./bootstrap')

window.Vue = require('vue')

import Toasted from 'vue-toasted';
import VueRouter from 'vue-router';
import store from './stores/global-store';
import VueSocketIO from "vue-socket.io";
import {BootstrapVue, IconsPlugin} from 'bootstrap-vue'
import {library} from '@fortawesome/fontawesome-svg-core'
import {fas} from '@fortawesome/free-solid-svg-icons'

library.add(fas)
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import moment from 'moment'
import VueApexCharts from 'vue-apexcharts'


require('moment/locale/en-gb');
Vue.use(VueApexCharts)
Vue.use(Toasted, {duration: 3000})

Vue.use(VueRouter);
Vue.use(require('vue-moment'), {
    moment
});


Vue.use(new VueSocketIO({
    debug: process.env.NODE_ENV === "development",
    connection: require('./../../config').default.WEBSOCKET_URL
}))

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)


Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component('apexchart', VueApexCharts)
Vue.config.productionTip = false

import NavbarComponent from './components/navbar'
import logger from './utilities/logger'

const router = new VueRouter({
    routes: require('./routes').default,
    mode: 'history'
});

router.beforeEach((to, from, next) => {
    let errorMessage = null
    if (to.matched && to.matched[0] && to.matched[0].components.default.auth) {
        const auth = to.matched[0].components.default.auth;
        if (auth.required === true) {
            const user = store.state.user ? store.state.user : JSON.parse(localStorage.getItem('user'));
            logger.log("[routerBeforeEach] user = ", user)
            if (user == null) { // user is not logged in,
                errorMessage = 'You need to be logged in to access this page'
            } else if (auth.allowed && Array.isArray(auth.allowed)) { // file has specific roles
                if (auth.allowed.includes(user.type)) { // user is in allowed roles
                    return next(true);
                } else { // not allowed
                    errorMessage = 'You dont have the permissions to access this page';
                }
            } else {
                return next(true);
            }
        } else {
            return next(true);
        }
    } else {
        Vue.toasted.info(`ATTENTION: ROUTE WITHOUT AUTHENTICATION PARAMETERS ${to.fullPath}`)
        logger.error(`ATTENTION: ROUTE WITHOUT AUTHENTICATION PARAMETERS:`, to)
        return next(true);
    }

    if (from.matched.length === 0) {
        Vue.toasted.error(errorMessage)
        router.push("/");
    }
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
    data() {
        return {
            isDev: process.env.NODE_ENV === "development",
        }
    },
    mounted() {

    },
    sockets: {
        connect() {
            if (this.$store.state.user) {
                axios.post('/api/users/socketID', {"socketID": this.$socket.id})
            }
        },
        connect_error(e) {
            this.$toasted.error("Could not connect to Socket Server, trying again..", {duration: 1000})
        },
        pm(msg) {
            this.$toasted.info(msg);
        },
        userBlocked() {
            axios.post('/api/logout').finally(() => {
                this.$store.commit('clearUser');
                if(this.$route.name !== 'main') {
                    this.$router.push({name: 'main'});
                }
                this.$toasted.error("Your account has been disabled by a manager.");
            })

        }
    }
})
