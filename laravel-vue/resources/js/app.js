require('./bootstrap')

window.Vue = require('vue')

import Toasted from 'vue-toasted';
import VueRouter from 'vue-router';
import store from './stores/global-store';
import VueSocketIO from "vue-socket.io";
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faBicycle, faUtensils, faInfo, faCartPlus, faShoppingCart, faTimesCircle, faSearch, faUserCog, faUser} from '@fortawesome/free-solid-svg-icons'
library.add(faBicycle, faUtensils, faInfo, faCartPlus, faShoppingCart, faTimesCircle, faSearch, faUserCog, faUser)
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import moment from 'moment'
require('moment/locale/pt');

Vue.use(Toasted, {duration: 3000})

Vue.use(VueRouter);
Vue.use(require('vue-moment'), {
    moment
});


Vue.use(new VueSocketIO({
    debug: true,
    connection: require('./../../config').default.WEBSOCKET_URL
}))

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)


Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.config.productionTip = false

import NavbarComponent from './components/navbar'

const router = new VueRouter({routes: require('./routes').default})

router.beforeEach((to, from, next) => {
    let errorMessage = null
    if (to.matched && to.matched[0] && to.matched[0].components.default.auth) {
        const auth = to.matched[0].components.default.auth;
        if (auth.required === true) {
            const user = store.state.user ? store.state.user : JSON.parse(localStorage.getItem('user'));
            console.log("[routerBeforeEach] user = ", user);
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
        console.error(`ATTENTION: ROUTE WITHOUT AUTHENTICATION PARAMETERS:`, to);
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
    mounted() {

    },
    sockets: {
        connect() {
            // TODO não deveria estar aqui só no login
           // axios.get('/sanctum/csrf-cookie').then(() => {
                if(this.$store.state.user) {
                    axios.post('/api/users/socketID', {"socketID": this.$socket.id})
                }
            //});
        },
        connect_error(e) {
            this.$toasted.error("Could not connect to Socket Server, trying again..", {duration: 1000})
        },
        pm(msg) {
            this.$toasted.info(msg);
        }
    }
})
