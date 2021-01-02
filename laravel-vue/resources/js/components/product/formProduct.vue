<template>
    <div>
        <form class="">
            <div class="row">
                <div class="col-md-3">
                    <img class="img-fluid" :src="photoUrl" alt="">
                    <label v-if="isEditing" for="photo"><small>Select a Photo</small></label>
                    <input type="file" id="photo" v-on:change="fileChanged" accept="image/*" style="display: none;" ref="photo">
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="type">Type:</label>
                        <b-form-select id="type" v-model="product.type" :options="types" v-bind:disabled="!isEditing" />
                    </div>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" class="form-control" v-bind:readonly="!isEditing" v-bind:class="{'is-invalid': errors.name}" v-model="product.name">
                        <span v-if="errors.name" class="invalid-feedback">{{errors.name[0]}}</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" rows="3" v-bind:readonly="!isEditing" v-model="product.description" v-bind:class="{'is-invalid': errors.description}"></textarea>
                <span v-if="errors.description" class="invalid-feedback">{{errors.description[0]}}</span>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" class="form-control" v-bind:readonly="!isEditing" v-bind:class="{'is-invalid': errors.price}" v-model="product.price">
                <span v-if="errors.price" class="invalid-feedback">{{errors.price[0]}}</span>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "formProduct",
    props: {
        isEditing: {
            type: Boolean,
            default: true,
        },
        product: {
            type: Object,
            default: function() {
                return {
                    type: 'drink',
                }
            },
        },
        errors: {
            type: Object,
            required: true,
            default: function() {
                return {}
            },
        },
    },
    data() {
        return {
            file: null,
            rawImage: null,
            types: [
                {value: "drink", text: "Drink"},
                {value: "dessert", text: "Dessert"},
                {value: "hot dish", text: "Hot Dish"},
                {value: "cold dish", text: "Cold Dish"},
            ]
        }
    },
    computed: {
        photoUrl() {
            return this.product.photo_url || this.rawImage;
        },
    },
    methods: {
        fileChanged() {
            this.file = this.$refs.photo.files[0];
            const reader = new FileReader();
            reader.onload = (ev => {
                this.rawImage = ev.target.result;
                this.product.photo_url = null;
            })
            reader.readAsDataURL(this.file);
        }
    }
}
</script>

<style scoped>

</style>
