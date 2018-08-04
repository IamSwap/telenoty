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
                <code id="webhook">https://telenoty.com/notify/{{ user.webhook_token }}</code>

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
    props: ['user']
}
</script>
