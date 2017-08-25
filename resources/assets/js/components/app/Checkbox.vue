<template>
	<div class="form-group">
		<label :for="id">
			<input type="checkbox" class="custom" :name="custom.name" :id="id" value="1" v-model="valueModel"/> {{custom.name}}
		</label>
		<p class="help-block">{{custom.help_text}}</p>
	</div>
</template>
<script>
var comp = require('../../mixins/app_component.js');
export default{
	mixins:[comp.default],
	data(){
		return {
			valueModel:null,
			isValidatorEnabled:false
		}
	},
	created(){
		this.valueModel=false;
	}
}
</script>
