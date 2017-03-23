<template>
    <div>
        <modal id="editFieldModal" ref="modal">
            <h4 class="modal-title" slot="header">Edit Field</h4>
              <form slot="body">
                  <tbvue-input name="name" id="in_name" placeholder="Name" rules="required|max:255" v-model="item.name">Name / Description</tbvue-input>
                  <tbvue-input name="key" id="in_key" placeholder="Key" rules="required|max:100" v-model="item.key">JSON Key</tbvue-input>
                  <tbvue-input name="help_text" id="in_help" placeholder="Help Text" rules="max:255" v-model="item.help_text">Help Text</tbvue-input>
              </form>
              <button type="button" slot="footer" class="btn btn-default"  data-dismiss="modal">Cancel</button>
              <button type="button" slot="footer" class="btn btn-success" :class="{'btn btn-success': true, 'disabled': hasValidateErrors}" @click="validate"><i class="fa fa-fw fa-floppy-o" ></i> Save</button>
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
              errors:null
          }
      },
      created(){
        this.provider = this.$resource("ajax/customs{/id}");
        this.createValidator();
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
                this.$refs.modal.hide();
            });
          },
          validate() {
            this.validator.validateAll(this.item).then(() => {
              this.save();
            }).catch(() => {});
          },
          show(item) {
              this.id=item.id;
              this.item.name=item.name;
              this.item.key=item.key;
              this.item.help_text=item.help_text;
              this.$refs.modal.show();
          },
          createValidator() {
              this.validator = new VeeValidate.Validator();
              this.validator.attach('delivery_id', 'required|numeric', { prettyName: 'Delivery' });
              this.validator.attach('name', 'required', { prettyName: 'Name' });
              this.validator.attach('key', 'required', { prettyName: 'Key' });
              this.validator.attach('help_text', '', { prettyName: 'Help Text' });
              this.validator.attach('component_id', 'required|numeric', { prettyName: 'Input Type' });
              this.validator.validateAll(this.item).then(() => {}).catch(() => {});
              this.$set(this, 'errors', this.validator.errorBag);
          },
      }
  }

</script>
