<template>
    <b-container fluid>
        <b-row>
            <b-col lg="6" class="my-1" v-if="userType!=='C'">
                <b-form-group
                    label="Customer"
                    label-for="sort-by-select"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-0"
                    v-slot="{ ariaDescribedby }"
                >
                    <b-input-group size="sm">
                        <b-form-select
                            id="sort-by-select"
                            v-model="customerId"
                            :options="customerOptions"
                            :aria-describedby="ariaDescribedby"
                            class="w-75"
                        >
                            <template #first>
                                <option value="">-- none --</option>
                            </template>
                        </b-form-select>
                    </b-input-group>
                </b-form-group>
            </b-col>
            <b-col lg="6" class="my-1" v-if="userType!=='EC'">
                <b-form-group
                    label="Cooker"
                    label-for="sort-by-select"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-0"
                    v-slot="{ ariaDescribedby }"
                >
                    <b-input-group size="sm">
                        <b-form-select
                            id="sort-by-select"
                            v-model="cookerId"
                            :options="cookerOptions"
                            :aria-describedby="ariaDescribedby"
                            class="w-100"
                        >
                            <template #first>
                                <option value="">-- none --</option>
                            </template>
                        </b-form-select>
                    </b-input-group>
                </b-form-group>
            </b-col>
            <b-col lg="6" class="my-1" v-if="userType!=='ED'">
                <b-form-group
                    label="Deliverer"
                    label-for="sort-by-select"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-0"
                    v-slot="{ ariaDescribedby }"
                >
                    <b-input-group size="sm">
                        <b-form-select
                            id="sort-by-select"
                            v-model="delivererId"
                            :options="delivererOptions"
                            :aria-describedby="ariaDescribedby"
                            class="w-75"
                        >
                            <template #first>
                                <option value="">-- none --</option>
                            </template>
                        </b-form-select>
                    </b-input-group>
                </b-form-group>
            </b-col>
            <b-col lg="6" class="my-1">
                <b-form-group
                    label="Sort"
                    label-for="sort-by-select"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-0"
                    v-slot="{ ariaDescribedby }"
                >
                    <b-input-group size="sm">
                        <b-form-select
                            id="sort-by-select"
                            v-model="sortBy"
                            :options="sortOptions"
                            :aria-describedby="ariaDescribedby"
                            class="w-75"
                        >
                            <template #first>
                                <option value="">-- none --</option>
                            </template>
                        </b-form-select>

                        <b-form-select
                            v-model="sortDesc"
                            :disabled="!sortBy"
                            :aria-describedby="ariaDescribedby"
                            size="sm"
                            class="w-25"
                        >
                            <option :value="false">Asc</option>
                            <option :value="true">Desc</option>
                        </b-form-select>
                    </b-input-group>
                </b-form-group>
            </b-col>


            <b-col lg="6" class="my-1">
                <b-form-group
                    v-model="sortDirection"
                    label="Status "
                    description="Leave all unchecked to filter on all data"
                    label-cols-sm="3"
                    label-align-sm="right"
                    label-size="sm"
                    class="mb-0"
                    v-slot="{ ariaDescribedby }"
                >
                    <b-form-checkbox-group
                        v-model="filterOn"
                        :aria-describedby="ariaDescribedby"
                        class="mt-1"
                    >
                        <b-form-checkbox value="H">H</b-form-checkbox>
                        <b-form-checkbox value="P">P</b-form-checkbox>
                        <b-form-checkbox value="R">R</b-form-checkbox>
                        <b-form-checkbox value="T">T</b-form-checkbox>
                        <b-form-checkbox value="D">D</b-form-checkbox>
                        <b-form-checkbox value="C">C</b-form-checkbox>
                    </b-form-checkbox-group>
                </b-form-group>
            </b-col>
            <b-col sm="7" md="6" class="my-1">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    align="fill"
                    size="sm"
                    class="my-0"
                ></b-pagination>
            </b-col>
        </b-row>
        <b-table
            id="my-table"
            :busy.sync="isBusy"
            :items="myProvider"
        ></b-table>
    </b-container>
</template>

<script>
export default {
    name: "orders",
    data () {
        return {
            isBusy: false,
            filterOn: [],
            cookerOptions: [],
            delivererOptions: [],
            customerOptions: [],
            cookerId: null,
            delivererId: null,
            customerId: null,
        }
    },
    props: {
        id: String,
        userType: String
    },
    mounted() {
        axios.get('api/users').then(response => {
            this.getUsers(response.data)
        })
    },
    methods: {
        myProvider () {
            let promise = axios.get(`/api/users/${this.id}/orders`)
            return promise.then((data) => {
                const items = data.data
                return(items)
            }).catch(error => {
                return []
            })
        },
    }
}
</script>

<style scoped>

</style>
