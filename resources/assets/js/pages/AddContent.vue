<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                 <form  @submit="validate()" onsubmit="return false;">
                    <div class="panel panel-default">
                        <div class="panel-heading">Delivery: Add Content</div>

                        <div class="panel-body">
                            <h4  data-toggle="collapse" data-target="#detailsHolder" id="detailsTitle" class="collapsed">Details <i class="fa fa-fw fa-plus-circle" ></i><i class="fa fa-fw fa-minus-circle" ></i></h4>
                            <app-deliverydetails v-model="delivery" collapseId="detailsHolder" collapse="true"></app-deliverydetails>
                            <hr>
                            <tbvue-input name="name" id="in_name" ref="lookup_name" placeholder="Name" rules="required|max:100" v-model="lookup_name">Lookup Name <span class="text-danger">*</span></tbvue-input>
                            <hr>
                            <div :is="field.component" v-for="field in field_list" v-bind="field.props" ref="customs"></div>
                        </div>
                        <div class="panel-footer">
                            <a  class="btn btn-default" :href="backURL" ><i class="fa fa-fw fa-chevron-left"></i> Back</a>
                            <button type="button" @click="validate"  :class="{'btn btn-success pull-right': true,'disabled':!isValidForm  }"><i class="fa fa-fw fa-floppy-o" ></i> Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
            data(){
                return{
                    lookup_name:'',
                    delivery:{},
                    field_list:[],
                    isValidForm:false 
                }
            },
            mounted() {
                this.load(this.$route.params.id);
            },
            computed:{
              
                backURL(){
                    return '#/deliveries/'+this.delivery.id+"/edit";
                },
                
            },
            methods: {
                load(id) {
                    this.$parent.$emit("SHOW_PRELOADER");
                    this.$http.get("/ajax/deliveries/" + id).then(response => {
                        this.$parent.$emit("HIDE_PRELOADER");
                        this.delivery = response.body;
                        this.createForm();
                    }, response => {
                        this.$parent.$emit("HIDE_PRELOADER");
                        this.$router.push('/400');
                    });
                },
                process(){
                    var formData = new FormData();
                    formData.append('lookup_name',this.lookup_name);
                    $.each(this.$refs.customs,function(i,v){
                            formData.append(v.custom.id, v.getValue()); 
                    });
                     this.$parent.$emit("SHOW_PRELOADER");
                     this.$http.post('/ajax/content/'+this.delivery.id, formData).then(response => {
                        this.$root.$emit("HIDE_PRELOADER");
                        this.$root.$emit("ALERT", "Ok!", "The Content has been created successfully", "success", 3);
                        this.$parent.$router.push('/deliveries/'+this.delivery.id+"/edit");
                      }, response => {
                        console.log("error",response);
                      });
                },
                createForm(){
                    $.each(this.delivery.customs,function(i,field){
                        var oField={
                                    component: field.component.tag, 
                                    props: {custom:field}
                                }
                        this.field_list.push(oField);
                        
                        $("#formHolder").append("<alert></alert>");
                        
                    }.bind(this));
                 
                },
                validate(){
                    this.isValidForm = true;
                    this.$children.forEach(function(element){
                        if(element.isValidatorEnabled){
                            element.validate();
                            if (this.isValidForm) this.isValidForm = element.isValid;
                        } 
                       
                    }.bind(this));
                   this.$refs.lookup_name.validate();
                   if (this.isValidForm) this.isValidForm = this.$refs.lookup_name.isValid;
                   if (this.isValidForm) {
                        this.process();
                    }
                }
            }
    }

</script>
