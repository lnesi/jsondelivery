export default {
	data(){
		return{
			valueModel:null
		}
		
	},
	props:['label','help_text','id','name','value','custom_id'],
	methods:{
		getValue(){
			return this.valueModel;
		}
	}
}