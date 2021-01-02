<template>
    <b-container fluid>
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
        <b-table id="my-table" :busy.sync="isBusy" :filter="filter" :current-page="currentPage" :items="myProvider"></b-table>
        <b-col sm="12" md="12" class="my-1">
            <b-pagination v-model="currentPage" :total-rows="totalRows" align="fill" size="sm" class="my-0"></b-pagination>
        </b-col>
    </b-container>
</template>

<script>
export default {
    name: "orders",
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
        }
    },
    props: {
        id: String,
        userType: String
    },
    mounted() {
        if(this.userType === "C") {
            this.filter.customer = this.id;
        } else if(this.userType === "EC") {
            this.filter.cook = this.id;
        } else if(this.userType === "ED") {
            this.filter.delivery = this.id;
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
