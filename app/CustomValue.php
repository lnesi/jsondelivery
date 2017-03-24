<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomValue extends Model
{
    //
    use SoftDeletes;
    protected $table = 'custom_values';
    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $fillable=['set_id','custom_id','data'];
    protected $hidden=['deleted_at','created_at','updated_at','set_id','custom'];
    public function content(){
    	return $this->belongsTo(DeliveryContent::class,'set_id');
    }

    public function custom(){
    	return $this->belongsTo(DeliveryCustom::class,'custom_id');
    }
    
    public function getDataAttribute($value){
        if($this->custom->component->tag=="app-image"){
            return base64_encode($value);
        }
        return $value;
    }
}
