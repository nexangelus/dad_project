<template>
    <edit-profile :user="user" :errors="errors" :is-editing="isEditing" v-on:finish="finish" ref="update"></edit-profile>
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

        }
    }
}
</script>

<style scoped>
</style>
