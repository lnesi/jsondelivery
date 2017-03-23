<template>
	<div class="form-group">
		<label :for="id">{{label}}</label>
		<input type="file" class="custom" :name="name" :id="id" accept="image/x-png,image/gif,image/jpeg" />
		<p class="help-block">{{help_text}} (Types allowed: jpg,gif,png)</p>
	</div>
</template>
<script>
var comp = require('../../mixins/app_component.js');
export default{
	mixins:[comp.default],
}
</script>
