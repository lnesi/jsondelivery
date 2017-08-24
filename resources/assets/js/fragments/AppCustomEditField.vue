<template>
    <div>
        <modal id="editFieldModal" size="modal-lg"  ref="editCustomFiled">
            <h4 class="modal-title"  slot="header">Edit Field</h4>
              <form slot="body">

                  <tbvue-input name="name" id="in_name" placeholder="Name" rules="required|max:255" v-model="item.name">Name / Description</tbvue-input>
                  <tbvue-input name="key" id="in_key" placeholder="Key" rules="required|max:100" v-model="item.key">JSON Key</tbvue-input>
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
      validator:null,
      data() {
          return {
              id:null,
              item: {
                name:'',
                key:'',
                help_text:'',
                component_id:null,
                data:{validation:null,values:null}
              },
            dropdownRaw:'',  
            isValidForm:false 
          }
      },
      created(){
        this.provider = this.$resource("ajax/customs{/id}");
      },
      props: ['value'],
      computed: {
          hasValidateErrors() {
              return false;
          }
      },
      methods: {
          save(){
            
            this.provider.update({id:this.id},this.item).then(response=>{
                this.$parent.$emit("OK_EDIT_FIELD",response.body);
                this.$root.$emit("ALERT","Ok!","The Field has been updated successfully","success",3);
                this.$refs.editCustomFiled.hide();
            });
          },
          validate() {
            this.item.data.validation=this.$refs.validationComponent.rules;
            if(this.item.component_id==5 && this.dropdownRaw!=''){
              this.item.data.values=this.dropdownRaw.split(';');
            }
           this.isValidForm=true;
            this.$refs.editCustomFiled.$children.forEach(function(element){
                if(element.isInput){
                  element.validate();
                  if (this.isValidForm) this.isValidForm = element.isValid;
                } 
            }.bind(this));
            if (this.isValidForm) {
                this.save();
             
            }
          },
          show(item) {
              this.id=item.id;
              this.item.name=item.name;
              this.item.key=item.key;
              this.item.help_text=item.help_text;
              this.item.component_id=item.component.id;
              if(item.data){
                this.item.data=item.data;
              }else{
                this.item.data={validation:null,values:null};
              }


              this.$refs.validationComponent.data_type=="";
              this.dropdownRaw='';

              if(item.component.id==5 && item.data && item.data.values){
                this.dropdownRaw=item.data.values.join('; ');
              }
                if(item.component.id==3){
                    this.$refs.validationComponent.size={w:null,h:null};           
                }
              
              
              if(item.data && item.data.validation){
                 this.$refs.validationComponent.required=item.data.validation.required;
                 if(item.data.validation.data){
                  this.$refs.validationComponent.data_type=item.data.validation.data.rule;
                }                 
                if(this.$refs.validationComponent.data_type==="" || this.$refs.validationComponent.data_type===null) this.$refs.validationComponent.data_type="text";

                if(item.component.id==3 && item.data.validation.data){
                   this.$refs.validationComponent.size=item.data.validation.data;
                }
              }else{
                this.$refs.validationComponent.required=false;
                this.$refs.validationComponent.data_type="text";
              }

              this.$refs.editCustomFiled.show();
          },
          
      }
  }

</script>
