<template>
<div>
    <div class="jumbotron"> 
        <h1>{{ title }}</h1>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-secondary" :class="{active : showUsers}" @click.prevent="usersSelected()">
                <input type="radio" name="options" id="users" :checked="showUsers"> Users
            </label>
            <label class="btn btn-secondary" :class="{active : showItems}" @click.prevent="itemsSelected()">
                <input type="radio" name="options" id="items" :checked="showItems"> Items
            </label>
            <label class="btn btn-secondary" :class="{active : showTables}" @click.prevent="tablesSelected()">
                <input type="radio" name="options" id="tables" :checked="showTables"> Tables
            </label>
        </div>
    </div>

    <users v-if="showUsers" title="Restaurant Staff" />
    <tables v-if="showTables" title="Restaurant Tables" />
    <items v-if="showItems" title="Restaurant Menu"/>

</div>

</template>

<script type="text/javascript">
import users from "./user.vue";
import tables from "./table.vue";
import items from "./item.vue";

export default {
data(){
    return{
        title: "Manager Dashboard",
        showUsers: true,
        showItems: false,
        showTables: false,
    };
},
methods:{
    usersSelected(){
        this.showUsers = true;
        this.showItems = false;
        this.showTables = false;
    },
    itemsSelected(){
        this.showUsers = false;
        this.showItems = true;
        this.showTables = false;
    },
    tablesSelected(){
        this.showUsers = false;
        this.showItems = false;
        this.showTables = true;
    },

},
components:{
    users: users,
    tables: tables,
    items: items
}
}
</script>

<style>

</style>
