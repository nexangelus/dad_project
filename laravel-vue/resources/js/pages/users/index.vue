<template>
    <div>
        <ul class="nav nav-tabs nav-fill">
            <li class="nav-item">
                <a class="nav-link" v-bind:class="{'active' : type === 'customers'}" @click="type = 'customers'">Customers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" v-bind:class="{'active' : type === 'employees'}" @click="type = 'employees'">Employees</a>
            </li>
        </ul>
        <router-link :to="{name: 'users-view', params: {id: 5}}">User 5</router-link>
        <router-link :to="{name: 'users-view', params: {id: 4}}">User 4</router-link>
        <router-link :to="{name: 'users-view', params: {id: 22}}">User 22</router-link>
    </div>
</template>

<script>
export default {
    auth: {
        required: true,
        allowed: ["EM"],
    },
    name: "users",
    data() {
        return {
            type: 'customers',
        }
    }
}
</script>

<style scoped>

</style>
