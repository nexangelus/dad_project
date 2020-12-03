<template>
    <div class="jumbotron">
        <h2>Login</h2>
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
            <a
                class="btn btn-default"
                v-on:click.prevent="login"
            >Login</a>
        </div>

        <a href="#" @click.prevent="myself">Myself</a>
    </div>
</template>
<script>
export default {
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
                    console.log('User has logged in')
                    console.dir(response.data)

                })
                    .catch(error => {
                        console.log('Invalid Authentication')
                    })
            })
        },
        myself() {
            axios.get('/api/users/me').then(response => {
                console.log('User currently logged:')
                console.dir(response.data)
            })
                .catch(error => {
                    console.log('Invalid Request')
                })
        }
    }
}
</script>
