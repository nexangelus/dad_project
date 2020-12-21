<template>
    <div>teste: {{test.name}}</div>
</template>

<script>
export default {
    name: "dashboard",
    auth: {
        required: true
    },
    data() {
        return {
            auth: true
        }
    },
    computed: {
        test() {
            return this.$store.state.user || {};
        }
    },
}
</script>

<style scoped>

</style>
