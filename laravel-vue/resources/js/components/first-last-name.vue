<template>
    <span :title="name">{{finalName}}</span>
</template>

<script>
export default {
    name: "first-last-name",
    props: {
        name: String
    },
    computed: {
        finalName(){
            if(this.name!== ''){
                const names = this.name.split(' ')
                if(names.length>=2){
                    return `${names[0]} ${names[names.length-1]}`
                }
                return this.name
            }
            return ''
        }
    }
}
</script>

<style scoped>

</style>
