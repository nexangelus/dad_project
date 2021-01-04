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
            orders: [],
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
            axios.patch(`/api/deliverers/orders/${id}/transit`).then(response => {
                if(response.status === 200){
                    this.$toasted.success('Delivery set to transit')
                    this.order = response.data.data
                }else{
                    this.$toasted.success('Something went wrong')
                }

            })
        },
        setDelivered(id){
            axios.patch(`/api/deliverers/orders/${id}/delivered`).then(response => {
                if(response.status === 200){
                    this.$toasted.success('Delivery has been completed')
                    this.order = null
                    this.getOrder()
                }else{
                    this.$toasted.success('Something went wrong')
                }

            })
        }
    },
    mounted() {
        this.getOrder();
    },
    sockets: {
        newOrder(order) {
            this.orders.push(order);
        },
        alreadyInTransit(id) {
            const index = this.orders.findIndex(o => o.id == id);
            if(index >= 0) {
                this.orders.splice(index, 1);
            }
        },
        orderCancelled(orderID) {
            if(this.order && this.order.id == orderID) {
                Vue.toasted.error("The order you were working on was cancelled by a manager.");
                this.getOrder();
            }
        }
    }
}
</script>

<style scoped>

</style>
