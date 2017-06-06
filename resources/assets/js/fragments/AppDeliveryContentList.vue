<template>
	<div>
		 <table  class="table table-bordered table-striped ">
			<thead>
				<tr>
					<th>Name</th>
					<th>Status</th>
					<th>Distribution</th>
					<th>Actions</th>
				</tr>
				<tr v-for="content in delivery.contents">
					<td>{{content.name}}</td>
					<td>{{content.status.name}}</td>
					<td>{{content.distribution}}</td>
					<td>
						<div class="btn-group btn-group-xs" role="group" aria-label="...">
                            <a class="btn btn-default" :href="'#deliveries/'+delivery.id+'/editcontent/'+content.id" ><i class="fa fa-fw fa-edit"></i> Edit</a>
                            <a class="btn btn-default" @click="publish(content)" v-show="content.status.id!=2"><i class="fa fa-cloud-upload" ></i> Publish</a>
                            <a class="btn btn-default" @click="unpublish(content)" v-show="content.status.id==2"><i class="fa fa-cloud-download" ></i> Unpublish</a>
                            <button type="button" class="btn btn-default" @click="deleteContent(content)" v-show="content.status.id!=2" ><i class="fa fa-fw fa-trash"></i> Delete</button>
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
				operatedItem:null,
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
	    watch:{
	    	value:{
	        	handler: function() {
	              this.delivery=this.value;
	          },
	          deep: true
	    	}
	    },
	    methods:{
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
	                console.log("errorDeleting");
	            });
	        },

	    }
	    
	    
	}

</script>
