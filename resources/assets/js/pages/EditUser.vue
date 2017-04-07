<template>
	<div class="container">
        <div class="row">
            <div class="col-md-12">
              <form  @submit="validateForm()" onsubmit="return false;">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit User</div>

                    <div class="panel-body">
                     	<div  class="form-group" v-show="item.partner!=null">
                            <label for="partner_id" class="control-label">Partner</label>
                            <p>{{item.is_admin?'-':item.partner.name}}</p>
                          </div>
                        <tbvue-input name="name" id="in_name" placeholder="Name" rules="required|max:100" v-model="item.name">Name</tbvue-input>
                        <tbvue-input name="name" id="in_abbr"  placeholder="abbr" rules="required|email" v-model="item.email" :disabled="true">Email</tbvue-input>
                    	
                    </div>
                    <div class="panel-footer">
                        <a  class="btn btn-default" href="#/users" ><i class="fa fa-fw fa-chevron-left"></i> Back</a>
                        <button type="submit"   :class="{'btn btn-success pull-right': true, 'disabled': errors.has('name') || errors.has('abbr') }"><i class="fa fa-fw fa-floppy-o" ></i> Save</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        created: function() {
            this.resource_url = "ajax/admin/users{/id}";
            this.singular = "User";

        },
        data: function() {
            return {
                item: { id: null, name: null, email: null, password: null,partner:{name:''},is_admin:false }
            }
        },

        mounted() {

            this.provider = this.$resource(this.resource_url);
            this.load(this.$route.params.id);
        },
        
        methods: {
            load(id) {
                this.$root.$emit("SHOW_PRELOADER");
                this.provider.get({ id: id }).then(response => {
                    this.$root.$emit("HIDE_PRELOADER");
                    this.item = response.body;
                }, response => {
                    this.$router.push('/400');
                });
            },
            save() {
                this.$root.$emit("SHOW_PRELOADER");
                this.provider.update({ id: this.item.id }, this.item).then(response => {
                    this.$root.$emit("HIDE_PRELOADER");
                    this.$root.$emit("ALERT", "Ok!", "The " + this.singular + " has been updated successfully", "success", 3);
                });
            },
            validateForm() {
                this.$validator.validateAll().then(result => {
                    this.save();
                }).catch(() => null);
            }
        }
    }

</script>