<template>
  <div>
    <h2>
      {{title}}
      <a class="btn btn-success" @click.prevent="showForm">Create a New Table</a>
    </h2>
    <table-form
      v-if="formVisible"
      @cancel-form="hideForm"
      @save-form="createTable"
      :table="newTable"
    />
    <table-list :tables="tables" @delete-table="deleteTable"/>
  </div>
</template>

<script>
import tableList from "./tableList.vue";
import tableForm from "./tableForm.vue";
export default {
  data() {
    return {
      tables: [],
      formVisible: false,
      newTable: {}
    };
  },
  props: ["title"],
  methods: {
    getTables() {
      axios.get("api/tables").then(response => {
        this.tables = response.data.data;
      });
    },
    showForm() {
      this.formVisible = true;
    },
    hideForm() {
      this.formVisible = false;
      this.newTable = {};
    },
    createTable() {
      axios.post("api/tables", this.newTable).then(response => {
        this.flashMessage.success({
          title: "",
          message: "Table created succesfully"
        });
        this.newTable = {};
        this.formVisible = false;
        this.getTables();
      });
    },
    deleteTable(table_number){
        axios.delete('api/tables/'+table_number)
        .then(response => {
              this.flashMessage.error({
              title: "",
              message: table_number + "  has been deleted"});
              this.getTables();
          });
    }
  },
  components: {
    "table-list": tableList,
    "table-form": tableForm
  },
  mounted() {
    this.getTables();
  }
};
</script>

<style>
</style>
