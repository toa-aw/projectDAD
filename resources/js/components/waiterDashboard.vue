<template>
  <div>
    <div class="jumbotron">
      <h1>{{title}}</h1>
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label
          class="btn btn-secondary"
          :class="{active : showMeals}"
          v-on:click.prevent="mealsSelected()"
        >
          <input type="radio" name="options" id="profile" :checked="showMeals"> My melas
        </label>
        <label
          class="btn btn-secondary"
          :class="{active : showPendingOrders}"
          v-on:click.prevent="pendingSelected()"
        >
          <input type="radio" name="options" id="pendingOrders" :checked="showPendingOrders"> Pending Orders
        </label>
        <label
          class="btn btn-secondary"
          :class="{active : showPrepared}"
          v-on:click.prevent="preparedSelected()"
        >
          <input type="radio" name="options" id="preparedOrders" :checked="showPrepared"> Prepared Orders
        </label>
      </div>
    </div>

    <div class="row" v-if="showPendingOrders">
      <order-list
        :orders="waiterConfirmedOrders"
        title="Confirmed Orders"
        :userType="userType"
        @update-orders="getWaiterMeals"
        class="col-sm"
      />
      <order-list
        :orders="waiterPendingOrders"
        title="Pending Orders"
        :userType="userType"
        showOptions="true"
        :waiterMeals="waiterMeals"
        @update-orders="getWaiterMeals"
        createButton="true"
        class="col-sm"
      />
    </div>

    <div v-if="showPrepared">
      <order-list
        :orders="wiaterPreparedOrders"
        title="Prepared Orders"
        :user-type="userType"
        showOptions="true"
        @update-orders="getWaiterMeals"
      />
    </div>

    <div v-if="showMeals">
      <meal-list :meals="waiterMeals" createButton="true" title="Active Meals" @update-meals="getWaiterMeals"
      />
    </div>
  </div>
</template>
<script>
import orderList from "./orderList.vue";
import mealList from "./mealList.vue";
export default {
  data() {
    return {
      title: "Waiters Dashboard",
      showMeals: true,
      showPendingOrders: false,
      showPrepared: false,
      waiterMeals: [],
      waiterConfirmedOrders: [],
      waiterPendingOrders: [],
      wiaterPreparedOrders: [],
      userType: 0
    };
  },
  methods: {
    mealsSelected() {
      this.showMeals = true;
      this.showPendingOrders = false;
      this.showPrepared = false;
    },
    pendingSelected() {
      this.showMeals = false;
      this.showPendingOrders = true;
      this.showPrepared = false;
    },
    preparedSelected() {
      this.showMeals = false;
      this.showPendingOrders = false;
      this.showPrepared = true;
    },
    getWaiterMeals() {
      this.clearLists();
      axios
        .get("api/meals/waiter/" + this.$store.state.user.id)
        .then(response => {
          this.waiterMeals = response.data.data;
          this.getAllOrders();
        });
    },
    getAllOrders() {
      for (const meal in this.waiterMeals) {
        if (this.waiterMeals.hasOwnProperty(meal)) {
          const element = this.waiterMeals[meal];

          element.orders.forEach(o => {
            if (o.state === "confirmed") {
              this.waiterConfirmedOrders.push(o);
            } else if (o.state === "prepared") {
              this.wiaterPreparedOrders.push(o);
            } else if (o.state == "pending") {
              this.waiterPendingOrders.push(o);
            }
          });
        }
      }
    },
    clearLists() {
      this.waiterMeals = [];
      this.waiterConfirmedOrders = [];
      this.waiterPendingOrders = [];
      this.wiaterPreparedOrders = [];
    }
  },
    sockets:{
    update_orders(order){
      if(order.state === 'prepared' || order.state === 'in preparation'){
        this.getWaiterMeals();
      }

    }
  },
  components: {
    "order-list": orderList,
    "meal-list": mealList
  },
  mounted() {
    this.getWaiterMeals();
    this.userType = this.$store.getters.getType;
  }
};
</script>

<style>
</style>
