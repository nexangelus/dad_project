<template>
    <b-popover target="popover" triggers="hover" ref="popovercart">
        <div class="sticky-top">
            <h2 class="text-center">Shopping Cart</h2>
            <div class="limited-height">
                <div v-for="item in cart">
                    <hr/>
                    <p>Name: {{item.name}}</p>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" @click="decrementProductFromCart(item)"><font-awesome-icon icon="minus"/></button>
                        </div>
                        <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon1"
                               v-model="item.quantity"/>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" @click="addToCart(item)"><font-awesome-icon icon="plus"/></button>
                            <b-button variant="danger" @click="removeProduct(item)"><font-awesome-icon icon="trash"/></b-button>
                        </div>
                    </div>
                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Price: {{item.price}} €</span>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">Sub-Total: {{parseFloat(item.price*item.quantity).toFixed(2)}} €</span>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="input-group mb-4">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon3">Total: {{total}} €</span>
                </div>
            </div>
            <div v-if="cart.length > 0">
                <b-button @click="deleteCart">Clean Cart</b-button>
                <router-link to="order"><b-button @click="$refs.popovercart.$emit('close')">Confirm</b-button></router-link>
            </div>
        </div>
    </b-popover>
</template>

<script>
import {mapGetters} from "vuex";
export default {
    name: "list-shopping-cart",
    data: function (){
        return {
            fields: [
                'name',
                'quantity',
                'action'
            ],
            option: 1

        }
    },
    computed:{
        ...mapGetters(['cart']),
        total(){
            let total = 0.00
            this.cart.forEach(e => {
                total+= e.price*e.quantity
            })
            return parseFloat(total).toFixed(2)
        }
    },
    methods: {
        addToCart(product) {
            if (isNaN(product.quantity)) {
                this.$toasted.error("Quantity inserted is not a number")
            } else {
                this.$toasted.success(`Added ${product.name} to the cart`)
                this.$store.commit('addToCart', product)
            }
        },
        deleteCart(){
            this.$store.commit('clearCart')
        },
        decrementProductFromCart(product){
            this.$store.dispatch('decrementProductFromCart', product)
        },
        removeProduct(product){
            this.$store.commit('removeProduct', product)
        }

    }
}
</script>

<style scoped>
.limited-height{
    max-height: 70vh;
    overflow: auto;
}
</style>
