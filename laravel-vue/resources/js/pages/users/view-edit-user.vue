<template>
    <edit-profile :user="user" :errors="errors" :is-editing="isEditing" v-on:finish="finish" v-on:action="action" ref="update"></edit-profile>
</template>

<script>
import EditProfile from "../../components/profile/edit";
export default {
    components: {EditProfile},
    auth: {
        required: true,
        allowed: ["EM"],
    },
    name: "view-user",
    data() {
        return {
            user: {},
            errors: {},
        }
    },
    computed: {
        id() {
            return this.$route.params.id;
        },
        isEditing() {
            return this.$route.name === "users-edit";
        }
    },
    mounted() {
        axios.get(`/api/users/${this.id}`).then(r => {
            this.user = r.data.data;
        })
    },
    methods: {
        finish() {
            const user = this.$refs.update.user;
            axios.put('/api/users/'+user.id, user).then(r => {
                if(this.$refs.update.file) {
                    this.user = r.data.data;
                    let formData = new FormData();
                    formData.append("file", this.$refs.update.file)
                    axios.post(`/api/users/${user.id}/photo`, formData, {headers: {"Content-Type": "multipart/form-data"}}).then(image => {
                        this.user.photo_url = image.data;
                        this.finishUpdating();
                    })
                } else {
                    this.finishUpdating();
                }
            }).catch(error => {
                this.errors = error.response.data.errors;
                Vue.toasted.error(error.response.data.message)
            })
        },
        action(type) {
            if(type === 'delete') {
                axios.delete(`/api/users/${this.id}`).then(r => {
                    this.user.deleted_at = true;
                    Vue.toasted.success(`The user has been deleted.`);
                }).catch(r => {
                    console.error(r);
                    Vue.toasted.error("An error occurred while trying to do this.");
                })
            } else {
                let status = type.split('-');
                if(status && status.length === 2 && status[1] === "0") {
                    status = "unblock";
                } else {
                    status = "block";
                }

                axios.patch(`/api/users/${this.id}/status/${status}`).then(r => {
                    this.user = r.data.data;
                    Vue.toasted.success(`The user has been ${status}ed with success.`);
                }).catch(r => {
                    console.error(r);
                    Vue.toasted.error("An error occurred while trying to do this.");
                })
            }
        },
        finishUpdating() {
            Vue.toasted.success("Details have been changed.");
            this.$router.go(-1);
        }
    }
}
</script>

<style scoped>
</style>