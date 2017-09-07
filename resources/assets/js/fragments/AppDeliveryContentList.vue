<template>
	<div>
		 <table  class="table table-bordered table-striped ">
			<thead>
				<tr>
					<th width="40%">Name</th>
					<th width="10%">
						<tablefilter data-url="ajax/status?paginate=false" labelKey="name" v-model="status_filter">All Status</tablefilter>
					</th>
					<th width="20%">Distribution</th>
					<th width="30%">Actions</th>
				</tr>
				<tr v-for="content in contentList">
					<td>{{content.name}}</td>
					<td><span :class="statusLabelClass(content.status)">{{content.status.name|uppercase}}</span></td>
					<td style="padding-right: 55px;">
						<vue-slider :tooltipStyle="tooltip_styles" ref="slider" tooltip-dir="right" v-model="content.distribution"></vue-slider>
					</td>
					<td>
						<div class="btn-group btn-group-xs" role="group" aria-label="...">
                            <a class="btn btn-default" :href="'#deliveries/'+delivery.id+'/editcontent/'+content.id" ><i class="fa fa-fw fa-edit text-primary"></i> Edit</a>
                            <a class="btn btn-default" @click="publish(content)" v-show="content.status.id!=2"><i class="fa fa-cloud-upload text-success" ></i> Publish</a>
                            <a class="btn btn-default" @click="unpublish(content)" v-show="content.status.id==2"><i class="fa fa-cloud-download text-danger" ></i> &nbsp; Expire</a>
                            <a class="btn btn-default" :href="'/preview/'+delivery.id+'/'+content.id" target="_blank"><i class="fa fa-eye text-warning" ></i> Preview</a>
                            <button type="button" class="btn btn-danger" @click="deleteContent(content)" v-show="content.status.id!=2" ><i class="fa fa-fw fa-trash"></i> Delete</button>
                         </div>
					</td>
				</tr>
			</thead>
		</table>

	</div>
</template>
<script>
	export default {
		data(){
			return {
				delivery:{
					contents:[],

				},
				status_filter:'',
				operatedItem:null,
				tooltip_styles:{
					backgroundColor:"#fff",
					borderColor:"#666",
					color:"#666"
				}
			}
		},
	
		mounted(){
			 this.provider = this.$resource("/ajax/content{/id}");
			 this.$on('OK_TO_PUBLISH', function() {
	            if (this.operatedItem != null) {
	                this.publishContent(this.operatedItem.id);
	                this.operatedItem = null;
	            }
	        }.bind(this));

			 this.$on('OK_TO_EXPIRE', function() {
	            if (this.operatedItem != null) {
	                this.unpublishContent(this.operatedItem.id);
	                this.operatedItem = null;
	            }
	        }.bind(this));

			 this.$on('OK_TO_DELETE', function() {
	            if (this.operatedItem != null) {
	                this.delete(this.operatedItem.id);
	                this.operatedItem = null;
	            }
	        }.bind(this));
		},
	    props: ['value'],
	    computed:{
	    	contentList(){
	    		let list=this.delivery.contents.filter(function(c){
					if(this.status_filter=="") {
						return true;
					}else{
						return c.status.id==this.status_filter;
					}
	    		}.bind(this));
	    		return list;
	    	}
	    
	    },
	    watch:{
	    	value:{
	        	handler: function() {
	              this.delivery=this.value;
	          },
	          deep: true
	    	}
	    },
	    methods:{
	    	statusLabelClass(status){
	          if(status.id==1){
	            return 'label label-warning';
	          }
	          if(status.id==2){
	            return 'label label-success';
	          }
	          
	          return 'label label-default';
	        },  
	    	publish(content){
	    		this.operatedItem = content;
            	this.$root.$emit("CONFIRM", "Attention!", "Are you sure you want to publish the following content: <strong><br>" + this.operatedItem.name + "</strong>?", this, "OK_TO_PUBLISH");
	    	},
	    	unpublish(content){
	    		this.operatedItem = content;
            	this.$root.$emit("CONFIRM", "Attention!", "Are you sure you want to expire the following content: <strong><br>" + this.operatedItem.name + "</strong>?", this, "OK_TO_EXPIRE");
	    	},
	    	publishContent(id){
	    		 this.$root.$emit("SHOW_PRELOADER");
		            this.$http.get('/ajax/content/' + this.delivery.id + "/"+id+"/publish").then(response => {
		                this.$root.$emit("HIDE_PRELOADER");
		                console.log(response.body);
		                this.delivery=response.body;

		                this.$root.$emit("ALERT", "Ok!", "The Content has been published successfully", "success", 3);
		            }, response => {
		                console.log("Error");
		          });
	    	},
	    	unpublishContent(id){
	    		this.$root.$emit("SHOW_PRELOADER");
		            this.$http.get('/ajax/content/' + this.delivery.id + "/"+id+"/expire").then(response => {
		                this.$root.$emit("HIDE_PRELOADER");
		                this.delivery=response.body;
		                console.log(this.response);
		                this.$root.$emit("ALERT", "Ok!", "The Content has been expired successfully", "success", 3);

		            }, response => {
		                console.log("Error");
		          });
	    	},
	    	deleteContent(content){
				this.operatedItem = content;
				this.$root.$emit("CONFIRM", "Attention!", "Are you sure you want to delete the following content: <strong><br>" + this.operatedItem.name + "</strong>?", this, "OK_TO_DELETE");
	    	},
	    	delete(id) {
	            this.$root.$emit("SHOW_PRELOADER");
	            this.provider.delete({ id: id }).then(response => {
	                this.$root.$emit("HIDE_PRELOADER");
	                this.$root.$emit("ALERT", " OK!", "The content has been deleted successfully", "warning");
	                 this.$parent.load(this.delivery.id);
	               
	            }, response => {
	            	this.$root.$emit("HIDE_PRELOADER");
	            	console.log(response);
	            	this.$root.$emit("ALERT", response.status+" Error!", response.body.message, "danger");
	                
	            });
	        },

	    },
	    filters: {
	        uppercase: function(value) {
	            if (!value) return '';
	            value = value.toString();
	            return value.toUpperCase();
	        }
	    },
	    
	    
	}

</script>
