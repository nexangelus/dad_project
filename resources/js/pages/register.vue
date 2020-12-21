<template>
    <div class="jumbotron">
        <h2>Register Account</h2>
        <router-link to="/login">Login</router-link>
        <form class="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" class="form-control" v-bind:class="{'is-invalid': errors.name}" v-model="user.name">
                <span v-if="errors.name" class="invalid-feedback">{{errors.name[0]}}</span>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" class="form-control" v-bind:class="{'is-invalid': errors.email}" v-model="user.email">
                <span v-if="errors.email" class="invalid-feedback">{{errors.email[0]}}</span>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" class="form-control" v-bind:class="{'is-invalid': errors.password}" v-model="user.password">
                <span v-if="errors.password" class="invalid-feedback">{{errors.password[0]}}</span>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" class="form-control" v-bind:class="{'is-invalid': errors.address}" v-model="user.address">
                <span v-if="errors.address" class="invalid-feedback">{{errors.address[0]}}</span>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="number" id="phone" class="form-control" v-bind:class="{'is-invalid': errors.phone}" v-model="user.phone">
                <span v-if="errors.phone" class="invalid-feedback">{{errors.phone[0]}}</span>
            </div>
            <div class="form-group">
                <label for="nif">NIF:</label>
                <input type="text" id="nif" class="form-control" v-bind:class="{'is-invalid': errors.nif}" v-model="user.nif">
                <span v-if="errors.nif" class="invalid-feedback">{{errors.nif[0]}}</span>
            </div>
            <div class="form-group">
                <label for="photo">Photo:</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="photo" v-on:change="fileChanged" accept="image/*">
                    <label class="custom-file-label" for="photo">{{fileName}}</label>
                </div>
            </div>
            <div class="form-group">
                <a class="btn btn-primary" v-on:click.prevent="create">Register</a>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    auth: {
        required: false
    },
    data: function () {
        return {
            user: {},
            errors: [],
            file: null,
        }
    },
    computed: {
        fileName() {
            return this.file != null ? this.file.name : "Choose a file";
        }
    },
    methods: {
        create() {

            axios.post('/api/register', this.user).then(r => {
                axios.post('/api/login', {email: this.user.email, password: this.user.password}).then(response => {
                    this.$store.commit('setUser', response.data);
                    if(this.file) {
                        let formData = new FormData();
                        formData.append("file", this.file)
                        axios.post('/api/user/photo', formData, {headers: {"Content-Type": "multipart/form-data"}}).then(r => {
                            this.finishedRegister(response.data);
                        })
                    } else {
                        this.finishedRegister(response.data);
                    }
                })
                this.errors = [];
            }).catch(error => {
                this.errors = error.response.data.errors;
                Vue.toasted.error(error.response.data.message)
            })
        },
        fileChanged(e) {
            this.file = e.target.files[0];
        },
        finishedRegister(user) {
            Vue.toasted.success(`Logged in successfully: ${user.name}`)
            this.$router.push('dashboard')
        }
    }
}
</script>

<style scoped>

</style>
