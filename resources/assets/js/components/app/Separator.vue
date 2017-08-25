<template>
	<div class="form-group">
		<label for="">{{custom.name}}</label>
		<p class="help-block" v-show="showHelp">{{custom.help_text}}</p>
		<hr class='separator_line'>
	</div>
</template>
<script>
var comp = require('../../mixins/app_component.js');
export default{
	mixins:[comp.default],
	data(){
		return{
			isValidatorEnabled:false
		}
	},
	computed:{

		showHelp(){
			return this.custom.help_text!="" && this.custom.help_text!=null;
			
		}
	}
}
</script>
<style lang="scss">
	.separator_line{
		margin-top:0;
	}
</style>
