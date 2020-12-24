<template>
    <div>
        <b-table striped small hover :items="items" :fields="fields">
            <template #cell(description)="data">
                <b-button v-b-popover.hover.top="data.item.description" variant="primary">
                    Full Description
                </b-button>
            </template>
            <template #cell(photo_url)="data">
                <div v-if="data.item.photo_url!=null">
                    <img class="rounded img-fluid" :src="`${data.item.photo_url}`">
                </div>
                <div v-else>
                    <p>No image</p><p> available</p>
                </div>

            </template>
            <template #cell(action)="data">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ isNaN(data.item.quantity)? "0.00": parseFloat(data.item.price*data.item.quantity).toFixed(2) }} €</span>
                    </div>
                    <input v-model="data.item.quantity" placeholder="0" type="number" min="0" step="1" class="form-control" style="width: 30px">
                    <div class="input-group-append" >
                        <b-button variant="success" :disabled="isNaN(data.item.quantity)" v-on:click="addToCart(data.item)" >
                            <font-awesome-icon icon="cart-plus"/>
                        </b-button>
                        <b-button v-on:click="removeFromCart(data.item)" variant="danger">
                            <font-awesome-icon icon="times-circle"/>
                        </b-button>
                    </div>
                </div>
            </template>
        </b-table>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "list",
    data: function () {
        return {
            fields: [
                {key: 'photo_url', label: ''},
                {key: 'name', sortable: true},
                'description',
                'type',
                'price',
                'action'
            ],
            items: null
        }
    },
    mounted() {
        axios.get('/api/products').then(response => {
            console.log(response)
            this.items = response.data.data
        })
    },
    computed: {
        ...mapGetters(['cart']),
    },
    methods: {
        addToCart(product){
            if(isNaN(product.quantity)){
                this.$toasted.error("Quantity inserted is not a number")
            }else{
                this.$toasted.success(`Added ${product.quantity} x ${product.name}`)
                this.$store.commit('addToCart', product);
            }
        },
        removeFromCart(product){
            this.$store.commit('removeProduct', product);
        }
    }
    //TODO filtro e img responsive e function para validar input do client
}
</script>

<style scoped>
img {
    max-width: 150px;
    max-height: 150px;
}
</style>
