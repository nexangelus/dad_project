<template>
    <div class="card w-100">
        <CardOrder v-on:childSetDelivered="setDelivered" v-if="order != null" :order="order"/>
        <OrderListForDeliveryMan v-on:childSetTransit="setTransit" v-else :orders="orders"/>
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
        setTransit(id){
            axios.patch(`api/deliverers/orders/${id}/transit`).then(response => {
                this.order = response.data.data
            })
        },
        setDelivered(id){
            axios.patch(`api/deliverers/orders/${id}/delivered`).then(response => {
                this.order = null
                this.getOrder()
            })
        }
    },
    mounted() {
        this.getOrder();
    },
}
</script>

<style scoped>

</style>
