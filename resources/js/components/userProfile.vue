<template>
    <div>
        <div class="jumbotron">
                <h1>{{title}}</h1> 

                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-secondary" :class="{active : showProfile}" v-on:click.prevent="profileSelected()">
                        <input type="radio" name="options" id="profile" :checked="showProfile"> Profile
                    </label>
                    <label class="btn btn-secondary" :class="{active : showUserForm}" v-on:click.prevent="editProfileSelected()">
                        <input type="radio" name="options" id="editProfile" :checked="showUserForm"> Edit Profile
                    </label>
                    <label class="btn btn-secondary" :class="{active : showPasswordForm}" v-on:click.prevent="changePasswordSelected()">
                        <input type="radio" name="options" id="changePassword" :checked="showPasswordForm"> Change Password
                    </label>
                </div>
        </div>
        <user-info :user="profileUser"  v-if="showProfile"> </user-info>
        <user-form :user="profileUser" :errors="errors" :createUser="createUser" @save-form="updateUser" v-if="showUserForm"></user-form>
        <password-form :user="profileUser" :password="password" :errors="errors" @save-password="updatePassword" v-if="showPasswordForm"></password-form>
    </div>
</template>

<script>
import userInfo from './userInfo.vue';
import passwordForm from './passwordForm.vue';
import userForm from './userForm.vue';

export default {
    data: function () {
        return {
            title: 'User Profile',
            profileUser: {},
            password: {},
            errors:{},
            showProfile: true,
            showPasswordForm: false,
            showUserForm: false,
            createUser: false,

        };
    },  
    methods:{
        changePasswordSelected: function () {
            this.showProfile = false;
            this.showPasswordForm = true;
            this.showUserForm = false;
        },
        profileSelected: function () {
            this.showProfile = true;
            this.showPasswordForm = false;
            this.showUserForm = false;
        },
        editProfileSelected: function () {
            this.showProfile = false;
            this.showPasswordForm = false;
            this.showUserForm = true;
        }, 
        loadUser: function () {
            this.profileUser = this.$store.state.user;
        },
        updatePassword: function () {
            axios.put("api/user/password/update/" + this.profileUser.id, this.password)
            .then(response => {
                this.flashMessage.success({
                title: "",
                message: "Your password has been updated successfully"
                });
                this.reset();
            })        
            .catch(error => {
                    this.errors = error.response.data.errors;
                });  
        }, 
        updateUser: function () {
            axios.put("api/users/" + this.profileUser.id, this.profileUser, {
            })
            .then(response => {
                Object.assign(this.profileUser, response.data.data);
                this.flashMessage.success({
                title: "",
                message: "Your profile has been updated successfully"
                });
                
                this.$store.commit('setUser', this.profileUser);
                this.reset();
            })        
            .catch(error => {
                    this.errors = error.response.data.errors;
                });         
        },
        reset: function () {
            this.profileSelected();
            this.password = {};
            this.errors = {};
        }
        
    },
    components:{
        'user-info': userInfo,
        'password-form': passwordForm,
        'user-form': userForm
    },
    mounted (){
        this.loadUser();
    }

}
</script>


<style>

</style>
