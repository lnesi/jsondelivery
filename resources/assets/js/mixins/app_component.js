export default {
	data(){
		return{
			valueModel:null,
			isInputComponent:true,
			validator:null,
		}
		
	},
	mounted(){
		if(this.value) this.valueModel=this.value;
	},
	computed:{
        hasErrors(){
          return this.validator.errors.has(this.id);
        },
        isValid(){
          return this.validator.errors.count()==0;
        }
      },
	props:['label','help_text','id','name','value','custom_id','data'],
	methods:{
		getValue(){
			return this.valueModel;
		},
		validate(){
			this.validator.validate(this.id, this.valueModel).then(() => {}).catch(() => {});
		}
	},
	watch:{
		value(val){
			console.log("value prop",val);
		},
		valueModel(value){
			if (value !== undefined) {
           		this.validator.validate(this.id, this.valueModel).then(() => {}).catch(() => {});
        	}
		}
	}
}