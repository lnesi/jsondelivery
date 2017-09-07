$.fn.outerHeight = function(){
	if(this.length==1){
		var el=this[0];
		var height = el.offsetHeight;
	  	var style = getComputedStyle(el);
	  	height += parseInt(style.marginTop) + parseInt(style.marginBottom);
	  	return height;
	}else{
		return this;
	}	
}

$.fn.outerWidth = function(){
	if(this.length==1){
		var el=this[0];
		var width = el.offsetWidth;
	  	var style = getComputedStyle(el);
	  	width += parseInt(style.marginLeft) + parseInt(style.marginRight);
	  	return width;
	}else{
		return this;
	}	
}