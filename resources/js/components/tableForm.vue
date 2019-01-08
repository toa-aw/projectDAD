<template>
    <form>
        <div class="form-group">
            <label for="inputTableNumber">Table Number</label>
            <input type="number" class="form-control" name="tableNumber" v-model="table.table_number"
            id="inputTableNumber" placeholder="Table Number"/>

            <!-- <div v-if="errors.email" class="text-danger">
                <div v-for="(error, index) in errors.email" :key="index">
                    {{error}}
                </div>
            </div> -->
        </div>
        <div class="form-group">
		 	<a class="btn btn-danger" @click.prevent="cancelCreate">Cancel</a>
	        <a class="btn btn-primary" @click.prevent="saveCreate">Save</a>
	    </div>
    </form>
</template>

<script>
export default {
    props:['table'],
    methods:{
        cancelCreate(){
            this.$emit('cancel-form');
        },
        saveCreate(){
            this.$emit('save-form');
        }
    }

}
</script>

<style>

</style>
