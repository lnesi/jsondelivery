<template>
	<div class="container">
        <div class="row">
            <div class="col-md-12">
              <form  @submit="validate()" onsubmit="return false;">
                <div class="panel panel-default">
                    <div class="panel-heading">Add User</div>

                    <div class="panel-body">
                     	  <tbvue-ajax-dropdown data-url="ajax/partners?paginate=false" name="partner_id" rules="required" id="partner_id" v-model="addObject.partner_id">Partner</tbvue-ajax-dropdown>
                        <tbvue-input name="name" id="in_name" placeholder="Name" rules="required|max:100" v-model="addObject.name">Name</tbvue-input>
                        <tbvue-input name="name" id="in_email"  placeholder="Email" rules="required|email|remote:ajax/admin/users/validate" v-model="addObject.email">Email</tbvue-input>
                    	  <tbvue-password v-model="addObject.password"></tbvue-password>
                    </div>
                    <div class="panel-footer">
                        <a  class="btn btn-default" href="#/users" ><i class="fa fa-fw fa-chevron-left"></i> Back</a>
                        <button type="submit"   :class="{'btn btn-success pull-right': true, 'disabled': hasErrors }"><i class="fa fa-fw fa-floppy-o" ></i> Save</button>
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
			return {
				addObject:{
					name:'',
					email:'',
					password:'',
					partner_id:''
				},
				errors: [],
            	validator: null
			}
		},
    computed:{
      hasErrors:function(){
        return false;
      }
    },
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
		methods:{
			validate() {
        this.$children.forEach(function(element){
          if(element.isInput){
            element.validate()
          }
        });
	      this.validator.validateAll(this.addObject).then(result => {
	          console.log("HERE WE ADD");
	      }).catch(() => null);
	      this.$set(this, 'errors', this.validator.errorBag);
      },
		}
	}
</script>