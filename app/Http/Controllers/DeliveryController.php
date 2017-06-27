<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Delivery;
use App\DeliveryCustom;
use App\Component;
use App\Status;
class DeliveryController extends ReadAjaxController
{
   
    protected $modelClass=\App\Delivery::class;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $item=Delivery::create($request->input());
        return $item;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $item=Delivery::findOrFail($id);
        $item->fill($request->input('delivery'));
        foreach($request->input('customs') as $custom){
            $oCustom=DeliveryCustom::find($custom['id']);
            if(isset($custom['sort_index'])){
                $oCustom->sort_index=$custom['sort_index'];
                $oCustom->save();
            }
        }
        $item->save();

        return $item;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $item=Delivery::findOrFail($id);
        $item->delete();
        $response=['message'=>'ok'];
        return $response;
      }

    public function saveDistribution(Request $request, $id){
        $item=Delivery::findOrFail($id);
        foreach($item->contents as $content){
            if($request->input($content->id)){
                $content->distribution=$request->input($content->id);
                $content->save();
            }
        }
        $item->save();
        return $item;
    }

    public function expire($id){
        $item=Delivery::findOrFail($id);
        $item->status_id=3;
        
        $item->save();
        $item->fresh('status');
       
        return $item;
    }

    public function publish($id){
        $item=Delivery::findOrFail($id);
        $item->status_id=2;
        $item->published_at=new \Carbon\Carbon();
        $item->save();
        $item->fresh('status');
        return $item;
    }          
   
}
