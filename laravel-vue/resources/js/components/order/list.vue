<template>
    <div>
        <h2>Orders</h2>
        <div class="row">
            <b-col lg="7" class="my-1">
                <b-form-group label="Type " description="Leave all unchecked to show all data" label-cols-sm="2" label-align-sm="right" label-size="sm" class="mb-0">
                    <b-form-checkbox-group v-model="filter.status" class="mt-1">
                        <b-form-checkbox value="H">Holding</b-form-checkbox>
                        <b-form-checkbox value="P">Preparing</b-form-checkbox>
                        <b-form-checkbox value="R">Ready</b-form-checkbox>
                        <b-form-checkbox value="T">Transit</b-form-checkbox>
                    </b-form-checkbox-group>
                </b-form-group>
            </b-col>
            <div class="col-sm-4">
                <b-form-group label="Per page" label-for="per-page-select" label-align-sm="right" label-cols-sm="4" label-size="sm">
                    <b-form-select id="per-page-select" v-model="perPage" :options="pageOptions" class="col-sm-8" size="sm" />
                </b-form-group>
            </div>

        </div>

        <b-table v-if="list.length >= 0" striped hover :items="list" :fields="fields" :per-page="perPage" :current-page="currentPage"
                 :filter="filter" :filter-function="runFilter" show-empty responsive="" sort-by="current_status_at">
            <template #cell(working)="data">
                <template v-if="data.item.working">
                    <icon-user-type :type="data.item.working.type" />
                    <first-last-name :name="data.item.working.name" />
                </template>
                <template v-else>
                    <small><em>waiting</em></small>
                </template>

            </template>
            <template #cell(status)="data">
                <status-banner :status="data.item.status" />
            </template>
            <template #cell(customer)="data">
                <first-last-name :name="data.item.customer.name"/>
            </template>
            <template #cell(actions)="data">
                <b-button variant="primary" size="sm" @click="data.toggleDetails">
                    <font-awesome-icon icon="info"/>
                </b-button>
                <b-button variant="danger" title="Cancel this order" size="sm" @click.prevent="cancelOrder(data.item.id)">
                    <font-awesome-icon icon="ban"/>
                </b-button>
            </template>
            <template #row-details="row">
                <b-card>
                    <ul>
                        <li>Customer: {{row.item.customer.name}}</li>
                        <li>Customer Phone Number: {{row.item.customer.phone}}</li>
                        <li>Order Created: {{row.item.opened_at | moment("L LT")}}</li>
                        <li>Assigned Cook: {{row.item.cook ? row.item.cook.name : "{no cook assigned}"}}</li>
                        <li>Assigned Deliveryman: {{row.item.deliveryman ? row.item.deliveryman.name : "{no deliveryman assigned}"}}</li>
                        <li>Current Status: <template v-if="row.item.current_status_at">{{row.item.current_status_at | moment("L LT")}}</template></li>
                        <li>Closed At: <template v-if="row.item.closed_at">{{row.item.closed_at | moment("L LT")}}</template></li>
                        <li>Notes: {{row.item.notes}}</li>
                    </ul>
                </b-card>
            </template>
        </b-table>
        <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage" limit="10" />
    </div>
</template>

<script>
import StatusBanner from "../badges-status/order-status-banner";
import IconUserType from "../iconUserType";
import FirstLastName from "../first-last-name";
export default {
    name: "list",
    components: {FirstLastName, IconUserType, StatusBanner},
    props: {
        list: {
            type: Array,
            default: () => {
                return []
            }
        }
    },
    data() {
        return {
            filter: {
                status: [],
            },
            perPage: 10,
            pageOptions: [5, 10, 20, 50],
            currentPage: 1,
            rows: 1,
            fields: [{
                key: 'id',
                label: 'ID',
                sortable: true,
            }, {
                key: 'customer',
                label: 'Customer',
                sortable: true,
            }, {
                key: 'working',
                label: 'Responsible',
                sortable: true,
            }, {
                key: 'current_status_at',
                label: 'Last Update',
                formatter: (value) => this.$moment(value).format('L LT'),
                sortable: true,
            }, {
                key: 'status',
                label: 'Status',
                sortable: true,
            }, {
                key: 'actions',
                label: 'Actions'
            }]
        }
    },
    mounted() {
        this.rows = this.list.length;
    },
    watch: {
        'list': function (val) {
            this.rows = val.length;
            for (let i = 0; i < val.length; i++) {
                val[i].working = val[i].deliveryman ? val[i].deliveryman : val[i].cook;
            }
        }
    },
    methods: {
        cancelOrder(id){
            this.$emit('childSetCancelOrder', id)
        },
        runFilter(row, filter){
            let status = null;

            if(filter.status.length > 0) {
                status = filter.status.includes(row.status);
            }

            status = status == null ? true : status;

            return status;
        }
    }
}
</script>

<style scoped>
h2 {
    margin: 10px;
}
</style>
