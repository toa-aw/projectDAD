<template>
    <password-form :authUser="authUser" :password="password" errors="errors" @save-password="setPassword"></password-form>
</template>

<script>
import passwordForm from "./passwordForm.vue"
export default {
    data: function(){
        return {
            password: {},
            authUser: null,
            errors:{}
    };
    },
    props: ['token'],
    methods: {
        setPassword: function () {
            axios.put("api/user/password/set/" + this.token, this.password).then(response => {
                this.flashMessage.success({
                title: "",
                message: "You have confirmed your account successfully"
                });
                this.$router.push({ name: "login" });
            })        
            .catch(error => {
                    this.errors = error.response.data.errors;
                });    
        }
    },
    components: {
        'password-form': passwordForm,
    }
}
</script>

<style>

</style>
