<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginInterfaceController extends Controller
{
    public function loginInterface()
    {
        return view('Authentication.login'); 
    }

}

