<template>
 <nav class="navbar navbar-expand-md navbar-light bg-light bg-light justify-content-between">
  <FlashMessage></FlashMessage>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
     </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">

    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active" >  
        <router-link to="/menu" class="nav-link">Home</router-link>
      </li>
      <li class="nav-item" v-if="!this.$store.state.user">  
        <router-link to="/login" class="nav-link">Log In</router-link>
      </li>
      <li class="nav-item" v-if="this.$store.state.user">  
        <router-link to="/profile" class="nav-link">Profile</router-link>
      </li>

      <li class="nav-item">  
        <router-link to="/manager" class="nav-link" v-if="this.$store.getters.getType === 1">Dashboard</router-link>
        <router-link to="/cook" class="nav-link" v-if="this.$store.getters.getType === 2">Dashboard</router-link>
        <router-link to="/waiter" class="nav-link" v-if="this.$store.getters.getType === 3">Dashboard</router-link>
        <router-link to="/cashier" class="nav-link" v-if="this.$store.getters.getType === 4">Dashboard</router-link>
      </li>

      <li class="nav-item dropdown" v-if="this.$store.state.user">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Options
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" v-on:click.prevent="logOut()">Log Out</a>
        </div>
      </li>    
    </ul>
    </div>

    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2" v-if="this.$store.state.user && showTimer">
      <span class="navbar-nav ml-auto"> <shift-timer :user="this.$store.state.user"></shift-timer></span> 
    </div>
  </div>
</nav>
</template>

<script>

import shiftTimer from "./shiftTimer.vue";

export default {
  data (){
    return{
      user: null,
      showTimer: true,
    };
  },
  methods: {
    logOut: function () {
          axios.post('api/logout')
          .then(response => {
              this.$store.commit('clearUserAndToken');
              this.flashMessage.success({
              title: "",
              message: "You have logged out"
        });
          })
          .catch(error => {
            this.$store.commit('clearUserAndToken');
            console.log(error);
          });
           this.$router.push({ name: "menu" });
    },
  },
  components: {
    'shift-timer': shiftTimer,
  }, 
};
</script>

<style>

</style>
