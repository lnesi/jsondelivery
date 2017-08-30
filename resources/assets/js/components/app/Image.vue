<template>
	<div :class="{'form-group': true, 'has-error': hasErrors || (validateSize && !validSize) }"> 
		<label :for="id" class="control-label">{{custom.name}} <span class="text-danger" v-show="isRequired">*</span></label>
		<p v-show="hasImage">
		<img :src="image" ref="imagecontainer" class="img-thumbnail">
		</p>
		<input type="file" class="custom " ref="fileinput" :name="custom.name" :id="id" accept="image/x-png,image/gif,image/jpeg" @change="onFileChange" />
		<p class="help-block" ><span v-show="validateSize">
		Size Requirement: 
		Width <strong>{{custom.data.validation.data.w}}px</strong> 
		Height <strong>{{custom.data.validation.data.h}}px</strong></span> 
		Types allowed: <strong>jpg,gif,png</strong></p>
		<p class="help-block">{{custom.help_text}}</p>
		<p class="help-block" v-show="hasErrors">This Image is required.</p>
		<p class="help-block" v-show="validateSize && !validSize">This size of the images does not meet requirements.</p>
	</div>
</template>
<script>

export default{
	data(){
		return{
			image:null,
			valueModel:null,
			isValidatorEnabled:true,
			hasErrors:false,
			validSize:true
		}
	},
	mounted(){
		this.$refs.imagecontainer.onload=function(){
			this.validate();
		}.bind(this);
		if(this.value){
			this.valueModel=this.value;
		}
	},
	methods:{
		getValue(){
			return this.valueModel;
		},
		onFileChange(e){
			var files = e.target.files || e.dataTransfer.files;
			 //if (!files.length) return;
			 this.createImage(files[0]);
		},
		createImage(file){
			console.log("createImage");
			 this.valueModel=file;
			 var reader = new FileReader();
			 reader.onload=function(e){
			 	this.image=e.target.result;
			 }.bind(this);
			 reader.readAsDataURL(file);

		},
		validate(){
			console.log('validate');
			if(this.custom.data.validation.required){
				this.hasErrors=!this.hasImage;
			}
			if(this.validateSize){
				this.validSize=true;
				
				if(this.image && this.custom.data.validation.data.w!=this.$refs.imagecontainer.width){
					this.validSize=false;
				}
				if(this.image && this.custom.data.validation.data.h!=this.$refs.imagecontainer.height){
					this.validSize=false;
				}
				if(!this.validSize){
					this.image=null;
					this.$refs.fileinput.value="";
				}
			}
			
			
		}
	},
	watch:{
		valueModel(value){
			console.log("valueModel",value instanceof File);
			if(value instanceof File){
				// Not sure i need this
			}else if(value!=null && value!=""){
				this.image=value;//"data:image/png;base64,"+value; 
			}
			
		},

	},
	props:['value','custom'],
	computed:{
		id(){
			return "custom_"+this.custom.id
		},
		validateSize(){
			
			return this.custom.data.validation.data.w && this.custom.data.validation.data.h;
		},
        isValid(){
          return this.hasImage
        },
		hasImage(){
			return this.image!="" && this.image!=null;
		},
		isRequired(){
        	if(this.custom.data && this.custom.data.validation && this.custom.data.validation.required) return true;
        	return false;
        }
	}
}
</script>
