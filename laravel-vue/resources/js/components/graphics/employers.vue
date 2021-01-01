<template>
    <div>
        Tempo até entrega em média
        <apexchart width="500" type="bar" :options="options" :series="stats"></apexchart>
        Tempo cookers
        <apexchart width="500" type="bar" :options="options" :series="cookers"></apexchart>
        Tempo Deliverer
        <apexchart width="500" type="bar" :options="options" :series="deliverers"></apexchart>
    </div>

</template>

<script>
export default {
    name: "employers",
    data: function () {
        return {
            options: {},
            stats : [],
            cookers: [],
            deliverers :[]

        }
    },
    mounted() {
        this.getEmployersStats()
        this.getCookersStats()
        this.getDeliverersStats()
    },
    methods: {
        getEmployersStats() {
            axios.get(`/api/managers/statistics/employers`).then(response => {
                let array = []
                for (let data of response.data) {
                    array.push({y: this.formatTime(data.average), x: this.getWeek(data.week)})
                }
                this.stats.push({name: 'Minutes', data: array})
            })
        },
        getCookersStats() {
            axios.get(`/api/managers/statistics/employers/cookers`).then(response => {
                let array = []
                for (let data of response.data) {
                    array.push({y: this.formatTime(data.average), x: this.getWeek(data.week)})
                }
                this.cookers.push({name: 'Minutes', data: array})
            })
        },
        getDeliverersStats() {
            axios.get(`/api/managers/statistics/employers/deliverers`).then(response => {
                let array = []
                for (let data of response.data) {
                    array.push({y: this.formatTime(data.average), x: this.getWeek(data.week)})
                }
                this.deliverers.push({name: 'Minutes', data: array})
            })
        },
        formatTime(average){
            return (parseInt(average)/60).toFixed(2)
        },
        getWeek(yearWeek){
            let yearWeekDivided = yearWeek.split('-')
            let year = yearWeekDivided[0]
            let week = yearWeekDivided[1]
            week = parseInt(week)+1
            return year.toString().concat(`-${week}`)
        },

    },
}
</script>

<style scoped>

</style>
