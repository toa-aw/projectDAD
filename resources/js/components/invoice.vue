<template>
  <div>
    <h2>
      {{title}}
    </h2>

    <invoice-list :invoices="invoices" showOptions="true"/>

    
  </div>
</template>

<script>
import invoiceList from "./invoiceList.vue";


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
    getInvoices: function() {
      axios.get("api/invoices").then(response => {
        this.invoices = response.data.data;
      });
    }
  },
  mounted() {
    this.getInvoices();
  }
};
</script>

<style>
</style>