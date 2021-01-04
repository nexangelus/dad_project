<template>
    <div class="row mainrow">
        <div class="col-sm-3 order-sm-12">
            <div class="container-fuild content row">
                <div class="col-sm-12 select" @click="select('orders')"
                     v-bind:class="{'enabled': selected === 'orders'}">
                    <h5>Orders</h5>
                    <h4>{{ orders.length }}</h4>
                </div>
                <div class="col-sm-12 select" @click="select('employees')"
                     v-bind:class="{'enabled': selected === 'employees'}">
                    <h6>Employees</h6>
                    <h5>{{ employees.length }}</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="content">
                <order-list v-on:childSetCancelOrder="setCancelOrder" v-show="selected==='orders'" :list="orders" />
                <employee-list v-show="selected==='employees'" :list="employees"/>
            </div>
        </div>
    </div>
</template>

<script>
import OrderList from "../order/list";
import EmployeeList from "../employee/list";
export default {
    name: "manager",
    components: {OrderList, EmployeeList},
    data() {
        return {
            selected: "orders",
            orders: [],
            employees: [],
            isFetchingData: false,
            cooksStatus: [],
        }
    },
    created() {
        this.getData();
    },
    methods: {
        select(type) {
            this.selected = type;
        },
        getData() {
            this.isFetchingData = true;
            this.orders = this.employees = [];
            axios.get('/api/managers/dashboard').then(r => {
                this.orders = r.data.orders;
                this.employees = r.data.employees;
                this.isFetchingData = false;
            }).catch(r => {
                this.isFetchingData = false;
            })
        },
        setCancelOrder(id){
            axios.patch(`/api/managers/order/${id}/cancel`).then(response => {
                if(response.status === 200){
                    this.$toasted.success('Order canceled')
                } else {
                    this.$toasted.error('Something went wrong')
                }
            })
        }
    },
    sockets: {
        updateOrdersTable(order) {
            const i = this.orders.findIndex(o => o.id === order.id)
            if(order.__remove || order.status === "C") {
                if(i >= 0) {
                    this.orders.splice(i, 1);
                }
            } else {
                if(i >= 0) {
                    Vue.set(this.orders, i, order);
                } else {
                    this.orders.push(order);
                }
            }
        },
        updatedEmployee(user) {
            const i = this.employees.findIndex(e => e.id === user.id)
            if(!user.__remove) { // definido pela API para remover o item da lista
                if(i >= 0) {
                    Vue.set(this.employees, i, user);
                } else {
                    this.employees.push(user);
                }
            } else {
                if(i >= 0) {
                    this.employees.splice(i, 1);
                }
            }
        }
    }
}
</script>

<style scoped>
.mainrow {
    margin-top: 20px;
}

.col-sm-8 {
    /*background-color: #ff0;
    border: #000 solid*/
}

.col-sm-4 {
    /*background-color: #f10;
    border: #000 solid*/
}

.content {
    border: 1px solid rgba(86, 61, 124, .2);
    border-radius: 10px;
    overflow: hidden;
}

.select {
    text-align: center;
}

.select:first-child {
    border-bottom: 1px solid rgba(86, 61, 124, .2)
}

.enabled {
    background-color: #6cb2eb;
}

#a {
    background-color: #ff1;
}
</style>
