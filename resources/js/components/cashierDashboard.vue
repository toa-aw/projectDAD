<template>
  <div>
    <div class="jumbotron">
      <h1>{{title}}</h1>
    </div>

    <div class="btn-group btn-group-toggle" data-toggle="buttons">
      <label class="btn btn-secondary" :class="{active : showAllInvoices}" @click.prevent="allInvoices()" >
        <input type="radio" name="options" id="all" :checked="showAllInvoices"> All
      </label>
      <label class="btn btn-secondary" :class="{active : showPendingInvoices}" @click.prevent="pendingInvoices()" >
        <input type="radio" name="options" id="pending" :checked="showPendingInvoices"> Pending
      </label>
    </div>

    <invoices v-if="showAllInvoices" title="Invoices"/>
    <pendingInvoices v-if="showPendingInvoices" title="Pending Invoices"/>
  </div>
</template>

<script type="text/javascript">
import invoices from "./invoice.vue";
import pendingInvoices from "./pendingInvoice.vue";

export default {
  data() {
    return {
      title: "Cashier Dashboard",
      showAllInvoices: true,
      showPendingInvoices: false
    };
  },
  methods: {
      allInvoices(){
        this.showAllInvoices = true;
        this.showPendingInvoices = false;        
      },
      pendingInvoices(){
        this.showAllInvoices = false;
        this.showPendingInvoices = true;
      }
  },
  components: {
    invoices: invoices,
    pendingInvoices: pendingInvoices
  }
};
</script>

<style>
</style>
