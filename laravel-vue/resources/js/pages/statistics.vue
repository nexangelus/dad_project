<template>
    <div>
        <b-tabs content-class="mt-3">
            <b-tab title="Global" active>
            </b-tab>
            <b-tab title="Products">
                <product-sold/>
            </b-tab>
            <b-tab title="Disabled" disabled><p>I'm a disabled tab!</p></b-tab>
        </b-tabs>
    </div>

</template>

<script>
import ProductSold from "../components/graphics/product-sold";
export default {
    name: "statistics",
    auth: {
        required: true
    },
    components: {ProductSold, },
}
</script>

<style scoped>

</style>
