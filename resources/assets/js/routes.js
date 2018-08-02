export default [
    {
        path: '/dashboard',
        component: require('./components/Servers.vue'),
        props: true
    },
    {
        path: '/dashboard/settings',
        component: require('./components/Settings.vue'),
        props: true
    },
];