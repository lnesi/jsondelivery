<template>
	<div :class="{'form-group': true, 'has-error': hasErrors }">
		<label :for="id" class="control-label">{{custom.name}} <span class="text-danger" v-show="isRequired">*</span></label>
		<div class="tbvue_dropdown_holder">
			<select class="form-control custom" v-model="valueModel" :name="custom.name" :id="id">
				<option value="">Select...</option>
				<option :value="option" v-for="option in custom.data.values">{{option}}</option>
			</select>
			<i class="fa fa-fw fa-exclamation-triangle text-danger" v-show="hasErrors" ></i>
		</div>
		<p class="help-block">{{custom.help_text}}</p>
		<p class="help-block">{{ validator.errors.first(id) }}</p>
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
        if(this.custom.data.validation.required) rules='required:true';
        this.validator.attach(this.id,rules,{alias:this.custom.name});
	}
}
</script>
