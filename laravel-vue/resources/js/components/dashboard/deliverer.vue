<template>
    <div class="card w-100">
        <CardOrder v-if="order != null" :order="order"/>
        <OrderListForDeliveryMan v-else :orders="orders"/>
    </div>
</template>

<script>
import CardOrder from "../order/card";
import OrderListForDeliveryMan from "../order/listForDeliveryMan";
export default {
    name: "delivererDashboard",
    components: {OrderListForDeliveryMan, CardOrder},
    data: function () {
        return {
            order: null,
            orders: null,
            time: null,
        }
    },
    methods: {
        getOrder() {
            axios.get('/api/deliverers/work').then(response => {
                if(response.data.data == null){
                    axios.get('/api/deliverers/orders').then(response => {
                        this.orders = response.data.data
                    })
                } else {
                    this.order = response.data.data
                }
            })
        },
        finishOrder() {
            axios.patch('/api/deliverers/ready').then(r => {
                Vue.toasted.success("This order has been submitted to transport successfully.")
                this.getOrder();
            }).catch(r => {
                Vue.toasted.error("An error occurred while trying to mark this as ready. Try again later.")
            })
        },
    },
    mounted() {
        this.getOrder();
    },
}
</script>

<style scoped>

</style>
