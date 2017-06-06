<template>
	<div>
      <div class="container">
          <div class="row">
              <div class="col-md-12 ">
                  <div class="panel panel-default">
                      <div class="panel-heading">
                      <h2><i class="lnr lnr-users"></i> Users
                          <div class="btn-group  pull-right">
                          <a href="#/users/invite" class="btn btn-default " ><i class="fa fa-fw fa-send"></i> Invite</a>
                          <a href="#/users/add" class="btn btn-default" ><i class="fa fa-fw fa-plus"></i> Add</a>
                          </div>
                      </h2>
                      
                      </div>

                      <div class="panel-body">
                       
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Partner</th>
                              <th>Status</th>
                              <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in list.data">
                                <td>{{item.name}}</td>
                                <td>{{item.email}}</td>
                                <td>{{item.is_admin?'-':item.partner.name}}</td>
                                <td v-html="getStatus(item.active)"></td>
                                <td>
                                     <div class="btn-group btn-group-xs" role="group" aria-label="...">
                                        <a class="btn btn-default" :href="'#users/edit/'+item.id" ><i class="fa fa-fw fa-edit"></i> Edit</a>
                                        <button type="button" class="btn btn-default" @click="activate(item)" v-show="!item.is_admin && !item.active"><i class="fa fa-fw fa-toggle-off"></i> Activate</button>
                                        <button type="button" class="btn btn-default" @click="deactivate(item)" v-show="!item.is_admin && item.active"><i class="fa fa-fw fa-toggle-on"></i> Deactivate</button>
                                        <button type="button" class="btn btn-default" @click="trash(item)" ><i class="fa fa-fw fa-trash"></i> Delete</button>
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
    mounted() {

        this.$on('OK_TO_DELETE', function() {
            if (this.toDelete != null) {
                this.delete(this.toDelete.id);
                this.toDelete = null;
            }
        }.bind(this));

        this.$on('OK_TO_ACTIVATE', function() {
            if (this.toStatus != null) {
                this.toStatus.active = 1;
                this.activateUser(this.toStatus.id);
                this.toStatus = null;
            }
        }.bind(this));

        this.$on('OK_TO_DEACTIVATE', function() {
            if (this.toStatus != null) {
                this.toStatus.active = 0;
                this.deactivateUser(this.toStatus.id);
                this.toStatus = null;
            }
        }.bind(this));

    },
    data: function() {
        return {
            toDelete: null,
            toStatus: null,
            errors: [],
            validator: null
        }
    },
    created: function() {
        this.resource_url = "ajax/admin/users{/id}";
        this.singular = "User";
        this.addObject = { name: "", email: "", partner_id: "", password: "" };

    },
    methods: {
        activateUser(id) {

            this.$root.$emit("SHOW_PRELOADER");
            this.$http.get('/ajax/admin/users/' + id + "/activate").then(response => {
                this.$root.$emit("HIDE_PRELOADER");
                this.$root.$emit("ALERT", "Ok!", "The User has been activated successfully", "success", 3);
            }, response => {
                console.log("Error");
            });
        },
        deactivateUser(id) {
            this.$root.$emit("SHOW_PRELOADER");
            this.$http.get('/ajax/admin/users/' + id + "/deactivate").then(response => {
                this.$root.$emit("HIDE_PRELOADER");
                this.$root.$emit("ALERT", "Ok!", "The User has been deactivated successfully", "success", 3);
            }, response => {
                console.log("Error");
            });
        },
        delete(id) {
            this.$root.$emit("SHOW_PRELOADER");
            this.provider.delete({ id: id }).then(response => {
                this.$root.$emit("HIDE_PRELOADER");
                this.$root.$emit("ALERT", "Ok!", "The " + this.singular + " has been deleted successfully", "warning", 3);
                this.load();
            }, response => {
                console.log("errorDeleting");
            });
        },
        getStatus: function(value) {
            if (value == 1) {
                return "<span class='label label-success'>Active</span>";
            } else {
                return "<span class='label label-danger'>Inactive</span>";
            }

        },
        trash(item) {
            this.toDelete = item;
            this.$root.$emit("CONFIRM", "Attention!", "Are you sure you want to delete the user: <strong>" + item.name + "</strong>?", this, "OK_TO_DELETE");
        },
        activate(item) {
            this.toStatus = item;
            this.$root.$emit("CONFIRM", "Attention!", "Are you sure you want to activate the user: <strong>" + item.name + "</strong>?", this, "OK_TO_ACTIVATE");
        },
        deactivate(item) {
            this.toStatus = item;
            this.$root.$emit("CONFIRM", "Attention!", "Are you sure you want to activate the user: <strong>" + item.name + "</strong>?", this, "OK_TO_DEACTIVATE");
        }


    }

}

</script>