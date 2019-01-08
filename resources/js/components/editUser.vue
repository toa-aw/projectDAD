<template>
  <div>
    <div class="jumbotron">
      <h1>{{title}}</h1>
    </div>

    <user-form :user="user" :createUser="createUser" :errors="errors" @cancel-form="cancelEdit" @save-form="saveEdit"></user-form>
  </div>
</template>

<script type="text/javascript">
import userForm from "./userForm.vue";

export default {
  data: function() {
    return {
      title: "Edit User",
      errors: {}, 
      user: {},
      createUser: true
    };
  },
  props: ['userId'],
  methods: {
    saveEdit: function() {
      axios.put("api/users/" + this.userId, this.user).then(response => {
        Object.assign(this.user, response.data.data);
        this.flashMessage.success({
          title: "",
          message: "User updated successfully"
        });
        this.$router.push({ name: "managerDash" });
      })        
      .catch(error => {
            this.errors = error.response.data.errors;
        });
    },

    cancelEdit: function() {
      this.$router.push({ name: "managerDash" });
    },
    loadUser: function () {
      axios.get("api/users/" + this.userId).then(response => {
        this.user = response.data.data;
      })       
      .catch(error => {
            this.$router.push({ name: "notFound" });
        });   
    }
  },
  components: {
    "user-form": userForm
  },
  mounted(){
    this.loadUser();
  }
};
</script>

