<template>
    <div>
        <h2 class="text-center">Orders in process</h2>
        <div v-if="orders.length">
            <transition-group name="order" tag="div">
                <div class="row mt-5 order" v-for="order in orders" :key="order.id">
                    <div class="col-12">
                        <b-card>
                            <b-card-text>
                                <div class="row text-center">
                                    <div class="col-md-4 custom-border">
                                        <table class="table b-table fds">
                                            <tr>
                                                <th class="no-border-top">ID:</th>
                                                <td class="no-border-top">{{order.id}}</td>
                                            </tr>
                                            <tr>
                                                <th>Status:</th>
                                                <td><order-status-banner :status="order.status"/></td>
                                            </tr>
                                            <tr>
                                                <th>Responsible:</th>
                                                <td>{{order.responsible}}</td>
                                            </tr>
                                            <tr>
                                                <th>Order at:</th>
                                                <td>{{order.opened_at | moment('L LT')}}</td>
                                            </tr>
                                            <tr>
                                                <th>Time waited:</th>
                                                <td><TimeSince :date="order.opened_at"/></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-4 custom-border padding-small" >
                                        <h2>Notes:</h2>
                                        <p>{{order.notes}}</p>
                                    </div>
                                    <div class="col-md-4 padding-small">
                                        <h3>Products Ordered</h3>
                                        <div class="limited-height">
                                            <b-table :items="order.order_items"/>
                                        </div>
                                    </div>
                                </div>
                            </b-card-text>
                        </b-card>
                    </div>
                </div>
            </transition-group>
        </div>

        <div v-else>
            <div class="col-12">
                <b-card>
                    <b-card-text>
                        <div class="text-center">
                            <p>You current don't have orders in process</p>
                            <router-link :to="{name: 'c-menu'}"><b-button variant="primary">Create new Order</b-button></router-link>
                            <router-link :to="{name: 'history'}"><b-button variant="primary">See my order history</b-button></router-link>
                        </div>
                    </b-card-text>
                </b-card>
            </div>
        </div>
    </div>
</template>

<script>
import TimeSince from "../timeSince";
import OrderStatusBanner from "../badges-status/order-status-banner";
export default {
    name: "customerDashboard",
    components: {OrderStatusBanner, TimeSince},
    data: function () {
        return {
            orders: [],
            time: null,
            fields: [ 'order.id', 'order.status'],
            statusName : ['Holding','Preparing','Ready','Transit'],
        }
    },
    mounted() {
        this.getOrder()
    },
    methods: {
        getOrder() {
            axios.get('/api/customers/order').then(response => {
                this.orders = response.data.data
            })
        },
    },
    sockets: {
        updateOrder(order) {
            const i = this.orders.findIndex(o => o.id === order.id)
            if (i >= 0) {
                Vue.set(this.orders, i, order);
                if(order.status === "D") {
                    setTimeout(() => {
                        const i = this.orders.findIndex(o => o.id === order.id)
                        this.orders.splice(i, 1);
                    }, 5000);
                }
            } else {
                this.orders.push(order);
            }
        }
    }
}
</script>

<style scoped>
.no-border-top{
    border: none;
}
@media (min-width: 0px) {
    .custom-border {
        border-right: none;
        border-bottom: 1px solid #dee2e6;
    }
    .padding-small{
        padding-top: 15px;
    }
}
@media (min-width: 768px) {
    .custom-border {
        border-right: 1px solid #dee2e6;
        border-bottom: none;
    }
    .padding-small{
        padding-top: 0;
    }
    .limited-height{
        max-height: 25vh;
        overflow: auto;
    }
}
.order-enter, .order-leave-to {
    opacity: 0;
}
.order-leave-to {
    transform: opacity(0);
}
.order {
    transition: all 1s;
}
</style>
