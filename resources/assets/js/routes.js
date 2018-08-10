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
        path: '/dashboard/projects',
        component: require('./components/Projects.vue'),
        props: true
    },
    {
        path: '/dashboard/projects/:id',
        component: require('./components/ProjectSubscribers.vue'),
        props: true
    },
    {
        path: '/dashboard/settings',
        component: require('./components/Settings.vue'),
        props: true
    },
];