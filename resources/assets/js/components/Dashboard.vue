<template>
    <div class="container">
        <h3 class="mb-4">Dashboard</h3>
        <div class="row">
            <div class="col-lg-2">
                <div class="nav inner-nav mb-2">
                    <router-link to="/dashboard" class="nav-link mr-4" exact>
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2zm14 8V5H5v6h14zm0 2H5v6h14v-6zM8 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm0 8a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>
                        </span>
                        <span>My Servers</span>
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
            .listen('AuthorizeReceiver', (e) => {
                this.authrizeReceiver(e);
            });
    },
    methods: {
        authrizeReceiver(e) {
            let receiver = e.receiver;
            receiver.chat_id = e.data.id;
            receiver.name = e.data.first_name + ' ' + e.data.last_name;
            receiver.username = e.data.username;

            swal({
                title: `Authorize ${receiver.name}?`,
                text: `${receiver.name} (@${receiver.username}) wants to receive deployment notifications for ${receiver.server.title}`,
                icon: "warning",
                buttons: ['Cancel', 'Authorize'],
                dangerMode: false,
            })
            .then((authorized) => {
                if (authorized) {
                    const data = {
                        chat_id: receiver.chat_id,
                        name: receiver.name,
                        username: receiver.username
                    };
                    axios.post(`/api/servers/${receiver.server.id}/receivers/${receiver.id}/authorize`, data)
                        .then(response => {
                            swal('Authorized!', `${receiver.name} is now authorized to receive deployment notifications for ${receiver.server.title}`, 'success');
                            Bus.$emit('receiverAuthorized');
                        }, error => {
                            swal('Oops!', 'There was error in activating receiver!', 'error');
                        })
                }
            });
        }
    }
}
</script>
