<template>
	<div>
		

	</div>
</template>
<script>
	export default {
		data(){
			return {
				delivery:{
					sets:[]
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
