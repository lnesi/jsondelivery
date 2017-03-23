<template>
	<div>
		<div class="row">
			<div class="col col-xs-4 col-sm-1">
				<strong>ID:</strong>
			</div>
			<div class="col col-xs-8 col-sm-1">
				{{delivery.id}}
			</div>
			<div class="col col-sm-2 col-md-1">
				<strong>Name:</strong>
			</div>
			<div class="col col-sm-8 col-md-9">
				{{delivery.name}}
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col col-sm-2">
				<strong>Type</strong>
			</div>
			<div class="col col-sm-4">
				{{delivery.type.name}} <small>({{delivery.type.abbr|uppercase}})</small>
			</div>
			<div class="col col-sm-2">
				<strong>Size</strong>
			</div>
			<div class="col col-sm-4">
				{{delivery.size.name}} 
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col col-sm-2">
				<strong>Partner</strong>
			</div>
			<div class="col col-sm-4">
				{{delivery.partner.name}} <small>({{delivery.partner.abbr|uppercase}})</small>
			</div>
		</div>
		<div class="row">
			<div class="col col-sm-2">
				<strong>Campaign</strong>
			</div>
			<div class="col col-sm-4">
				{{delivery.campaign.name}} <small>({{delivery.campaign.abbr|uppercase}})</small>
			</div>
			<div class="col col-sm-2">
				<strong>Audience</strong>
			</div>
			<div class="col col-sm-4">
				{{delivery.audience.name}} <small>({{delivery.audience.abbr|uppercase}})</small>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col col-sm-2">
				<strong>Region</strong>
			</div>
			<div class="col col-sm-4">
				{{delivery.region.name}} <small>({{delivery.region.abbr|uppercase}})</small>
			</div>
		</div>
		<div class="row">
			<div class="col col-sm-2">
				<strong>Country</strong>
			</div>
			<div class="col col-sm-4">
				{{delivery.country.name}} <small>({{delivery.country.code|uppercase}})</small>
			</div>
			<div class="col col-sm-2">
				<strong>Language</strong>
			</div>
			<div class="col col-sm-4">
				{{delivery.language.name}} <small>({{delivery.language.code|uppercase}})</small>
			</div>
		</div>

	</div>
</template>
<script>
	export default {
		data(){
			return {
				delivery:{
					partner:{},
					campaign:{},
					audience:{},
					region:{},
					country:{},
					language:{},
					size:{},
					type:{}
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
	    filters: {
	        uppercase: function(value) {
	            if (!value) return '';
	            value = value.toString();
	            return value.toUpperCase();
	        }
	    },
	    
	}

</script>
