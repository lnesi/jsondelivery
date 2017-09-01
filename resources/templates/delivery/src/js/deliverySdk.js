var $jsonp = (function(){
  var that = {};

  that.send = function(src, options) {
    var options = options || {},
      callback_name = options.callbackName || 'callback',
      on_success = options.onSuccess || function(){},
      on_timeout = options.onTimeout || function(){},
      timeout = options.timeout || 10;

    var timeout_trigger = window.setTimeout(function(){
      window[callback_name] = function(){};
      on_timeout();
    }, timeout * 1000);

    window[callback_name] = function(data){
      window.clearTimeout(timeout_trigger);
      on_success(data);
    };

    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.async = true;
    script.src = src;

    document.getElementsByTagName('head')[0].appendChild(script);
  };

  return that;
})();

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
    var url=this.rootURL+'/api/delivery/'+this.deliveryID;
    if(this.contentID){
      url+="/"+this.contentID+"/jdeliveryCallback/debug";
    }
    $jsonp.send(url, {
        callbackName: 'jdeliveryCallback',
        onSuccess: function(json){
          callback(json)
        },
        onTimeout: function(){
          console.log('timeout!');
        },
        timeout: 5
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
