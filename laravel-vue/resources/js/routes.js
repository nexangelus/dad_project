//import IndexPage from "/pages/index";

export default [
    {name: 'main', path: '/', component: require('./pages/index').default},

    {name: 'login', path: '/login', component: require('./pages/login').default},
    {name: 'register', path: '/register', component: require('./pages/register').default},

    {name: 'dashboard', path: '/dashboard', component: require('./pages/dashboard').default},
    {name: 'profile', path: '/profile', component: require('./pages/profile').default},
    {name: 'menu', path: '/menu', component: require('./pages/menu').default},

























































































    { path: '/:pathMatch(.*)*', component: require('./pages/errors/not-found').default },
];