<template>
    <div>
        <h1>Order Items</h1>
        <b-table :items="cart" :fields="fields">
            <template #cell(price)="data">
                {{ data.item.price }} €
            </template>
            <template #cell(Sub-Total)="data">
                {{ (data.item.price * data.item.quantity).toFixed(2) }} €
            </template>
        </b-table>
        <hr />
        <h3>Notes</h3>
        <b-form-textarea v-model="note" />
        <p />
        <b-button :disabled="cart.length <= 0" @click="order">Order</b-button>

    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    auth: {
        required: true
    },
    name: "order",
    data: function () {
        return {
            fields: [
                'name', 'price', 'quantity', 'Sub-Total'
            ],
            note: null
        }
    },
    computed: {
        ...mapGetters(['cart']),
    },
    methods: {
        order() {
            axios.post('/api/orders', {
                note: this.note,
                cart: this.cart
            }).then(response => {
                if (response.status === 200) {
                    this.$store.commit('clearCart')
                    this.$toasted.success('Order completed')
                    this.$router.push({name: 'dashboard'})
                } else {
                    this.$toasted.error('Something went wrong')
                }
            }).catch(r => {
                this.$toasted.error(r.response.data.message || 'Something went wrong');
            })
        }
    }
}
</script>

<style scoped>

</style>
