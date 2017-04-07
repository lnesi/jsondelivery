<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CrudApiController;
use App\User;
class UserController extends CrudAjaxController
{
    //
    protected $modelClass=User::class;
}
