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
					contents:[] 
				}
			}
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
	    
	    
	}

</script>
