<template>
  <div :class="{'form-group': true, 'has-error': hasErrors }">
    <label :for="id" class="control-label"><slot></slot></label>
    <div class="tbvue_input_holder">
      <input v-model="inputmodel" type="text" :name="name" class="form-control" :id="id" @blur="validate" :placeholder="placeholder" :disabled="this.disabled">
      <i class="fa fa-fw fa-exclamation-triangle text-danger" v-show="hasErrors " ></i>
      <i class="fa fa-spinner fa-pulse fa-fw" v-show="loading"></i>
    </div>
    <p class="help-block">{{ validator.errors.first(this.name) }}</p>
  </div>
</template>

<style lang="scss">
    .tbvue_input_holder{
        position:relative;
        i{
            position: absolute;
            top: 0.34em;
            right: 1em;
            font-size: 1.5em;
            opacity: 0.5;
        }
    }
</style>
<script>
  

    export default {
      validator: null,
       created(){
        this.validator=new VeeValidate.Validator();
        this.validator.attach(this.name, this.rules , {prettyName:this.$slots.default[0].text});
       },
       mounted(){
          this.inputmodel=this.value;
       },
       data(){
        return {
          inputmodel:null,
          loading:false,
          isInput:true,
          validator:null
        }
       },
       watch:{
          inputmodel(value){
            this.$emit('input', value);
          },
          value(value){
             this.inputmodel=value;
          }
       },
      computed:{
      
      
        hasErrors(){
          return this.validator.errors.has(this.name);
        },
        isValid(){
          return this.validator.errors.count()==0;
        }
      },
       methods:{

          validate(){
            this.loading=true;
            this.validator.validate(this.name, this.inputmodel).then(() => {
                this.loading=false;
                // success
            }).catch(() => {
                this.loading=false;
                // failed
            });
          },
       },
       props:{
          value:{default:null},
          id:{type:String,required: true},
          name:{type:String,required:true},
          rules:{type:String,default:''},
          disabled:{type:Boolean,default:false},
          placeholder:{type:String,default:''}
       }
      
    }
</script>
