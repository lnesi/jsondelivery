<template>
	<div class="form-group">
		<label :for="id">{{label}}</label>
		<select class="form-control custom" :name="name" :id="id">
			<option value="">Select</option>
		</select>
		<p class="help-block">{{help_text}}</p>
	</div>
</template>
<script>
var comp = require('../../mixins/app_component.js');
export default{
	mixins:[comp.default],
}
</script>
