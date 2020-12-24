<template>
    <div class="container">
        <div class="row mainrow">
            <div class="col-sm-4 order-sm-12">
                <div class="container-fuild content row">
                    <div class="col-sm-12 select" @click="select('orders')"
                         v-bind:class="{'enabled': selected === 'orders'}">
                        <h5>Orders</h5>
                        <h4>{{ orders.length }}</h4>
                    </div>
                    <div class="col-sm-6 select" @click="select('cooks')"
                         v-bind:class="{'enabled': selected === 'cooks'}">
                        <h6>Cooks</h6>
                        <h5>{{ cooks.length }}</h5>
                    </div>
                    <div class="col-sm-6 select" @click="select('delivery')"
                         v-bind:class="{'enabled': selected === 'delivery'}">
                        <h6>Delivery</h6>
                        <h5>{{ delivs.length }}</h5>
                    </div>
                </div>
                <div class="text-center">
                    <input class="form-check-input" type="checkbox" value="" id="checkShowAll">
                    <label class="form-check-label" for="checkShowAll">
                        Show All Data
                    </label>
                </div>

            </div>
            <div class="col-sm-8">
                <div class="content">
                    <order-list v-show="selected==='orders'" :list="orders"></order-list>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import OrderList from "../order/list";
export default {
    name: "manager",
    components: {OrderList},
    data() {
        return {
            selected: "orders",
            orders: [],
            cooks: [],
            delivs: [],

        }
    },
    created() {
        axios.get('/api/managers/dashboard').then(r => {
            this.orders = r.data.orders;
            this.cooks = r.data.cooks;
            this.delivs = r.data.delivery;
        })
    },
    methods: {
        select(type) {
            this.selected = type;
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

.select:last-child {
    border-left: 1px solid rgba(86, 61, 124, .2)
}

.enabled {
    background-color: #6cb2eb;
}

#a {
    background-color: #ff1;
}
</style>
