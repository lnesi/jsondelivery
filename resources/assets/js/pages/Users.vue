<template>
	<div>
      <div class="container">
          <div class="row">
              <div class="col-md-12 ">
                  <div class="panel panel-default">
                      <div class="panel-heading">
                      <h2><i class="lnr lnr-users"></i> Users <small><button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#addModal"><i class="fa fa-fw fa-plus"></i> Add</button></small></h2>
                      
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
var crud_mix = require('../mixins/crd.js').default;
    export default {
        mixins: [crud_mix],
        created: function () {
          this.resource_url="ajax/admin/users{/id}";
          this.singular="User";
          this.addObject={name:"",email:"",partner_id:"",password:""};
          this.validator=new VeeValidate.Validator();
          this.validator.attach('name', 'required|max:100', { prettyName: 'Name' });
          this.validator.attach('email', 'required|email', { prettyName: 'Email' });
          this.validator.attach('partner_id', 'required|numeric', { prettyName: 'Partner' });
          this.validator.attach('password', 'required', { prettyName: 'Password' });
          this.validator.validateAll(this.addObject).then(() => {}).catch(() => {});
          this.$set(this, 'errors', this.validator.errorBag);
       },
       
        
        
    }
</script>