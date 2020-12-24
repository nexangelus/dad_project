<template>
    <div>
        <h2>Orders</h2>
        <div class="row">
            <div class="col-sm-7">
                <b-form-input v-model="filter" type="search" placeholder="Type to Search" size="sm" class="col-sm-10 offset-sm-1"></b-form-input>
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
                    ></b-form-select>
                </b-form-group>
            </div>

        </div>

        <b-table striped hover :items="list" :fields="fields" :per-page="perPage" :current-page="currentPage"
                 :filter="filter" @filtered="onFiltered" show-empty responsive="sm">
            <template #cell(working)="data">
                <font-awesome-icon v-bind:icon="data.item.working.type == 'EC' ? 'utensils' : 'bicycle'" />
                {{data.item.working.name.split(" ")[0]}}
            </template>
            <template #cell(actions)="data">
                <b-button size="sm" @click="data.toggleDetails">
                    <font-awesome-icon icon="info"/>
                </b-button>
            </template>
            <template #row-details="row">
                <b-card>
                    <ul>
                        <li>Customer: {{row.item.customer.name}}</li>
                        <li>Customer Phone Number: {{row.item.customer.phone}}</li>
                        <li>Order Created: {{row.item.opened_at}}</li>
                        <li>Assigned Cook: {{row.item.cook ? row.item.cook.name : "{no cook assigned}"}}</li>
                        <li>Assigned Deliveryman: {{row.item.deliveryman ? row.item.deliveryman.name : "{no deliveryman assigned}"}}</li>
                        <li>Current Status: {{row.item.current_status_at}}</li>
                        <li>Closed At: {{row.item.closed_at}}</li>
                        <li>Notes: {{row.item.notes}}</li>
                    </ul>
                </b-card>
            </template>
        </b-table>
        <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage" limit="10"
                      aria-controls="table-profiles"></b-pagination>
    </div>
</template>

<script>
export default {
    name: "list",
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
                key: 'customer',
                label: 'Customer',
                formatter: (value) => value.name.split(" ")[0],
                sortable: true,
            },/* {
                key: 'opened_at',
                label: 'Created At',
                formatter: (value) => this.$moment(value).format('L LT')
            },*/ {
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
        onFiltered(filtered) {
            this.rows = filtered.length;
            this.currentPage = 1;
        }
    }
}
</script>

<style scoped>
h2 {
    margin: 10px;
}
</style>
