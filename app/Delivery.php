<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\DeliveryObserver;
class Delivery extends Model
{
    //
    use SoftDeletes;
    protected $table = 'deliveries';
    protected $dates = ['deleted_at','created_at','updated_at','published_at'];
    protected $fillable=['name','partner_id','campaign_id','audience_id','country_id','language_id','region_id','size_id','type_id','preview_url'];
    protected $with=['status','type','partner','campaign','country','region','language','size','audience','customs','contents'];
    protected $hidden=['partner_id','country_id','region_id','language_id','size_id','audience_id','status_id','type_id','campaign_id'];
    public static function boot(){
      parent::boot();
      Delivery::observe(new DeliveryObserver());
    }

    public function partner(){
    	return $this->belongsTo(Partner::class);
    }
    
    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }

    public function size(){
    	return $this->belongsTo(DeliverySize::class,'size_id');
    }

    public function status(){
    	return $this->belongsTo(Status::class);
    }

    public function type(){
    	return $this->belongsTo(DeliveryType::class,'type_id');
    }

    public function audience(){
    	return $this->belongsTo(Audience::class);
    }

    public function region(){
    	return $this->belongsTo(Region::class);
    }

    public function country(){
    	return $this->belongsTo(Country::class);
    }

    public function language(){
      return $this->belongsTo(Language::class);
    }
    
    public function customs(){
        return $this->hasMany(DeliveryCustom::class)->orderBy('sort_index');
    }

    public function contents(){
        return $this->hasMany(DeliveryContent::class);
    }

    public function setCountry($country_code){
      $country=Country::getByCode($country_code);
      if($country){
        $this->country()->associate($country);
        return TRUE;
      }
      return FALSE;
    }

    public function setLanguage($language_code){
      $language=Language::getByCode($language_code);
      if($language){
        $this->language()->associate($language);
        return TRUE;
      }
      return FALSE;
    }

    public function getObject(){
        $result=new \stdClass;
        $result->id=$this->id;
        $result->name=$this->name;
        $result->status=$this->status;
        $result->created_at=$this->created_at;
        $result->updated_at=$this->updated_at;
        $result->published_at=$this->published_at;
        $result->type=$this->type;
        $result->size=$this->size;
        $result->partner=new \stdClass;
        $result->partner->id=$this->partner->id;
        $result->partner->abbr=$this->partner->abbr;
        $result->partner->name=$this->partner->name;
        $result->campaign=new \stdClass;
        $result->campaign->id=$this->campaign->id;
        $result->campaign->abbr=$this->campaign->abbr;
        $result->campaign->name=$this->campaign->name;
        $result->country=$this->country;
        $result->language=$this->language;
        $result->region=new \stdClass;
        $result->region->id=$this->region->id;
        $result->region->abbr=$this->region->abbr;
        $result->region->name=$this->region->name;
        $result->audience=new \stdClass;
        $result->audience->id=$this->audience->id;
        $result->audience->abbr=$this->audience->abbr;
        $result->audience->name=$this->audience->name;
        return $result;
    }
}
