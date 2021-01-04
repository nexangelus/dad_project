<template>
    <div class="jumbotron">
        <h2>Register Account</h2>
        <router-link :to="{name: 'login'}"><< Login instead</router-link>
        <editProfile v-on:finish="create" :errors="errors" ref="register"></editProfile>
    </div>
</template>

<script>
import editProfile from '../components/profile/edit'
export default {
    components: {editProfile},
    auth: {
        required: false
    },
    data: function () {
        return {
            user: {},
            errors: {},
        }
    },
    methods: {
        create() {
            const user = this.$refs.register.user;
            const socketID = this.$socket ? this.$socket.id : null;
            axios.post('/api/register', user).then(r => {
                axios.post('/api/login', {email: user.email, password: user.password, socketID: socketID}).then(response => {
                    this.$store.commit('setUser', response.data.data);
                    if(this.$refs.register.file) {
                        let formData = new FormData();
                        formData.append("file", this.$refs.register.file)
                        axios.post('/api/users/photo', formData, {headers: {"Content-Type": "multipart/form-data"}}).then(r => {
                            this.finishedRegister(response.data.data);
                        })
                    } else {
                        this.finishedRegister(response.data.data);
                    }
                })
                this.errors = {};
            }).catch(error => {
                this.errors = error.response.data.errors;
                Vue.toasted.error(error.response.data.message)
            })
        },
        finishedRegister(user) {
            Vue.toasted.success(`Logged in successfully: ${user.name}`)
            this.$router.push({name: 'dashboard'})
        }
    }
}
</script>

<style scoped>

</style>
