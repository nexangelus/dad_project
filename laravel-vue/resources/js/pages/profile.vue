<template>
    <div>{{$store.state.user.id}}
        <ProfileEdit v-on:finish="finish" :user="this.$store.state.user" :errors="errors" ref="update"></ProfileEdit>
    </div>
</template>

<script>
import ProfileEdit from "../components/profile/edit";
export default {
    components: {ProfileEdit},
    auth: {
        require: true,
    },
    name: "profile",
    data() {
        return {
            errors: {}
        }
    },
    methods: {
        finish() {
            const user = this.$refs.update.user;
            user.id = this.$store.state.user.id;
            user.type = this.$store.state.user.type;
            axios.put('/api/users', user).then(r => {
                Vue.toasted.success("Details have been changed.");
                this.$store.commit('setUser', r.data.data);
                this.errors = {};
            }).catch(error => {
                this.errors = error.response.data.errors;
                Vue.toasted.error(error.response.data.message)
            })
        }
    }
}
</script>

<style scoped>

</style>
