<template>
    <div>
        <CookDashboard v-if="user.type == 'EC'"/>
        <ManagerDashboard v-if="user.type == 'EM'"/>
        <CustomerDashboard v-if="user.type == 'C'"/>
    </div>
</template>

<script>
import CookDashboard from "../components/dashboard/cook";
import ManagerDashboard from "../components/dashboard/manager";
import {mapGetters} from 'vuex';
import CustomerDashboard from "../components/dashboard/customer";
export default {
    name: "dashboard",
    components: {CustomerDashboard, CookDashboard, ManagerDashboard},
    auth: {
        required: true
    },
    data() {
        return {
            auth: true
        }
    },
    computed: {
        ...mapGetters(['user']),
    },
}
</script>

<style scoped>

</style>
