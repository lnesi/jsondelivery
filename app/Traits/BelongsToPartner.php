<?php 
namespace App\Traits;
use App\Partner;
trait BelongsToPartner{
	 public function partner(){
    	return $this->belongsTo(Partner::class);
    }
}