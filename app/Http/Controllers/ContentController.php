<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Delivery;
use App\DeliveryContent;
use App\CustomValue;
class ContentController extends Controller
{
    public function add(Request $request, $id){
    	$delivery=Delivery::findOrFail($id);
    	$content=new DeliveryContent(['name'=>$request->input('lookup_name')]);
	    $content->delivery()->associate($delivery);
	    $content->save();
    	
    	
    	foreach($delivery->customs as $custom){
			if($custom->component->tag!="app-image"){
				CustomValue::create(['set_id'=>$content->id,'custom_id'=>$custom->id,"data"=>$request->input($custom->id)]);
			}else{
				$fileUpload=$request->file($custom->id);
				$file=file_get_contents($fileUpload->path());
				CustomValue::create(['set_id'=>$content->id,'custom_id'=>$custom->id,"data"=>$file]);
			}
    		
    	}
    	$content->values;
    	return $content;
    }	
}
