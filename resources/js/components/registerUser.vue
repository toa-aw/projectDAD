<template>
  <div>
    <div class="jumbotron">
      <h1>{{title}}</h1>
    </div>
    <user-form :user="user" :errors="errors" :createUser="createUser" @save-form="saveUser" @cancel-form="cancelEdit"></user-form>
  </div>
</template>

<script type="text/javascript">
import userForm from "./userForm.vue";

export default {
  data: function() {
    return {
      title: "Create User",
      user: {
        username: "",
        name: "",
        email: "",
        type: "",
        photo_url: ""
      }, 
      errors: {},
      createUser: true
    };
  },
  methods: {
     saveUser: function() {
      axios
        .post("api/users", this.user)
        .then(response => {
          this.flashMessage.success({
            title: "",
            message: "User created successfully"
          });
          this.$router.push({ name: "managerDash" });
        })
        .catch(error => {
            this.errors = error.response.data.errors;
        });
    },
    cancelEdit: function() {
      this.$router.push({ name: "managerDash" });
    }
  },
  components: {
    "user-form": userForm
  },
};
</script>

