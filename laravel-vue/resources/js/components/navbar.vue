<template>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <router-link class="navbar-brand" to="/">Food@Home</router-link>
        <!-- <div class="d-flex order-sm-1 order-md-1 order-lg-1 ml-auto pr-2">
            <ul class="navbar-nav flex-row">
                <li class="nav-item mx-2 mx-lg-0">
                    <router-link to="#"><font-awesome-icon icon="shopping-cart"/></router-link>
                </li>
                <li class="nav-item dropdown" v-if="$store.state.user">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Profile
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <router-link class="dropdown-item" to="profile">My Profile</router-link>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" @click.prevent="logout()" href="#">Logout</a>
                    </div>
                </li>
                <li class="nav-item" v-else>
                    <router-link class="nav-link" to="login">Login</router-link>
                </li>
            </ul>
        </div>-->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar"> <!-- LIMIT OF 10 ITEMS IN THIS MENU -->
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0 navbar-borders">
                <li class="nav-item" v-if="$store.state.user">
                    <router-link class="nav-link" to="dashboard">Dashboard</router-link>
                </li>
                <li class="nav-item" >
                    <router-link class="nav-link" to="menu">Menu</router-link>
                </li>
            </ul>
            <div class="my-2 my-lg-0 navbar-borders">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0 navbar-borders">
                    <li class="navbar-nav">
                        <b-button id="popover" v-on:click.prevent class="nav-link" to="menu"><font-awesome-icon icon="shopping-cart"/></b-button>
                        <b-popover target="popover" triggers="click" placement="top">
                            <div class="text-center">
                                <h2>Shopping Cart</h2>
                                <b-table :items="$store.state.cart"> <!-- filtro -->

                                </b-table>
                                <b-button size="sm" variant="danger">Cancel</b-button>
                                <b-button size="sm" variant="primary">Ok</b-button>
                            </div>

                        </b-popover>
                    </li>
                    <li class="nav-item dropdown" v-if="$store.state.user">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Profile
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <router-link class="dropdown-item" to="profile">My Profile</router-link>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" @click.prevent="logout()" href="#">Logout</a>
                        </div>
                    </li>
                    <li class="nav-item" v-else>
                        <router-link class="nav-link" to="login">Login</router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    name: "navbar",
    methods: {
        logout() {
            axios.post('/api/logout', this.credentials).then(response => {
                this.$store.commit("clearUser");
                Vue.toasted.success(`${response.data.msg}`)
                this.$router.push({name: 'main'})
            }).catch(error => {
                Vue.toasted.error(error.response.data.message)
            })
        },
    },
}
</script>

<style scoped>
.navbar-borders {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}
</style>
