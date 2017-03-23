<?php 
namespace App\Observers;

class DeliveryObserver{

  public function created($delivery){
    $defaultContent=new \App\DeliveryContent(['name'=>'Default']);
    $defaultContent->delivery()->associate($delivery);
    $defaultContent->save();
  }

}
