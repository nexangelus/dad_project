<template>
    <div class="sticky-top">
        <h2>Shopping Cart</h2>
        <br>
        <b-table :fields="fields" :items="cart"  thead-class="d-none">
            <template #cell(quantity)="data">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary" type="button" @click="decrementProductFromCart(data.item)"><font-awesome-icon icon="minus"/></button>
                    </div>
                    <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon1"
                           v-model="data.item.quantity"/>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" @click="addToCart(data.item)"><font-awesome-icon icon="plus"/></button>
                        <b-button variant="danger" @click="removeProduct(data.item)"><font-awesome-icon icon="trash"/></b-button>
                    </div>
                </div>
            </template>
            <template #cell(action)="data">

            </template>
        </b-table>
        <div v-if="cart.length > 0">
            <b-button @click="deleteCart">Clean Cart</b-button>
            <b-button >Confirm</b-button><!-- TODO -->
        </div>
    </div>
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

</style>
