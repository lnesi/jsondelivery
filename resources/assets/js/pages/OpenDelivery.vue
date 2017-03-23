<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                 <form  @submit="validate()" onsubmit="return false;">
                    <div class="panel panel-default">
                        <div class="panel-heading">Delivery</div>

                        <div class="panel-body">
                            <h4>Details</h4>
                            <app-deliverydetails v-model="delivery"></app-deliverydetails>
                            <hr>
                            <h4>Contents</h4>
                            <app-deliverycontentlist v-model="delivery"></app-deliverycontentlist>
                        </div>
                        <div class="panel-footer">
                            <a  class="btn btn-default" href="#/" ><i class="fa fa-fw fa-chevron-left"></i> Back</a>
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
                    delivery:{}
                }
            },
            mounted() {
                this.load(this.$route.params.id);
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
                }
            }
    }

</script>
