import Vue from "vue"
import Vuex from "vuex"

Vue.use(Vuex)
export default new Vuex.Store({
    state: {
        user: null
    },
    mutations: {
        clearUser(state) {
            state.user = null;
            localStorage.removeItem("user")
        },
        setUser(state, user) {
            state.user = user;
            localStorage.setItem('user', JSON.stringify(state.user))
        },
    },
    getters: {
        user(state) {
            return state.user;
        }
    },
    actions: {
        rebuildData(context, vue) {
            context.commit('setUser', JSON.parse(localStorage.getItem('user')));

            if (localStorage.getItem('user') === null) {
                context.commit('clearUser');
            } else {
                //context.commit('setUser', JSON.parse(localStorage.getItem('user')));
                console.log("[store.rebuildData] checking users me");
                axios.get('/api/users/me').then(r => {
                    console.log("[store.rebuildData] success: ", r);
                    context.commit('setUser', r.data.data);
                }).catch(r => {
                    console.error("[store.rebuildData] failed: ", r);
                    context.commit('clearUser');
                    this.$router.push("/");
                })
            }
        },
    }
})
