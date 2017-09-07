<template>
	<div>
		<select class="tableFilter" v-model="inputmodel">
			<option value=''><slot></slot></option>
			<option v-for="partner in list" :value="partner.id">{{partner[labelKey]}}</option>
		</select>
		
	</div>
</template>
<script>
	export default{
		data(){
			return {
				list:null,
				inputmodel:''
			}
		},
		created(){
			if (this.dataUrl != "") {
                this.load();
            }
		},
		watch:{
			inputmodel(value) {
                if (value !== undefined) {
					this.$emit('input', value);
                }

            },
		},
		methods:{
			load(){
                this.$http.get(this.dataUrl).then(response => {
                    this.list = response.body;
                }, response => {
                 
                });
			}
		},
		props:['data-url','labelKey']
	}
</script>
<style lang="scss" >
	.tableFilter{
		width: 100%;
    	border: none;
    	background-color: #fff;

	}
</style>