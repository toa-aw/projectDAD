<template>
  <div>
    <form>
      <div class="form-group">
        <label for="mealId">Meal</label>
        <select class="form-control" id="mealId" name="mealId" v-model="newOrder.meal_id">
          <option
            v-for="meal in activeMeals"
            :key="meal.id"
            :value="meal.id"
          >Table: {{meal.table_number}}</option>
        </select>
      </div>

      <div class="form-group">
        <label for="itemId">Item of the menu</label>
        <select class="form-control" id="itemId" name="itemId" v-model="newOrder.item_id">
          <option
            v-for="item in items"
            :key="item.id"
            :value="item.id"
          >{{item.name}}</option>
        </select>
      </div>

      <div>
        <a class="btn btn-danger" v-on:click.prevent="cancelOrder()">Cancel</a>
        <a class="btn btn-primary" v-on:click.prevent="saveOrder()">Save</a>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data(){
    return{
      items: null,
    }
  },
  props: ["activeMeals", "newOrder"],
  methods:{
    cancelOrder(){
      this.$emit('back');
    },
    saveOrder(){
      this.newOrder.state = 'pending';
      this.$emit('create-order');
    },
    getMenuItems(){
      if(this.$store.state.items.length == 0){
       axios
      .get('api/items')
      .then(response =>  {
        this.items = response.data.data;
        this.$store.commit('setItems', this.items);
      });
      
      }else{
        this.items = this.$store.state.items;
      }

    }
  }, 
  created(){
    this.getMenuItems();
  }
};
</script>

<style>
</style>
