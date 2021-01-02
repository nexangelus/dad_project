import Vue from "vue"
import Vuex from "vuex"
import logger from "../utilities/logger"

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
                    state.cart.push({'id':product.id,'name':product.name, 'price': product.price, 'quantity': parseInt(product.quantity)})
                } else {
                    object.quantity += 1
                }
                localStorage.setItem('cart', JSON.stringify(state.cart))
            }
        },
        removeProduct(state, product) {
            let index = state.cart.findIndex(obj => {
                return obj.id === product.id
            })
            state.cart.splice(index, 1)
            localStorage.setItem('cart', JSON.stringify(state.cart))
        },
        setCart(state, cart) {
            state.cart = cart
            localStorage.setItem('cart', JSON.stringify(state.cart))
        },
        decrementProductFromCart(state, product) {
            let object = state.cart.find(obj => {
                return  obj.id === product.id
            })
            object.quantity -= 1

            if (object.quantity === 0){
                this.removeProduct(state, product)
            }
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
                logger.log("[store.rebuildData] checking users me");
                axios.get('/api/users/me').then(r => {
                    logger.log("[store.rebuildData] success: ", r);
                    context.commit('setUser', r.data.data);
                }).catch(r => {
                    if(r.response.status === 401) {
                        context.commit('clearUser');
                        if(vue.$route.matched && vue.$route.matched[0].components.default.auth && vue.$route.matched[0].components.default.auth.required === true) {
                            vue.$router.push({name: 'main'})
                        }
                    } else {
                        logger.error("[store.rebuildData] failed: ", r);
                    }


                })
            }
        },
        decrementProductFromCart(context, product){
            if (product.quantity-1 === 0){
                context.commit('removeProduct', product)
            }else {
                context.commit('decrementProductFromCart', product)
            }

        }
    }
})
