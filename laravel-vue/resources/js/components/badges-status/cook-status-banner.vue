<template>
    <b-badge pill :variant="statusColor">{{statusLong}}</b-badge>
</template>

<script>
export default {
    name: "cook-status-banner",
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
            statusColor: "",
            statusLong: "",
        }
    },
    watch: {
        'data': function (val) {

        }
    },
    created() {
        if(this.data.status === "busy"){
            this.statusLong = "Working";
            this.statusColor = "primary";
        } else if (this.data.status === "wait") {
            this.statusLong = "Online";
            this.statusColor = "success";
        } else if(this.data.deleted_at != null){
            this.statusLong = "Deleted";
            this.statusColor = "danger";
        } else if(this.data.blocked === 1) {
            this.statusLong = "Blocked";
            this.statusColor = "danger";
        } else {
            this.statusLong = "Offline";
            this.statusColor = "secondary";
        }
    },
    computed: {
        status() {

        },
        /*statusLong() {
            switch (this.status) {
                case "H":
                    return "Holding";
                case "P":
                    return "Preparing";
                case "R":
                    return "Ready";
                case "T":
                    return "Transit";
                case "D":
                    return "Delivered";
                case "C":
                    return "Canceled";
            }
        },
        statusColor() {
            switch (this.status) {
                case "H":
                    return "secondary";
                case "P":
                case "R":
                case "T":
                    return "primary";
                case "D":
                    return "success";
                case "C":
                    return "danger";
            }
        }*/
    }
}
</script>

<style scoped>

</style>
