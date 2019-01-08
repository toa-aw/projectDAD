<template>
    <div>
        <h2>{{title}}
               <router-link to="register/user" class="btn btn-success">Register User</router-link>
            </h2> 
        <user-list :users="users" @delete-click="deleteUser" @edit-click="editUser" @alter-block="alterBlock"></user-list>
    </div>
</template>

<script type="text/javascript">
import userList from './userList.vue';
export default {
    data: function (){
        return{
            users:[],
        };
    },
    props:{
        title:{
            default: 'Users'
        },
    },
    methods:{
        getUsers: function() {
            axios
                .get('api/users')
                .then(response =>  {
                this.users = response.data.data;
                });
        },
        deleteUser: function(user){
            axios.delete('api/users/'+user.id)
	                .then(response => {
                        this.flashMessage.error({
                        title: "",
                        message: user.username + "  has been deleted"});
                        this.getUsers();
                    });
        }, 
        editUser: function (user) {
            this.$router.push({ name: "editUser", params: {userId: user.id }});
        },
        alterBlock(user){
            axios.put('api/alter/block/'+user.id)
            .then(response => {
                if(response.data.blocked){
                    this.flashMessage.error({
                    title: "",
                    message: user.username + "  has been blocked"});
                }else{
                    this.flashMessage.success({
                    title: "",
                    message: user.username + "  has been unblocked"});
                }
                 this.getUsers();
            });
        }
    },
    components:{
        'user-list': userList,
    }, 
    mounted() {
        this.getUsers();
    }
}
</script>

