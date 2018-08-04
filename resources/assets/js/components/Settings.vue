<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h5>Change Profile Infofmation</h5>
            </div>

            <div class="card-body">
                <form @submit.prevent="updateProfile()">
                    <div class="form-group row">
                        <label for="name" class="col-md-3 col-form-label text-md-right">Name</label>
                        <div class="col-md-6">
                            <input type="text" v-model="user.name" class="form-control" :class="{ 'is-invalid' : profileFormErrors.name }" required>
                            <div class="invalid-feedback" v-if="profileFormErrors.name" v-for="error in profileFormErrors.name" :key="error">{{ error }}</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-md-3 col-form-label text-md-right">E-Mail Address</label>
                        <div class="col-md-6">
                            <input type="email" v-model="user.email" class="form-control" :class="{ 'is-invalid' : profileFormErrors.email }" required>
                            <div class="invalid-feedback" v-if="profileFormErrors.email" v-for="error in profileFormErrors.email" :key="error">{{ error }}</div>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="offset-md-3 col-md-6">
                            <button type="submit" class="btn btn-primary">
                                Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>Change Password</h5>
            </div>
            <div class="card-body">
                <form @submit.prevent="updatePassword()" ref="formPassword">
                    <div class="form-group row">
                        <label for="old_password" class="col-md-3 col-form-label text-md-right">Old Password</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="old_password" :class="{ 'is-invalid' : passwordFormErrors.old_password }" required>
                            <div class="invalid-feedback" v-if="passwordFormErrors.old_password" v-for="error in passwordFormErrors.old_password" :key="error">{{ error }}</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-md-3 col-form-label text-md-right">New Password</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password" :class="{ 'is-invalid' : passwordFormErrors.password }" required>
                            <div class="invalid-feedback" v-if="passwordFormErrors.password" v-for="error in passwordFormErrors.password" :key="error">{{ error }}</div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password_confirmation" class="col-md-3 col-form-label text-md-right">Confirm Password</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password_confirmation" :class="{ 'is-invalid' : passwordFormErrors.password }" requiredr>
                            <div class="invalid-feedback" v-if="passwordFormErrors.password_confirmation" v-for="error in passwordFormErrors.password_confirmation" :key="error">{{ error }}</div>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="offset-md-3 col-md-6">
                            <button type="submit" class="btn btn-primary">
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            profileFormErrors: {},
            passwordFormErrors: {},
        }
    },
    methods: {
        updateProfile() {
            this.profileFormErrors = {};
            axios.post('/api/profile', this.user)
                .then(response => {
                    this.profileFormErrors = {};
                    swal('Updated', 'Profile information updated successfully!', 'success')
                        .then((ok) => { location.reload(); });
                }, error => {
                    this.profileFormErrors = error.response.data.errors;
                });
        },
        updatePassword() {
            this.passwordFormErrors = {};
            let formData = new FormData(this.$refs.formPassword);

            axios.post('/api/profile/password', formData)
                .then(response => {
                    this.passwordFormErrors = {};
                    this.$refs.formPassword.reset();
                    swal('Updated', 'Profile information updated successfully!', 'success');
                }, error => {
                    this.passwordFormErrors = error.response.data.errors;
                });
        },
    }
}
</script>
