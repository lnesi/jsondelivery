<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Status;
class StatusController extends ReadAjaxController
{
    //
    protected $modelClass=Status::class;
}
