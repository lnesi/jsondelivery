<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="devkey" value="UkdWMlpXeHZjR1ZrSUVKNUlFeDFhWE1nVG1WemFTQXRJR3h1WlhOcExtZHBkR2gxWWk1cGJ3PT0=">
        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Two AdCms</title>

        <!-- Styles -->
        <script src="https://use.fontawesome.com/305feb8e9e.js"></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/linearicons.css') }}" rel="stylesheet">


    </head>
    <style>
        #iframePreview{
            width:{{$delivery->size->width}}px;
            height:{{$delivery->size->height}}px;
            border:none;
            
        }
        .tableHolder{
            margin:0 auto;
        }
    </style>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
          <div class="container" >
            <div class="navbar-header">
                <a class="navbar-brand" href="#/">
                    JSON<small>Cms</small>
                </a>
            </div>
          </div>
        </nav>

    <div class="container" style="margin-top:75px;">
          
          <div class="row">
               <div class="col col-sm-2"><strong>ID</strong></div>
               <div class="col col-sm-2">{{$delivery->id}}</div>
               <div class="col col-sm-2"><strong>Name</strong></div>
               <div class="col col-sm-6">{{$delivery->name}}</div>
          </div>
          <p>
          <a  role="button" data-toggle="collapse" href="#detailsHolder" ><i class="fa fa-fw fa-plus"></i> Details </a>
          </p>
          <div id="detailsHolder" class="collapse" aria-expanded="true" style="margin-top:30px;">
                
                <div class="row">
                    <div class="col col-sm-2"><strong>Type</strong></div>
                    <div class="col col-sm-4">
                         {{$delivery->type->name}} <small>({{$delivery->type->abbr}})</small></div>
                    <div class="col col-sm-2"><strong>Size</strong></div>
                    <div class="col col-sm-4">
                        {{$delivery->size->name}}
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col col-sm-2"><strong>Partner</strong></div>
                    <div class="col col-sm-4">
                       {{$delivery->partner->name}}  <small>({{$delivery->partner->abbr}})</small></div>
                    <div class="col col-sm-2"><strong>Audience</strong></div>
                    <div class="col col-sm-4">
                        {{$delivery->audience->name}} <small>({{$delivery->audience->abbr}})</small></div>
                </div>
                <div class="row">
                    <div class="col col-sm-2"><strong>Campaign</strong></div>
                    <div class="col col-sm-4">
                        {{$delivery->campaign->name}} <small>({{$delivery->campaign->abbr}})</small></div>
                   <div class="col col-sm-2"><strong>Region</strong></div>
                    <div class="col col-sm-4">
                         {{$delivery->region->name}} <small>({{$delivery->region->abbr}})</small></div>
                </div>
                <div class="row">
                    <div class="col col-sm-2"><strong>Country</strong></div>
                    <div class="col col-sm-4">
                         {{$delivery->country->name}} <small>({{$delivery->country->code}})</small></div>
                    <div class="col col-sm-2"><strong>Language</strong></div>
                    <div class="col col-sm-4">
                        {{$delivery->language->name}} <small>({{$delivery->language->code}})</small></div>
                </div>
            </div>
            <hr>
            <div class="row">
                
                <div class="col col-sm-12 text-center">
                    <table cellpadding="0" cellspacing="0" class="tableHolder">
                        <tr>
                            <td width="40px"></td>
                            <td width="{{$delivery->size->width}}" style="text-align: left"  id="rulerWidth"></td>
                        </tr>
                        <tr>
                            <td width="40px" id="rulerHeight" style="vertical-align: top;"></td>
                            <td style="text-align: left" >
                                <iframe src="{{env('AWS_S3_HOST_URL',$_SERVER['AWS_S3_HOST_URL'])}}/previews/{{$delivery->partner->id}}/{{$delivery->id}}/index.html?debug=true<?php if($content_id) echo '&content_id='.$content_id?>" id="iframePreview" scrolling="no"></iframe>
                            </td>
                        </tr>
                    </table>
                    
                    
                   
                </div>
            </div> 
    </div><!-- /.container -->

     <!-- Scripts -->
    <script src="{{ asset('js/preview.js') }}"></script>
    <script>
    

    $(function(){
        var rulerHeight = new rulerGenerator({
            width: 40,
            height:{{$delivery->size->height}},
            tickInterval: 50,
            tickParts: 5,
            direciton:0,
            backgroundColor:'rgba(0,0,0,0.05)'
        });

        var rulerWidth = new rulerGenerator({
            width: {{$delivery->size->width}},
            height:40,
            tickInterval: 50,
            tickParts: 5,
            direction:1,
            backgroundColor:'rgba(0,0,0,0.05)'
        });
        $("#rulerHeight").html(rulerHeight.img);
        $("#rulerWidth").html(rulerWidth.img);
        document.getElementById("iframePreview")
    });
    

        console.log('DEV Key','UkdWMlpXeHZjR1ZrSUVKNUlFeDFhWE1nVG1WemFTQXRJR3h1WlhOcExtZHBkR2gxWWk1cGJ3PT0=');
    </script>


    </body>
</html>
