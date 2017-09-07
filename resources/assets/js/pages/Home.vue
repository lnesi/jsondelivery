<template>
    <div>
      <div class="container">
          <div class="row">
              <div class="col-md-12 ">
                  <div class="panel panel-default">
                      <div class="panel-heading">
                      <h2><i class="lnr lnr-code"></i>  Deliveries 
		      <small><a type="button" class="btn btn-default pull-right" href="#/delivery/new" v-show="user.is_admin"><i class="fa fa-fw fa-plus"></i> New</a></small></h2>
                      
                      </div>

                      <div class="panel-body">
                       
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                              <th v-show="user.is_admin" width="10%">
                                <tablefilter data-url="ajax/partners?paginate=false" labelKey="abbr" v-model="filters.partner_id">Partners</tablefilter>
                              </th>
                              <th width="11%">
                                <tablefilter data-url="ajax/campaigns?paginate=false" labelKey="abbr" v-model="filters.campaign_id">Campaigns</tablefilter>
                              </th>
                              <th width="10%">
                                <tablefilter data-url="ajax/audiences?paginate=false" labelKey="abbr" v-model="filters.audience_id">Audiences</tablefilter>
                              </th>
                              <th width="10%">
                                <tablefilter data-url="ajax/regions?paginate=false" labelKey="abbr" v-model="filters.audience_id">Regions</tablefilter>
                              </th>
                              <th >Name</th>
                              <th width="9%">
                                <tablefilter data-url="ajax/status?paginate=false" labelKey="name" v-model="filters.status_id">Status</tablefilter>
                              </th>
                              <th width="31%">Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                            <tr v-show="list.data.length==0"><td colspan="7">No data found.</td></tr>
                            <tr v-for="item in list.data">
                                <td :data-id="item.partner.id" v-show="user.is_admin">{{item.partner.abbr|uppercase}}</td>
                                <td>{{item.campaign.abbr|uppercase}}</td>
                                <td>{{item.audience.abbr|uppercase}}</td>
                                <td>{{item.region.abbr|uppercase}}</td>
                                <td>{{item.name}}</td>
                                <td><span :class="statusLabelClass(item.status)">{{item.status.name|uppercase}}</span></td>
                                <td>
                                     <div class="btn-group btn-group-xs" role="group" aria-label="...">
                                        
                                        <a class="btn btn-default" @click="publish(item)" v-show="item.status.id!=2"><i class="fa fa-cloud-upload text-success" ></i> Publish</a>
                                        <a class="btn btn-default" @click="expire(item)" v-show="item.status.id==2"><i class="fa fa-cloud-download text-danger" ></i> &nbsp; Expire</a>
                                        <a class="btn btn-default" :href="'#deliveries/'+item.id+'/edit'" ><i class="fa fa-fw fa-edit text-primary"></i> Edit</a>
                                        <a class="btn btn-default" :href="item.preview_url" target="_blank"  v-show="item.preview_url!=null"><i class="fa fa-eye text-warning" ></i> Preview </a>
                                        <a class="btn btn-primary" :href="'#deliveries/'+item.id" v-show="$root.user.is_admin"><i class="fa fa-fw fa-cog" ></i> Setup</a>
                                        <button type="button" class="btn btn-danger" @click="trash(item)" v-show="$root.user.is_admin"><i class="fa fa-fw fa-trash" ></i> Delete</button>
                                     </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination :list="list"/>
                      </div>
                  </div>
              </div>
          </div>
      </div>
</div>
    
</template>

<script>
let list_mix = require('../mixins/list.js').default;
export default {
    mixins: [list_mix],
    created() {
        this.resource_url = "ajax/deliveries{/id}";
        this.$on("OK_TO_DELETE", function() {
            if (this.toAction != null) {
                this.delete(this.toAction.id);
                this.toAction = null;
            }
        }.bind(this));

        this.$on("OK_TO_PUBLISH", function() {
            if (this.toAction != null) {
                this.do_publish(this.toAction.id);
                this.toAction = null;
            }
        }.bind(this));

        this.$on("OK_TO_EXPIRE", function() {
            if (this.toAction != null) {
                this.do_expire(this.toAction.id);
                this.toAction = null;
            }
        }.bind(this));
    },

    data: function() {
        return {
            toAction: null,
            filters:{
              partner_id:'',
              status_id:'',
              campaign_id:'',
              audience_id:'',
              region_id:''
            }
        }
    },
    watch:{
      filters:{
         handler(val){
            this.load()
         },
         deep: true
      }
    },
    methods: {
        statusLabelClass(status){
          if(status.id==1){
            return 'label label-warning';
          }
          if(status.id==2){
            return 'label label-success';
          }
          
          return 'label label-default';
        },    
        trash(item) {
            this.toAction = item;
            this.$root.$emit("CONFIRM", "Attention!", "Are you sure you want to delete the delivery: <strong>" + item.name + "</strong>?<br> The delivery will become unavailable after delete.", this, "OK_TO_DELETE");
        },
        delete(id) {
            this.$root.$emit("SHOW_PRELOADER");
            this.provider.delete({ id: id }).then(response => {
                this.$root.$emit("HIDE_PRELOADER");
                this.$root.$emit("ALERT", "Ok!", "The Delivery has been deleted successfully", "warning", 3);
                this.load();
            }, response => {
                console.log("errorDeleting");
            });
        },
        publish(delivery){
            this.toAction = delivery;
            this.$root.$emit("CONFIRM", "Attention!", "Are you sure you want to publish the delivery: <strong>" + delivery.name + "</strong>?<br> The delivery will become available on the API.", this, "OK_TO_PUBLISH");
        },
        expire(delivery){
          this.toAction = delivery;
          this.$root.$emit("CONFIRM", "Attention!", "Are you sure you want to expire the delivery: <strong>" + delivery.name + "</strong>?<br> The delivery will become unavailable on the API.", this, "OK_TO_EXPIRE");
        },
        do_publish(id){
            this.$root.$emit("SHOW_PRELOADER");
            this.$http.get('/ajax/deliveries/' + id + "/publish").then(response => {
                this.$root.$emit("HIDE_PRELOADER");
                console.log(response.body);
                this.$root.$emit("ALERT", "Ok!", "The Delivery has been published successfully", "success", 3);
                this.load();
            }, response => {
                console.log("Error");
            });
        },
        do_expire(id){
            this.$root.$emit("SHOW_PRELOADER");
            this.$http.get('/ajax/deliveries/' + id + "/expire").then(response => {
                this.$root.$emit("HIDE_PRELOADER");
                console.log(response.body);
                this.$root.$emit("ALERT", "Ok!", "The Delivery has been expire successfully", "success", 3);
                this.load();
            }, response => {
                console.log("Error");
            });
        }
    }


}
</script>