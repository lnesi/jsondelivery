<template>
	<div>
		  <div :class="{'form-group': true, 'has-error': this.errors.has('password1') }">
		    <label for="inpassword1">Password</label>
		    <input type="password" class="form-control" id="inpassword1" @blur="validate" v-model="model.password1" placeholder="Password">
		    <p class="help-block">{{ errors.first('password1') }}</p>
		  </div>
		  <div :class="{'form-group': true, 'has-error': this.errors.has('password2') }">
		    <label for="inpassword2">Confirm Password</label>
		    <input type="password" class="form-control" id="inpassword2" @blur="validate" v-model="model.password2" placeholder="Password">
		    <p class="help-block">{{ errors.first('password2') }}</p>
		  </div>
	</div>
</template>
<script>
	export default {
		created(){
			this.validator=new VeeValidate.Validator();
	        this.validator.attach('password1', 'required', {prettyName:"Password"});
	        this.validator.attach('password2', 'required', {prettyName:"Password confirmation"});
	        this.$set(this, 'errors', this.validator.errorBag);
		},
		data(){
			return {
				validator:null,
				errors:null,
				model:{
					password1:'',
					password2:''
				}
				
			}
		},
		
		methods:{
			validate(){
	            this.validator.validateAll(this.model).then(() => {
	                // success
	            }).catch(() => {
	                // failed
	            });;
	          },
		}
	}
</script>