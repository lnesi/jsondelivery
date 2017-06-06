<template>
	<div>
		  <div :class="{'form-group': true, 'has-error': this.errors.has('password1') }">
		    <label for="inpassword1" class="control-label">Password</label>
		    <input type="password" class="form-control" id="inpassword1" @blur="validate" name="password1" v-model="model.password1" placeholder="Password">
		    <p class="help-block">{{ errors.first('password1') }}</p>
		  </div>
		  <div :class="{'form-group': true, 'has-error': this.errors.has('password2') }">
		    <label for="inpassword2" class="control-label">Confirm Password</label>
		    <input type="password" class="form-control" id="inpassword2" @blur="validate" name="password2"  v-model="model.password2" placeholder="Password">
		    <p class="help-block">{{ errors.first('password2') }}</p>
		  </div>
	</div>
</template>
<script>
   

	export default {
		
		created(){
			this.validator=new VeeValidate.Validator();
	        this.validator.attach('password1', 'required:true|min:8|passregex', {prettyName:"Password"});
	        this.validator.attach('password2', {required:true,confirmed:'password1'}, {prettyName:"Password"});
	        
	        this.$set(this, 'errors', this.validator.errorBag);
		},
		mounted(){

		},
		data(){
			return {
				validator:null,
				errors:null,
				model:{
					password1:'',
					password2:''
				},
				isInput:true,
			}
		},
		
		methods:{
			validate(){
	            this.validator.validateAll(this.model).then(() => {
	                console.log("is valid password");
	                this.$emit('input', this.model.password1);
	            }).catch(() => {
	                // failed
	            });;
	          },
		}
	}
</script>