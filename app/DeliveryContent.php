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
    protected $with=['status','values'];
    protected $hidden=['status_id','delivery_id'];
    public function delivery(){
    	return $this->belongsTo(Delivery::class,'delivery_id');
    }

    public function status(){
    	return $this->belongsTo(Status::class,'status_id');
    }

    public function values(){
        return $this->hasMany(CustomValue::class,'set_id');
    }
    public function getValueByCustomId($id){
        foreach($this->values as $value){
            if ($value->custom_id==$id) return $value->data;
        }
        return null;
    }
}
