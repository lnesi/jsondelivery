<template>
	<div :class="{'form-group': true, 'has-error': hasErrors }"> 
		<label :for="id" class="control-label">{{label}}</label>
		<div class="tbvue_input_holder">
		<input type="text" v-show="data.validation.data.type=='text'" class="form-control custom" :name="name" :id="id" v-model="valueModel"/>
		<input type="text" v-show="data.validation.data.type=='url'" class="form-control custom" :name="name" :id="id" v-model="valueModel"/>
		<input type="email" v-show="data.validation.data.type=='email'" class="form-control custom" :name="name" :id="id" v-model="valueModel"/>
		<input type="number" v-show="data.validation.data.type=='number'" class="form-control custom" :name="name" :id="id" v-model="valueModel"/>
		<i class="fa fa-fw fa-exclamation-triangle text-danger" v-show="hasErrors " ></i>
		</div>
		<p class="help-block">{{help_text}}</p>
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
        if(this.data.validation.required) rules='required:true';
        if(this.data.validation.data.rule) rules+="|"+this.data.validation.data.rule;
        console.log('required:'+this.data.validation.required);
        this.validator.attach(this.id,rules,{alias:this.label});
       },	
       
}
</script>
