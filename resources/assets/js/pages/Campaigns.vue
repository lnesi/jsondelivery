<template>
    <div>
      <div class="container">
          <div class="row">
              <div class="col-md-12 ">
                  <div class="panel panel-default">
                      <div class="panel-heading">
                      <h2><i class="lnr lnr-pushpin"></i>  Campaigns <small><button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#addModal"><i class="fa fa-fw fa-plus"></i> Add</button></small></h2>
                      
                      </div>

                      <div class="panel-body">
                       
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                              <th>Partner</th>
                              <th>Name</th>
                              <th>Abbr</th>
                              <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                             <tr v-show="list.data.length==0"><td colspan="4">No data found.</td></tr>
                            <tr v-for="item in list.data">
                                <td>{{item.partner.abbr | uppercase}}</td>
                                <td>{{item.name}}</td>
                                <td>{{item.abbr | uppercase}}</td>
                                <td>
                                     <div class="btn-group btn-group-xs" role="group" aria-label="...">
                                        <a class="btn btn-default" :href="'#campaigns/'+item.id" ><i class="fa fa-fw fa-edit"></i> Edit</a>
                                        <button type="button" class="btn btn-default" @click="trash(item)"><i class="fa fa-fw fa-trash"></i> Delete</button>
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
      <modal id="addModal" ref="addModal">
          <h4 class="modal-title" slot="header">Add Campaing</h4>
          <form slot="body">
              <tbvue-ajax-dropdown data-url="ajax/partners?paginate=false" name="partner_id" rules="required" id="partner_id" v-model="addObject.partner_id">Partner</tbvue-ajax-dropdown>
              <tbvue-input name="name" id="in_name" placeholder="Name" rules="required|max:100" v-model="addObject.name">Name</tbvue-input>
              <tbvue-input name="abbr" id="in_abbr"  placeholder="abbr" rules="required|max:10" v-model="addObject.abbr">Abbreviation</tbvue-input>
          </form>
          <button type="button" slot="footer" class="btn btn-default"  data-dismiss="modal">Cancel</button>
          <button type="button" slot="footer" class="btn btn-success" :class="{'btn btn-success': true, 'disabled': hasValidateErrors }" @click="validate"><i class="fa fa-fw fa-floppy-o" ></i> Save</button>
      </modal>
</div>
    
</template>

<script>
    let crud_mix = require('../mixins/crd.js');
    let admin_only = require('../mixins/admin_only.js').default;
    export default {
        mixins: [admin_only,crud_mix.default],
        created: function() {
            this.resource_url = "ajax/campaigns{/id}";
            this.singular = "Campaign";
            this.addObject = { name: "", abbr: "", partner_id: "" }

        },
        

    }

</script>
