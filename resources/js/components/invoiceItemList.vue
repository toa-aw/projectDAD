<template>
  <div>
    <div v-if="title">
      <h2>{{title}}</h2>
    </div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Quantity</th>
          <th>Unit Price</th>
          <th>Sub-total Price</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in invoiceItems" :key="item.invoice_id">
          <td>{{item.name}}</td>
          <td>{{item.pivot.quantity}}</td>
          <td>{{item.pivot.unit_price}}</td>
          <td>{{item.pivot.sub_total_price}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script type="text/javascript">
export default {
  data() {
    return {

    };
  },
  props: ["invoiceItems", "title"],
  methods: {
  }
};
</script>

