<template>
    <div>
        <ul class="nav nav-tabs nav-fill">
            <li class="nav-item">
                <a class="nav-link" v-bind:class="{'active' : type === 'customers'}" @click="setType('customers')">Customers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" v-bind:class="{'active' : type === 'employees'}" @click="setType('employees')">Employees</a>
            </li>
        </ul>
        <div class="form-row"> <!-- 8 4 no customer-->
            <div class="my-2" v-bind:class="{'col-sm-4': type === 'employees', 'col-sm-8': type === 'customers'}">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Name:</div>
                    </div>
                    <input type="text" v-model="filtering.name" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-sm-4 my-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Blocked:</div>
                    </div>
                    <b-form-select v-model="filtering.blocked" :options="[{value: null, text: 'All'},{value: 1, text: 'Blocked'}, {value: 0, text: 'Not Blocked'}]" />
                </div>
            </div>
            <div class="col-sm-4 my-2" v-if="type === 'employees'">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Type:</div>
                    </div>
                    <b-form-select v-model="filtering.type" :options="[{value: null, text: 'All'},{value: 'EM', text: 'Manager'}, {value: 'EC', text: 'Cook'}, {value: 'ED', text: 'Deliveryman'}]" />
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm-6 my-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Email:</div>
                    </div>
                    <input type="text" v-model="filtering.email" class="form-control" placeholder="Email">
                </div>
            </div>
            <div class="my-2" v-bind:class="{'col-sm-5': type === 'employees', 'col-sm-6': type === 'customers'}">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Logged:</div>
                    </div>
                    <b-form-select v-model="filtering.logged" :options="[{value: null, text: 'All'},{value: 1, text: 'Logged in'}, {value: 0, text: 'Logged Out'}]" />
                </div>
            </div>
            <div class="col-sm-1 my-2" v-if="type === 'employees'">
                <b-button variant="success" class="btn-block" :to="{name: 'users-create'}" title="Create new Employee">
                    <font-awesome-icon icon="plus" />
                </b-button>
            </div>
        </div>
        <b-table striped hover :items="users" :fields="fields" :no-local-sorting="true" :sort-desc.sync="sorting.desc" :sort-by.sync="sorting.by" :busy="tableBusy">
            <template #cell(photo_url)="data">
                <img class="img-fluid" :src="data.value" />
            </template>
            <template #cell(type)="data">
                <icon-user-type :type="data.item.type" />
            </template>
            <template #cell(actions)="row">
                <router-link class="btn btn-primary" :to="{name: 'users-view', params: {id: row.item.id}}">
                    <font-awesome-icon icon="info" />
                </router-link>
            </template>
            <template #table-busy>
                <div class="text-center text-danger my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Loading...</strong>
                </div>
            </template>
        </b-table>
        <nav>
            <ul class="pagination">
                <li v-for="data in paginationData" class="page-item" v-bind:class="{'active': data.active, 'disabled': data.url === null}"><a class="page-link" @click.prevent="getData(data.url)" v-html="data.label"></a></li>
            </ul>
        </nav>
    </div>
</template>

<script>
import IconUserType from "../../components/iconUserType";

export default {
    components: {IconUserType},
    auth: {
        required: true,
        allowed: ["EM"],
    },
    name: "users",
    data() {
        return {
            type: 'customers',
            users: [],
            paginationData: [],
            fieldsCustomers: [
                {key: 'id', label: '#', sortable: true},
                {key: 'photo_url', label: 'Photo'},
                {key: 'name', sortable: true},
                {key: 'email', sortable: true},
                'phone',
                'actions'
            ],
            fieldsEmployees: [
                {key: 'id', label: '#', sortable: true},
                {key: 'photo_url', label: 'Photo'},
                {key: 'type', sortable: true},
                {key: 'name', sortable: true},
                {key: 'email', sortable: true},
                'actions'
            ],
            filtering: {
                name: null,
                blocked: null,
                email: null,
                logged: null,
                type: null,
            },
            sorting: {
                desc: false,
                by: 'id',
            },
            tableBusy: false,
            isWaitingToSearch: false
        }
    },
    computed: {
        fields() {
            return this.type === 'customers' ? this.fieldsCustomers : this.fieldsEmployees;
        },
        filters() {
            const filters = [`${this.type}=1`];

            if (this.sorting.by != null && this.sorting.by.length > 0) {
                filters.push(`sort[by]=${this.sorting.by}`)
                filters.push(`sort[order]=${this.sorting.desc ? "desc" : "asc"}`)
            }

            if (this.filtering.name != null && this.filtering.name.length > 0) {
                filters.push(`name=${this.filtering.name}`)
            }
            if (this.filtering.email != null && this.filtering.email.length > 0) {
                filters.push(`email=${this.filtering.email}`)
            }
            if (this.filtering.blocked != null) {
                filters.push(`blocked=${this.filtering.blocked}`)
            }
            if (this.filtering.logged != null) {
                filters.push(`logged_at=${this.filtering.logged}`)
            }
            if (this.filtering.type != null) {
                filters.push(`type=${this.filtering.type}`)
            }

            return filters;

        }
    },
    watch: {
        filters: function (val) {
            if (!this.isWaitingToSearch) {
                this.tableBusy = true;
                setTimeout(() => {
                    this.getData();
                    this.isWaitingToSearch = false;
                }, 500);
            }
            this.isWaitingToSearch = true;
        }
    },
    created() {
        this.getData();
    },
    methods: {
        setType(type) {
            this.users = this.paginationData = [];
            this.type = type;
            this.filtering = {
                name: null,
                blocked: null,
                mail: null,
                logged: null,
                type: null,
            };
            this.sorting = {
                desc: false,
                by: 'id',
            };
            this.getData();
        },
        getData(page = null) {
            const defaultPage = `/api/users?${this.filters.join('&')}`
            axios.get(page || defaultPage).then(r => {
                this.users = r.data.data;
                this.paginationData = r.data.meta.links;
                this.tableBusy = false
            })
        }
    }
}
</script>

<style scoped>
.page-link {
    cursor: pointer;
}

img {
    max-height: 70px;
}
</style>
