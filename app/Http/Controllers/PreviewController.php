<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Delivery;
class PreviewController extends Controller
{
    //
    public function index(Request $request, $id,$content_id=null){
    	$item=Delivery::find($id);
    	if($item){
    		return view('preview',['delivery'=>$item,'content_id'=>$content_id]);
    	}else{
    		echo '404';
    	}
    }
}
