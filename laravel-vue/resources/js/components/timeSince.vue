<template>
    <span>{{num}}</span>
</template>

<script>
export default {
    name: "timeSince",
    props: {
        date: {
            type: String,
            default: "0"
        }
    },
    computed: {
        dateFormatted() {
            return +this.$moment(this.date);
        }
    },
    data() {
        return {
            num: ""
        }
    },
    created() {
        this.update();
    },
    methods: {
        update() {

            const dateNow = +new Date();
            const difference = (dateNow - this.dateFormatted)/1000;
            if(difference === 0) {
                this.num = "now";
            } else {
                let after = difference > 0;

                let d = Math.floor(difference / 86400);
                let h = Math.floor((difference % 86400) / 3600);
                let m = Math.floor(((difference % 86400) % 3600) / 60);
                let s = Math.floor(((difference % 86400) % 3600) % 60);

                let msg = `${d!==0?`${d}d`:""} ${h!==0?`${h}h`:""} ${m!==0?`${m}m`:""} ${s!==0?`${s}s`:""}`;

                this.num = (after ? "" : "in ") + msg + (after ? " ago" : "");

            }

            setTimeout(this.update, 1000);
        }
    }
}
</script>

<style scoped>

</style>
