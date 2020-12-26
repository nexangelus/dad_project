<template>
    <b-badge pill :variant="statusColor">{{statusLong}}</b-badge>
</template>

<script>
export default {
    name: "employee-status-banner",
    props: {
        data: {
            type: Object,
            default: () => {
                return {}
            }
        }
    },
    data() {
        return {
            statusLong: "",
        }
    },
    computed: {
        statusColor() {
            if(this.data.status === "busy"){
                this.statusLong = "Working";
                return "primary";
            } else if(this.data.deleted_at != null){
                this.statusLong = "Deleted";
                return "danger";
            } else if(this.data.blocked === 1) {
                this.statusLong = "Blocked";
                return "danger";
            } else if (this.data.status === "wait" || this.data.logged_at != null) {
                this.statusLong = "Online";
                return "success";
            } else {
                this.statusLong = "Offline";
                return "secondary";
            }
        }
    }
}
</script>

<style scoped>

</style>
