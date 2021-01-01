<template>
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center">Average time to complete order</h2>
            <apexchart width="100%" type="bar" :options="options" :series="stats"></apexchart>
        </div>
        <div class="col-md-6">
            <h2 class="text-center">Average cooking time</h2>
            <apexchart width="100%" type="bar" :options="options" :series="cookers"></apexchart>
        </div>
        <div class="col-md-6">
            <h2 class="text-center">Average delivering time</h2>
            <apexchart width="100%" type="bar" :options="options" :series="deliverers"></apexchart>
        </div>
    </div>
</template>

<script>
export default {
    name: "global",
    data: function () {
        return {
            options: {},
            stats: [],
            cookers: [],
            deliverers: [],
        }
    },
    mounted() {
        this.getEmployersStats()
        this.getCookersStats()
        this.getDeliverersStats()
    },
    methods: {
        getEmployersStats() {
            axios.get(`/api/managers/statistics/global/employers`).then(response => {
                let array = []
                for (let data of response.data) {
                    array.push({y: this.formatTime(data.average), x: this.getWeek(data.week)})
                }
                this.stats.push({name: 'Minutes', data: array})
            })
        },
        getCookersStats() {
            axios.get(`/api/managers/statistics/global/cookers`).then(response => {
                let array = []
                for (let data of response.data) {
                    array.push({y: this.formatTime(data.average), x: this.getWeek(data.week)})
                }
                this.cookers.push({name: 'Minutes', data: array})
            })
        },
        getDeliverersStats() {
            axios.get(`/api/managers/statistics/global/deliverers`).then(response => {
                let array = []
                for (let data of response.data) {
                    array.push({y: this.formatTime(data.average), x: this.getWeek(data.week)})
                }
                this.deliverers.push({name: 'Minutes', data: array})
            })
        },
        formatTime(average) {
            return (parseInt(average) / 60).toFixed(2)
        },
        getWeek(yearWeek) {
            let yearWeekDivided = yearWeek.split('-')
            let year = yearWeekDivided[0]
            let week = yearWeekDivided[1]
            week = parseInt(week) + 1
            return year.toString().concat(`-${week}`)
        },

    },
}
</script>

<style scoped>

</style>
