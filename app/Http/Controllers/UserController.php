<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CrudApiController;
use App\User;
class UserController extends CrudAjaxController
{
    //
    protected $modelClass=User::class;

    public function validateEmail(Request $request){
    	$query=User::where('email',$request->input('value'));
    	$user=$query->get();
    	if($user->count()>0) return response('Already Exist',404);
    	
    
    }
}
