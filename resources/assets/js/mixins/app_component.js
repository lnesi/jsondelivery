export default {
	data(){
		return{
			valueModel:null,
			isValidatorEnabled:true,
			validator:null,
		}
		
	},
	mounted(){
		if(this.value) this.valueModel=this.value;
	},
	computed:{
		id(){
			return "custom_"+this.custom.id
		},
        hasErrors(){
          return this.validator.errors.has(this.id);
        },
        isValid(){
          return this.validator.errors.count()==0;
        }

      },
	props:['value','custom'],
	methods:{
		getValue(){
			return this.valueModel;
		},
		validate(){
			if(this.isValidatorEnabled)	this.validator.validate(this.id, this.valueModel).then(() => {}).catch(() => {});
		}
	},
	watch:{
		value(val){
			console.log("value prop",val);
		},
		valueModel(value){
			if (value !== undefined && this.isValidatorEnabled) {
           		this.validator.validate(this.id, this.valueModel).then(() => {}).catch(() => {});
        	}
		}
	}
}