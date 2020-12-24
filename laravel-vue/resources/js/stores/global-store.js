import Vue from "vue"
import Vuex from "vuex"

Vue.use(Vuex)
export default new Vuex.Store({
    state: {
        user: null,
        cart: {}
    },
    mutations: {
        clearUser(state) {
            state.user = null
            localStorage.removeItem("user")
        },
        setUser(state, user) {
            state.user = user
            localStorage.setItem('user', JSON.stringify(state.user))
        },
        clearCart(state) {
            state.cart = {}
            localStorage.removeItem('cart')
        },
        addToCart(state, product) {
            if(state.cart[product.id] == null){
                state.cart[product.id] = product.quantity
            }else{
                this.updateCart(state, product)
            }
        },
        updateCart(state, product){
            state.cart[product.id] = product.quantity
        },
        removeProduct(state, product){
          state.cart.splice(product.id,1)
        },
        setCart(state, cart) {
            state.cart = cart
            localStorage.setItem('cart', JSON.stringify(state.cart))
        }
    },
    getters: {
        user(state) {
            return state.user
        },
        cart(state) {
            return state.cart
        }

    },
    actions: {
        rebuildData(context, vue) {
            context.commit('setUser', JSON.parse(localStorage.getItem('user')))
            context.commit('')

            if (localStorage.getItem('user') === null) {
                context.commit('clearUser')
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
