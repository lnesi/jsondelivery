<template>
	<div>
		<div class="panel panel-default" id="custom_fileds_panel">
		    <div class="panel-heading">
		        <h3 class="panel-title">Dynamic Content Form Builder</h3>
		    </div>
		     <div class="panel-body " id="custom_fileds_panel_body">
  				 <p><button type="button" class="btn btn-default" @click="add"><i class="fa fa-fw fa-plus" ></i>Add</button></p>
  				 <table id="custom_fileds_list" class="table table-bordered table-striped ">
  		        <thead>
  		         		<tr>
  		         			<th>Description</th>
  		         			<th>Key</th>
  		         			<th>Type</th>
  		         			<th>Actions</th>
  		         			<th>Sort</th>
  		         		</tr>
  						</thead>
  						<tbody>
		         			<tr v-show="hasCustoms">
		         				<td colspan="5">No results.</td>
		         			</tr>
                  <tr v-for="(item, index) in value">
                    <td>{{item.name}}</td>
                    <td>{{item.key}}</td>
                    <td>{{item.component.name}}</td>
                    <td>
                      <div class="btn-group btn-group-xs" role="group" aria-label="...">
                        <button type="button" class="btn btn-default btn-xs" @click="edit(item)"><i class="fa fa-fw fa-edit"></i> Edit</button>
                        <button type="button" class="btn btn-default btn-xs" @click="remove(item)"><i class="fa fa-fw fa-trash"></i> Remove</button>
                      </div>
                    </td>
                    <td>
                      <div class="btn-group btn-group-xs" role="group" aria-label="..." v-show="hasMoreOne">
                          <button type="button" class="btn btn-default btn-xs" v-show="index>0" @click="sortUp(index)"><i class="fa fa-fw fa-chevron-circle-up"></i></button>
                          <button type="button" class="btn btn-default btn-xs" v-show="index<value.length-1" @click="sortDown(index)"><i class="fa fa-fw fa-chevron-circle-down"></i></button>
                      </div>
                    </td>
                  </tr>  
  		        </tbody>
  		    </table>  	
		     </div>
		     
		</div>
		
      <app-customaddfield :deliveryId="delivery_id" ref="addform"></app-customaddfield>
      <app-customeditfield  ref="editform"></app-customeditfield>
	</div>
</template>
<script>
  export default {
  	validator:null,
  	provider:null,
  	data(){
  		return{
  			delivery_id:null,
  			errors:[],
        toRemove:null
  		}
  	},
    props:['value'], 
  	created(){
  		this.provider = this.$resource("ajax/customs{/id}");  	
      this.$on("OK_TO_REMOVE_FIELD",function(){
        if(this.toRemove!=null){
          this.delete(this.toRemove.id);
          this.toRemove = null;
        }
      }.bind(this));
     
      this.$on('OK_EDIT_FIELD',function(field){
          this.value.splice(this.getFieldIndex(field),1,field);
          this.$emit("input",this.value);
        
      }.bind(this));
  	},
  	computed: {
        hasValidateErrors() {
        	this.validator.validateAll(this.item).then(() => {}).catch(() => {});
            return this.errors.count() > 0;
        },
        getDeliveryId(){
        	return this.$parent.$data.delivery.id;
        },
        hasCustoms(){
          if(Array.isArray(this.value)){
            return this.value.length==0;
          }else{
            return true;
          }
          
        },
        hasMoreOne(){
           return this.value.length>1;
        }
    },
    watch:{
    	value:{
        	handler: function() {
              this.delivery_id=this.getDeliveryId;
          },
          deep: true
    	}
    },
    methods:{
    	add(){
         this.$refs.addform.show();
  		 
    	},
    	validate(){
    		this.validator.validateAll(this.item).then(() => {
    			this.add();
    		}).catch(() => {});
    	},
      remove(item){
        this.toRemove=item;
        
        this.$root.$emit("CONFIRM", "Attention!", "Are you sure you want to remove the field: <strong>" + item.name + "</strong>?", this, "OK_TO_REMOVE_FIELD");
      },
      delete(id){
        this.$root.$emit("SHOW_PRELOADER");
        this.provider.delete({ id: id }).then(response => {
            this.$root.$emit("HIDE_PRELOADER");
            var newList=this.value.filter(function( obj ) {
              return obj.id != id;
            });
            
            this.$emit("input",newList);
            this.$root.$emit("ALERT", "Ok!", "The Field has been deleted successfully", "warning", 3);
            
        }, response => {
            console.log("errorDeleting");
        });
      },
      edit(item){
        this.$refs.editform.show(item);
      },
      sortUp(index){
        this.value[index].sort_index--;
        this.value[index-1].sort_index++;
        this.sort();
      },
      sortDown(index){
        this.value[index].sort_index++;
        this.value[index+1].sort_index--;
        this.sort();
      },
      sort(){
          this.value.sort(function(a,b){
            return a.sort_index-b.sort_index;
          });
          this.$emit("input",this.value);
      },
      getFieldIndex(field){
          return this.value.findIndex(function(currentValue, index, arr){
            if (currentValue.id==this.id) return true;
            return false;
          },field);
      }
    	
    }
  }
</script>