<template>
    <div class="row">
        <div class="col-md-6">
            <b-form-select v-model="cooker_id"  :options="cookers" @change="getCookersStats"/>
            <h2 class="text-center">Average cooking time</h2>
            <apexchart width="100%" type="bar" :options="options" :series="cooker_stats"></apexchart>
        </div>
        <div class="col-md-6">
            <b-form-select v-model="deliver_id" :options="deliverers" @change="getDeliverersStats"/>
            <h2 class="text-center">Average delivering time</h2>
            <apexchart width="100%" type="bar" :options="options" :series="deliverer_stats"></apexchart>
        </div>
    </div>
</template>

<script>
export default {
    name: "employers",
    data: function () {
        return {
            options: {},
            cookers: [
                {value: -1, text: 'Cooker', disabled: true}
            ],
            deliverers: [
                {value: -1, text: 'Deliverer', disabled: true}
            ],
            cooker_id: -1,
            deliver_id: -1,
            cooker_stats: [],
            deliverer_stats: []
        }
    },
    mounted() {
        axios.get('api/users/employers').then(response =>{
            this.cookers.push(...response.data.cookers)
            this.deliverers.push(...response.data.deliverers)

        })
    },
    methods: {
        getCookersStats() {
            if(this.cooker_id != null){
                axios.get(`/api/managers/statistics/cookers/${this.cooker_id}`).then(response => {
                    this.cooker_stats = []
                    let array = []
                    for (let data of response.data) {
                        array.push({y: this.formatTime(data.average), x: data.week})
                    }
                    this.cooker_stats.push({name: 'Minutes', data: array})
                })
            }
        },
        getDeliverersStats() {
            if(this.deliver_id != null){
                axios.get(`/api/managers/statistics/deliverers/${this.deliver_id}`).then(response => {
                    this.deliverer_stats = []
                    let array = []
                    for (let data of response.data) {
                        array.push({y: this.formatTime(data.average), x: data.week})
                    }
                    this.deliverer_stats.push({name: 'Minutes', data: array})
                })
            }
        },
        formatTime(average) {
            return (parseInt(average) / 60).toFixed(2)
        },
    },
}
</script>

<style scoped>

</style>
