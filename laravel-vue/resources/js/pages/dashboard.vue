<template>
    <div>
        <CookDashboard v-if="user.type == 'EC'"/>
        <ManagerDashboard v-if="user.type == 'EM'"/>
    </div>
</template>

<script>
import CookDashboard from "../components/dashboard/cook";
import ManagerDashboard from "../components/dashboard/manager";
import {mapGetters} from 'vuex';
export default {
    name: "dashboard",
    components: {CookDashboard, ManagerDashboard},
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
