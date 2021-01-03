<template>
    <b-badge pill :variant="statusColor">{{statusLong}}</b-badge>
</template>

<script>
export default {
    name: "order-status-banner",
    props: {
        status: {
            type: String,
            default: () => {
                return "H"
            }
        }
    },
    computed: {
        statusLong() {
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
                    return "preparing"
                case "R":
                    return "ready"
                case "T":
                    return "transit";
                case "D":
                    return "success";
                case "C":
                    return "danger";
            }
        }
    }
}
</script>

<style scoped>
.badge-preparing {
    color: #fff;
    background-color: #ff6500;
}
.badge-ready {
    color: #fff;
    background-color: #ff0ccf;
}
.badge-transit {
    color: #fff;
    background-color: #009aff;
}
</style>
