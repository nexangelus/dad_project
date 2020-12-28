<template>
    <div>
        <p/>
        <h2 class="text-center">Orders Ready to Deliver</h2>
        <div class="row">
            <div class="col-sm-7">
                <b-form-input v-model="filter" type="search" placeholder="Type to Search" size="sm"
                              class="col-sm-10 offset-sm-1"/>
            </div>
            <div class="col-sm-5">
                <b-form-group
                    label="Per page"
                    label-for="per-page-select"
                    label-align-sm="right"
                    label-cols-sm="4"
                    label-size="sm">
                    <b-form-select
                        id="per-page-select"
                        v-model="perPage"
                        :options="pageOptions"
                        class="col-sm-8"
                        size="sm"
                    />
                </b-form-group>
            </div>

        </div>

        <b-table striped hover :items="orders" :fields="fields" :per-page="perPage" :current-page="currentPage"
                 :filter="filter" @filtered="onFiltered" show-empty responsive="" sort-by="current_status_at">
            <template #empty>
                <h3 class="text-center">No orders are ready to be delivered</h3>
            </template>
        </b-table>
        <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage" limit="10"/>
    </div>
</template>

<script>
export default {
    name: "orderListForDeliveryMan",
    props: {
        orders: {
            type: Array,
            default: () => {
                return []
            }
        }
    },
    data() {
        return {
            filter: null,
            perPage: 10,
            pageOptions: [5, 10, 20, 50],
            currentPage: 1,
            rows: 1,
            fields: [{
                key: 'id',
                label: 'ID',
                sortable: true,
            }, {
                key: 'name',
                label: 'Name',
            }, {
                key: 'time_ready',
                label: 'Last Update',
                formatter: (value) => this.$moment(value).format('L LT'),
                sortable: true,
            }, {
                key: 'actions',
                label: 'Actions'
            }]
        }
    },
    mounted() {
        this.rows = this.orders.length;
    },
    methods: {
        onFiltered(filtered) {
            this.rows = filtered.length;
            this.currentPage = 1;
        }
    }
}
</script>

<style scoped>

</style>
