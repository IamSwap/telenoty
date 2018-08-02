<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h5>My Servers</h5>
            </div>

            <div class="card-body">
                <table class="table table-responsive" v-if="servers.length">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="server in servers" :key="server.id">
                            <td>{{ server.id }}</td>
                            <th>{{ server.title }}</th>
                            <th class="text-right">
                                <button class="btn btn-info mr-4">Edit</button>
                                <button class="btn btn-danger">Edit</button>
                            </th>
                        </tr>
                    </tbody>
                </table>

                <div class="d-flex justify-content-center align-items-center text-center" v-else>
                    <div>
                        <p>You have no servers yet. Please add one to continue.</p>
                        <button class="btn btn-primary">Add a server</button>
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
            currentServer: '',
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
        }
    }
}
</script>

