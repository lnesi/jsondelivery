<template>
	<div class="form-group">
		<label for="">{{label}}</label>
		<p class="help-block" v-show="showHelp">{{help_text}}</p>
		<hr>
	</div>
</template>
<script>
var comp = require('../../mixins/app_component.js');
export default{
	mixins:[comp.default],
	computed:{
		showHelp(){
			
			return this.help_text!="" && this.help_text!=null;
			
		}
	}
}
</script>
