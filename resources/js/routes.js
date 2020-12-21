import IndexPage from "./pages/index";

import LoginComponent from "./components/login";
import RegisterComponent from "./components/register";

import DashboardPage from "./pages/dashboard";




export default [
    {path: '/', component: IndexPage},

    {name: 'login', path: '/login', component: LoginComponent},
    {name: 'register', path: '/register', component: RegisterComponent},

    {name: 'dashboard', path: '/dashboard', component: DashboardPage},
];
