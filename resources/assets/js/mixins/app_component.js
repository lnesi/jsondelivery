export default {
	data(){
		return{
			valueModel:null
		}
		
	},
	mounted(){
		if(this.value) this.valueModel=this.value;
	},
	props:['label','help_text','id','name','value','custom_id'],
	methods:{
		getValue(){
			return this.valueModel;
		}
	},
	watch:{
		value(val){
			console.log("value prop",val);
		}
	}
}