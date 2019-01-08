<template>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User Full Name</th>
                    <th>UserName</th>
                    <th>User Email</th>
                    <th>User Charge</th>
                    <th>Blocked</th>
                    <th>User Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{user.name}}</td>
                    <td>{{user.username}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.type}}</td>
                    <td v-if="user.blocked"> <b>Yes </b></td>
                    <td v-else>No</td>
                    <td> <img :src= user.photo_url height="85" width="80"/> </td>
                    <td v-if="user.deleted_at === null">
                        <a class="btn btn-primary" v-on:click.prevent="editUser(user)">Edit</a>
                        <a class="btn btn-danger" v-on:click.prevent="deleteUser(user)">Delete</a>
                        <a class="btn btn-danger" v-on:click.prevent="alterBlock(user)" v-if="!user.blocked">Block</a>
                        <a class="btn btn-success" v-on:click.prevent="alterBlock(user)" v-else>Unblock</a>
                        
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script tyep="text/javascript">
    module.exports = {
        props: ['users'],
        methods: {
            deleteUser: function(user){
                this.$emit('delete-click', user);
            },
            editUser: function (user) {
                this.$emit('edit-click', user);
            },
            alterBlock(user){
                this.$emit('alter-block', user);
            }
        }
    }
</script>

