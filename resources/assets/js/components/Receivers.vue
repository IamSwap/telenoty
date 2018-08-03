<template>
    <div>
        <div class="card" v-if="server">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white mb-0">
                    <li class="breadcrumb-item">
                        <router-link to="/dashboard">My Servers</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ server.title }}</li>
                </ol>
            </nav>
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5>Telegram Receivers <span class="text-muted">({{ server.title }})</span></h5>
                <button class="btn btn-primary" @click="generateToken()">Generate a token</button>
            </div>

            <div class="card-body">
                <p class="font-size: 1rem;">Here you can generate tokens for your <strong>Telegram</strong> users to authorize with 🤖 <strong>TeleNotyBot</strong>, our Telegram bot to deployment notifications.</p>
                <h5>How to use these tokens?</h5>
                <ol>
                    <li>First, make sure to <a href="#" role="button" @click="generateToken()">generate</a> a token for your Telegram user.</li>
                    <li>Open your <strong>Telegram</strong> app &amp; search for our bot <strong>TeleNotyBot</strong></li>
                    <li>Send <code>/start</code> command to start conversation with bot.</li>
                    <li>Send <code>/authorize <i>[your-token]</i></code> command to start authorization with bot.</li>
                </ol>
            </div>
            <div class="table-responsive" v-if="server.receivers.length">
                <table class="table mb-0">
                    <thead>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>&nbsp;</th>
                    </thead>
                    <tbody>
                        <tr v-for="receiver in server.receivers" :key="receiver.id">
                            <td>{{ receiver.id }}</td>
                            <td>
                                <span v-if="receiver.name">{{ receiver.name }}</span>
                                <span class="text-muted" v-else>---</span>
                            </td>
                            <td>
                                <span class="text-muted" v-if="receiver.username">@{{ receiver.username }}</span>
                                <span class="text-muted" v-else>---</span>
                            </td>
                            <td>
                                <span class="badge" :class="{ 'badge-success': (receiver.status === 'active'), 'badge-danger': (receiver.status === 'inactive')  }">{{ receiver.status }}</span>
                            </td>
                            <td class="d-flex justify-content-end text-right">
                                <button class="btn btn-sm btn-light border mr-2" @click="viewToken(receiver)">View Token</button>

                                <button class="btn btn-sm mr-2 btn-info text-white"
                                    @click="makeActive(receiver)"
                                    v-if="receiver.status === 'inactive'"> Make Active
                                </button>
                                <button class="btn btn-sm mr-2 btn-warning"
                                    @click="makeInactive(receiver)"
                                    v-if="receiver.status === 'active'"> Make Inactive
                                </button>

                                <button class="btn btn-sm btn-danger" @click="deleteReceiver(receiver)">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card bg-white py-5 text-center" v-if="loading">
            <h5>Loading...</h5>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" ref="modalToken">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelTitleId">Authorization Token</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>How to use this token?</h5>
                        <hr>
                        <p>You can use this token to authorize with our Telegram bot 🤖 <strong>TeleNotyBot</strong> to receive deployment notifications from <strong>{{ server.title }}</strong>.</p>
                        <ol>
                            <li>Open your <strong>Telegram</strong> app &amp; search for our bot <strong>TeleNotyBot</strong></li>
                            <li>Send <code>/start</code> command to start conversation with bot.</li>
                            <li>Send <code>/authorize {{ currentReceiver.token }}</code> command to start authorization with bot.</li>
                        </ol>
                        <hr>
                        <div class="text-center">
                            <a href="https://telegram.me/TeleNotyBot" class="btn btn-primary" style="" target="_blank">Open Telegram</a>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [ 'id', 'user' ],
    data() {
        return {
            server: '',
            loading: false,
            currentReceiver: ''
        }
    },
    mounted() {
        this.fetch();
        Bus.$on('receiverAuthorized', () => {
            this.fetch();
            $(this.$refs.modalToken).modal('hide');
        });
    },
    methods: {
        fetch() {
            this.loading = true;
            axios.get(`/api/servers/${this.id}`)
                .then(response => {
                    this.server = response.data;
                    this.loading = false;
                }, error => {
                    this.loading = false;
                    swal('Oops!', error.response.data.message, 'error');
                });
        },
        fetchReceivers() {
            this.loading = true;
            axios.get(`/api/servers/${this.id}/receivers`)
                .then(response => {
                    this.server.receivers = response.data;
                    this.loading = false;
                }, error => {
                    this.loading = false;
                    swal('Oops!', error.response.data.message, 'error');
                });
        },
        generateToken() {
            this.loading = true;
            axios.post(`/api/servers/${this.id}/receivers`)
                .then(response => {
                    this.fetchReceivers();
                    this.loading = false;
                    this.currentReceiver = response.data;
                    swal('Generated', 'Token generated successfully!', 'success');
                    $(this.$refs.modalToken).modal('show');
                }, error => {
                    this.loading = false;
                    swal('Oops!', error.response.data.message, 'error');
                });
        },
        viewToken(receiver) {
            this.currentReceiver = receiver;
            $(this.$refs.modalToken).modal('show');
        },
        deleteReceiver(receiver) {
            swal({
                title: "Are you sure?",
                text: "This receiver will be deleted from our database!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((deleteReceiver) => {
                if (deleteReceiver) {
                    axios.delete(`/api/servers/${this.server.id}/receivers/${receiver.id}`)
                        .then(response => {
                            this.fetchReceivers();
                            swal('Deleted', 'Receiver has been deleted!', 'success');
                        }, error => {
                            swal('Oops!', error.response.data.message, 'error');
                        })
                }
            });
        },
        makeActive(receiver) {
            swal({
                title: "Are you sure?",
                text: "This receiver will be activated!",
                icon: "warning",
                buttons: ['No', 'Yes'],
                dangerMode: false,
            })
            .then((makeActive) => {
                if (makeActive) {
                    axios.patch(`/api/servers/${this.server.id}/receivers/${receiver.id}`, { status: 'active' })
                        .then(response => {
                            this.fetchReceivers();
                            swal('Activated', 'Receiver has been activated & will get notifications on Telegram!', 'success');
                        }, error => {
                            swal('Oops!', error.response.data.message, 'error');
                        })
                }
            });
        },
        makeInactive(receiver) {
            swal({
                title: "Are you sure?",
                text: "This receiver will be deactivated!",
                icon: "warning",
                buttons: ['No', 'Yes'],
                dangerMode: true,
            })
            .then((makeInactive) => {
                if (makeInactive) {
                    axios.patch(`/api/servers/${this.server.id}/receivers/${receiver.id}`, { status: 'inactive' })
                        .then(response => {
                            this.fetchReceivers();
                            swal('Deactivated', 'Receiver has been deactivated & will not receive notifications on Telegram!', 'success');
                        }, error => {
                            swal('Oops!', error.response.data.message, 'error');
                        })
                }
            });
        }
    }
}
</script>

