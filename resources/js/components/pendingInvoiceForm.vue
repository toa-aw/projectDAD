<template>
  <div>
    <form>
      <div class="form-group">
        <label for="inputNIF">NIF</label>
        <input
          type="text"
          class="form-control"
          v-model="invoice.nif"
          name="nif"
          id="inputNIF"
          placeholder="NIF"
          value="invoice.nif"
          required
        >

        <div v-if="errors.nif" class="text-danger">
          <div v-for="(error, index) in errors.nif" :key="index">{{error}}</div>
        </div>
      </div>

      <div class="form-group">
        <label for="inputName">Name</label>
        <input
          type="text"
          class="form-control"
          v-model="invoice.name"
          name="name"
          id="inputName"
          placeholder="Fullname"
          value="invoice.name"
        >

        <div v-if="errors.name" class="text-danger">
          <div v-for="(error, index) in errors.name" :key="index">{{error}}</div>
        </div>
      </div>

      <div>
        <a class="btn btn-danger" v-on:click.prevent="cancelInvoice()">Cancel</a>
        <a class="btn btn-primary" v-on:click.prevent="saveInvoice()">Save</a>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {};
  },
  props: ["invoice", "errors"],
  methods: {
    cancelInvoice() {
      this.$emit("back");
    },
    saveInvoice() {
      //this.invoice.state = "paid";
      // this.invoice.meal.state = "paid";
      this.$emit("close-invoice");
    }
  }
};
</script>

<style>
</style>
