<?php namespace App\Observers;

class DeliveryObserver{

  public function created($delivery){
    $defaultContent=new \App\DeliveryContent(['name'=>'Default','rotation'=>100]);
    $defaultContent->delivery()->associate($delivery);
    $defaultContent->save();
  }

}
