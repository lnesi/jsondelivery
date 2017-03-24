<template>
	<div class="form-group">
		<label :for="id">{{label}}</label>
		<input type="file" class="custom" :name="name" :id="id" accept="image/x-png,image/gif,image/jpeg" @change="onFileChange" />
		<p class="help-block">{{help_text}} (Types allowed: jpg,gif,png)</p>
	</div>
</template>
<script>
var comp = require('../../mixins/app_component.js');
export default{
	data(){
		return{
			image:null,
		}
	},
	mixins:[comp.default],
	methods:{
		onFileChange(e){
			var files = e.target.files || e.dataTransfer.files;
			 if (!files.length) return;
			 this.createImage(files[0]);
		},
		createImage(file){
			this.valueModel=file;
			 var image = new Image();
			 var reader = new FileReader();
			 reader.onload=function(e){
			 	this.image=e.target.result;
			 }.bind(this);
			 reader.readAsDataURL(file);
		}
	}
}
</script>
