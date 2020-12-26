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
                case "R":
                case "T":
                    return "primary";
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

</style>
