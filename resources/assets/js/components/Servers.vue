<template>
    <div>
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5>My Servers</h5>
                <button class="btn btn-primary" v-if="servers.length" @click="showAddServerModal()">Add a server</button>
            </div>

            <div>
                <div class="py-5 text-center" v-if="loading">
                    <h5>Loading...</h5>
                </div>
                <div class="table-responsive" v-if="servers.length">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Server</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Webhook</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="server in servers" :key="server.id">
                                <td>
                                    <router-link :to="`/dashboard/servers/${server.id}`"><strong>{{ server.title }}</strong></router-link>
                                </td>
                                <td class="text-center">
                                    <span class="badge" :class="{ 'badge-success': (server.status === 'active'), 'badge-danger': (server.status === 'inactive')  }">{{ server.status }}</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light border" @click="showWebhookModal(server)">View Webhook</button>
                                </td>
                                <td class="d-flex justify-content-end text-right">
                                    <router-link :to="`/dashboard/servers/${server.id}`" class="btn btn-sm btn-light border mr-2">Manage Receivers</router-link>
                                    <button class="btn btn-sm btn-info text-white mr-2" @click="showEditServerModal(server)">Edit</button>
                                    <button class="btn btn-sm btn-danger" @click="deleteServer(server)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center align-items-center text-center" v-else>
                    <div>
                        <p>You have no servers yet. Please add one to continue.</p>
                        <button class="btn btn-primary" @click="showAddServerModal()">Add a server</button>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" ref="modalServer">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-if="currentServer.id">Edit {{ currentServer.title }}</h4>
                        <h4 class="modal-title" v-else>Add a server</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" v-model="currentServer.title" placeholder="Server title" required>
                        </div>
                        <div class="form-group">
                            <label for="title">Status</label>
                            <select name="status" class="form-control" v-model="currentServer.status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">cancel</button>
                        <button type="button" class="btn btn-primary" @click="updateServer()" v-if="currentServer.id">Update</button>
                        <button type="button" class="btn btn-primary" @click="addServer()" v-else>Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" ref="modalWebhook">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Webhook for {{ currentServer.title }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>In order to receive deployment notification, we will need to add following webhook inside Laravel Forge panel. So log in to your Forge panel &amp; go to the site for which you want receive deployment notifications.</p>
                        <p>Then find section called <strong>Deployment Webhooks</strong> &amp; add webhook using following URL.</p>

                        <h5>Webhook URL:</h5>
                        <code id="webhook">https://telenoty.com/notify/{{ currentServer.token }}</code>

                        <hr>

                        <h5>Next adding receivers:</h5>
                        <p>After adding our webhook in Forge, it's time for you to add <strong>Telegram</strong> receivers for <strong>{{ currentServer.title }}</strong>.</p>
                        <p>Telegram users will need to authorize themselves with our 🤖 <strong>TeleNotyBot</strong> to receive deployment notifications on there Telegram app.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary mr-auto" @click="goToManageReceivers()">Add Receivers</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            servers: [],
            currentServer: {
                title: '',
                status: 'active'
            },
            loading: false
        }
    },
    mounted() {
        this.fetch();
    },
    methods: {
        fetch() {
            this.loading = true;
            axios.get('/api/servers')
                .then(response => {
                    this.servers = response.data;
                    this.loading = false;
                }, error => {
                    this.loading = false;
                    swal('Oops!', error.response.data.message, 'error');
                })
        },
        showAddServerModal() {
            this.currentServer = {
                title: '',
                status: 'active'
            };
            $(this.$refs.modalServer).modal('show');
        },
        addServer() {
            axios.post('/api/servers', this.currentServer)
                .then(response => {
                    this.fetch();
                    $(this.$refs.modalServer).modal('hide');
                    swal('Added', 'New server added successfully!', 'success');
                    this.currentServer = response.data;
                    $(this.$refs.modalWebhook).modal('show');
                }, error => {
                    swal('Oops!', error.response.data.message, 'error');
                })
        },
        showEditServerModal(server) {
            this.currentServer = server;
            $(this.$refs.modalServer).modal('show');
        },
        updateServer() {
            axios.patch(`/api/servers/${this.currentServer.id}`, this.currentServer)
                .then(response => {
                    this.fetch();
                    $(this.$refs.modalServer).modal('hide');
                    swal('Updated', 'Server updated successfully!', 'success');
                }, error => {
                    swal('Oops!', error.response.data.message, 'error');
                })
        },
        showWebhookModal(server) {
            this.currentServer = server;
            $(this.$refs.modalWebhook).modal('show');
        },
        goToManageReceivers() {
            $(this.$refs.modalWebhook).modal('hide');
            this.$router.push({ path: `/dashboard/servers/${this.currentServer.id}` })
        },
        deleteServer(server) {
            swal({
                title: "Are you sure?",
                text: "This server will be deleted from our database!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((deleteServer) => {
                if (deleteServer) {
                    axios.delete(`/api/servers/${server.id}`)
                        .then(response => {
                            this.fetch();
                            swal('Deleted', 'Your server has been deleted!', 'success');
                        }, error => {
                            swal('Oops!', error.response.data.message, 'error');
                        })
                }
            });
        }
    }
}
</script>

