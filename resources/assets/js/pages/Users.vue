<template>
	<div>
      <div class="container">
          <div class="row">
              <div class="col-md-12 ">
                  <div class="panel panel-default">
                      <div class="panel-heading">
                      <h2><i class="lnr lnr-users"></i> Users <small><a href="#/users/add" class="btn btn-default pull-right" ><i class="fa fa-fw fa-plus"></i> Add</a></small></h2>
                      
                      </div>

                      <div class="panel-body">
                       
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Partner</th>
                              <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in list.data">
                                <td>{{item.name}}</td>
                                <td>{{item.email}}</td>
                                <td>{{item.is_admin?'-':item.partner.name}}</td>
                                <td>
                                     <div class="btn-group btn-group-xs" role="group" aria-label="...">
                                        <a class="btn btn-default" :href="'#users/edit/'+item.id" ><i class="fa fa-fw fa-edit"></i> Edit</a>
                                        <button type="button" class="btn btn-default" @click="trash(item)" v-show="!item.is_admin"><i class="fa fa-fw fa-trash"></i> Delete</button>
                                     </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
</template>
<script>
var list_mix = require('../mixins/list.js').default;
    export default {
        mixins: [list_mix],
        created: function () {
          this.resource_url="ajax/admin/users{/id}";
          this.singular="User";
          this.addObject={name:"",email:"",partner_id:"",password:""};
          
       },
       
        
        
    }
</script>