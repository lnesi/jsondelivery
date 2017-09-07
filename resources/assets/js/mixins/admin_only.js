export default {
	created(){
		if(!this.$root.user.is_admin){
			this.$root.$router.push("/401");
		}
		
	}
}