<template>
    <div class="card w-100">
        <div class="card-body" v-if="order"> <!-- if order exits  -->
            <h2 class="card-title text-center">Order to be prepared</h2>
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th scope="row">ID</th>
                    <td>{{ order.id }}</td>
                </tr>
                <tr>
                    <th scope="row">Costumer Name</th>
                    <td>{{ order.customerName }}</td>
                </tr>
                <tr>
                    <th scope="row">Started at</th>
                    <td>{{ order.current_status_at | moment("L LT") }}</td>
                </tr>
                <tr>
                    <th scope="row">Time since started preparing</th>
                    <td><time-since v-if="order.current_status_at" :date="order.current_status_at"/></td>
                </tr>
                <tr>
                    <th scope="row">Order Items</th>
                    <td>
                        <table class="table table-striped">
                            <tbody>
                                <tr v-for="item in order.order_items">
                                    <td>{{ item.name }} <small>x{{item.quantity}}</small></td>
                                </tr>
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
            <div class="text-center">
                <a href="#" class="btn btn-primary align-items-center" @click.prevent="finishOrder()">Finish</a>
            </div>
        </div>
        <div class="card-body text-center" v-else> <!-- if order exits  -->
            <h2 class="card-title">Order not assigned</h2>
            <p class="card-text">Waiting for order to be assign.</p>
        </div>
    </div>
</template>

<script>
import TimeSince from "../timeSince";
export default {
    name: "cookDashboard",
    components: {TimeSince},
    data: function () {
        return {
            order: {},
            time: null,
        }
    },
    mounted() {
        this.getOrder();
    },
    methods: {
        getOrder() {
            axios.get('/api/cooks/work', this.credentials).then(response => {
                this.order = response.data.data
            })
        },
        finishOrder() {
            axios.patch('/api/cooks/ready').then(r => {
                Vue.toasted.success("This order has been submitted to transport successfully.")
                this.getOrder();
            }).catch(r => {
                Vue.toasted.error("An error occurred while trying to mark this as ready. Try again later.")
            })
        },

    }
}
</script>

<style scoped>

</style>
