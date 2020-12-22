<template>
    <CookDashboard v-if="user.type == 'EC'"/>
</template>

<script>
import CookDashboard from "../components/dashboard/cook";
export default {
    name: "dashboard",
    components: {CookDashboard},
    auth: {
        required: true
    },
    data() {
        return {
            auth: true
        }
    },
    computed: {
        user() {
            return this.$store.state.user || {};
        }
    },
}
</script>

<style scoped>

</style>
