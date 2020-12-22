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
                    <td>{{ order.costumerName }}</td>
                </tr>
                <tr>
                    <th scope="row">Started at</th>
                    <td>moment</td>
                </tr>
                <tr>
                    <th scope="row">Time since started preparing</th>
                    <td>moment</td>
                </tr>
                <tr>
                    <th scope="row">Costumer Notes</th>
                    <td>{{ order.notes }}</td>
                </tr>
                <tr>
                    <th scope="row">Order Items</th>
                    <td>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>Each item</td>
                                </tr>
                                <tr>
                                    <td>Each item</td>
                                </tr>
                                <tr>
                                    <td>Each item</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>



                </tbody>
            </table>
            <a href="#" class="btn btn-primary">Button</a>
        </div>
        <div class="card-body text-center"" v-else> <!-- if order exits  -->
            <h2 class="card-title">Order not assigned</h2>
            <p class="card-text">Waiting for order to be assign.</p>
            <a href="#" class="btn btn-primary">Button</a>
        </div>
    </div>
</template>

<script>
export default {
    name: "cookDashboard",
    data: function () {
        return {
            order: {}
        }
    },
}
</script>

<style scoped>

</style>
