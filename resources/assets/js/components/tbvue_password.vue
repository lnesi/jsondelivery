<template>
	<div>
		  <div :class="{'form-group': true, 'has-error': errorBag.password1.length!=0 }">
		    <label for="inpassword1" class="control-label">Password</label>
		    <div class="tbvue_input_holder">
		    <input type="password" class="form-control" id="inpassword1" @blur="validate" name="password1" v-model="password1" placeholder="Password">
		    <i class="fa fa-fw fa-exclamation-triangle text-danger" v-show="errorBag.password1.length!=0  " ></i>
		    </div>
		    <div class="row">
		    <div class="col-md-4">
		   	    <p class="strengthlabel">Strength:</p>
			    <ul class="strengthbar">
			    	<li :class='segmentClass(0)'></li>
			    	<li :class='segmentClass(1)'></li>
			    	<li :class='segmentClass(2)'></li>
			    	<li :class='segmentClass(3)'></li>
			    	<li :class='segmentClass(4)'></li>
			    </ul>
		    </div>
		    </div>
		    <p class="help-block" v-show="errorBag.password1.length!=0">{{errorBag.password1[0]}}</p>
		  </div>

		  <div :class="{'form-group': true, 'has-error': errorBag.password2.length!=0 }">
		    <label for="inpassword2" class="control-label">Confirm Password</label>
		    <div class="tbvue_input_holder">
		    <input type="password" class="form-control" id="inpassword2" @blur="validate" name="password2"  v-model="password2" placeholder="Password">
		    <i class="fa fa-fw fa-exclamation-triangle text-danger" v-show="errorBag.password2.length!=0  " ></i>
		    </div>
		    <p class="help-block" v-show="errorBag.password2.length!=0">{{errorBag.password2[0]}}</p>
		  </div>
	</div>
</template>
<script>

	export default {
		props:['userInput'],
		data(){
			return {
				password1:'',
				password2:'',
				isInput:true,
				check:{score:0},
				errorBag:{
					'password1':[],
					'password2':[]
				}
			}
		},
		computed:{
			
	        isValid(){
	          return this.errorBag.password1.length==0 && this.errorBag.password2.length==0;
	        },
	        lengthScore(){
	        	return Math.round(this.password1.length/3);
	        },
	       
		},

		watch:{
			password1(value){
				this.check=zxcvbn(value,this.userInput);
				
			}
		},
		

		methods:{
			validate(){
				this.errorBag.password1=[];
				this.errorBag.password2=[];

	            if($.trim(this.password1)==""){
	            	this.errorBag.password1.push('The password is required.');
	            }else if(this.check.score<3){
	            	this.errorBag.password1.push('Password is to weak.');
	            }else if($.trim(this.password2)==""){
	            	this.errorBag.password2.push('The confirmation is required.');
	            }else if(this.password1!=this.password2){
					this.errorBag.password2.push('The confirmation does not match');
	            }
	           
	        },
	        segmentClass(index){
	        	if(index<this.lengthScore){
	        		if(this.check.score>=3){
	        			return 'green'
	        		}else{
	        			return 'red';
	        		}
	        	}
	        	return '';
	        }
		}
	}
</script>

<style lang="scss">
	.strengthlabel{
		margin-bottom: 0;
		margin-top: 2px;
	}
	.strengthbar{
		
		width:100%;
		padding:0;
		list-style: none;
		margin-top:5px;
		li{
			float:left;
			width:16%;
			height: 5px;
			background-color: lightgray;
			margin-right: 5%; 
			&:last-child{
				margin-right: 0;
			}
		}

		.green{
			background-color: green;
		}
		.red{
			background-color: red;
		}

	}
</style>