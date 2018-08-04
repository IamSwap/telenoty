<template>
    <div>
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5>Telegram Subscribers</h5>
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
            <div class="table-responsive" v-if="subscribers.length">
                <table class="table mb-0">
                    <thead>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>&nbsp;</th>
                    </thead>
                    <tbody>
                        <tr v-for="subscriber in subscribers" :key="subscriber.id">
                            <td>{{ subscriber.id }}</td>
                            <td>
                                <span v-if="subscriber.name">{{ subscriber.name }}</span>
                                <span class="text-muted" v-else>---</span>
                            </td>
                            <td>
                                <span class="text-muted" v-if="subscriber.username">@{{ subscriber.username }}</span>
                                <span class="text-muted" v-else>---</span>
                            </td>
                            <td>
                                <span class="badge" :class="{ 'badge-success': (subscriber.status === 'active'), 'badge-danger': (subscriber.status === 'inactive')  }">{{ subscriber.status }}</span>
                            </td>
                            <td class="d-flex justify-content-end text-right">
                                <button class="btn btn-sm btn-light border mr-2" @click="viewToken(subscriber)">View Token</button>

                                <button class="btn btn-sm mr-2 btn-info text-white"
                                    @click="makeActive(subscriber)"
                                    v-if="subscriber.status === 'inactive'"> Make Active
                                </button>
                                <button class="btn btn-sm mr-2 btn-warning"
                                    @click="makeInactive(subscriber)"
                                    v-if="subscriber.status === 'active'"> Make Inactive
                                </button>

                                <button class="btn btn-sm btn-danger" @click="deleteSubscriber(subscriber)">Delete</button>
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
                        <p>You can use this token to authorize with our Telegram bot 🤖 <strong>TeleNotyBot</strong> to receive deployment notifications.</p>
                        <ol>
                            <li>Open your <strong>Telegram</strong> app &amp; search for our bot <strong>TeleNotyBot</strong></li>
                            <li>Send <code>/start</code> command to start conversation with bot.</li>
                            <li>Send <code>/authorize {{ currentSubscriber.token }}</code> command to start authorization with bot.</li>
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
            subscribers: '',
            loading: false,
            currentSubscriber: ''
        }
    },
    mounted() {
        this.fetch();
        Bus.$on('subscriberAuthorized', () => {
            this.fetch();
            $(this.$refs.modalToken).modal('hide');
        });
    },
    methods: {
        fetch() {
            this.loading = true;
            axios.get(`/api/subscribers`)
                .then(response => {
                    this.subscribers = response.data;
                    this.loading = false;
                }, error => {
                    this.loading = false;
                    swal('Oops!', error.response.data.message, 'error');
                });
        },
        generateToken() {
            this.loading = true;
            axios.post(`/api/subscribers`)
                .then(response => {
                    this.fetch();
                    this.loading = false;
                    this.currentSubscriber = response.data;
                    swal('Generated', 'Token generated successfully!', 'success');
                    $(this.$refs.modalToken).modal('show');
                }, error => {
                    this.loading = false;
                    swal('Oops!', error.response.data.message, 'error');
                });
        },
        viewToken(subscriber) {
            this.currentSubscriber = subscriber;
            $(this.$refs.modalToken).modal('show');
        },
        deleteSubscriber(subscriber) {
            swal({
                title: "Are you sure?",
                text: "This subscriber will be deleted from our database!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((deleteSubscriber) => {
                if (deleteSubscriber) {
                    axios.delete(`/api/subscribers/${subscriber.id}`)
                        .then(response => {
                            this.fetch();
                            swal('Deleted', 'Subscriber has been deleted!', 'success');
                        }, error => {
                            swal('Oops!', error.response.data.message, 'error');
                        })
                }
            });
        },
        makeActive(subscriber) {
            swal({
                title: "Are you sure?",
                text: "This subscriber will be activated!",
                icon: "warning",
                buttons: ['No', 'Yes'],
                dangerMode: false,
            })
            .then((makeActive) => {
                if (makeActive) {
                    axios.patch(`/api/subscribers/${subscriber.id}`, { status: 'active' })
                        .then(response => {
                            this.fetch();
                            swal('Activated', 'Subscriber has been activated & will get notifications on Telegram!', 'success');
                        }, error => {
                            swal('Oops!', error.response.data.message, 'error');
                        })
                }
            });
        },
        makeInactive(subscriber) {
            swal({
                title: "Are you sure?",
                text: "This subscriber will be deactivated!",
                icon: "warning",
                buttons: ['No', 'Yes'],
                dangerMode: true,
            })
            .then((makeInactive) => {
                if (makeInactive) {
                    axios.patch(`/api/subscribers/${subscriber.id}`, { status: 'inactive' })
                        .then(response => {
                            this.fetch();
                            swal('Deactivated', 'Subscriber has been deactivated & will not receive notifications on Telegram!', 'success');
                        }, error => {
                            swal('Oops!', error.response.data.message, 'error');
                        })
                }
            });
        }
    }
}
</script>

