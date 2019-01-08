<template>
  <div>
    <div v-if="showInvoiceInfo">
      <b>Date: {{selectedInvoice.date}}</b>
      <br>
      <b>Table Number: {{selectedInvoice.table_number}}</b>
      <br>
      <b>Waiter: {{selectedInvoice.responsible_waiter}}</b>
      <br>
      <b>Total Price: {{selectedInvoice.total_price}}</b>
      <br>
      <invoice-items title="Invoice Items" :invoiceItems="selectedInvoice.items"/>
      <a class="btn btn-danger" @click="closeInfo">Close</a>
    </div>

    <div v-if="title">
      <h2>{{title}}</h2>
    </div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>State</th>
          <th>NIF</th>
          <th>Client Name</th>
          <th>Table Number</th>
          <th>Responsable Waiter</th>
          <th>Date</th>
          <th>Total Price</th>
          <th>Options</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="invoice in invoices" :key="invoice.id">
          <td>{{invoice.state}}</td>
          <td>{{invoice.nif}}</td>
          <td>{{invoice.name}}</td>
          <td>{{invoice.table_number}}</td>
          <td>{{invoice.responsible_waiter}}</td>
          <td>{{invoice.date}}</td>
          <td>{{invoice.total_price}}</td>
          <td>
            <div>
              <a class="btn btn-primary" @click.prevent="showInfo(invoice)">Invoice Info</a>
              <a
                class="btn btn-secondary"
                @click.prevent="downloadPdf(invoice)"
                v-if="invoice.state === 'paid'"
              >Dowload Invoice Info</a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script type="text/javascript">
import invoiceItemsList from "./invoiceItemList.vue";

export default {
  data() {
    return {
      showInvoiceInfo: false,
      selectedInvoice: null
    };
  },
  props: ["invoices", "title"],
  components: {
    "invoice-items": invoiceItemsList
  },
  methods: {
    showInfo(invoice) {
      this.showInvoiceInfo = true;
      this.selectedInvoice = invoice;
    },
    closeInfo() {
      this.showInvoiceInfo = false;
      this.selectedInvoice = {};
    },
    downloadPdf(invoice) {
      axios({
        url: "api/invoices/dowload/" + invoice.id,
        method: "GET",
        responseType: "blob"
      }).then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", "invoice_" + invoice.id + "_" + invoice.state + ".pdf"); //or any other extension
        document.body.appendChild(link);
        link.click();
      });
    }
  }
};
</script>

