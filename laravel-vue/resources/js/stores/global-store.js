import Vue from "vue"
import Vuex from "vuex"

Vue.use(Vuex)
export default new Vuex.Store({
    state: {
        user: null,
        cart: new Array()
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
            state.cart = new Array()
            localStorage.removeItem('cart')
        },
        addToCart(state, product) {
            if (product.quantity >0){
                let object = state.cart.find(obj => {
                    return  obj.id === product.id
                })
                if (object == null) {
                    state.cart.push({'id':product.id, 'quantity': parseInt(product.quantity)})
                } else {
                    object.quantity += product.quantity
                }

                localStorage.setItem('cart', JSON.stringify(state.cart))
            }
        },
        removeProduct(state, product) {
            let index = state.cart.findIndex(obj => {
                return obj.id === product.id
            })
            state.cart.splice(index)
            localStorage.setItem('cart', JSON.stringify(state.cart))
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
            context.commit('setCart', JSON.parse(localStorage.getItem('cart'))|| [])

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
        addCart(context, product) {
            product.quantity = parseInt(product.quantity)

            if (product.quantity > 0) {
                context.commit('addToCart', product)
            }
        }
    }
})
