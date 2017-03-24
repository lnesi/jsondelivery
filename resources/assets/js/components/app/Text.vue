<template>
	<div class="form-group">
		<label :for="id">{{label}}</label>
		<input type="text" class="form-control custom" :name="name" :id="id" v-model="valueModel"/>
		<p class="help-block">{{help_text}}</p>
	</div>
</template>
<script>
var comp = require('../../mixins/app_component.js');
export default{
	mixins:[comp.default],

}
</script>
