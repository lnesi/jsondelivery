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
					isValidForm:false,
					partner_id:''
				},
			
			}
		},
    computed:{
      hasErrors:function(){
        return false;
      }
    },
		created: function () {          
          this.provider = this.$resource("ajax/admin/users");

          
       },
		methods:{
			validate() {
        this.isValidForm = true;
        this.$children.forEach(function(element){
          if(element.isInput){
            element.validate();
            if (this.isValidForm) this.isValidForm = element.isValid;
          }
        }.bind(this));
        if(this.isValidForm) this.sendInvitation();
      },
      sendInvitation() {
            this.$root.$emit("SHOW_PRELOADER");

             this.$http.post('/ajax/admin/users/invite',this.addObject).then(response => {
                this.$root.$emit("HIDE_PRELOADER");
                this.$root.$emit("ALERT", "Ok!", "The invitation has been sent successfully", "success", 3);
                this.$parent.$router.push('/users/');
            }, response => {
            	this.$root.$emit("HIDE_PRELOADER");
                console.log("Error");
            });
           
        },
		}
	}
</script>