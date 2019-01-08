<template>
    <div>
        <div>   
            <form>

				<div class="form-group">
	                <label for="inputUsername">Username</label>
	                <input type="text" class="form-control" v-model="user.username" name="username" 
					id="inputUsername" placeholder="Username" value="user.username" required/>

					<div v-if="errors.username" class="text-danger">
						<div v-for="(error, index) in errors.username" :key="index">
							{{error}}
						</div>
					</div>
	            </div>

				<div class="form-group">
	                <label for="inputName">Name</label>
	                <input type="text" class="form-control" v-model="user.name" name="name" 
					id="inputName" placeholder="Fullname" value="user.name"/>

					<div v-if="errors.name" class="text-danger">
						<div v-for="(error, index) in errors.name" :key="index">
							{{error}}
						</div>
					</div>
	            </div>

	            <div class="form-group">
                    <label for="inputEmail">Email</label>
	                <input type="email" class="form-control" v-model="user.email" name="email" 
					id="inputEmail" placeholder="Email address" value="user.email" :readonly="!createUser"/>

					<div v-if="errors.email" class="text-danger">
						<div v-for="(error, index) in errors.email" :key="index">
							{{error}}
						</div>
					</div>
	            </div>

	            <div class="form-group">
	                <label for="userCharge">Charge</label>
	                <select class="form-control" id="userCharge" name="userCharge" v-model="user.type" >
					<option v-for="charge in charges" :key="charge.id" :value="charge.id" :disabled="charge.id == ''"> {{ charge.name }} </option>
	                </select>

					<div v-if="errors.type" class="text-danger">
						<div v-for="(error, index) in errors.type" :key="index">
							{{error}}
						</div>
					</div>
	            </div>

				<div class="from-group">
					<label for="userPhoto">Photo</label>
					<input id="photo" type="file" accept="image/*" ref="photo" name="userPhoto" class="form-control" @change="photoUploaded">

					<div v-if="errors.photo_url" class="text-danger">
						<div v-for="(error, index) in errors.photo_url" :key="index">
							{{error}}
						</div>
					</div>
				</div>

				<br>

	    <div class="form-group">
		 	<a class="btn btn-danger" v-on:click.prevent="cancelEdit()" v-if="createUser">Cancel</a>
	        <a class="btn btn-primary" v-on:click.prevent="saveUser()">Save</a>
	    </div>
            </form>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
	props: ['user', 'errors', 'createUser'],
	data: function(){
			return{
				charges: [
					{id: "", name: "--- select a charge ---"},
					{id: "manager", name: "Manager"},
					{id: "waiter",name: "Waiter"},
					{id: "cashier",name: "Cashier"},
					{id: "cook",name: "Cook"}
					],
				photo: null,
				photoErrors: {},
			};
	},
    methods: {
	        saveUser: async function(){
				if(this.photo != null){
					let fd = new FormData();
					fd.append('photo', this.photo);
					fd.append('type', 'profiles');
					await axios.post("api/photo/upload", fd)
						.then(response => {
							this.user.photo_url = response.data;
						})
						.catch(error => {
							console.log(error.response.data.errors);
						});
				}
				this.$emit('save-form', this.user); 
	        },
	        cancelEdit: function(){
	            this.$emit('cancel-form');
			},
			photoUploaded: function () {
				this.photo = this.$refs.photo.files[0];
			}
		},

}
</script>

