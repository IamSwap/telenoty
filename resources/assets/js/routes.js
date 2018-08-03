export default [
    {
        path: '/dashboard',
        component: require('./components/Servers.vue'),
        props: true
    },
    {
        path: '/dashboard/servers',
        component: require('./components/Servers.vue'),
        props: true
    },
    {
        path: '/dashboard/servers/:id',
        component: require('./components/Receivers.vue'),
        props: true
    },
    {
        path: '/dashboard/settings',
        component: require('./components/Settings.vue'),
        props: true
    },
];