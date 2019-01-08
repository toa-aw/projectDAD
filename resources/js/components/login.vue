<template>
<div>

  <div class="jumbotron">
    <h1>{{ title }}</h1>

    <form>
      <div class="form-group">
        <label for="inputUserCredential"> Login Credential </label>
        <input type="text" class="form-control" id="inputUserCredential" placeholder="Enter username or email" v-model.trim="user.credential">
      </div>

      <div class="form-group">
        <label for="inputPassword">Password</label>
        <input type="password" class="form-control" id="inputPassword" placeholder="Password" v-model.trim="user.password">
      </div>

      <div class="form-group">
        <a class="btn btn-primary" @click.prevent="login">Login</a>
      </div>


        <div v-if="error">
							<strong>{{error}}</strong>
				</div>
    </form>
  </div>
  </div>
</template>

<script type="text/javascript" >

export default {
  data: function() {
    return {
      title: "Login",
      user: {
        credential: "",
        password: ""
      },
      error: "",
    };
  },
  methods: {
    async  login() {
       await axios.post('api/login', this.user)
          .then(response => {
              this.$store.commit('setToken', response.data.access_token);
              return axios.get('api/users/me');
          })
          .then(response => {
            this.$store.commit('setUser',response.data.data);

              this.flashMessage.success({
                title: "",
                message: "You have logged in successfully"
              });

              this.$router.push({ name: "root"});
          })
          .catch(error => {

              this.flashMessage.error({
                title: "",
                message: "Login failed"
              });

              this.$store.commit('clearUserAndToken');
              this.error = error.response.data.msg;

          })
    }
  }
};
</script>

