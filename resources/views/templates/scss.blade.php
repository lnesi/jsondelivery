$width:{{$width}}px;
$height:{{$height}}px;

body{
    margin:0;
    background-color:#000; 
    font-family:Arial;
}

.wrapper{
    width:$width;
    height:$height;
    background-color:#fff; 
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
    overflow: hidden;
}

.preloader{
	position: absolute;
    width: $width - 2;
    height: $height - 2;
    background-color: #fff;
    text-align: center;
    z-index:1000;
    display: inline-block;
    box-sizing: border-box;
    padding-top: ($height/2) - 50;
    pointer-events: none;
}