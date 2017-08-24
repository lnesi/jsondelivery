<template>
	<div v-show="isEnabled">
        <hr>
        <h4>Validation</h4>
          <div class="row">
          <div class="form-horizontal">
             <div class="col-md-1">
                <div class="checkbox">
                  <label><input type="checkbox" v-model="required" value="">Required</label>
                </div>
             </div>
             <div class="col-md-11">
                <div class="form-group" v-show="value==1">
	                <label for="data_type" class="control-label col-sm-2">Type</label> 
	                <div class="col-sm-10">    
	                <select name="data_type" v-model="data_type" id="data_type" class="form-control">
	                  <option value="text">String</option>
	                  <option value="numeric">Number</option>
	                  <option value="email">Email</option>
	                  <option value="url">Url</option>
	                </select> 
	                </div>
               </div>
               <div v-show="value==3">
               		<div class="col-sm-4 text-right">
               		<label  class="control-label ">Size </label> <span>(w)px x (h)px </span>
               		</div>
	               	<div class="col-sm-8">
	               		<div class="col-sm-5">  
	               			<input type="number" class="form-control" v-model="size.w" placeholder="px" required/>
	               		</div>
	               		<div class="col-sm-1">  
	               			<label class="control-label ">X</label> 
	               		</div>
	               		<div class="col-sm-5">  
	               			<input type="number" class="form-control" v-model="size.h" placeholder="px" required/>
	               		</div>
	               	</div>
               </div>
            </div>
          </div> 
        </div>
      </div>
</template>
<script>
  export default {
  	data(){
  		return{
  			validationEnable:[1,3,5],
			required:false,
			data_type:"text",
			size:{w:null,h:null}
  		}
  	},
  	computed:{
  		isEnabled(){
  			return this.validationEnable.indexOf(this.value)!==-1;
  		},
  		rules(){
  			if(this.isEnabled){
  				switch(this.value){
  					case 1:
  						var type_rule=this.data_type;
		  				var field_type=this.data_type;
		  				if(type_rule=="text") type_rule='';
		  				if(type_rule=="numeric") field_type="number";
		  				if(type_rule=="url") field_type="text";

		  				return {
		  					required:this.required,
		  					data:{
			  					type:field_type,
			  					rule:type_rule
		  					}
		  				}
  						break;
  					case 3:
  						return {
		  					required:this.required,
		  					data:this.size
		  				}
  						break;
  					default:
  						return {
		  					required:this.required,
		  					data:null
		  				}
  						break;
  				}

  				
  			}
  			return null;
  		}
  	},
  	props:{
          value:{default:null}
    },
    
   
  }
</script>