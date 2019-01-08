<template>

<div>
<div v-if="title">
    <h2>
        {{title}}
    </h2>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Photo</th>
            <th>Price</th>
            <th v-if="showOptions">Options</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="item in items" :key="item.id">
            <td>{{item.name}}</td>
            <td>{{item.description}}</td>
            <td> <img :src= item.photo_url height="85" width="80"> </td>
            <td>{{item.price}}</td>
            <td v-if="showOptions">
                <div>
                    <a class="btn btn-primary" @click.prevent="editItem(item)">Edit</a>
                    <a class="btn btn-danger" @click.prevent="deleteItem(item)">Delete</a>
                </div>
            </td>
        </tr>
    </tbody>
</table>
</div>

</template>

<script type="text/javascript">
    module.exports = {
        props: ['items', 'title', 'showOptions'],
        methods:{
            editItem(item){
                this.$emit('edit-click', item);
            },
            deleteItem(item){
                this.$emit('delete-click', item);
            }
        }
    }
</script>

