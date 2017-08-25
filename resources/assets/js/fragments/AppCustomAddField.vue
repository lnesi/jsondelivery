<template>
  <div>
    <modal id="addFieldModal" size="modal-lg" ref="addCustomFiled">
          <h4 class="modal-title" slot="header">Add Field</h4>
          <form slot="body" ref="form">
              <tbvue-input name="name" id="in_name" placeholder="Name" rules="required|max:255" v-model="item.name">Name / Description</tbvue-input>
              <div class="row">
                    <div class="col-md-6">
                    <tbvue-input name="key" id="in_key" placeholder="Key" rules="required|max:100" v-model="item.key">JSON Key</tbvue-input>
                    </div>
                    <div class="col-md-6">
                    <tbvue-ajax-dropdown data-url="ajax/components?paginate=false" name="input_type_id" rules="required" id="input_type_id" v-model="item.component_id">Input Type</tbvue-ajax-dropdown>
                    </div>
                  </div>
			        
		          <tbvue-input name="help_text" id="in_help" placeholder="Help Text" rules="max:255" v-model="item.help_text">Help Text</tbvue-input>
              
              <div class="form-group" v-show="item.component_id==5">
                <label for="in_options" class="control-label">Options</label> 
                <div class="tbvue_input_holder">
                    <input type="text" name="options" id="in_options" v-model="dropdownRaw" placeholder="" class="form-control">
                    
                  </div> 
                  <p class="help-block">Sparated by ";" Example: Option 1; Option 2</p>
              </div>
              
              <app-customfieldvalidation v-model="item.component_id" ref="validationComponent"></app-customfieldvalidation>

          </form>
          <button type="button" slot="footer" class="btn btn-default"  data-dismiss="modal">Cancel</button>
          <button type="button" slot="footer" class="btn btn-success" :class="{'btn btn-success': true, 'disabled': isValidForm}" @click="validate"><i class="fa fa-fw fa-floppy-o" ></i> Save</button>
      </modal>
  </div>
</template>
<script>
  export default {
      provider:null,
      mounted(){
        this.item.delivery_id=this.deliveryId;
        this.$refs.addCustomFiled.$on('HIDE',function(){
            this.reset();
        }.bind(this));
      },
      created(){
        this.provider = this.$resource("ajax/customs{/id}");

       
      },
      data(){
        return {
          item:{
              delivery_id:'',
              name:'',
              key:'',
              help_text:'',
              component_id:'',
              data:{validation:null,values:null}

            },
          dropdownRaw:'',
          isValidForm:false 
        }
       
      },
      methods:{
        add(){
         
          this.provider.save(this.item).then(response => {
              this.$parent.value.push(response.body);
              this.$refs.addCustomFiled.hide();
              this.reset();
          }, response => {
              console.log("errorAdding");
          });
        },
        validate(){
            this.item.data.validation=this.$refs.validationComponent.rules;
            if(this.item.component_id==5 && this.dropdownRaw!=''){
              this.item.data.values=this.dropdownRaw.split(';');
            }
           
            this.isValidForm=true;
            this.$refs.addCustomFiled.$children.forEach(function(element){
                if(element.isInput){
                  element.validate();
                  if (this.isValidForm) this.isValidForm = element.isValid;
                } 
                

            }.bind(this));
            if (this.isValidForm) {
                this.add();
             
            }
          
        },
        show(){
          this.$refs.addCustomFiled.show();
        },
       
        reset(){
            this.item.name="";
            this.item.key="";
            this.item.help_text="";
            this.item.component_id="";
        }
      },
      watch:{
        deliveryId(value){
          this.item.delivery_id=value;
        }
      },
      props:["deliveryId"],
   

    }
</script>