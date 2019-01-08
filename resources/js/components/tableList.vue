<template>
    <div>
    <table class="table table-striped">
    <thead>
        <tr>
            <th>Table Number</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="table in tables" :key="table.table_number">
            <td>{{table.table_number}}</td>
            <td>
                <a class="btn btn-danger" @click="deleteTable(table.table_number)">Delete</a>                  
            </td>
        </tr>
    </tbody>
    </table>
    </div>
</template>

<script>
export default {
props: ['tables'],
methods:{
    deleteTable(table_number){
        this.$emit('delete-table', table_number);
    }
}

}
</script>

<style>

</style>
