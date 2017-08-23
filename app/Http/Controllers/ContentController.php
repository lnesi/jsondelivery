<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Delivery;
use App\DeliveryContent;
use App\CustomValue;
use App\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
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
                    $s3 = Storage::disk('s3');
                    $imageFileName = time() . '.' . $fileUpload->getClientOriginalExtension();
                    $filePath = 'images/' . $imageFileName;
                    $result=$s3->put($filePath, file_get_contents($fileUpload), 'public');
                    CustomValue::create(['set_id'=>$content->id,'custom_id'=>$custom->id,"data"=>Storage::cloud()->url($filePath)]);
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
            $data=null;
            $query=CustomValue::query();
            $query->where('custom_id',$custom->id);
            $query->where('set_id',$content_id);
            $cc=$query->get();
            if($custom->component->tag!="app-image"){
                $data=$request->input($custom->id);
            }else{
                $fileUpload=$request->file($custom->id);
                if($fileUpload){
                    $s3 = Storage::disk('s3');
                    $imageFileName = time() . '.' . $fileUpload->getClientOriginalExtension();
                    $filePath = 'images/' . $imageFileName;
                    $result=$s3->put($filePath, file_get_contents($fileUpload), 'public');
                    $data=Storage::cloud()->url($filePath);//file_get_contents($fileUpload->path());
                }
            }
            if($data){
                if($cc->count()>0){
                     $cc[0]->data=$data;
                     $cc[0]->save();
                }else{
                     CustomValue::create(['set_id'=>$content->id,'custom_id'=>$custom->id,"data"=>$data]);
                }
            }
            
        }
       $content=$content->fresh('values');
       return $content;
    }	

    public function delete(Request $request, $id){
        $content=DeliveryContent::findOrFail($id);
        $user = Auth::user();
        if($user->is_admin || $user->partner==$content->delivery->partner){
            $content->delete();
            $response=['message'=>'ok'];
            return $response;
        }else{
            abort(403, 'Unauthorized action. You cannot delete this content');
        }
        //var_dump($content->delivery->partner==);
        
    }

    public function publish($id,$content_id){
        $delivery=Delivery::findOrFail($id);
        $content=DeliveryContent::findOrFail($content_id);
        $content->status_id=2;//Live
        $content->published_at=new \Carbon\Carbon();
        $content->save();  
        $content->fresh('status');
        $delivery=Delivery::findOrFail($id);
        $delivery->fresh('contents');
        //$content=
        return $delivery;
    }

    public function expire($id,$content_id){
        $delivery=Delivery::findOrFail($id);
        $content=DeliveryContent::findOrFail($content_id);
        $content->status_id=3;//Live
        $content->published_at=new \Carbon\Carbon();
        $content->save();  
        $content->fresh('status');
        $delivery=Delivery::findOrFail($id);
        $delivery->fresh('contents');
       
        return $delivery;
    }
}
