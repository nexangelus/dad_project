//import IndexPage from "/pages/index";

export default [
    {name: 'main', path: '/', component: require('./pages/index').default},

    {name: 'login', path: '/login', component: require('./pages/login').default},
    {name: 'register', path: '/register', component: require('./pages/register').default},

    {name: 'dashboard', path: '/dashboard', component: require('./pages/dashboard').default},
    {name: 'profile', path: '/profile', component: require('./pages/profile').default},
    {name: 'c-menu', path: '/menu', component: require('./pages/c-menu').default},
    {name: 'order', path: '/order', component: require('./pages/order').default},
    {name: 'history', path: '/history', component: require('./pages/history').default},

    {name: 'users', path: '/users', component: require('./pages/users/index').default},
    {name: 'users-create', path: '/users/create', component: require('./pages/users/new').default},
    {name: 'users-view', path: '/users/:id', component: require('./pages/users/view-edit-user').default},
    {name: 'users-edit', path: '/users/:id/edit', component: require('./pages/users/view-edit-user').default},

    {name: 'statistics', path: '/statistics', component: require('./pages/statistics').default},

    {name: 'products', path: '/products', component: require('./pages/products').default},

    { path: '/:pathMatch(.*)*', component: require('./pages/errors/not-found').default },
];
