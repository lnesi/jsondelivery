<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CrudApiController;
use App\DeliveryCustom;
class DeliveryCustomController extends CrudAjaxController{
    //
     protected $modelClass=DeliveryCustom::class;
     
  //Override
  public function store(Request $request){
  	 $custom=new DeliveryCustom();
  	 $custom->fill($request->input());
     $custom->data=json_encode($request->input('data'));
  	 $custom->save();
     $custom->component;
     return $custom;
  }

  public function update(Request $request, $id){
    $custom=DeliveryCustom::findOrFail($id);
    $custom->fill($request->input());
    $custom->data=json_encode($request->input('data'));
    $custom->save();
    $custom->component;
    return $custom;
  }

 
      
}
