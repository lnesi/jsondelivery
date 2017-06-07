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

Route::get("/delivery/{id}/{content_id?}/{jsonp?}/{debug?}",function(Request $request,$id,$content_id=null,$jsonp="jdeliveryCallback",$debug=false){
	if($debug && $debug==="debug"){
		$debug=true;
	}else{
		$debug=false;
	}

	$item=App\Delivery::find($id);

	
	if($item && ($item->status->id==2 || $debug)){
		if(!$content_id){
			if(!$debug){
				$contents=$item->contents->where('status_id',2);
			}else{
				$contents=$item->contents;
			}
			$contentPull=[];
			$totalW=1;
			foreach($contents as $c){
				$contentPull[]=["min"=>$totalW,"max"=>$totalW+$c->distribution-1,"dist"=>$c->distribution,"id"=>$c->id];
				$totalW+=$c->distribution;
			}
			$contentPull=collect($contentPull);
			$weightIndex=rand(1,$contents->sum('distribution'));

			$contentSelect=$contentPull->where('min','<',$weightIndex)->where('max','>',$weightIndex)->first();
			$content=$contents->where('id',$contentSelect['id'])->first();
		}else{
			if(!$debug){
				$contents=$item->contents->where('status_id',2);
			}else{
				$contents=$item->contents;
			}
			$content=$contents->where('id',$content_id)->first();
		}
		
		if($content){
			$result=$item->getObject();
		
			$result->content=[];
			foreach($item->customs as $custom){
				$value=$content->values->where('custom_id',$custom->id)->first();
				if($value){
					$result->content[$custom->key]=$value->data;
				}else{
					$result->content[$custom->key]=null;
				}
			}
			return response()->json($result)->withCallback($jsonp);
		}
		
		
	}
	return response()->json([ 'error'=> 404, 'message'=> 'Not found' ]);
	
});
