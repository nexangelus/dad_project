<template>
    <div class="row">
        <div class="col-12">
            <List/>
        </div>
    </div>
</template>

<script>
import List from "../components/product/list";
import ListShoppingCart from "../components/shopping-cart/list";
import {mapGetters} from "vuex";
export default {
    auth: {
        required: false
    },
    name: "menu",
    components: {ListShoppingCart, List},
    computed: {
        ...mapGetters(['user']),
    },
}
</script>

<style scoped>

</style>
