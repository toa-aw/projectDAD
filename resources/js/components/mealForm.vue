<template>
<div>
  <form>
    <div class="form-group">
      <label for="freeTables">Select table number</label>
      <select class="form-control" id="freeTables" name="freeTables" v-model="meal.table_number">
        <option
          v-for="table in freeTables"
          :key="table.table_number"
          :value="table.table_number"
        >{{ table.table_number }}</option>
      </select>

      <!-- <div v-if="errors.table_number" class="text-danger">
        <div v-for="(error, index) in errors.type" :key="index">{{error}}</div>
      </div> -->
    </div>

    <div class="form-group">
      <a class="btn btn-danger" v-on:click.prevent="cancelEdit()">Cancel</a>
      <a class="btn btn-primary" v-on:click.prevent="saveMeal()">Create meal</a>
    </div>
  </form>
  </div>
</template>

<script>
export default {
  props: ["freeTables", 'meal'],
  methods: {
    cancelEdit() {
      this.$emit("back");
    },
    saveMeal() {
      this.meal.state = 'active';
      this.meal.responsible_waiter_id = this.$store.state.user.id;  
      this.$emit("create-meal");
    }
  }
};
</script>

<style>
</style>
