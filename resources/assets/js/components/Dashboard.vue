<template>
    <div class="container">
        <h3 class="mb-4">Dashboard</h3>
        <div class="row">
            <div class="col-lg-2">
                <div class="nav inner-nav mb-2">
                    <router-link to="/dashboard" class="nav-link mr-4" exact>
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zM5.68 7.1A7.96 7.96 0 0 0 4.06 11H5a1 1 0 0 1 0 2h-.94a7.95 7.95 0 0 0 1.32 3.5A9.96 9.96 0 0 1 11 14.05V9a1 1 0 0 1 2 0v5.05a9.96 9.96 0 0 1 5.62 2.45 7.95 7.95 0 0 0 1.32-3.5H19a1 1 0 0 1 0-2h.94a7.96 7.96 0 0 0-1.62-3.9l-.66.66a1 1 0 1 1-1.42-1.42l.67-.66A7.96 7.96 0 0 0 13 4.06V5a1 1 0 0 1-2 0v-.94c-1.46.18-2.8.76-3.9 1.62l.66.66a1 1 0 0 1-1.42 1.42l-.66-.67zM6.71 18a7.97 7.97 0 0 0 10.58 0 7.97 7.97 0 0 0-10.58 0z"/></svg>
                        </span>
                        <span>Dashboard</span>
                    </router-link>
                    <router-link to="/dashboard/subscribers" class="nav-link mr-4" exact>
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M9 12A5 5 0 1 1 9 2a5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm7 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H7a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v2zm1-5a1 1 0 0 1 0-2 5 5 0 0 1 5 5v2a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3zm-2-4a1 1 0 0 1 0-2 3 3 0 0 0 0-6 1 1 0 0 1 0-2 5 5 0 0 1 0 10z"/></svg>
                        </span>
                        <span>Subscribers</span>
                    </router-link>
                    <router-link to="/dashboard/settings" class="nav-link mr-4" exact>
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M9 4.58V4c0-1.1.9-2 2-2h2a2 2 0 0 1 2 2v.58a8 8 0 0 1 1.92 1.11l.5-.29a2 2 0 0 1 2.74.73l1 1.74a2 2 0 0 1-.73 2.73l-.5.29a8.06 8.06 0 0 1 0 2.22l.5.3a2 2 0 0 1 .73 2.72l-1 1.74a2 2 0 0 1-2.73.73l-.5-.3A8 8 0 0 1 15 19.43V20a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-.58a8 8 0 0 1-1.92-1.11l-.5.29a2 2 0 0 1-2.74-.73l-1-1.74a2 2 0 0 1 .73-2.73l.5-.29a8.06 8.06 0 0 1 0-2.22l-.5-.3a2 2 0 0 1-.73-2.72l1-1.74a2 2 0 0 1 2.73-.73l.5.3A8 8 0 0 1 9 4.57zM7.88 7.64l-.54.51-1.77-1.02-1 1.74 1.76 1.01-.17.73a6.02 6.02 0 0 0 0 2.78l.17.73-1.76 1.01 1 1.74 1.77-1.02.54.51a6 6 0 0 0 2.4 1.4l.72.2V20h2v-2.04l.71-.2a6 6 0 0 0 2.41-1.4l.54-.51 1.77 1.02 1-1.74-1.76-1.01.17-.73a6.02 6.02 0 0 0 0-2.78l-.17-.73 1.76-1.01-1-1.74-1.77 1.02-.54-.51a6 6 0 0 0-2.4-1.4l-.72-.2V4h-2v2.04l-.71.2a6 6 0 0 0-2.41 1.4zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                        </span>
                        <span>Settings</span>
                    </router-link>
                </div>
            </div>
            <div class="col-lg-10">
                <router-view :user="user"></router-view>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            //user: ''
        };
    },
    mounted() {
        Echo.private(`App.User.${this.user.id}`)
            .listen('AuthorizeSubscriber', (e) => {
                this.authorizeSubscriber(e);
            });
    },
    methods: {
        authorizeSubscriber(e) {
            let subscriber = e.subscriber;
            subscriber.chat_id = e.data.id;
            subscriber.name = e.data.first_name + ' ' + e.data.last_name;
            subscriber.username = e.data.username;

            swal({
                title: `Authorize ${subscriber.name}?`,
                text: `${subscriber.name} (@${subscriber.username}) wants to receive deployment notifications!`,
                icon: "warning",
                buttons: ['Cancel', 'Authorize'],
                dangerMode: false,
            })
            .then((authorized) => {
                if (authorized) {
                    const data = {
                        chat_id: subscriber.chat_id,
                        name: subscriber.name,
                        username: subscriber.username
                    };
                    axios.post(`/api/subscribers/${subscriber.id}/authorize`, data)
                        .then(response => {
                            swal('Authorized!', `${subscriber.name} is now authorized to receive deployment notifications`, 'success');
                            Bus.$emit('subscriberAuthorized');
                        }, error => {
                            swal('Oops!', 'There was error in authorizing subscriber!', 'error');
                        })
                }
            });
        }
    }
}
</script>
