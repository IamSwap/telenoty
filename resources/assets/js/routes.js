export default [
    {
        path: '/dashboard',
        component: require('./components/Welcome.vue'),
        props: true
    },
    {
        path: '/dashboard/subscribers',
        component: require('./components/Subscribers.vue'),
        props: true
    },
    {
        path: '/dashboard/settings',
        component: require('./components/Settings.vue'),
        props: true
    },
];