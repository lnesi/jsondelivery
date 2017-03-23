<template>
	<div class="form-group">
		<label :for="id">
			<input type="checkbox" class="custom" :name="name" :id="id" value="1" /> {{label}}
		</label>
		<p class="help-block">{{help_text}}</p>
	</div>
</template>
<script>
var comp = require('../../mixins/app_component.js');
export default{
	mixins:[comp.default],
}
</script>
