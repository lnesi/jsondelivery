var deliverySDK={
  rootURL:'',
  deliveryID:null,
  debugMode:false,
  contentID:null,
  init:function(delivery_id){
    this.deliveryID=delivery_id;
    this.debugMode=this.getParameterByName('debug') && this.getParameterByName('debug')=="true";
    if(this.getParameterByName('content_id')){
      this.contentID=this.getParameterByName('content_id');
    }
  },

  loadContent:function(callback){
    var url=this.rootURL+'/api/delivery/debug/'+this.deliveryID;
    if(this.contentID){
      url+="/"+this.contentID;
    }
    //Require Zpto.js
    $.ajax({
      url:url,
      dataType:'jsonp',
      jsonpCallback:"jdeliveryCallback",

      success:function(json){
          callback(json)
      },
      error: function(){
          console.log('error');
      }
    });
    
    
  },
  getParameterByName:function(name, url) {
      if (!url) url = window.location.href;
      name = name.replace(/[\[\]]/g, "\\$&");
      var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
          results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
      return decodeURIComponent(results[2].replace(/\+/g, " "));
  }
};
