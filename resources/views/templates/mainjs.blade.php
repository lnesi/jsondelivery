(function(){
  var exit_url="#";

  var currentContent={
  	@foreach($delivery->customs as $custom)
  	"{{$custom->key}}":'',
  	@endforeach
  };

  window.onload = function() {
    if (Enabler.isInitialized()) {
      enablerInitHandler();
    } else {
      Enabler.addEventListener(studio.events.StudioEvent.INIT, enablerInitHandler);
    }
  }

  function enablerInitHandler() {
    loadContent();
    setupListeners();
  }

  function start(){
    TweenLite.to(".preloader",0.25,{opacity:0});
  }

  function loadContent(){
    deliverySDK.rootURL="{{config('app.url')}}";
    deliverySDK.init({{$delivery->id}});
    if(deliverySDK.debugMode){
      deliverySDK.loadContent(function(data){
        console.log(data);
        currentContent=data.content;
        attachContent();
        start();
      });
    }else{
      /* Handle the data Manually
         Assign Current Content form DoubleClick */
      //attachContent();
      start();
    }
    
  }
  function setupListeners(){
  	document.getElementById('mainWrapper').addEventListener('click', exitHandler, false);
  }

  function exitHandler(e) {
    Enabler.exitOverride('Click',exit_url);
  }

  function attachContent(){
    //exit_url='CLICK_URL';
  @foreach($delivery->customs as $custom)
    @if($custom->component->tag=="app-image")
    $("#custom_{{$custom->key}}").attr('src',currentContent.{{$custom->key}});
    @else
  	$("#custom_{{$custom->key}}").html(currentContent.{{$custom->key}});
    @endif
  @endforeach

  }
})();