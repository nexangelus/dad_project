<template>
    <div>
        <b-table striped hover :items="items" :fields="fields">
            <template #cell(photo_url)="data">
                <!-- `data.value` is the value after formatted by the Formatter -->
                <img class="rounded img-fluid" :src="`${data.item.photo_url}`">
            </template>
            <template #cell(action)="data">
                <input v-model="data.item.number" placeholder="0" type="number" min="0" step="1">
            </template>
        </b-table>
    </div>
</template>

<script>
export default {
    name: "list",
    data: function () {
        return {
            fields: [
                {key: 'photo_url', label: 'Image'},
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
    methods: {}
    //TODO filtro e img responsive e function para validar input do client
}
</script>

<style scoped>
img {
    max-width: 150px;
    max-height: 150px;
}
</style>
