<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h5>Welcome {{ user.name }} 🎉</h5>
            </div>

            <div class="card-body">
                <p>In order to receive deployment notifications, you will need to add following webhook inside Laravel Forge panel. So log in to your Forge panel &amp; go to the site for which you want receive deployment notifications.</p>
                <p>Then find the section called <strong>Deployment Webhooks</strong> &amp; add webhook using the following URL.</p>

                <h5>Webhook URL:</h5>

                <div class="input-group mb-3">
                    <input type="text" class="form-control" :value="`https://telenoty.com/notify/${user.webhook_token}`">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" v-clipboard:copy="`https://telenoty.com/notify/${user.webhook_token}`" v-clipboard:success="webhookCopied">
                            <img src="/images/clippy.svg" width="13">
                        </button>
                    </div>
                </div>

                <hr>

                <h5>Next, adding subscribers:</h5>
                <p>After adding our webhook in Forge, it's time for you to add <strong>Telegram</strong> subscribers.</p>
                <p>Telegram users will need to authorize themselves with our 🤖 <strong>TeleNotyBot</strong> to receive deployment notifications on there Telegram app.</p>

                <div class="text-center py-3">
                    <router-link to="/dashboard/subscribers" class="btn btn-primary btn-lg">Add subscribers</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user'],
    methods: {
        webhookCopied() {
            swal('Webhook has been copied to your clipboard!');
        }
    }
}
</script>
