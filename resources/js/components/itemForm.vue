<template>
     <div>
        <div>   
            <form>
				<div class="form-group">
	                <label for="inputItemName">Name</label>
	                <input type="text" class="form-control" v-model="item.name" name="itemName" 
					id="inputItemName" placeholder="Name" value="item.name" required/>

					<div v-if="errors.name" class="text-danger">
						<div v-for="(error, index) in errors.name" :key="index">
							{{error}}
						</div>
					</div>
	            </div>

				<div class="form-group">
	                <label for="inputDescription">Description</label>
	                <textarea type="text" class="form-control" v-model="item.description" name="description" 
					id="inputDescription" placeholder="Description" value="item.description"/>

					<div v-if="errors.description" class="text-danger">
						<div v-for="(error, index) in errors.description" :key="index">
							{{error}}
						</div>
					</div>
	            </div>

	            <div class="form-group">
                    <label for="inputPrice">Price</label>
	                <input type="number" class="form-control" v-model="item.price" name="price" 
					id="inputPrice" placeholder="Price" value="item.price"/>

					<div v-if="errors.price" class="text-danger">
						<div v-for="(error, index) in errors.price" :key="index">
							{{error}}
						</div>
					</div>
	            </div>

	            <div class="form-group">
	                <label for="itemType">Item type</label>
	                <select class="form-control" id="itemType" name="itemType" v-model="item.type" >
					<option v-for="type in mealType" :key="type.id" :value="type.id" :disabled="type.id == ''"> {{ type.name }} </option>
	                </select>

					<div v-if="errors.type" class="text-danger">
						<div v-for="(error, index) in errors.type" :key="index">
							{{error}}
						</div>
					</div>
	            </div>

				<div class="from-group">
					<label for="itemPhoto">Photo</label>
					<input id="photo" type="file" accept="image/*" ref="photo" name="itemPhoto" class="form-control" @change="photoUploaded">

					<div v-if="errors.photo_url" class="text-danger">
						<div v-for="(error, index) in errors.photo_url" :key="index">
							{{error}}
						</div>
					</div>
				</div>

				<br>

	    <div class="form-group">
		 	<a class="btn btn-danger" v-on:click.prevent="cancelEdit()">Cancel</a>
	        <a class="btn btn-primary" v-on:click.prevent="saveItem()">Save</a>
	    </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    props:['item', 'errors'],
    data(){
        return{
            mealType: [
            {id: "", name: "--- select a meal type ---"},
            {id: "drink", name: "Drink"},
            {id: "dish",name: "Dish"}
			],
			photo: null,
			photoErrors: {},
        };
    },
    methods:{
		saveItem: async function(){
			if(this.photo != null){
				let fd = new FormData();
				fd.append('photo', this.photo);
				fd.append('type', 'items');
				await axios.post("api/photo/upload", fd)
					.then(response => {
						this.item.photo_url = response.data;
					})
					.catch(error => {
						console.log(error.response.data.errors);
					});
		}
		this.$emit('save-form', this.item); 
	},
	cancelEdit: function(){
		this.$emit('cancel-form');
	},
	photoUploaded: function () {
		this.photo = this.$refs.photo.files[0];
	}
    }

}
</script>

<style>

</style>
