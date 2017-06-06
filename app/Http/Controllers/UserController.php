<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CrudApiController;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
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

     public function store(Request $request){
        $user=new User($request->input());
        $user->password=bcrypt($request->input('password'));
        $user->save();
        return $user;
    }

    public function invite(Request $request){
        $current = Carbon::now();
        $user=new User($request->input());
        $user->password=bcrypt(str_random(10));
        $user->invite_token=base64_encode(Crypt::encryptString($user->email.":".$current->timestamp));
        $user->save();
        return $user;
        //return url('/accept/'.$user->invite_token);
    } 

}
