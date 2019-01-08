<template>
 <div>
    <div class="jumbotron">
        <h1>{{ title }}</h1>
    </div>

    <item-form :item="item" :errors="errors" @cancel-form="cancelEdit" @save-form="saveEdit" />
  </div>
</template>

<script>
import itemForm from "./itemForm.vue";

export default {
  data() {
    return{
        title: "Edit Item",
        item: {},
        errors:[]
    };
  },
  props:['itemId'],
  methods:{
      getItem(){
        axios.get("api/items/" + this.itemId).then(response => {
            this.item = response.data.data;
        })       
        .catch(error => {
                this.$router.push({ name: "notFound" });
            });  
      },
      cancelEdit(){
        this.$router.push({ name: "managerDash" });
      },
      saveEdit(){
        axios.put("api/items/" + this.itemId, this.item).then(response => {
            Object.assign(this.item, response.data.data);
            this.flashMessage.success({
            title: "",
            message: "Item updated successfully"
            });
            this.$router.push({ name: "managerDash" });
        })        
        .catch(error => {
                this.errors = error.response.data.errors;
            });
        }
  },
  components:{
      'item-form': itemForm,
  },
  mounted(){
      this.getItem();
  }
};
</script>

<style>
</style>
