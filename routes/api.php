<?php

use Illuminate\Http\Request;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/delivery/{id}/{jsonp?}/{debug?}",function(Request $request,$id,$jsonp="jdeliveryCallback",$debug=false){
	
	if($debug && $debug==="debug"){
		$debug=true;
	}else{
		$debug=false;
	}
	
	$item=App\Delivery::findOrFail($id);
	if($item->status->id==2 || $debug){
		$result=$item->getObject();
		return response()->json($result)->withCallback($jsonp);;
	}else{
		return response()->json([ 'error'=> 404, 'message'=> 'Not found' ])->withCallback($jsonp);
	}
	
});
