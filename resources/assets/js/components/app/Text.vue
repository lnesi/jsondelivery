<template>
	<div :class="{'form-group': true, 'has-error': hasErrors }"> 
		<label :for="id" class="control-label">{{custom.name}} <span class="text-danger" v-show="isRequired">*</span></label>
		<div class="tbvue_input_holder">
		<input type="text" v-show="custom.data.validation.data.type=='text'" class="form-control custom" :name="custom.key" :id="id" v-model="valueModel"/>
		<input type="text" v-show="custom.data.validation.data.type=='url'" class="form-control custom" :name="custom.key" :id="id" v-model="valueModel"/>
		<input type="email" v-show="custom.data.validation.data.type=='email'" class="form-control custom" :name="custom.key" :id="id" v-model="valueModel"/>
		<input type="number" v-show="custom.data.validation.data.type=='number'" class="form-control custom" :name="custom.key" :id="id" v-model="valueModel"/>
		<i class="fa fa-fw fa-exclamation-triangle text-danger" v-show="hasErrors " ></i>
		</div>
		<p class="help-block">{{custom.help_text}}</p>
		<p class="help-block">{{ validator.errors.first(this.id) }}</p>
	</div>
</template>
<script>
var comp = require('../../mixins/app_component.js');
export default{
	mixins:[comp.default],
	 created(){
        this.validator=new VeeValidate.Validator();
        var rules='';
        if(this.custom.data.validation.required) rules='required:true';
        if(this.custom.data.validation.data.rule) rules+="|"+this.custom.data.validation.data.rule;
        this.validator.attach(this.id,rules,{alias:this.custom.name});
       },	
       
}
</script>
