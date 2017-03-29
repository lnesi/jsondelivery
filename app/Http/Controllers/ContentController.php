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
                if($request->input($custom->id)){
                    CustomValue::create(['set_id'=>$content->id,'custom_id'=>$custom->id,"data"=>$request->input($custom->id)]);
                }
			}else{
				$fileUpload=$request->file($custom->id);
                if($fileUpload){
                    $file=file_get_contents($fileUpload->path());
                    CustomValue::create(['set_id'=>$content->id,'custom_id'=>$custom->id,"data"=>$file]);
                }
				
			}
    		
    	}
    	$content=$content->fresh('values');
    	return $content;
    }
    public function edit(Request $request, $delivery_id,$content_id){
        $delivery=Delivery::findOrFail($delivery_id);
        $content=DeliveryContent::findOrFail($content_id);
        $content->name=$request->input('lookup_name');
        $content->save();
        foreach($delivery->customs as $custom){
            if($custom->component->tag!="app-image"){
                if($request->input($custom->id)){
                    $query=CustomValue::query();
                    $query->where('custom_id',$custom->id);
                    $query->where('set_id',$content_id);
                    $cc=$query->get();
                    if($cc->count()>0){
                        $cc[0]->data=$request->input($custom->id);
                        $cc[0]->save();
                    }else{
                       CustomValue::create(['set_id'=>$content->id,'custom_id'=>$custom->id,"data"=>$request->input($custom->id)]);
                    }
                }
            }else{

                
            }
            $content=$content->fresh('values');
        
            return $content;
        }
    }	
}
