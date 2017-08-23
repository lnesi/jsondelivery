<template>
    <div>
        <modal id="editFieldModal" ref="editCustomFiled">
            <h4 class="modal-title" slot="header">Edit Field</h4>
              <form slot="body">
                  <tbvue-input name="name" id="in_name" placeholder="Name" rules="required|max:255" v-model="item.name">Name / Description</tbvue-input>
                  <tbvue-input name="key" id="in_key" placeholder="Key" rules="required|max:100" v-model="item.key">JSON Key</tbvue-input>
                  <tbvue-input name="help_text" id="in_help" placeholder="Help Text" rules="max:255" v-model="item.help_text">Help Text</tbvue-input>
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
                help_text:''
              },
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
           this.isValidForm=true;
            this.$refs.editCustomFiled.$children.forEach(function(element){
                if(element.isInput) element.validate();
                if (this.isValidForm) this.isValidForm = element.isValid;
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
              this.$refs.editCustomFiled.show();
          },
          
      }
  }

</script>
