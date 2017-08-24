<template>
    <div :class="{'form-group': true, 'has-error': hasErrors}">
        <label :for="id" class="control-label"><slot></slot></label>
        <div class="tbvue_dropdown_holder">
        <select class="form-control"   :name="name"  :id="id" v-model="inputmodel" @blur="validate">
            <option value="">{{label}}</option>
            <option v-for="item in list" :value="item.id" >{{item.name}}</option>
        </select>
        <i class="fa fa-spinner fa-pulse fa-fw" v-show="loading"></i>
        <i class="fa fa-fw fa-exclamation-triangle text-danger" v-show="hasErrors && !loading" ></i>
        </div>
        <p class="help-block">{{ validator.errors.first(this.name) }}</p>
    </div>
</template>
<style lang="scss">
    .tbvue_dropdown_holder{
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
        created() {
            this.validator = new VeeValidate.Validator();
            var prettyName = this.name;
            if (this.$slots.default) {
                prettyName = this.$slots.default[0].text;
            }
            
            var test=this.validator.attach(this.name, this.rules, { alias: prettyName });
          
            if (this.dataUrl != "") {
                this.load();
            }

            // this.validate();

        },
        mounted() {
            this.inputmodel = this.value;
        },

        data() {
            return {
                inputmodel: '',
                validator:null,
                list: [],
                loading:false,
                label:"Loading...",
                isInput:true,
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
        watch: {
            inputmodel(value) {
                if (value !== undefined) {
                    this.validator.validate(this.name, value).then(() => {
                        // success
                    }).catch(() => {
                        // failed
                    });
                    this.$emit('input', value);
                    //console.log(this,"watching inputmodel",value)
                }

            },
            value(value) {
                //console.log("value",value);
                if (value !== undefined) this.inputmodel = value;

            },
            dataUrl(value) {
                //this.inputmodel=undefined;
                this.load();
            }
        },
        props: ['data-url', 'value', 'name', 'rules', 'id'],
        methods: {
            load() {
                this.loading=true;
                this.label="Loading...";
                this.$http.get(this.dataUrl).then(response => {
                    this.list = response.body;
                    this.inputmodel = this.value;
                    this.loading=false;
                    this.label="Select...";
                }, response => {
                    // error callback
                    this.label="Error...";
                    this.loading=false;
                });
            },
            validate() {
                this.validator.validate(this.name, this.value).then(() => {
                    // success
                }).catch(() => {
                    // failed
                });;
            }
        }

    }

</script>
