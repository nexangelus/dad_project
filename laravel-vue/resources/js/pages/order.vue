<template>
    <div>
        <h1>Order Items</h1>
        <b-table :items="cart" :fields="fields">
            <template #cell(price)="data">
                {{data.item.price}} €
            </template>
            <template #cell(Sub-Total)="data">
                {{data.item.price*data.item.quantity}} €
            </template>
        </b-table>
        <hr/>
        <h3>Notes</h3>
        <b-form-textarea v-model="note"/>
        <p/>
        <b-button @click="order">Order</b-button>

    </div>
</template>

<script>
import {mapGetters} from "vuex";
export default {
    name: "order",
    data: function () {
        return {
            fields: [
                'name', 'price', 'quantity', 'Sub-Total'
            ],
            note: null
        }
    },
    computed:{
        ...mapGetters(['cart']),
    },
    methods: {
        order(){
            axios.post('api/order', {
                note: this.note,
                cart: this.cart
            }).then()
        }
    }
}
</script>

<style scoped>

</style>
