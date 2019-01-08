<template>
  <div>
    <h2>
      {{title}}
      <a class="btn btn-success" @click.prevent="showMealForm">Start Meal</a>
    </h2>

    <meal-form
      :freeTables="freeTables"
      :meal="newMeal"
      v-if="showSelectTable"
      @back="hideMealForm"
      @create-meal="startNewMeal"
    />

    <table class="table table-striped">
      <thead>
        <tr>
          <th>Table Number</th>
          <th>Current Price</th>
          <th>Options</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="meal in meals" :key="meal.id">
          <td>{{meal.table_number}}</td>
          <td>{{meal.total_price}}</td>
          <td>
            <a class="btn btn-primary" @click.prevent="showInfo(meal)">Meal Info</a>
            <a class="btn btn-danger" @click.prevent="endMeal(meal)">End Meal</a>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="showMealInfo">
      <order-list title="Meal Info" :orders="selectedMeal.orders"/>
      <b>Table number: {{selectedMeal.table_number}}</b>
      <br>
      <b>Current price: {{selectedMeal.total_price}}</b>
      <br>

      <a class="btn btn-danger" @click="closeInfo">Close</a>
    </div>
  </div>
</template>

<script>
import orderList from "./orderList";
import mealForm from "./mealForm.vue";

export default {
  data() {
    return {
      showMealInfo: false,
      selectedMeal: null,
      freeTables: [],
      newMeal: {},
      showSelectTable: false
    };
  },
  props: ["meals", "title"],
  methods: {
    showInfo(meal) {
      this.showMealInfo = true;
      this.selectedMeal = meal;
    },
    closeInfo() {
      this.showMealInfo = false;
      this.selectedMeal = {};
    },
    showMealForm() {
      this.getFreeTables();
      this.showSelectTable = true;
    },
    hideMealForm() {
      this.showSelectTable = false;
      this.newMeal = {};
    },
    getFreeTables() {
      axios.get("api/tables/free").then(response => {
        this.freeTables = response.data;
      });
    },
    startNewMeal() {
      axios.post("api/meals", this.newMeal).then(response => {
        this.flashMessage.success({
          title: "",
          message:
            "Meal at the table" + this.newMeal.table_number + "has been started"
        });
        this.showSelectTable = false;
        this.newMeal = {};
        this.$emit("update-meals");
      });
    },
    endMeal(meal) {
      let mealOrders = meal.orders;
      let orderCounter = 0;
      mealOrders.forEach(order => {
        if (order.state !== "delivered") {
          orderCounter++;
        }
      });
      if (orderCounter > 0) {
        let answer =window.confirm('You sitll have '+ orderCounter +' meals to deliver. Are you sure you want to finish this meal?');
        if(answer){
          this.alterMealState('terminated', meal.id);
        }else{

        }
      } else {
        this.alterMealState('terminated', meal.id);
      }
    },
    alterMealState(newState, id) {
      let request = {
        state: newState,
        userId: this.$store.state.user.id,
      }

      axios.put("api/meals/"+ id, request).then(response => {
        this.flashMessage.success({
          title: "",
          message:
            "You have ended a meal"
        });
        this.$socket.emit('update_invoices');
        this.$emit("update-meals");
      });
    }
  },
  components: {
    "order-list": orderList,
    "meal-form": mealForm
  }
};
</script>

<style>
</style>
