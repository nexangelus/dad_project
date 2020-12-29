<template>
    <div>
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
            if(!user.nif) {
                delete user.nif;
            }
            user.id = this.$store.state.user.id;
            user.type = this.$store.state.user.type;
            axios.put('/api/users', user).then(r => {
                if(this.$refs.update.file) {
                    let formData = new FormData();
                    formData.append("file", this.$refs.update.file)
                    axios.post('/api/users/photo', formData, {headers: {"Content-Type": "multipart/form-data"}}).then(_ => {
                        this.finishUpdating(r.data.data);
                    })
                } else {
                    this.finishUpdating(r.data.data);
                }
            }).catch(error => {
                this.errors = error.response.data.errors;
                Vue.toasted.error(error.response.data.message)
            })
        },
        finishUpdating(user) {
            Vue.toasted.success("Details have been changed.");
            this.$store.commit('setUser', user);
            this.errors = {};
        }
    }
}
</script>

<style scoped>

</style>
