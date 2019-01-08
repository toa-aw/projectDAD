<template>
  <div>
    <h2>
      {{title}}
    </h2>

    <invoice-list :invoices="invoices" @update-invoices='getPendingInvoices' />
    
    
  </div>
</template>

<script>
import invoiceList from "./pendingInvoiceList.vue";
export default {
  data() {
    return {
      invoices: []
    };
  },
  props: ["title"],
  components: {
    "invoice-list": invoiceList
  },
  methods: {
    getPendingInvoices: function() {
      axios.get("api/invoices/pending").then(response => {
          this.invoices = response.data.data;
      });
    }
  },
  sockets:{
    update_invoices(){
        this.getPendingInvoices();
    }
  },
  mounted() {
    this.getPendingInvoices();
  }
};
</script>

<style>
</style>