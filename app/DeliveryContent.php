<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryContent extends Model
{
    //
    use SoftDeletes;
    protected $table = 'delivery_contents';
    protected $dates = ['deleted_at','created_at','updated_at','published_at'];
    protected $fillable=['name','rotation'];
    protected $with=['status'];
    protected $hidden=['status_id','delivery_id'];
    public function delivery(){
    	return $this->belongsTo(Delivery::class,'delivery_id');
    }

    public function status(){
    	return $this->belongsTo(Status::class,'status_id');
    }


}
