<template>
    <b-container fluid>
        <h3>Order History</h3>
        <b-row>
            <b-col lg="6" class="my-1" v-if="userType !== 'C'">
                <b-form-group label="Customer" label-for="filter-customer" label-cols-sm="3" label-align-sm="right" label-size="sm" class="mb-0">
                    <b-input-group size="sm">
                        <b-form-select id="filter-customer" v-model="filter.customer" :options="customerOptions" class="w-75">
                            <template #first>
                                <b-form-select-option :value="null">-- all --</b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-input-group>
                </b-form-group>
            </b-col>
            <b-col lg="6" class="my-1" v-if="userType !== 'EC'">
                <b-form-group label="Cooker" label-for="filter-cook" label-cols-sm="3" label-align-sm="right" label-size="sm" class="mb-0">
                    <b-input-group size="sm">
                        <b-form-select id="filter-cook" v-model="filter.cook" :options="cookerOptions" class="w-100">
                            <template #first>
                                <b-form-select-option :value="null">-- all --</b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-input-group>
                </b-form-group>
            </b-col>
            <b-col lg="6" class="my-1" v-if="userType!=='ED'">
                <b-form-group label="Deliverer" label-for="filter-delivery" label-cols-sm="3" label-align-sm="right" label-size="sm" class="mb-0">
                    <b-input-group size="sm">
                        <b-form-select id="filter-delivery" v-model="filter.delivery" :options="delivererOptions" class="w-75">
                            <template #first>
                                <b-form-select-option :value="null">-- all --</b-form-select-option>
                            </template>
                        </b-form-select>
                    </b-input-group>
                </b-form-group>
            </b-col>
            <b-col lg="6" class="my-1">
                <b-form-group label="Status " description="Leave all unchecked to filter on all data" label-cols-sm="3" label-align-sm="right" label-size="sm" class="mb-0">
                    <b-form-checkbox-group v-model="filter.status" class="mt-1">
                        <b-form-checkbox value="H">H</b-form-checkbox>
                        <b-form-checkbox value="P">P</b-form-checkbox>
                        <b-form-checkbox value="R">R</b-form-checkbox>
                        <b-form-checkbox value="T">T</b-form-checkbox>
                        <b-form-checkbox value="D">D</b-form-checkbox>
                        <b-form-checkbox value="C">C</b-form-checkbox>
                    </b-form-checkbox-group>
                </b-form-group>
            </b-col>
        </b-row>
        <b-table striped hover id="my-table" :busy.sync="isBusy" :fields="fields" :filter="filter" :current-page="currentPage" :items="myProvider" sort-by="id" :sort-desc="false">
            <template #cell(customer)="data">
                <first-last-name :name="data.item.customer"/>
            </template>
            <template #cell(deliveryman)="data">
                <first-last-name :name="data.item.deliveryman"/>
            </template>
            <template #cell(cook)="data">
                <first-last-name :name="data.item.cook"/>
            </template>
            <template #cell(status)="data">
                <order-status-banner :status="data.item.status"/>
            </template>
            <template #cell(action)="data">
                <b-button variant="primary" size="sm" @click="data.toggleDetails">
                    <font-awesome-icon icon="info"/>
                </b-button>
            </template>
            <template #row-details="row">
                <b-card>
                    <ul>
                        <li>Cooking time: <template v-if="row.item.preparation_time">{{ $moment.duration(row.item.preparation_time, 'seconds').humanize()}}</template> </li>
                        <li>Delivery time: <template v-if="row.item.delivery_time">{{ $moment.duration(row.item.delivery_time, 'seconds').humanize()}}</template> </li>
                        <li>Current Status: <template v-if="row.item.current_status_at">{{row.item.current_status_at | moment("L LT")}}</template></li>
                        <li>Closed At: <template v-if="row.item.closed_at">{{row.item.closed_at | moment("L LT")}}</template></li>
                        <li v-if="row.item.notes">Notes: {{row.item.notes}}</li>
                    </ul>
                    Order Items:
                    <b-table :items="row.item.order_items" foot-clone>
                        <template #cell(unit_price)="data">
                            <p>{{data.item.unit_price}} €</p>
                        </template>
                        <template #cell(sub_total_price)="data">
                            <p>{{data.item.sub_total_price}} €</p>
                        </template>
                        <template #foot(name)="data">&nbsp;</template>
                        <template #foot(unit_price)="data">&nbsp;</template>
                        <template #foot(quantity)="data">
                            Total:
                        </template>
                        <template #foot(sub_total_price)="data">
                            {{ row.item.total_price }} €
                        </template>
                    </b-table>
                </b-card>
            </template>

        </b-table>
        <b-pagination v-model="currentPage" :total-rows="totalRows" :per-page="perPage" class="my-0" limit="10"></b-pagination>
        <b-col>
        </b-col>
    </b-container>
</template>

<script>
import FirstLastName from "../first-last-name";
import OrderStatusBanner from "../badges-status/order-status-banner";
export default {
    name: "orders",
    components: {OrderStatusBanner, FirstLastName},
    data() {
        return {
            isBusy: false,
            filter: {
                customer: null,
                cook: null,
                delivery: null,
                status: [],
            },
            cookerOptions: [],
            delivererOptions: [],
            customerOptions: [],
            currentPage: 1,
            totalRows: 0,
            perPage: 10,
            fields: [{
                key: 'id',
                label: 'ID',
                sortable: true,
            }, {
                key: 'customer',
            }, {
                key: 'cook',
            }, {
                key: 'deliveryman',
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
                key: 'action'
            }]
        }
    },
    props: {
        id: Number,
        userType: String
    },
    mounted() {
        if(this.userType === "C") {
            this.filter.customer = this.id;
            this.fields.splice(this.fields.findIndex(f=>f.key === "customer"), 1)
        } else if(this.userType === "EC") {
            this.filter.cook = this.id;
            this.fields.splice(this.fields.findIndex(f=>f.key === "cook"), 1)
        } else if(this.userType === "ED") {
            this.filter.delivery = this.id;
            this.fields.splice(this.fields.findIndex(f=>f.key === "deliveryman"), 1)
        }
        if (this.userType !== 'C') {
            axios.get('/api/users?mini=C').then(r => {
                this.customerOptions = r.data;
            })
        }
        if (this.userType !== 'EC') {
            axios.get('/api/users?mini=EC').then(r => {
                this.cookerOptions = r.data;
            })
        }
        if (this.userType !== 'ED') {
            axios.get('/api/users?mini=ED').then(r => {
                this.delivererOptions = r.data;
            })
        }
    },
    methods: {
        myProvider(ctx) {
            const filter = ["page="+ctx.currentPage];

            if(ctx.filter.customer) {
                filter.push('customer='+ctx.filter.customer);
            }
            if(ctx.filter.cook) {
                filter.push('cooker='+ctx.filter.cook);
            }
            if(ctx.filter.delivery) {
                filter.push('deliverer='+ctx.filter.delivery);
            }
            if(ctx.filter.status.length > 0) {
                filter.push('status='+ctx.filter.status.join(','));
            }
            if(ctx.sortBy) {
                filter.push(`sort[by]=${ctx.sortBy}`)
                filter.push(`sort[order]=${ctx.sortDesc ? "desc" : "asc"}`)
            }

            let promise = axios.get(`/api/users/orders?${filter.join('&')}`)
            return promise.then(result => {
                const items = result.data.data;
                this.currentPage = result.data.meta.current_page;
                this.totalRows = result.data.meta.total;
                return (items)
            }).catch(error => {
                return []
            })
        },
    }
}
</script>

<style scoped>

</style>
