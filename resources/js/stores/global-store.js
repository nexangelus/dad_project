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
    actions: {
        rebuildData(context, vue) {
            this.state.user = JSON.parse(localStorage.getItem('user'))

            if (localStorage.getItem('user') === null) {
                context.commit('clearUser');
            } else {
                context.commit('setUser', JSON.parse(localStorage.getItem('user')));
                axios.get('/api/users/me').then(r => {
                    context.commit('setUser', r.data);
                    // TODO Perguntar se é possível executar o "beforeEnter" nas routes aqui, ou seja para
                    //  aplicar as regras de permissões para entrar em páginas automaticamente quando houver
                    //  verificação do servidor novamente
                }).catch(r => {
                    context.commit('clearUser');
                })
            }
        },
    }
})
