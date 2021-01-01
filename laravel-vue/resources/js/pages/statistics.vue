<template>
    <div>
        <b-tabs content-class="mt-3" lazy>
            <b-tab title="Global" active>
                <employers/>
            </b-tab>
            <b-tab title="Products">
                <product-sold/>
            </b-tab>
            <b-tab title="Employers" >

            </b-tab>
        </b-tabs>
    </div>

</template>

<script>
import ProductSold from "../components/graphics/product-sold";
import Employers from "../components/graphics/employers";
export default {
    name: "statistics",
    auth: {
        required: true
    },
    components: {Employers, ProductSold, },
}
</script>

<style scoped>

</style>
