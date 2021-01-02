<template>
    <b-modal id="modal-newProduct" size="xl" title="Create new Product" @ok="save" @hidden="closeModal">
        <form-product :errors="errors" :product="product" ref="form" />
    </b-modal>
</template>

<script>
import FormProduct from "./formProduct";
export default {
    name: "createProductModal",
    components: {FormProduct},
    data() {
        return {
            errors: {},
            product: {
                type: 'drink',
            },
        }
    },
    methods: {
        closeModal() {
            this.product = {
                type: 'drink',
            };
        },
        save(e) {
            e.preventDefault();

            if(!this.product.photo_url) {
                if(this.$refs.form.file ) {
                    let formData = new FormData();
                    formData.append("file", this.$refs.form.file)
                    axios.post(`/api/images/products`, formData, {headers: {"Content-Type": "multipart/form-data"}}).then(photo => {
                        this.product.photo_url = photo.data;
                        this.postProduct()
                    })
                } else {
                    Vue.toasted.error("Please upload a image for the product")
                }
            } else {
                this.postProduct()
            }

        },
        postProduct() {
            axios.post(`/api/products`, this.product).then(r => {
                this.product = r.data.data;
                this.$emit('saved', this.product);
                this.errors = {};
                this.product = {
                    type: 'drink',
                };
                this.$bvModal.hide('modal-newProduct');
            }).catch(r => {
                this.errors = r.response.data.errors;
            })
        }
    }
}
</script>

<style scoped>
.modal-footer > div > * {
    margin: 0.25rem;
}
</style>
