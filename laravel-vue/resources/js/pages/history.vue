<template>
    <div>
        <h2>Orders History</h2>
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
            <template #cell(status)="data">
                <p v-if="data.item.status === 'D'">Delivered</p>
                <p v-else>Canceled</p>
            </template>
            <template #cell(actions)="data">
                <b-button size="sm" @click="data.toggleDetails">
                    <font-awesome-icon icon="info"/>
                </b-button>
            </template>
            <template #cell(total_price)="data">
                {{data.item.total_price}} €
            </template>
            <template #row-details="row">
                <b-card>
                    <ul>
                        <li>Cooker: {{ row.item.cook }}</li>
                        <li>Deliveryman: {{ row.item.deliveryman }}</li>
                        <li>Closed At: {{ row.item.closed_at }}</li>
                        <li>Notes: {{ row.item.notes }}</li>
                    </ul>
                    Order Items:
                    <b-table :items="row.item.order_items">
                        <template #cell(unit_price)="data">
                            <p>{{data.item.unit_price}} €</p>
                        </template>
                        <template #cell(sub_total_price)="data">
                            <p>{{data.item.sub_total_price}} €</p>
                        </template>
                    </b-table>
                </b-card>
            </template>
        </b-table>
        <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage" limit="10"/>
    </div>
</template>

<script>
export default {
    name: "history",
    auth: {
        required: true
    },
    data() {
        return {
            orders: null,
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
                key: 'opened_at',
                label: 'Created At',
                formatter: (value) => this.$moment(value).format('L LT')
            }, {
                key: 'total_price',
                label: 'Total Price'
            }, {
                key: 'status',
                label: 'Status',
                sortable: true,
            }, {
                key: 'actions',
                label: 'Actions'
            }],
            fieldsItems: [
                'name', 'unit_price', 'quantity', 'sub_total_price'
            ]
        }
    },
    mounted() {
        axios.get('/api/customer/history').then(r => {
            this.orders = r.data.data;
        }).catch(r => {
            this.isFetchingData = false;
        })
    }
}
</script>

<style scoped>

</style>
