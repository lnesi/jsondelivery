<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                 <form  @submit="validate()" onsubmit="return false;">
                    <div class="panel panel-default">
                        <div class="panel-heading">Delivery: Edit Content</div>

                        <div class="panel-body">
                            <h4  data-toggle="collapse" data-target="#detailsHolder" id="detailsTitle" class="collapsed">Details <i class="fa fa-fw fa-plus-circle" ></i><i class="fa fa-fw fa-minus-circle" ></i></h4>
                            <app-deliverydetails v-model="delivery" collapseId="detailsHolder" collapse="true"></app-deliverydetails>
                            <hr>
                            <tbvue-input name="name" id="in_name" placeholder="Name" rules="required|max:100" v-model="lookup_name">Lookup Name</tbvue-input>
                            <hr>
                            <div :is="field.component" v-for="field in fields" v-bind="field.props" ref="customs"></div>
                        </div>
                        <div class="panel-footer">
                            <a  class="btn btn-default" :href="backURL" ><i class="fa fa-fw fa-chevron-left"></i> Back</a>
                            <button type="button" @click="process"  :class="{'btn btn-success pull-right': true }"><i class="fa fa-fw fa-floppy-o" ></i> Save</button>
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
                  
                    delivery:{},
                    content:{name:''},
                    fields:[],
                    lookup_name:''
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
                        this.content=this.getContent(this.$route.params.content_id);
                        this.lookup_name=this.content.name;
                        this.createForm();
                    }, response => {
                        this.$parent.$emit("HIDE_PRELOADER");
                        this.$router.push('/400');
                    });
                },
                process(){
                    this.$parent.$emit("SHOW_PRELOADER");
                    var formData = new FormData();
                    formData.append('lookup_name',this.lookup_name);
                    formData.append('_method','put');
                    $.each(this.$refs.customs,function(i,v){
                            formData.append(v.custom_id, v.getValue()); 
                    });
                     this.$http.post('/ajax/content/'+this.delivery.id+'/'+this.$route.params.content_id, formData).then(response => {
                       console.log("ok",response);
                       this.$parent.$emit("HIDE_PRELOADER");
                      }, response => {
                        console.log("error",response);
                        this.$parent.$emit("HIDE_PRELOADER");
                      });
                },
                createForm(){
                    $.each(this.delivery.customs,function(i,field){
                       
                       
                        var oField={
                                    component: field.component.tag, 
                                    props: {id:"custom_"+field.id,
                                            custom_id:field.id,
                                            name:field.key,
                                            label: field.name,
                                            help_text:field.help_text,
                                            value:  this.getValue(field.id)
                                            }
                                }
                        this.fields.push(oField);
                        
                        $("#formHolder").append("<alert></alert>");
                        
                    }.bind(this));
                 
                },
                getValue(fieldID){
                    var valueArray=this.content.values.filter(function(value){return value.custom_id==fieldID});
                    
                    if(valueArray.length>0){
                        return valueArray[0].data;
                    }
                    return '';
                    
                },
                getContent(content_id){
                    for(var i=0;i<this.delivery.contents.length;i++){
                        if(this.delivery.contents[i].id==content_id){
                           return this.delivery.contents[i];  
                        } 
                    }
                }
            }
    }

</script>
