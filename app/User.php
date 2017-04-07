<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','partner_id'
    ];

    protected $with=['partner'];
   
    public function partner(){
        return $this->belongsTo(Partner::class);
    }

    public function getIsAdminAttribute($value){
        return $value==1;
    }
}
