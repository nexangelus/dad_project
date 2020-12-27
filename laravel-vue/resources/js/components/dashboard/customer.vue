<template>
    <div class="card w-100">
        <div class="card-body" v-if="orders"> <!-- if order exits  -->
            <h2 class="card-title text-center">Order List</h2>
            <table class="table table-striped"  v-for="order in orders">
                <tbody>
                <tr>
                    <th scope="row">ID</th>
                    <td>{{ order.id }}</td>
                </tr>
                <tr>
                    <th scope="row">Started at</th>
                </tr>
                <tr>
                    <th scope="row">Time since started preparing</th>
                    <td>

                    </td>
                </tr>
                <tr>
                    <th scope="row">Order Items</th>
                    <td>
                        <table class="table table-striped">
                            <tbody>
                            <!--<tr v-for="item in order.order_items">
                                <td>{{ item.name }} <small>x{{ item.quantity }}</small></td>
                            </tr>-->
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Costumer Notes</th>
                    <td>{{ order.notes }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="card-body text-center" v-else> <!-- if order exits  -->
            <h2 class="card-title">No orders found</h2>
            <p class="card-text">Waiting for order to be assign.</p>
        </div>
    </div>
</template>

<script>
export default {
    name: "customerDashboard",
    data: function () {
        return {
            orders: null,
            time: null,
        }
    },
    mounted() {
        this.getOrder()
    },
    methods: {
        getOrder() {
            axios.get('/api/customer/order').then(response => {
                console.log(response)
                this.orders = response.data
            })
        },
    }
}
</script>

<style scoped>

</style>
