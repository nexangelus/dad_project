<template>
    <List/>
</template>

<script>
import List from "../components/product/list";
export default {
    auth: {
        required: false
    },
    name: "menu",
    components: {List},
}
</script>

<style scoped>

</style>
