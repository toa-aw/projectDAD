<template>
  <div>
    <div class="jumbotron">
      <h1>{{ title }}</h1>
    </div>
    <item-form :item="item" :errors="errors" @cancel-form="cancelEdit" @save-form="saveItem"/>
  </div>
</template>

<script>
import itemForm from "./itemForm.vue";
export default {
  data() {
    return {
      item: {
        type: ""
      },
      errors: [],
      title: "Create Menu Item"
    };
  },
  methods:{
      saveItem(){
       axios
        .post("api/items/", this.item)
        .then(response => {
          this.flashMessage.success({
            title: "",
            message: "Item created successfully"
          });
          this.$router.push({ name: "managerDash" });
        })
        .catch(error => {
            this.errors = error.response.data.errors;
        });
      },
      cancelEdit(){
          this.$router.push({ name: "managerDash" });
      }
  },
  components: {
    "item-form": itemForm
  }
};
</script>

<style>
</style>
