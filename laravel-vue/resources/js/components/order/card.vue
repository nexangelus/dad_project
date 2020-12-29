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
                    <td>{{ order.name }}</td>
                </tr>
                <tr>
                    <th scope="row">Costumer Email</th>
                    <td>{{ order.email }}</td>
                </tr>
                <tr>
                    <th scope="row">Costumer Phone</th>
                    <td>{{ order.phone }}</td>
                </tr>
                <tr>
                    <th scope="row">Costumer Photo</th>
                    <td><img v-bind:src="order.photo"/> </td>
                </tr>
                <tr v-if="order.notes">
                    <th scope="row">Notes</th>
                    <td><img v-bind:src="order.notes"/> </td>
                </tr>
                <tr>
                    <th scope="row">Time delivery started</th>
                    <td>{{ order.time_delivery | moment('L LT') }} </td>
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
                <a href="#" class="btn btn-primary align-items-center" @click.prevent="setDelivered(order.id)">Finish</a>
            </div>
        </div>
        <div class="card-body text-center" v-else> <!-- if order exits  -->
            <h2 class="card-title">Order not assigned</h2>
            <p class="card-text">Waiting for order to be assign.</p>
        </div>
    </div>
</template>

<script>
export default {
    name: "cardOrder",
    props: {
        order: {
            type: Array,
        }
    },
    methods: {
        setDelivered(id){
            this.$emit('childSetDelivered', id)
        }
    }
}
</script>

<style scoped>

</style>
