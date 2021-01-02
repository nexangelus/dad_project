<template>
    <div>
        <h2>Create new Employee</h2>
        <div class="form-group">
            <label for="type">Type:</label>
            <b-form-select id="type" v-model="user.type" :options="[{value: 'EM', text: 'Manager'},{value: 'EC', text: 'Cook'}, {value: 'ED', text: 'Deliveryman'}]"/>
        </div>

        <create-new-employee :user="user" v-on:finish="create" :errors="errors" ref="createNew" />
    </div>
</template>

<script>
import CreateNewEmployee from "../../components/profile/edit";
export default {
    components: {CreateNewEmployee},
    auth: {
        required: true,
        allowed: ["EM"],
    },
    name: "users-create",
    data: function () {
        return {
            user: {
                type: "EM"
            },
            errors: {},
        }
    },
    methods: {
        create() {
            const user = this.$refs.createNew.user;
            const socketID = this.$socket ? this.$socket.id : null;

            axios.post('/api/users', user).then(r => {
                const newUser = r.data.data;
                if(this.$refs.createNew.file) {
                    let formData = new FormData();
                    formData.append("file", this.$refs.createNew.file)
                    axios.post(`/api/users/${newUser.id}/photo`, formData, {headers: {"Content-Type": "multipart/form-data"}}).then(r => {
                        this.$router.push({name: 'users-view', params: {id: newUser.id}});
                    })
                } else {
                    this.$router.push({name: 'users-view', params: {id: newUser.id}});
                }
                this.errors = {};
            }).catch(error => {
                Vue.toasted.error(error.response.data.message)
                this.errors = error.response.data.errors;
            })
        }
    }
}
</script>

<style scoped>

</style>
