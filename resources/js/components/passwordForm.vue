<template>
    <div>
        <form>
            <div class="form-group" >
                <div class="form-group" v-if="user">
                    <label for="currentPassword">Current password</label>
                    <input type="password" class="form-control" name="currentPassword" 
                        id="oldPassword" placeholder="Password" v-model="password.current" required/>

                    <div v-if="errors.current" class="text-danger">
						<div v-for="(error, index) in errors.current" :key="index">
							{{error}}
						</div>
					</div>
                </div>

                <div class="form-group">
                    <label for="newPassword">New password</label>
                    <input type="password" class="form-control" name="newPassword" 
                        id="newPassword" placeholder="Password" v-model="password.new" required/>

                    <div v-if="errors.new" class="text-danger">
						<div v-for="(error, index) in errors.new" :key="index">
							{{error}}
						</div>
					</div>
                </div>

                <div class="form-group">
                    <label for="passwordConfirm">Confirm password</label>
                    <input type="password" class="form-control" name="passwordConfirm" 
                        id="passwordConfirm" placeholder="Password" v-model="password.new_confirmation" required/>
                </div> 

                 <div class="form-group">
                    <a class="btn btn-primary" v-on:click.prevent="savePassword()">Save</a>
                </div>
	        </div>
        </form>
    </div>
</template>

<script>
export default {
    props: ['user', 'errors', 'password'],
    methods: {
        savePassword: function () {
             this.$emit('save-password', this.password);
        }
    },
}
</script>

<style>

</style>
            