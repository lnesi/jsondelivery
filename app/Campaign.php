<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToPartner;

class Campaign extends Model
{
    //
    use SoftDeletes;
    use BelongsToPartner;
    protected $table = 'campaigns';
    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $fillable = ['name','abbr','partner_id'];
    protected $with=['partner'];
    protected $hidden=['partner_id'];
   
}
