<template>
    <b-modal id="modal-viewEditProduct" size="xl" title="Product Details" @ok="save">
        <form-product :errors="errors" :product="selected" ref="form"/>
    </b-modal>
</template>

<script>
import FormProduct from "./formProduct";
export default {
    name: "viewEditProductModal",
    components: {FormProduct},
    props: {
        selected: {
            type: Object,
            required: true,
            default: function() {
                return {}
            },
        }
    },
    data() {
        return {
            isEditing: false,
            errors: {},
        }
    },
    methods: {
        save(e) {
            e.preventDefault();

            if(this.$refs.form.file) {
                let formData = new FormData();
                formData.append("file", this.$refs.form.file)
                axios.post(`/api/images/products`, formData, {headers: {"Content-Type": "multipart/form-data"}}).then(photo => {
                    this.selected.photo_url = photo.data;
                    this.postProduct()
                })
            } else {
                this.postProduct()
            }


            /*axios.put(`/api/products/${this.selected.id}`, this.selected).then(r => {
                if(this.$refs.form.file) {
                    let formData = new FormData();
                    formData.append("file", this.$refs.form.file)
                    axios.post(`/api/products/${this.selected.id}/photo`, formData, {headers: {"Content-Type": "multipart/form-data"}}).then(photo => {
                        r.data.data.photo_url = photo.data;
                        this.$emit('saved', r.data.data);
                        this.errors = {};
                        this.$bvModal.hide('modal-viewEditProduct');
                    })
                } else {
                    this.$emit('saved', r.data.data);
                    this.errors = {};
                }

            }).catch(r => {
                this.errors = r.response.data.errors;
            })
            e.preventDefault();*/
        },
        postProduct() {
            axios.put(`/api/products/${this.selected.id}`, this.selected).then(r => {
                this.$emit('saved', r.data.data);
                this.errors = {};
                this.$bvModal.hide('modal-viewEditProduct');
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
