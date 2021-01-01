<template>
    <div>
        <h2>Filters</h2>
        <b-form-select label-field="Product" v-model="selected_time" :options="time" @change="getLastMonthProduct"/>
        <b-form-select label-field="Product" v-model="selected_product_id" :options="items" @change="getLastMonthProduct"/>
        <h2>Quantity of Products Sold</h2>
        <apexchart width="500" type="line" :options="options" :series="chart_quantity"></apexchart>
        <h2>Money earned</h2>
        <apexchart width="500" type="line" :options="options2" :series="chart_earn"></apexchart>
    </div>
</template>

<script>
export default {
    name: "product-sold",
    data: function () {
        return {
            options: {},
            options2: {},
            statistics_quantity: [],
            statistics_earn: [],
            chart_quantity: [],
            chart_earn: [],
            items: [ {value: '', text:'All'}],
            selected_product_id: '',
            time: [
                { value: 'currentMonth', text: 'Current Month' },
                { value: 'lastMonth', text: 'Last Month' },
                { value: 'last6Months', text: 'Last 6 Months' },
                { value: 'last2Years', text: 'Last 2 Years' },
            ],
            selected_time: 'currentMonth',
        }
    },
    mounted() {
        axios.get('/api/managers/products').then(response => {
            for (let argument of response.data.data) {
                this.items.push({value: argument.id , text: argument.name})
            }
            this.getLastMonthProduct()
        })
    },
    methods: {
        getLastMonthProduct() {
            axios.get(`/api/managers/statistics/${this.selected_time}/products/${this.selected_product_id}`).then(response => {
                this.chart_quantity = []
                this.chart_earn = []
                this.statistics_quantity = []
                this.statistics_earn = []
                if(this.selected_time === 'lastMonth' || this.selected_time === 'currentMonth'){
                    this.days(response.data)
                } else if(this.selected_time === 'last6Months'){
                    this.weeks(response.data)
                } else if(this.selected_time === 'last2Years'){
                    this.months(response.data)
                }
                this.chart_quantity.push({name: 'Sold', data: this.statistics_quantity})
                this.chart_earn.push({name:'Earned' , data: this.statistics_earn})
            })
        },
        days(response){
            let days
            if(this.selected_time === 'lastMonth'){
                let lastMonth = new Date().getMonth()
                let year = new Date().getFullYear()
                days = new Date(year, lastMonth, 0).getDate()
            }else {
                days = new Date().getDate()
            }
            for (let i = 1; i <= days; i++) {
                this.statistics_quantity.push({y: this.getQuantityFromDay(response, i), x: i})
                this.statistics_earn.push({y: this.getValueFromDay(response, i), x: i})
            }
        },
        getQuantityFromDay(list, day) {
            for (let item of list) {
                if (day === item.day) {
                    return parseInt(item.sum)
                }
            }
            return 0;
        },
        getValueFromDay(list, day) {
            for (let item of list) {
                if (day === item.day) {
                    return parseFloat(item.earn).toFixed(2)
                }
            }
            return 0;
        },
        weeks(response){
            for (const data of response) {
                this.statistics_quantity.push({y: data.sum, x: this.getWeek(data.week)})
                this.statistics_earn.push({y: data.earn, x: this.getWeek(data.week)})
            }
        },
        getWeek(yearWeek){
            let yearWeekDivided = yearWeek.split('-')
            let year = yearWeekDivided[0]
            let week = yearWeekDivided[1]
            week = parseInt(week)+1
            return year.toString().concat(`-${week}`)
        },
        months(response){
            for (const data of response) {
                this.statistics_quantity.push({y: data.sum, x: data.month})
                this.statistics_earn.push({y: data.earn, x: data.month})
            }
        }

    },
}
</script>

<style scoped>

</style>
