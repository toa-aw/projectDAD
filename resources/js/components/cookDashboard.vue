<template>
  <div>
    <div class="jumbotron">
      <h1>{{title}}</h1>
    </div>
    <div class="row">
      <order-list
        :orders="cooksOrders"
        title="Your orders"
        :userType="userType"
        showOptions="true"
        @update-orders="getAllOrders"
        class="col-sm"
      />
      <order-list
        :orders="unassignedOrders"
        title="Orders to be done"
        :userType="userType"
        showOptions="true"
        @update-orders="getAllOrders"
        class="col-sm"
      />
    </div>
  </div>
</template>

<script>
import orderList from "./orderList.vue";

export default {
  data() {
    return {
      cooksOrders: [],
      unassignedOrders: [],
      title: "Cooks Dashboard",
      userType: 0,
    };
  },
  methods: {
    getCooksOrders() {
      axios
        .get("api/orders/cook/" + this.$store.state.user.id)
        .then(response => {
          this.cooksOrders = response.data.data;
        });
    },
    getUnassignedOrders() {
      axios.get("api/orders/unassigned").then(response => {
        this.unassignedOrders = response.data.data;
      });
    },
    getAllOrders() {
      this.getCooksOrders();
      this.getUnassignedOrders();
    }
  },
  components: {
    "order-list": orderList
  },
  mounted() {
    this.getAllOrders();
    this.userType = this.$store.getters.getType;
  }, 
  sockets:{
    update_orders(order){
      if(order.state === 'confirmed' || order.state === 'in preparation'){
           this.getAllOrders();
      }
   
    }
  }
};
</script>

<style>
</style>
