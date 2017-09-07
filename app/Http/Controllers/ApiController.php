<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use \App\Delivery;
class ApiController extends Controller
{
    //
    public function JSONDelivery(Request $request,$debug=false,$id,$content_id=null,$jsonp="jdeliveryCallback"){
    
    	if($debug && $debug==="debug"){
			$debug=true;
		}else{
			$debug=false;
		}

		$item=Delivery::find($id);

		
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
					if($custom->component->tag!="app-separator"){
						$value=$content->values->where('custom_id',$custom->id)->first();
						if($value){
							$result->content[$custom->key]=$value->data;
						}else{
							$result->content[$custom->key]=null;
						}
					}
				}
				return response()->json($result)->withCallback($jsonp);
			}
			
			
		}
		return response()->json([ 'error'=> 404, 'message'=> 'Not found' ]);
    }

    public function XMLFeed(Request $request, $id){
    	$delivery=Delivery::findOrFail($id);
		
		$xml='<?xml version="1.0" encoding="UTF-8"  ?>';
		$xml.='<feed>';

		foreach($delivery->contents as $content){
			$xml.='<content>';
				$xml.='<unique_id>'.$delivery->id.'080'.$content->id.'</unique_id>';
				$xml.='<reporting_label>'.str_replace(' ','_',strtolower($content->name)).'</reporting_label>';
				
				foreach($delivery->customs as $custom){
					if($custom->component->tag!="app-separator"){
						$xml.="<".$custom->key.">";
						if($custom->component->tag=="app-wysiwyg" || $custom->component->tag=="app-plaintext") $xml.="<![CDATA[";
						$xml.=$content->getValueByCustomId($custom->id);
						if($custom->component->tag=="app-wysiwyg" || $custom->component->tag=="app-plaintext") $xml.="]]>";
						$xml.="</".$custom->key.">";
					}
				}

				$xml.="<default>FALSE</default>";
				if($content->status->name=="Live"){
					$xml.="<active>TRUE</active>";
				}else{
					$xml.="<active>FALSE</active>";
				}
				$xml.='<weights>'.$content->distribution.'</weights>';
			$xml.='</content>';
		}
		
		$xml.='</feed>';

		return Response::make($xml, '200')->header('Content-Type', 'text/xml');
    }
}
