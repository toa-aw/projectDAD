<template>
    <div>
        <h2>
            {{title}}
            <router-link to="create/item" class="btn btn-success">Create Menu Item</router-link>
        </h2>
        <item-list :items="items" showOptions="true" @delete-click="deleteItem" @edit-click="editItem"/>
    </div>
</template>

<script>
import itemList from "./itemList.vue";
export default {
    data(){
        return{
            items: [],
        };
    },
    props:['title'],
    components:{
        'item-list': itemList,
    }, 
    methods:{
    getItems: function() {
        axios
        .get('api/items')
        .then(response =>  {
            this.items = response.data.data;
            this.$store.commit('setItems', this.items);
        });
    },
    deleteItem(item){
        axios.delete('api/items/'+item.id)
        .then(response => {
            this.flashMessage.error({
            title: "",
            message: item.name + "  has been deleted"});
            this.getItems();
        });  
    },
    editItem(item){
        this.$router.push({ name: "editItem", params: {itemId: item.id }});
    }
    },
    mounted(){
        this.getItems();
    }
}
</script>

<style>

</style>
