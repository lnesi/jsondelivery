<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Delivery;
class PreviewController extends Controller
{
    //
    public function index(Request $request, $id){
    	$item=Delivery::find($id);
    	if($item){
    		return view('preview',['delivery'=>$item]);
    	}else{
    		echo '404';
    	}
    }
}
