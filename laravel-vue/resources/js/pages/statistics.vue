<template>
    <div>
        <b-tabs content-class="mt-3" lazy>
            <b-tab title="Global" active>
                <global/>
            </b-tab>
            <b-tab title="Products">
                <product-sold/>
            </b-tab>
            <b-tab title="Employers">
                <employers/>
            </b-tab>
        </b-tabs>
    </div>

</template>

<script>
import ProductSold from "../components/graphics/product-sold";
import Employers from "../components/graphics/employers";
import Global from "../components/graphics/global";
export default {
    name: "statistics",
    auth: {
        required: true,
        allowed: ["EM"],
    },
    components: {Global, Employers, ProductSold},
}
</script>

<style scoped>

</style>
