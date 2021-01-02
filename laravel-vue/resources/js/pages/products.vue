<template>
   <div>
       <div class="form-row">
           <div class="col-sm-6 my-2">
               <div class="input-group">
                   <div class="input-group-prepend">
                       <div class="input-group-text">Name:</div>
                   </div>
                   <input type="text" v-model="filter.name" class="form-control" placeholder="Name">
               </div>
           </div>
           <div class="col-sm-5 my-2">
               <div class="input-group">
                   <div class="input-group-prepend">
                       <div class="input-group-text">Type:</div>
                   </div>
                   <div class="form-control overflow-auto">
                       <b-form-checkbox-group id="checkbox-group-2" v-model="filter.type">
                           <b-form-checkbox value="drink">Drinks</b-form-checkbox>
                           <b-form-checkbox value="dessert">Desserts</b-form-checkbox>
                           <b-form-checkbox value="hot dish">Hot Dish</b-form-checkbox>
                           <b-form-checkbox value="cold dish">Cold Dish</b-form-checkbox>
                       </b-form-checkbox-group>
                   </div>

               </div>
           </div>
           <div class="col-sm-1 my-2">
               <b-button variant="success" class="btn-block" title="Create new Product" v-b-modal.modal-newProduct><font-awesome-icon icon="plus"/></b-button>
           </div>
       </div>
       <b-table striped hover :items="products" :fields="fields" :filter-function="runFilter" :filter="filter" @filtered="onFiltered" :per-page="perPage" :current-page="currentPage">
           <template #cell(photo_url)="data">
               <img class="img-fluid" :src="data.value" />
           </template>
           <template #cell(actions)="row">
               <b-button variant="primary" @click="selectedProduct = Object.assign({}, row.item)" v-b-modal.modal-viewEditProduct><font-awesome-icon icon="info"/></b-button>
               <b-button variant="danger" @click="deleteProduct(row.item)"><font-awesome-icon icon="trash"/></b-button>
           </template>
       </b-table>
       <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage" limit="10"/>
       <create-product-modal @saved="saved"/>
       <view-edit-product-modal :selected="selectedProduct" @saved="saved" />
   </div>
</template>

<script>
import CreateProductModal from "../components/product/createProductModal";
import ViewEditProductModal from "../components/product/viewEditProductModal";
export default {
    components: {ViewEditProductModal, CreateProductModal},
    auth: {
        required: true,
        allowed: ["EM"],
    },
    name: "products-index",
    data() {
        return {
            products: [],
            fields: [
                {key: 'id', label: 'ID', sortable: true},
                {key: 'photo_url', label: 'Photo'},
                {key: 'name', sortable: true},
                {key: 'typeFormatted', label: 'Type', sortable: true},
                'price',
                'actions',
            ],
            filter: {
                name: null,
                type: []
            },
            perPage: 7,
            currentPage: 1,
            rows: 1,
            selectedProduct: {},
        }
    },
    created() {
        axios.get('/api/products').then(r => {
            this.products = r.data.data;
        })
    },
    methods: {
        runFilter(row, filter){
            let name = true, type = true;
            let filterName = filter.name && filter.name.length > 0;
            let filterType = filter.type.length > 0;

            if(filterName) {
                name = row.name.toLowerCase().includes(filter.name.toLowerCase());
            }

            if(filterType) {
                type = filter.type.includes(row.type.toLowerCase());
            }

            if(filterName && filterType) {
                return name && type
            } else if (filterName) {
                return name;
            } else if (filterType) {
                return type;
            }
            return true;
        },
        onFiltered(filtered) {
            this.rows = filtered.length;
            this.currentPage = 1;
        },
        saved(product) {
            const savedProductIndex = this.products.findIndex(p => p.id === product.id);
            if(savedProductIndex >= 0) {
                Vue.set(this.products, savedProductIndex, product)
            } else {
                this.products.push(product);
            }
        },
        deleteProduct(product) {
            axios.delete(`/api/products/${product.id}`).then(r => {
                const savedProductIndex = this.products.findIndex(p => p.id === product.id);
                this.products.splice(savedProductIndex, 1);
            }).catch(_ => {
                Vue.toasted.error("An error occurred while trying to delete.");
            });
        }
    }
}
</script>

<style scoped>
img {
    max-height: 70px;
}
</style>
