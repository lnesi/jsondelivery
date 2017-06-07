<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CrudApiController;
use Illuminate\Support\Facades\Crypt;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvite;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Password;

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
        
        $user=new User($request->input());
        $user->password=bcrypt(str_random(10));
        $current = Carbon::now();
        $user->invite_token=base64_encode(Crypt::encryptString($user->email.":".$current->timestamp));
        $user->save();
        Mail::to($user)->send(new UserInvite($user));
        return $user;
    } 

    public function accept(Request $request, $token){
        try {
            $now = Carbon::now();
            $decrypted = Crypt::decryptString(base64_decode($token));
            $atoken=explode(":",$decrypted);
            $tokendate= Carbon::createFromTimestamp($atoken[1]);
            $diff=$tokendate->diffInMinutes($now);
            if($diff<=1440){ // 24 Hours
                $user=User::where(['email'=>$atoken[0]])->first();
                if($user){
                    if(!$user->active){
                        $user->active=true;
                        $user->invite_token=null;
                        $user->save();
                        $broker=Password::broker();
                        $broker->sendResetLink(['email'=>$atoken[0]]);
                        return view('auth.invite_ok');
                    }else{
                        return view('auth.invite_expired');
                    }
                    
                }else{
                    return view('auth.invite_invalid');
                }
                
            }else{
                return view('auth.invite_expired');
            }
           
        } catch (DecryptException $e) {
            return view('auth.invalid_invite');
        }
       
    }

    public function resendinvite(Request $request, $id){
        $user=User::findOrFail($id);
        $current = Carbon::now();
        $user->invite_token=base64_encode(Crypt::encryptString($user->email.":".$current->timestamp));
        $user->save();
        Mail::to($user)->send(new UserInvite($user));


    }

}
