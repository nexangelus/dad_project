<template>
    <div id="first">
        <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
                <a class="nav-link" v-bind:class="{'active' : type === 'hot dish'}" @click="type = 'hot dish'">Hot
                    Dish</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" v-bind:class="{'active' : type === 'cold dish'}" @click="type = 'cold dish'">Cold
                    Dish</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" v-bind:class="{'active' : type === 'drink'}" @click="type = 'drink'">Drinks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" v-bind:class="{'active' : type === 'dessert'}" @click="type = 'dessert'">Dessert</a>
            </li>
        </ul>
        <div class="input-group input-group-lg" id="search">
            <div class="input-group-prepend">
                <span class="input-group-text"><font-awesome-icon icon="search"/></span>
            </div>
            <b-input v-model="search" class="form-control" placeholder="Search for a product"/>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6" v-for="item in filteredList">
                <div class="card" >
                    <img class="card-img-top" :src="`${item.photo_url}`" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">{{ item.name }}</h5>
                        <p>Type: {{ item.typeFormatted }}</p>
                        <p>Unit Price: {{ item.price }} €</p>
                        <b-button v-b-popover.hover.bottom="item.description" variant="primary">
                            Description
                        </b-button>
                        <b-button variant="success" v-on:click="addToCart(item)" v-if="user && user.type === 'C'">
                            <font-awesome-icon icon="cart-plus"/>
                        </b-button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "list",
    data: function () {
        return {
            type: 'hot dish',
            items: null,
            search: null
        }
    },
    mounted() {
        axios.get('/api/products').then(response => {
            this.items = response.data.data
        })
    },
    computed: {
        ...mapGetters(['user']),
        filteredList: function () {
            return this.items ? this.items.filter(i => i.type === this.type && (!this.search || i.name.toLowerCase().includes(this.search.toLowerCase()))) : []
        }
    },
    methods: {
        addToCart(product) {
            if (isNaN(product.quantity)) {
                this.$toasted.error("Quantity inserted is not a number")
            } else {
                this.$toasted.success(`Added ${product.name} to the cart`)
                this.$store.commit('addToCart', product)
            }
        },
    }
}
</script>

<style scoped>
.card-img-top {
    width: 100%;
    height: 10rem;
    object-fit: cover;
}
h5.card-title {
    height: 42px;
}
.col-lg-3.col-md-4.col-sm-6{
    margin-bottom: 10px;
}
.nav-link {
    cursor: pointer;
}

.row {
    margin-top: 1%;
}

#search {
    margin-top: 1%;
    margin-bottom: 1%;
}
</style>
