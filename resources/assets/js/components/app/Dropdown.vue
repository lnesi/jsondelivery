<template>
	<div :class="{'form-group': true, 'has-error': hasErrors }">
		<label :for="id" class="control-label">{{label}}</label>
		<div class="tbvue_dropdown_holder">
			<select class="form-control custom" v-model="valueModel" :name="name" :id="id">
				<option value="">Select...</option>
				<option :value="option" v-for="option in this.data.values">{{option}}</option>
			</select>
			<i class="fa fa-fw fa-exclamation-triangle text-danger" v-show="hasErrors" ></i>
		</div>
		<p class="help-block">{{help_text}}</p>
		<p class="help-block">{{ validator.errors.first(this.id) }}</p>
	</div>
</template>
<script>
var comp = require('../../mixins/app_component.js');
export default{
	mixins:[comp.default],
	data(){
		return{
			valueModel:'',
			isInputComponent:true,
			validator:null,
		}
		
	},
	created(){
		this.validator=new VeeValidate.Validator();
        var rules='';
        if(this.data.validation.required) rules='required:true';
        this.validator.attach(this.id,rules,{alias:this.label});
	}
}
</script>
