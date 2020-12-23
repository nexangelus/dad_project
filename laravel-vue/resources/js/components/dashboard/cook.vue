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
                    <td>{{ order.current_status_at | moment("h:mm:ss ,dddd, MMMM Do YYYY") }}</td>
                </tr>
                <tr>
                    <th scope="row">Costumer Notes</th>
                    <td>{{ order.notes }}</td>
                </tr>
                <tr>
                    <th scope="row">Time since started preparing</th>
                    <td>{{ time }}</td>
                </tr>
                <tr>
                    <th scope="row">Order Items</th>
                    <td>
                        <table class="table table-striped">
                            <tbody>
                                <tr v-for="item in order.order_items">
                                    <td>{{ item }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="text-center">
                <a href="#" class="btn btn-primary align-items-center">Done</a>
            </div>
        </div>
        <div class="card-body text-center" v-else> <!-- if order exits  -->
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
            order: {},
            time: null,
        }
    },
    mounted() {
        axios.get('/api/cook/work', this.credentials).then(response => {
            console.log(response)
            this.time = new Date() //response.data.current_status_at
            this.order = response.data.data
            this.teste = this.$moment(this.time).fromNow()
        })
        this.timer()
        //TODO get order items
    },
    methods: {
        timer(){
            this.$moment.relativeTimeThreshold('s');
            this.teste = this.$moment(this.time).fromNow();
            this.teste2 = this.teste.format('dddd, MMMM Do YYYY, h:mm:ss a');
            //TODO formatar timer para mostrar quanto tempo começou  1 min, 37 secs ago
            setTimeout(()=>{
                 this.timer()
            },1000)
        }
    }

}
</script>

<style scoped>

</style>
