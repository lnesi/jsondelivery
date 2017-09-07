<!DOCTYPE html>
<!--

             _                 _ 
       ____ | |               (_)
      / __ \| |_ __   ___  ___ _ 
     / / _` | | '_ \ / _ \/ __| |
    | | (_| | | | | |  __/\__ \ |
     \ \__,_|_|_| |_|\___||___/_|
      \____/                     
	                               
	::::::::::::::::::::::::::::::

	       lnesi.github.io        

	::::::::::::::::::::::::::::::

-->
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="ad.size" content="width={{$delivery->size->width}},height={{$delivery->size->height}}">
	<!-- build:js js/vendor.min.js -->
	<script src="js/zepto.js"></script>
	<script src="js/zepto.plugins.js"></script><?php /*Inline to have propper formating on generation*/?>
	<!-- endbuild --><script src="https://s0.2mdn.net/ads/studio/cached_libs/tweenlite_1.19.1_9fecaf2f68ee2520ddaa79e268d743a6_min.js"></script>
	<script src="https://s0.2mdn.net/ads/studio/cached_libs/cssplugin_1.19.1_3e055071719ea45b6807509f844b72d9_min.js"></script>
	<script src="https://s0.2mdn.net/ads/studio/Enabler.js"></script>

	<!-- build:css css/bundle.min.css -->
	<link rel="stylesheet" href="css/styles.css" />
	<!-- endbuild -->
</head>
<body>
	<div class="wrapper" id="mainWrapper">
	<div class="preloader">
		<img src="img/ajax-loader.gif" alt="Loading"/>
	</div>
	@foreach($delivery->customs as $custom)
			@if($custom->component->tag=="app-image")
				<?php 
				$w='50';
				$h='50';
				if($custom->data->validation->data->w) $w=$custom->data->validation->data->w;
				if($custom->data->validation->data->h) $h=$custom->data->validation->data->h;
				?>
				<div>
					<img id="custom_{{$custom->key}}" src="https://dummyimage.com/{{$w}}x{{$h}}/666/fff.jpg" alt="{{$custom->name}}" width="{{$w}}" height="{{$h}}"/>
				</div>
			@else
				<div id="custom_{{$custom->key}}">
					{{$custom->name}}
				</div>
			@endif
	@endforeach
	</div>
	<!-- build:js js/bundle.min.js -->
	<script src="js/deliverySdk.js"></script>[nl]
	<script src="js/main.js"></script>
	<!-- endbuild -->
</body>
</html>