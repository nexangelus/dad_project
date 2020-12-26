<template>
    <div>
        <h2>Employees</h2>
        <div class="row">
            <div class="col-sm-7">
                <b-form-input v-model="filter" type="search" placeholder="Type to Search" size="sm" class="col-sm-10 offset-sm-1" />
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

        <b-table striped hover :items="list" :fields="fields" :per-page="perPage" :current-page="currentPage"
                 :filter="filter" @filtered="onFiltered" show-empty responsive="">
            <template #cell(photo_url)="data">
                <img :src="data.value" />
            </template>
            <template #cell(type)="data">
                <icon-user-type :type="data.item.type"/>
            </template>
            <template #cell(blocked)="data">
                <employee-status-banner :data="data.item" />
            </template>
            <template #cell(created_at)="data">
                {{ (data.item.available_at ? data.item.available_at : (data.item.updated_at ? data.item.updated_at : data.item.created_at)) | moment("L LT")}}
            </template>
            <template #cell(actions)="data">
                <b-button size="sm" @click="data.toggleDetails">
                    <font-awesome-icon icon="info"/>
                </b-button>
            </template>
            <template #row-details="row">
                <b-card>
                    <ul>
                        <li>Full Name: {{row.item.name}}</li>
                        <li>Email: {{row.item.email}}</li>
                        <li v-if="row.item.status === 'busy'">Currently working on: <span><i id="popover-reactive-1" v-bind:id="`popover-details-${row.item.id}`">hover for details</i></span></li>
                        <li>Logged in At: {{$moment(row.item.logged_at).format("L LT")}} (<time-since :date="row.item.logged_at"/>)</li>
                        <li v-if="row.item.available_at">Last Available At: {{$moment(row.item.available_at).format("L LT")}}</li>
                        <li>Created At: {{$moment(row.item.created_at).format("L LT")}}</li>
                        <li>Updated At: {{$moment(row.item.updated_at).format("L LT")}}</li>
                    </ul>
                </b-card>
                <b-popover v-if="row.item.status === 'busy'" v-bind:target="`popover-details-${row.item.id}`" triggers="hover" placement="auto" container="my-container" ref="popover" @show="getCookWorkingOn(row.item.id, row.item.type)">
                    <template #title>
                        Details
                    </template>
                    <div class="text-center text-primary my-2" v-if="!details">
                        <b-spinner class="align-middle"></b-spinner>
                        <strong>Loading...</strong>
                    </div>
                    <div v-if="details">
                        <p><strong>ID:</strong> {{details.id}}</p>
                        <p><strong>Customer Name:</strong> {{details.customerName}}</p>
                        <p v-if="details.current_status_at"><strong>Current Status:</strong> {{details.current_status_at | moment("L LT")}}  (<time-since :date="details.current_status_at" />) </p>
                        <strong>Order Items:</strong>
                        <ul>
                            <li v-for="item in details.order_items">{{item}}</li>
                        </ul>
                    </div>
                </b-popover>
            </template>
        </b-table>
        <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage" limit="10" />
    </div>
</template>

<script>
import StatusBanner from "../badges-status/order-status-banner";
import EmployeeStatusBanner from "../badges-status/employee-status-banner";
import IconUserType from "../iconUserType";
import TimeSince from "../timeSince";
export default {
    name: "list",
    components: {TimeSince, IconUserType, EmployeeStatusBanner, StatusBanner},
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
                key: 'photo_url',
                label: 'Photo',
            }, {
                key: 'type',
                label: 'Type',
                formatter: (value) => value.split(" ")[0],
            }, {
                key: 'name',
                label: 'Name',
                sortable: true,
                formatter: (value) => value.split(" ")[0],
            }, {
                key: 'blocked',
                label: 'Status',
                sortable: true,
            }, {
                key: 'created_at',
                label: 'Last Available At',
                sortable: true,
            }, {
                key: 'actions',
                label: 'Actions'
            }],
            details: {has: false}
        }
    },
    mounted() {
        this.rows = this.list.length;
    },
    watch: {
        'list': function (val) {
            this.rows = val.length;
        }
    },
    methods: {
        onFiltered(filtered) {
            this.rows = filtered.length;
            this.currentPage = 1;
        },
        getCookWorkingOn(id, type) {
            this.details = false;
            type = type === "EC" ? "cook" : "delivery";
            axios.get(`/api/managers/dashboard/${type}/${id}`).then(r => {
                this.details = r.data.data;
            })
        }
    }
}
</script>

<style scoped>
h2 {
    margin: 10px;
}
img {
    max-height: 60px;
}
p {
    margin: 0;
}
</style>
