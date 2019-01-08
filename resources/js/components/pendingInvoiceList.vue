<template>
  <div>
    <div v-if="title">
      <h2>{{title}}</h2>
    </div>

    <invoice-form
      :invoice="currentInvoice"
      :errors="errors"
      v-if="showForm"
      @back="hideInvoiceForm"
      @close-invoice="startCloseInvoice"
    />

    <table class="table table-striped">
      <thead>
        <tr>
          <th>Table Number</th>
          <th>Responsable Waiter</th>
          <th>Total Price</th>
          <th>Options</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="invoice in invoices" :key="invoice.id">
          <td>{{invoice.table_number}}</td>
          <td>{{invoice.responsible_waiter}}</td>
          <td>{{invoice.total_price}}</td>
          <td>
            <div>
              <a
                class="btn btn-primary"
                @click.prevent="showInvoiceForm(invoice)"
              >Fill Client's Name and NIF</a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script type="text/javascript">
import invoiceForm from "./pendingInvoiceForm.vue";

export default {
  data() {
    return {
      currentInvoice: null,
      showForm: false,
      errors: {}
    };
  },
  props: ["invoices", "title"],
  methods: {
    showInvoiceForm(invoice) {
      this.currentInvoice = invoice;
      this.showForm = true;
    },
    hideInvoiceForm() {
      this.showForm = false;
      this.currentInvoice = {};
    },
    startCloseInvoice() {
      console.log(this.currentInvoice);
      axios
        .put("api/invoices/" + this.currentInvoice.id, this.currentInvoice)
        .then(response => {
          this.flashMessage.success({
            title: "",
            message: "Invoice closed."
          });
          this.showForm = false;
          this.currentInvoice = {};
          this.emit("update-invoices");
        })
        .catch(error => {
          console.log(error);
          this.errors = error.response.data.errors;
        });
    }
  },
  components: {
    "invoice-form": invoiceForm
  }
};
</script>

