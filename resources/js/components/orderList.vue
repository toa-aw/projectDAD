<template>
  <div>
    <div>
      <h2>
        {{title}}
        <a class="btn btn-success" v-if="createButton" @click.prevent="showCreateOrderForm">
          New Order</a>
      </h2>
      <order-form :activeMeals="waiterMeals" :newOrder="newOrder" v-if="creatingOrder" @back="hideCreateOrderForm"
      @create-order="createOrder"/>
    </div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Item to prepare</th>
          <th>State</th>
          <th>Start Date</th>
          <th v-if="showOptions">Options</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="order in orders" :key="order.id">
          <td>{{order.item}}</td>
          <td>{{order.state}}</td>
          <td>{{order.start}}</td>
          <td v-if="userType === 2 && showOptions">
            <a
              class="btn btn-primary"
              v-if="!order.responsible_cook_id"
              @click.prevent="updateOrderState('in preparation', order.id)"
            >Assing To Me</a>
            
            <a
              class="btn btn-success"
              v-if="order.responsible_cook_id"
              @click.prevent="updateOrderState('prepared', order.id)"
            >Finished</a>
          </td>

          <td v-if="userType === 3 && showOptions">
            <a
              class="btn btn-danger"
              v-if="order.state === 'pending'"
              @click.prevent="cancelOrder(order)"
            >Cancel</a>
            
            <a
              class="btn btn-success"
              v-if="order.state === 'prepared'"
              @click.prevent="updateOrderState('delivered', order.id)"
            >Deliver</a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import orderForm from "./orderForm.vue";
export default {
  data() {
    return {
      creatingOrder: false,
      newOrder: {}
    };
  },
  props: [
    "orders",
    "title",
    "userType",
    "createButton",
    "waiterMeals",
    "showOptions"
  ],
  methods: {
    updateOrderState(newState, orderId) {
      let request = {
        userId: this.$store.state.user.id,
        state: newState
      };

      axios.put("api/order/change/state/" + orderId, request).then(response => {
        this.flashMessage.success({
          title: "",
          message: "Order state update succesfully"
        });
        this.$emit("update-orders");
        this.$socket.emit('update_orders', response.data);
        
      });
    },
    showCreateOrderForm() {
      this.creatingOrder = true;
    },
    hideCreateOrderForm() {
      this.creatingOrder = false;
      this.newOrder = {};
    },
    cancelOrder(order) {
      axios.delete("api/orders/" + order.id).then(response => {
        this.flashMessage.error({
          title: "",
          message: order.item + " has been canceled"
        });
        this.$emit("update-orders");
      });
    },
    createOrder() {
      axios.post("api/orders", this.newOrder).then(response => {
        this.flashMessage.success({
          title: "",
          message: "Order created succesfully"
        });
        this.newOrder = {};
        this.creatingOrder = false;
        setTimeout(
          this.updateOrderState,
          5000,
          "confirmed",
          response.data.data.id
        );
        this.$emit("update-orders");
      });
    }
  },
  components: {
    "order-form": orderForm
  }
};
</script>

<style>
</style>
