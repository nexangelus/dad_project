<template>
    <div>
        <CookDashboard v-if="user.type == 'EC'"/>
        <ManagerDashboard v-if="user.type == 'EM'"/>
        <CustomerDashboard v-if="user.type == 'C'"/>
        <DelivererDashboard v-if="user.type == 'ED'"/>
    </div>
</template>

<script>
import CookDashboard from "../components/dashboard/cook";
import ManagerDashboard from "../components/dashboard/manager";
import {mapGetters} from 'vuex';
import CustomerDashboard from "../components/dashboard/customer";
import DelivererDashboard from "../components/dashboard/deliverer";
export default {
    name: "dashboard",
    components: {DelivererDashboard, CustomerDashboard, CookDashboard, ManagerDashboard},
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
