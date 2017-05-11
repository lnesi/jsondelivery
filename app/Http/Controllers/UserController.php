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

    public function activate(Request $request,$id){
    	$user=User::findOrFail($id);
    	if(!$user->is_admin){
			$user->active=true;
			$user->save();
			return $user;
    	}else{
    		return response('You  cannot perform this operation on an admin user',401);
    	}
    
    }

    public function deactivate(Request $request,$id){
    	$user=User::findOrFail($id);
    	if(!$user->is_admin){
			$user->active=false;
			$user->save();
			return $user;
    	}else{
    		return response('You  cannot perform this operation on an admin user',401);
    	}
    	
    }
}
