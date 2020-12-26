<template>
    <span v-bind:title="title"><font-awesome-icon v-bind:icon="icon" /></span>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: () => {
                return "C"
            }
        }
    },
    name: "iconUserType",
    data() {
        return {
            title: ""
        }
    },
    computed: {
        icon() {
            switch (this.type) {
                case "EC":
                    this.title = "Cook";
                    return "utensils";
                case "EM":
                    this.title = "Manager";
                    return "user-cog";
                case "ED":
                    this.title = "Deliveryman";
                    return "bicycle";
                case "C":
                    this.title = "Customer";
                    return "user";
            }
        }
    }
}
</script>

<style scoped>

</style>
