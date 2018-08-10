<template>
    <div>
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5>My Projects</h5>
                <button class="btn btn-primary" @click="showProjectModal()">Add a project</button>
            </div>

            <div class="card-body">
                <p>Here you can get project specific webhook to only notify project's team members. This webhook will only send deployment notifications to Telegram subscribers authorized with the project.</p>
            </div>

            <div>
                <div class="py-5 text-center" v-if="loading">
                    <h5>Loading...</h5>
                </div>

                <div class="table-responsive" v-if="!loading && projects.length">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Project</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Webhook</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="project in projects" :key="project.id">
                                <td>{{ project.id }}</td>
                                <td>
                                    <router-link :to="`/dashboard/projects/${project.id}`"><strong>{{ project.title }}</strong></router-link>
                                </td>
                                <td class="text-center">
                                    <span class="badge" :class="{ 'badge-success': (project.status === 'active'), 'badge-danger': (project.status === 'inactive')  }">{{ project.status }}</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-light border" @click="showWebhookModal(project)">View Webhook</button>
                                </td>
                                <td class="d-flex justify-content-end text-right">
                                    <router-link :to="`/dashboard/projects/${project.id}`" class="btn btn-sm btn-light border mr-2">Manage Subscribers</router-link>
                                    <button class="btn btn-sm btn-info text-white mr-2" @click="showEditProjectModal(project)">Edit</button>
                                    <button class="btn btn-sm btn-danger" @click="deleteProject(project)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center align-items-center text-center" v-if="!loading && !projects.length">
                    <div class="py-4">
                        <p>You have no projects yet. Please add one to continue.</p>
                        <button class="btn btn-primary" @click="showProjectModal()">Add a project</button>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" ref="modalProject">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-if="currentProject.id">Edit {{ currentProject.title }}</h4>
                        <h4 class="modal-title" v-else>Add a project</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" v-model="currentProject.title" placeholder="Project title" required>
                        </div>
                        <div class="form-group">
                            <label for="title">Status</label>
                            <select name="status" class="form-control" v-model="currentProject.status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="updateProject()" v-if="currentProject.id">Update</button>
                        <button type="button" class="btn btn-primary" @click="addProject()" v-else>Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" ref="modalWebhook">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Webhook for {{ currentProject.title }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>In order to receive deployment notifications for this project, we will need to add following webhook inside Laravel Forge panel. So log in to your Forge panel &amp; go to the site for which you want receive deployment notifications.</p>
                        <p>Then find section called <strong>Deployment Webhooks</strong> &amp; add webhook using following URL.</p>

                        <h5>Webhook URL:</h5>
                        <code id="webhook">https://telenoty.com/notify/{{ currentProject.token }}-{{ user.webhook_token }}</code>

                        <hr>

                        <h5>Next, adding subscribers:</h5>
                        <p>After adding our webhook in Forge, it's time for you to add <strong>Telegram</strong> subscribers for project <strong>{{ currentProject.title }}</strong>.</p>
                        <p>Telegram users will need to authorize themselves with our 🤖 <strong>TeleNotyBot</strong> to receive deployment notifications on there Telegram app.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary mr-auto" @click="goToSubscribers()">Add subscribers</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            projects: [],
            loading: false,
            currentProject: {
                title: '',
                status: 'active'
            }
        }
    },
    mounted() {
        this.fetch();
    },
    methods: {
        fetch() {
            this.loading = true;
            axios.get(`/api/projects`)
                .then(response => {
                    this.projects = response.data;
                    this.loading = false;
                }, error => {
                    this.loading = false;
                    swal('Oops!', error.response.data.message, 'error');
                });
        },
        showProjectModal() {
            this.currentProject = {
                title: '',
                status: 'active'
            };
            $(this.$refs.modalProject).modal('show');
        },
        addProject() {
            axios.post(`/api/projects`, this.currentProject)
                .then(response => {
                    this.fetch();
                    $(this.$refs.modalProject).modal('hide');
                    swal('Added', 'New project added successfully!', 'success');
                    this.currentProject = response.data;
                    $(this.$refs.modalWebhook).modal('show');
                })
        },
        showEditProjectModal(project) {
            this.currentProject = project;
            $(this.$refs.modalProject).modal('show');
        },
        updateProject() {
            axios.patch(`/api/projects/${this.currentProject.id}`, this.currentProject)
                .then(response => {
                    this.fetch();
                    $(this.$refs.modalProject).modal('hide');
                    swal('Updated', 'Project updated successfully!', 'success');
                }, error => {
                    swal('Oops!', error.response.data.message, 'error');
                })
        },
        showWebhookModal(project) {
            this.currentProject = project;
            $(this.$refs.modalWebhook).modal('show');
        },
        goToSubscribers() {
            $(this.$refs.modalWebhook).modal('hide');
            this.$router.push({ path: `/dashboard/projects/${this.currentProject.id}` })
        },
        deleteProject(project) {
            swal({
                title: "Are you sure?",
                text: "This project will be deleted from our database!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((deleteProject) => {
                if (deleteProject) {
                    axios.delete(`/api/projects/${project.id}`)
                        .then(response => {
                            this.fetch();
                            swal('Deleted', 'Your project has been deleted!', 'success');
                        }, error => {
                            swal('Oops!', error.response.data.message, 'error');
                        })
                }
            });
        }
    }
}
</script>

