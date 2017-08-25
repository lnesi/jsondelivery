<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                 
                    <div class="panel panel-default">
                        <div class="panel-heading">Delivery
                        <a class="btn btn-primary btn-xs pull-right" :href="delivery.preview_url" target="_blank"  v-show="delivery.preview_url!=null"><i class="fa fa-eye" ></i> Preview </a>
                        </div>

                        <div class="panel-body">
                            <h4>Details</h4>
                            <app-deliverydetails v-model="delivery"></app-deliverydetails>
                            <hr>
                            <h4>Contents: &nbsp; <a type="button" :href="addLink" class="btn btn-primary btn-xs"><i class="fa fa-fw fa-plus"></i>Add</a></h4>
                            <app-deliverycontentlist v-model="delivery"></app-deliverycontentlist>
                        </div>
                        <div class="panel-footer">
                            <a  class="btn btn-default" href="#/" ><i class="fa fa-fw fa-chevron-left"></i> Back</a>
                            <button type="button" class="btn btn-success pull-right" @click="save_distribution()"><i class="fa fa-fw fa-floppy-o"></i> Save</button>
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
                    delivery:{}
                }
            },
            mounted() {
                this.load(this.$route.params.id);
            },
            computed:{
                addLink(){
                    return '#/deliveries/'+this.delivery.id+"/addcontent";
                }
            },
            methods: {
                load(id) {
                    this.$parent.$emit("SHOW_PRELOADER");
                    this.$http.get("/ajax/deliveries/" + id).then(response => {
                        this.$parent.$emit("HIDE_PRELOADER");
                        this.delivery = response.body;
                    }, response => {
                        this.$parent.$emit("HIDE_PRELOADER");
                        this.$router.push('/400');
                    });
                },
                save_distribution(){
                    //this.$parent.$emit("SHOW_PRELOADER");
                    var list={}
                    this.delivery.contents.forEach( function(c){
                        list[c.id]=c.distribution;
                    });
                    this.$parent.$emit("SHOW_PRELOADER");
                    this.$http.put("/ajax/deliveries/" + this.delivery.id+"/distribution",list).then(response => {
                        this.$parent.$emit("HIDE_PRELOADER");
                        
                    }, response => {
                        this.$parent.$emit("HIDE_PRELOADER");
                        console.log(response);
                        this.$root.$emit("ALERT", response.status+" Error!", response.body.message, "danger");
                    });
                    
                }
            }
    }

</script>
