<template>
    <div class="jumbotron">
        <h2>Login</h2>
        <router-link to="/register">Create an account</router-link>
        <div class="form-group">
            <label for="inputEmail">Email</label>
            <input
                type="email"
                class="form-control"
                v-model="credentials.email"
                name="email"
                id="inputEmail"
                placeholder="Email address"
            />
        </div>
        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input
                type="password"
                class="form-control"
                v-model="credentials.password"
                name="password"
                id="inputPassword"
            />
        </div>
        <div class="form-group">
            <a class="btn btn-default" v-on:click.prevent="login">Login</a>
        </div>
        <div class="temporary-login">
            <p>Managers:
                <a class="btn btn-outline-success" v-for="n in 3" v-on:click.prevent="log(`manager_${n}`)">{{ n }}</a>
            </p>
            <p>Cook:
                <a class="btn btn-outline-success" v-for="n in 6" v-on:click.prevent="log(`cook_${n}`)">{{ n }}</a>
            </p>
            <p>Delivery:
                <a class="btn btn-outline-success" v-for="n in 12" v-on:click.prevent="log(`deliveryman_${n}`)">{{ n }}</a>
            </p>
            <p>Customer:
                <a class="btn btn-outline-success" v-for="n in 10" v-on:click.prevent="log(`customer_${n}`)">{{ n }}</a>
            </p>
        </div>
    </div>
</template>
<script>
export default {
    auth: {
        required: false
    },
    data: function () {
        return {
            credentials: {
                email: '',
                password: ''
            }
        }
    },
    methods: {
        login() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/login', this.credentials).then(response => {
                    this.$store.commit('setUser', response.data.data);
                    Vue.toasted.success(`Logged in successfully: ${response.data.data.name}`)
                    this.$router.push('dashboard')
                }).catch(error => {
                    Vue.toasted.error(error.response.data.message)
                })
            })
        },
        log(user) {
            this.credentials.email = `${user}@mail.pt`
            this.credentials.password = "123"
            this.login()
        }
        /*,
        myself() {
            axios.get('/api/users/me').then(response => {
                console.log('User currently logged:')
                console.dir(response.data)
            }).catch(error => {
                console.log('Invalid Request')
            })
        }*/
    }
}
</script>
